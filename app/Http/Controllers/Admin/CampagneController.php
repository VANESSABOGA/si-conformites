<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Campagne;
use App\Models\Declaration;
use App\Models\User;
use App\Notifications\CampagneConflitsOuverte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\NotificationService;
use Illuminate\Support\Str;

/**
 * Back-office Conformité : campagnes annuelles de déclaration.
 */
class CampagneController extends Controller
{
    public function index()
    {
        $campagnes = Campagne::with('auteur')
            ->orderByDesc('date_ouverture')
            ->paginate(15);

        return view('conformites.conflits-interets.campagnes.index', compact('campagnes'));
    }

    public function create()
    {
        return view('conformites.conflits-interets.campagnes.create', [
            'campagne' => new Campagne(['annee' => now()->year]),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'libelle'        => ['required', 'string', 'max:255'],
            'annee'          => ['required', 'integer', 'min:2020', 'max:2100'],
            'date_ouverture' => ['required', 'date'],
            'date_cloture'   => ['nullable', 'date', 'after:date_ouverture'],
            'message'        => ['nullable', 'string', 'max:3000'],
        ]);

        $campagne = DB::transaction(function () use ($data) {

            // Clôture auto des anciennes OUVERTE
            Campagne::where('statut', '!=', 'CLOTUREE')->update([
                'statut'       => 'CLOTUREE',
                'date_cloture'  => now(),
                'updated_at'   => now(),
            ]);

            // Création nouvelle en PREPARATION
            return Campagne::create([
                ...$data,
                'statut'        => 'PREPARATION',
                'code_campagne' => $this->genererCodeCampagne(),
                'cree_par'      => auth()->id(),
            ]);
        });

        return redirect()
            ->route('admin.conflits.campagnes.show', $campagne)
            ->with('success', "Campagne \"{$campagne->libelle}\" créée. Les campagnes précédentes ont été clôturées automatiquement.");
    }

    /**
     * Génère un code de campagne unique (avec retry en cas de collision).
     */
    private function genererCodeCampagne(string $prefix = 'CI'): string
    {
        $annee = now()->format('Y');
        $tentatives = 0;

        do {
            $reference = sprintf(
                '%s-%s-%s',
                $prefix,
                $annee,
                strtoupper(Str::random(6))
            );
            $tentatives++;
        } while (
            Campagne::where('code_campagne', $reference)->exists()
            && $tentatives < 5
        );

        if ($tentatives >= 5) {
            throw new \RuntimeException("Impossible de générer un code de campagne unique.");
        }

        return $reference;
    }

    public function show(Campagne $campagne)
    {
        $campagne->load('auteur');

        // dd($campagne);

        // Statistiques de participation (adapte selon ton modèle de déclarations)
        $stats = [
            'total_agents' => Agent::where('statut', 'actif')->count(),
            'declarations' => Declaration::where('id_campagne', $campagne->id)->count(),
            'en_attente'   => Declaration::where('id_campagne', $campagne->id)
                ->where('statut', 'SOUMISE')->count(),
            'validees'     => Declaration::where('id_campagne', $campagne->id)
                ->where('statut', 'VALIDEE')->count(),
        ];

        // dd($stats);
        return view('conformites.conflits-interets.campagnes.show', compact('campagne', 'stats'));
    }


    /**
     * Ouvre la campagne et notifie tous les agents concernés.
     */
    public function ouvrir(Campagne $campagne)
    {
        // Garde-fou : une seule campagne ouverte
        if (Campagne::ouverte()->exists()) {
            return back()->with('error', 'Une campagne est déjà ouverte.');
        }

        DB::transaction(function () use ($campagne) {
            $campagne->update([
                'statut'         => 'OUVERTE',
                'date_ouverture' => now(),
            ]);
        });

        // Notifier tous les agents actifs (après commit, par lots pour la mémoire).
        $notifies = 0;
        $service  = app(NotificationService::class);

        // Tous les agents en poste (actif, en congé, en mission…), hors inactifs / sortis.
        Agent::query()
            ->whereNotIn('statut', ['inactif', 'Inactif', 'sorti', 'Sorti'])
            ->with(['user', 'contacts'])
            ->chunkById(100, function ($agents) use ($service, $campagne, &$notifies) {
                $notifies += $service->envoyer($agents, new CampagneConflitsOuverte($campagne->fresh()));
            });

        return redirect()
            ->route('admin.conflits.campagnes.index')
            ->with('success', "Campagne ouverte. {$notifies} agent(s) notifié(s).");
    }

    public function cloturer(Campagne $campagne)
    {
        // abort_unless($campagne->isOuverte(), 422, 'Campagne non ouverte.');

        $campagne->update([
            'statut'      => 'CLOTUREE',
            'date_cloture' => now(),
        ]);

        return back()->with('success', 'Campagne clôturée.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'libelle'        => ['required', 'string', 'max:150'],
            'annee'          => ['required', 'integer', 'min:2024', 'max:2100'],
            'message'        => ['nullable', 'string', 'max:2000'],
            'date_ouverture' => ['required', 'date'],
            'date_cloture'   => ['nullable', 'date', 'after:date_ouverture'],
        ]);
    }
}
