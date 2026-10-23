# Extraction du module Conformité hors du SI-RH — Guide de bascule

## Livrables

| Archive | Contenu |
|---|---|
| `si-conformite.zip` | Nouvelle application Laravel 10 autonome (README d'architecture inclus) |
| `si-rh-retrait-conformite.zip` | Fichiers SI-RH modifiés + script `retirer-module-conformite.sh` + patch |

Les deux applications utilisent **la même base `gestion_rh`** : aucune migration de données, aucun export/import.

## Choix d'architecture (résumé)

1. **Base partagée, propriété claire.** Le SI-RH reste maître des agents et de l'organisation ; la Conformité possède ses 7 tables. Les modèles SI-RH sont en lecture seule dans la Conformité (une écriture accidentelle lève une exception).
2. **Mêmes modèles, même namespace.** `App\Models\User` est identique dans les deux applications, ce qui rend communs les rôles Spatie et les notifications.
3. **Migrations déplacées, pas recréées.** Les 3 migrations du module partent dans la Conformité avec les mêmes noms de fichiers : déjà enregistrées dans la table `migrations`, elles ne sont pas rejouées.
4. **Isolation technique.** File d'attente dédiée (`conformite`) dans la table `jobs` partagée ; cookie de session et préfixe de cache distincts.
5. **Continuité.** Mêmes noms de routes (vues reprises sans réécriture) ; les anciennes URL du SI-RH redirigent en 301 vers la Conformité.

## Bascule — dans cet ordre

**1. Déployer la Conformité** (sous-domaine, ex. `conformite.yakoafricassur.com`)
```bash
composer install --no-dev -o
cp .env.example .env && php artisan key:generate
# DB_* = mêmes valeurs que le SI-RH ; SIRH_URL ; MAIL_* ; CONFLIT_INTERET_MATRICULES
cp -r ../si-rh/public/assets public/assets      # thème (le dossier documents/ peut être exclu)
php artisan migrate                              # 1 migration d'alignement, sans effet sur la prod
php artisan optimize
```
Ajouter le cron `* * * * * php artisan schedule:run`.

**2. Recette** : se connecter avec un compte agent, puis avec un compte RH/Conformité ; ouvrir une déclaration existante, un PDF, la liste des campagnes.

**3. Retirer le module du SI-RH** (après sauvegarde / commit)
```bash
# copier le contenu de si-rh/ de l'archive par-dessus le projet, puis :
bash retirer-module-conformite.sh
```
Ajouter au `.env` du SI-RH : `CONFORMITE_URL=https://conformite.yakoafricassur.com`
Supprimer du `.env` du SI-RH : `SIRH_EMAILS_CONFORMITE`, `SIRH_ROLES_CONFORMITE`, `CONFLIT_INTERET_MATRICULES` (désormais dans la Conformité).

**4. Vérifier** : le menu « Conformité » du SI-RH ouvre la nouvelle application ; `/conflits` redirige.

**Retour arrière** : restaurer le commit du SI-RH. Aucune donnée n'a été déplacée, rien d'autre à faire.

## Changements dans le SI-RH

- Supprimés : 3 contrôleurs (+ copies), 3 Form Requests, 7 modèles, 2 notifications, le mail et la vue `conflit-soumise`, le middleware d'habilitation, `config/conflit_interet.php`, le générateur de références, le seeder de campagnes, les vues `conflits/` et `conformites/`, les 3 migrations du module.
- Routes : remplacées par des redirections 301 (`/conflits/*` → `/declarations/*`, `/admin/conflits-interets/*` → `/admin/*`).
- Menu latéral, header et page d'accueil : lien vers `CONFORMITE_URL` (masqué si non renseigné).
- `Agent` : relations `declarations()` et `statutsParticipations()` retirées.
- Cloche : le clic sur une notification émise par la Conformité ouvre la bonne application.
- `layouts/main.blade.php` : ajout de `@stack('styles')` et `@stack('scripts')` (les `@push` des vues n'étaient jamais affichés — bug préexistant).

## Corrections apportées pendant l'extraction (Conformité)

- **Dénonciations** : un N+1 pouvait consulter la dénonciation déposée par un collaborateur. Elles sont désormais visibles du seul déclarant et de la Conformité.
- **Routes cassées** : `admin.conflits.accuse.pdf` / `declaration.pdf` pointaient vers des méthodes inexistantes ; `conflits.accueilForm` n'existait pas ; un doublon de route faisait perdre `conflits.storeAuto`.
- **Schéma** : `agent_denonce_id/nom/fonction` et `campagnes.annee/message/cree_par/…` n'étaient créés par aucune migration. Une migration d'alignement les ajoute sur une installation neuve (sans effet sur la production).
- **Campagnes** : la date de clôture saisie était ignorée ; ajout d'une clôture automatique quotidienne des campagnes échues.
- **Emails** : les natures de conflit (tableau) faisaient échouer l'email à la Conformité ; libellés adaptés aux dénonciations (« classé sans suite »).
- Code mort retiré : 6 anciennes méthodes et 9 vues inutilisées.

## Validation effectuée

Scénario automatisé sur une copie de la base : 25 pages testées (déclarant, N+1, back-office, PDF, campagnes), contrôles d'accès (403 attendus), écriture refusée sur `Agent`, dépôt d'une déclaration et d'une dénonciation anonyme, rejet par la Conformité, création de campagne, emails et cloche. Côté SI-RH : les 20 tests de notifications repassent et les redirections 301 sont vérifiées.
