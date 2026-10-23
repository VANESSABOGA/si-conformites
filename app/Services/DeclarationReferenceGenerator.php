<?php

namespace App\Services;

use App\Models\Declaration;

class DeclarationReferenceGenerator
{
    /**
     * Génère une référence unique du type :
     *  - DECL-XXXXXXXX   (auto-déclaration standard)
     *  - DEN-XXXXXXXX    (dénonciation)
     */
    public function generate(string $prefix = 'DECL'): string
    {
        do {
            $reference = sprintf('%s-%s', $prefix, strtoupper(bin2hex(random_bytes(4))));
        } while (Declaration::where('reference', $reference)->exists());

        return $reference;
    }
}