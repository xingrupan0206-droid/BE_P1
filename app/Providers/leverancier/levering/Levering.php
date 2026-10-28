<?php

namespace App\Providers\leverancier\levering;

use App\Models\Leverancier;
use App\Models\Levering as LeveringModel;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class Levering
{
    public function paginaData(int $leverancierId, int $productId): array
    {
        return ['leverancier' => Leverancier::query()->findOrFail($leverancierId), 'product' => Product::query()->findOrFail($productId)];
    }

    public function opslaan(int $leverancierId, int $productId, array $gegevens): RedirectResponse
    {
        $leverancier = Leverancier::query()->findOrFail($leverancierId);
        $product = Product::query()->findOrFail($productId);
        if (! $product->IsActief) return redirect()->route('leveranciers.levering.create', [$leverancierId, $productId])->with('inactive_product', "Het product {$product->Naam} van de leverancier {$leverancier->Naam} wordt niet meer geproduceerd.");
        LeveringModel::query()->create(['ProductId' => $productId, 'LeverancierId' => $leverancierId, 'Aantal' => $gegevens['aantal'], 'DatumLevering' => now()->toDateString(), 'DatumEerstVolgendeLevering' => $gegevens['datum_eerst_volgende_levering']]);
        DB::table('Magazijn')->where('ProductId', $productId)->update(['AantalAanwezig' => DB::raw('AantalAanwezig + ' . (int) $gegevens['aantal'])]);
        return redirect()->route('leveranciers.producten', $leverancierId)->with('status', 'De levering is opgeslagen.');
    }
}

