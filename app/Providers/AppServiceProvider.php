<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        // DEV / RECETTE : tous les emails vers une seule adresse (ignoré en production).
        $redirection = config('conformite.emails.rediriger_vers');
        if ($redirection && ! $this->app->environment('production')) {
            Mail::alwaysTo($redirection);
        }
    }
}
