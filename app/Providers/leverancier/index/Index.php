<?php

namespace App\Providers\leverancier\index;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Index
{
    public function overzicht(): Collection
    {
        return DB::table('Leverancier as l')
            ->leftJoin('ProductPerLeverancier as pl', 'pl.LeverancierId', '=', 'l.Id')
            ->select(['l.Id', 'l.Naam', 'l.ContactPersoon', 'l.LeverancierNummer', 'l.Mobiel'])
            ->selectRaw('COUNT(DISTINCT pl.ProductId) as AantalVerschillendeProducten')
            ->groupBy('l.Id', 'l.Naam', 'l.ContactPersoon', 'l.LeverancierNummer', 'l.Mobiel')
            ->orderByDesc('AantalVerschillendeProducten')
            ->orderBy('l.Naam')
            ->get();
    }
}
