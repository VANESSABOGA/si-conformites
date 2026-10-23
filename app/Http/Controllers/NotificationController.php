<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Notifications applicatives (cloche du header + page "Mes notifications").
 */
class NotificationController extends Controller
{
    /** Page listant toutes les notifications de l'utilisateur. */
    public function index(Request $request): View
    {
        $filtre = $request->query('filtre', 'toutes');

        $query = Auth::user()->notifications()->latest();
        if ($filtre === 'non-lues') {
            $query->whereNull('read_at');
        }

        return view('notifications.index', [
            'notifications' => $query->paginate(20)->withQueryString(),
            'nbNonLues'     => Auth::user()->unreadNotifications()->count(),
            'filtre'        => $filtre,
        ]);
    }

    /** Ouvre une notification : la marque comme lue puis redirige vers sa cible. */
    public function lire(string $id): RedirectResponse
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        $url = $notification->data['url'] ?? null;

        // Redirection uniquement vers cette application ou l'application sœur
        // (la table "notifications" est partagée entre le SI-RH et la Conformité).
        $autorisees = array_filter([url('/'), config('conformite.sirh_url')]);
        foreach ($autorisees as $base) {
            if ($url && str_starts_with($url, rtrim($base, '/'))) {
                return redirect()->away($url);
            }
        }

        return redirect()->route('notifications.index');
    }

    /** Marque toutes les notifications comme lues. */
    public function toutLire(Request $request): RedirectResponse|JsonResponse
    {
        Auth::user()->unreadNotifications()->update(['read_at' => now()]);

        return $request->expectsJson()
            ? response()->json(['non_lues' => 0])
            : back()->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }

    /** Supprime une notification. */
    public function supprimer(Request $request, string $id): RedirectResponse|JsonResponse
    {
        Auth::user()->notifications()->whereKey($id)->delete();

        return $request->expectsJson()
            ? response()->json(['ok' => true])
            : back()->with('success', 'Notification supprimée.');
    }

    /** Compteur + dernières notifications (rafraîchissement AJAX de la cloche). */
    public function resume(): JsonResponse
    {
        $user = Auth::user();

        return response()->json([
            'non_lues'      => $user->unreadNotifications()->count(),
            'notifications' => $user->notifications()
                ->latest()
                ->limit(config('conformite.notifications.limite_cloche', 8))
                ->get()
                ->map(fn ($n) => [
                    'id'      => $n->id,
                    'title'   => $n->data['title'] ?? 'Notification',
                    'message' => $n->data['message'] ?? '',
                    'icon'    => $n->data['icon'] ?? 'bx-bell',
                    'color'   => $n->data['color'] ?? 'primary',
                    'lue'     => $n->read_at !== null,
                    'date'    => $n->created_at->diffForHumans(),
                    'url'     => route('notifications.lire', $n->id),
                ]),
        ]);
    }
}
