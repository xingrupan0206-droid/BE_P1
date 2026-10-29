<?php

namespace App\Providers\leverancier\product;

use App\Models\Leverancier;
use Illuminate\Support\Facades\DB;

class Product
{
    public function view(array $data): string
    {
        return $data['producten']->isEmpty()
            ? 'leverancier.producten-leeg'
            : 'leverancier.producten';
    }

    public function paginaData(int $leverancierId): array
    {
        return ['leverancier' => Leverancier::query()->findOrFail($leverancierId), 'producten' => DB::table('ProductPerLeverancier as pl')->join('Product as p', 'p.Id', '=', 'pl.ProductId')->leftJoin('Magazijn as m', 'm.ProductId', '=', 'p.Id')->where('pl.LeverancierId', $leverancierId)->select(['p.Id as ProductId', 'p.Naam as ProductNaam', 'm.AantalAanwezig', 'm.VerpakkingsEenheid'])->selectRaw('MAX(pl.DatumLevering) as LaatsteLevering')->groupBy('p.Id', 'p.Naam', 'm.AantalAanwezig', 'm.VerpakkingsEenheid')->orderByDesc('m.AantalAanwezig')->orderBy('p.Naam')->get()];
    }
}
