<?php

namespace App\Providers\magazijn\index;

use Illuminate\Support\Facades\DB;

class Index
{
    public function paginaData(): array
    {
        return ['producten' => DB::table('Product as p')->join('Magazijn as m', 'm.ProductId', '=', 'p.Id')->orderBy('p.Barcode')->get(['p.Id', 'p.Barcode', 'p.Naam', 'm.VerpakkingsEenheid', 'm.AantalAanwezig'])];
    }
}

