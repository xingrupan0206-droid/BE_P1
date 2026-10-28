<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Magazijn extends Model
{
    protected $table = 'Magazijn';

    protected $primaryKey = 'Id';

    public $timestamps = false;

    public static function voorraadVoorProduct(int $productId): int
    {
        return (int) static::query()
            ->where('ProductId', $productId)
            ->sum('AantalAanwezig');
    }
}
