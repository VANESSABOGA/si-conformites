<?php

use App\Http\Controllers\Admin\CampagneController;
use App\Http\Controllers\Admin\DeclarationAdminController;
use App\Http\Controllers\Auth\ConnexionController;
use App\Http\Controllers\DeclarationController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

/*
|==========================================================================
| CONFORMITÉ — Conflits d'intérêts (application autonome)
|==========================================================================
| Les noms de routes (conflits.*, admin.conflits.*) sont IDENTIQUES à ceux
| de l'ancien module du SI-RH : les vues ont été reprises sans modification.
*/

/* ---------- Authentification (comptes du SI-RH) ---------- */

Route::middleware('guest')->group(function () {
    Route::get('/connexion',  [ConnexionController::class, 'create'])->name('login');
    Route::post('/connexion', [ConnexionController::class, 'store'])
        ->middleware('throttle:5,1')
        ->name('login.store');
});

Route::post('/deconnexion', [ConnexionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function () {

    /* ---------- Accueil : back-office pour la Conformité, sinon espace déclarant ---------- */
    Route::get('/', function () {
        return auth()->user()->can('administrer-conformite')
            ? redirect()->route('admin.conflits.index')
            : redirect()->route('conflits.index');
    })->name('dashboard');

    Route::get('/accueil', fn() => redirect()->route('dashboard'))->name('home');

    /* ---------- Notifications applicatives (table partagée avec le SI-RH) ---------- */
    Route::prefix('notifications')->name('notifications.')->controller(NotificationController::class)->group(function () {
        Route::get('/',           'index')->name('index');
        Route::get('/resume',     'resume')->name('resume');
        Route::post('/tout-lire', 'toutLire')->name('tout-lire');
        Route::get('/{id}',       'lire')->name('lire');
        Route::delete('/{id}',    'supprimer')->name('supprimer');
    });

    /* ---------- Espace déclarant (tout agent) ---------- */
    Route::middleware('agent')
        ->prefix('declarations')
        ->name('conflits.')
        ->controller(DeclarationController::class)
        ->group(function () {
            Route::get('/',         'index')->name('index');
            Route::get('/nouvelle', 'create')->name('create');
            Route::get('/accueil',  'create')->name('accueilForm');

            // Auto-déclaration
            Route::get('/auto/create', 'createAuto')->name('auto.create');
            Route::post('/auto/store', 'storeAuto')->name('storeAuto');
            Route::post('/store',      'storeAuto')->name('store'); // nom historique utilisé par certaines vues

            // Dénonciation
            Route::get('/denonciation/create', 'createDenonciation')->name('denonciation.create');
            Route::post('/denonciation/store', 'storeDenonciation')->name('storeDenonciation');

            // PDF (avant /{declaration})
            Route::get('/{declaration}/pdf',             'accusePdf')->name('accuse.pdf');
            Route::get('/{declaration}/declaration-pdf', 'declarationPdf')->name('accuse.declarationPdf');

            Route::get('/{declaration}/edit', 'edit')->name('edit');
            Route::put('/{declaration}',      'update')->name('update');
            Route::get('/{declaration}',      'show')->name('show');
        });

    /* ---------- Back-office Conformité ---------- */
    Route::middleware('conformite.admin')
        ->prefix('admin')
        ->name('admin.conflits.')
        ->group(function () {

            // Campagnes (AVANT /{declaration})
            Route::prefix('campagnes')->name('campagnes.')->controller(CampagneController::class)->group(function () {
                Route::get('/',                      'index')->name('index');
                Route::get('/creer',                 'create')->name('create');
                Route::post('/',                     'store')->name('store');
                Route::patch('/{campagne}/ouvrir',   'ouvrir')->name('ouvrir');
                Route::patch('/{campagne}/cloturer', 'cloturer')->name('cloturer');
                Route::get('/{campagne}',            'show')->name('show');
            });

            // PDF : mêmes documents que côté déclarant (accès contrôlé par authorizeAccess)
            Route::get('/{declaration}/accuse',      [DeclarationController::class, 'accusePdf'])->name('accuse.pdf');
            Route::get('/{declaration}/declaration', [DeclarationController::class, 'declarationPdf'])->name('declaration.pdf');

            Route::controller(DeclarationAdminController::class)->group(function () {
                Route::get('/',                          'index')->name('index');
                Route::get('/export-pdf',                'exportPdf')->name('export.pdf');
                Route::patch('/{declaration}/valider',   'valider')->name('valider');
                Route::patch('/{declaration}/rejeter',   'rejeter')->name('rejeter');
                Route::get('/{declaration}',             'show')->name('show');
            });
        });
});
