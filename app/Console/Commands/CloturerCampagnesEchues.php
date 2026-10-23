<?php

namespace App\Console\Commands;

use App\Models\Campagne;
use Illuminate\Console\Command;

class CloturerCampagnesEchues extends Command
{
    protected $signature = 'conformite:cloturer-campagnes';

    protected $description = 'Clôture les campagnes ouvertes dont la date de clôture est dépassée';

    public function handle(): int
    {
        $nb = Campagne::query()
            ->where('statut', 'OUVERTE')
            ->whereNotNull('date_cloture')
            ->whereDate('date_cloture', '<', today())
            ->update(['statut' => 'CLOTUREE', 'updated_at' => now()]);

        $this->info("{$nb} campagne(s) clôturée(s).");

        return self::SUCCESS;
    }
}
