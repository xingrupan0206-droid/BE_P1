<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Allergeen extends Model
{
    protected $table = 'Allergeen';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    public static function getAllergeen(int $productId): Collection
    {
        return DB::table('Allergeen')
            ->join('ProductPerAllergeen', 'Allergeen.Id', '=', 'ProductPerAllergeen.AllergeenId')
            ->where('ProductPerAllergeen.ProductId', $productId)
            ->select('Allergeen.Naam', 'Allergeen.Omschrijving')
            ->orderBy('Allergeen.Naam')
            ->get();
    }
}
