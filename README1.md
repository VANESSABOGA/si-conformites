<<<<<<< HEAD
# Conformité YAKO AFRICA — Conflits d'intérêts

Application Laravel 10 **autonome**, issue de l'extraction du module « Conformité / Conflits d'intérêts » du SI-RH.
Elle partage **la même base de données** (`gestion_rh`) et les **mêmes modèles** que le SI-RH.

---

## 1. Architecture

```
                ┌──────────────────────┐         ┌──────────────────────────┐
                │        SI-RH         │         │       CONFORMITÉ         │
                │ si-rh.yakoafricassur │         │ conformite.yakoafricassur│
                │                      │         │                          │
                │ Agents, congés,      │ liens / │ Déclarations, dénoncia-  │
                │ demandes RH, carrière│ redirec-│ tions, campagnes,        │
                │ onboarding…          │ tions   │ back-office Conformité   │
                └──────────┬───────────┘  301    └────────────┬─────────────┘
                           │                                  │
                           └──────────────┬───────────────────┘
                                          ▼
                          ┌───────────────────────────────┐
                          │   MySQL « gestion_rh »        │
                          ├───────────────────────────────┤
                          │ PROPRIÉTÉ SI-RH (lecture ici) │  users*, agents, agent_affectations,
                          │                               │  agent_contacts, directions, departements,
                          │                               │  postes, services, roles/permissions*
                          ├───────────────────────────────┤
                          │ PROPRIÉTÉ CONFORMITÉ          │  campagnes, declarations,
                          │                               │  declaration_conflits, declaration_honneurs,
                          │                               │  declaration_historiques, consentements,
                          │                               │  statut_participations
                          ├───────────────────────────────┤
                          │ PARTAGÉ                       │  notifications (cloche commune),
                          │                               │  jobs (files séparées), failed_jobs, migrations
                          └───────────────────────────────┘
                 * users : remember_token seulement est écrit par la Conformité
```

### Règles de propriété des données

| Règle | Mise en œuvre |
|---|---|
| Le SI-RH est la source de vérité des agents et de l'organisation | Modèles `Agent`, `AgentAffectation`, `AgentContact`, `Direction`, `Departement`, `Poste`, `Service` en **lecture seule** (trait `App\Models\Concerns\LectureSeule` : toute écriture lève une exception) |
| La Conformité possède ses tables | Ses 3 migrations historiques sont **déplacées** ici (mêmes noms de fichiers → déjà enregistrées dans `migrations`, donc non rejouées) |
| Mêmes rôles et mêmes notifications | Classe `App\Models\User` **identique** (même namespace), indispensable pour `model_has_roles.model_type` et `notifications.notifiable_type` |
| Pas d'interférence des files d'attente | La table `jobs` est partagée : la Conformité utilise la file **`conformite`**, le SI-RH la file `default` |
| Pas de collision de session/cache | Cookie `conformite_session`, préfixe de cache `conformite_` |

### Arborescence

```
app/
├── Console/Commands/CloturerCampagnesEchues.php   ← clôture auto quotidienne
├── Http/
│   ├── Controllers/
│   │   ├── DeclarationController.php               ← espace déclarant (ex-ConflitInteretController)
│   │   ├── Admin/DeclarationAdminController.php    ← back-office (ex-ConflitInteretAdminController2)
│   │   ├── Admin/CampagneController.php            ← campagnes (ex-ConflitsCampagneController)
│   │   ├── Auth/ConnexionController.php            ← connexion avec les comptes SI-RH
│   │   └── NotificationController.php              ← cloche (table partagée)
│   ├── Middleware/EnsureAccesConformite.php        ← back-office (Gate « administrer-conformite »)
│   ├── Middleware/EnsureUserIsAgent.php            ← espace déclarant
│   └── Requests/Store*Request.php
├── Models/
│   ├── Concerns/LectureSeule.php
│   ├── Declaration*.php, Campagne.php, …           ← modèles Conformité (propriétaires)
│   └── Agent.php, AgentAffectation.php, …          ← modèles SI-RH (lecture seule)
├── Notifications/                                  ← BaseNotification + DeclarationConflit + Campagne
└── Services/                                       ← NotificationService, DeclarationReferenceGenerator
```

Les **noms de routes** (`conflits.*`, `admin.conflits.*`) sont conservés : les vues du module ont été reprises telles quelles.

---

## 2. Installation

```bash
git clone … si-conformite && cd si-conformite
composer install --no-dev --optimize-autoloader
cp .env.example .env
php artisan key:generate
# Renseigner DB_* (MÊME base que le SI-RH), SIRH_URL, MAIL_*, CONFLIT_INTERET_MATRICULES

# Thème graphique : copier les assets du SI-RH (hors documents/)
rsync -a --exclude documents ../si-rh/public/assets/ public/assets/

php artisan migrate        # n'applique que la migration d'alignement (no-op en production)
php artisan storage:link
php artisan optimize
```

Cron (emails en arrière-plan + clôture automatique des campagnes) :

```
* * * * * cd /var/www/si-conformite && php artisan schedule:run >> /dev/null 2>&1
```

> ⚠️ Ne lancez **jamais** `migrate:fresh` / `migrate:rollback` sur cette base : elle est partagée avec le SI-RH.

---

## 3. Accès

| Profil | Accès |
|---|---|
| Tout utilisateur rattaché à un agent | Espace déclarant (`/declarations`) |
| Rôles `CONFORMITE_ROLES_ADMIN` (défaut : admin, rh, conformite, compliance) **ou** matricule dans `CONFLIT_INTERET_MATRICULES` | Back-office (`/admin`) |
| N+1 | Lecture des auto-déclarations de ses collaborateurs ; **jamais** des dénonciations |

Les comptes, mots de passe et rôles se gèrent dans le SI-RH (lien « Mot de passe oublié » vers le SI-RH).

---

## 4. Notifications

- Déclarant : accusé de réception, décision (validée / rejetée).
- Conformité : nouvelle déclaration positive, modification, dénonciation (identité masquée si anonymat demandé).
- Tous les agents : ouverture de campagne.

La table `notifications` étant commune, une notification Conformité apparaît aussi dans la cloche du SI-RH, et inversement ; le clic redirige vers la bonne application.

---

## 5. SSO optionnel (une seule connexion pour les deux applications)

Si les deux applications sont sur des sous-domaines du même domaine :

1. Créer la table `sessions` (`php artisan session:table && php artisan migrate`, une seule fois, dans l'une des deux applications).
2. Dans les **deux** `.env` :
   ```env
   APP_KEY=…              # la MÊME clé dans les deux applications
   SESSION_DRIVER=database
   SESSION_COOKIE=sirh_session
   SESSION_DOMAIN=.yakoafricassur.com
   ```
Un agent connecté au SI-RH arrive alors directement connecté sur la Conformité.

---

## 6. Évolutions possibles

- Base de données dédiée : remplacer la lecture directe des tables SI-RH par une API du SI-RH (ou une vue SQL / un compte MySQL en lecture seule sur ces tables).
- Compte MySQL dédié avec `SELECT` uniquement sur les tables SI-RH : protection au niveau base, en plus du trait `LectureSeule`.
=======
# si-conformites
>>>>>>> 03883d6ba304cec853c18729e625e736379a44e0
