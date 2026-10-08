<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Magazijn extends Model
{
    protected $table = 'Magazijn';

    protected $primaryKey = 'Id';

    public $timestamps = false;

    public static function overzicht(): Collection
    {
        return DB::table('Product as p')
            ->join('Magazijn as m', 'm.ProductId', '=', 'p.Id')
            ->orderBy('p.Barcode')
            ->get(['p.Id', 'p.Barcode', 'p.Naam', 'm.VerpakkingsEenheid', 'm.AantalAanwezig']);
    }

    public static function voorraadVoorProduct(int $productId): int
    {
        return (int) static::query()
            ->where('ProductId', $productId)
            ->sum('AantalAanwezig');
    }
}
