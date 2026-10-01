<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Levering extends Model
{
    protected $table = 'ProductPerLeverancier';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    public static function voorProduct(int $productId): Collection
    {
        return DB::table('ProductPerLeverancier as pl')
            ->join('Leverancier as l', 'l.Id', '=', 'pl.LeverancierId')
            ->where('pl.ProductId', $productId)
            ->orderBy('pl.DatumLevering')
            ->orderBy('pl.Id')
            ->get([
                'pl.DatumLevering', 'pl.Aantal', 'pl.DatumEerstVolgendeLevering',
                'l.Id as LeverancierId', 'l.Naam', 'l.ContactPersoon', 'l.LeverancierNummer', 'l.Mobiel',
            ]);
    }
}
