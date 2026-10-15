<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Leverancier extends Model
{
    protected $table = 'Leverancier';

    protected $primaryKey = 'Id';

    public $timestamps = false;

    public static function overzicht(): Collection
    {
        return DB::table('Leverancier as l')
            ->leftJoin('ProductPerLeverancier as pl', 'pl.LeverancierId', '=', 'l.Id')
            ->select([
                'l.Id',
                'l.Naam',
                'l.ContactPersoon',
                'l.LeverancierNummer',
                'l.Mobiel',
            ])
            ->selectRaw('COUNT(DISTINCT pl.ProductId) as AantalVerschillendeProducten')
            ->groupBy('l.Id', 'l.Naam', 'l.ContactPersoon', 'l.LeverancierNummer', 'l.Mobiel')
            ->orderByDesc('AantalVerschillendeProducten')
            ->orderBy('l.Naam')
            ->get();
    }
}
