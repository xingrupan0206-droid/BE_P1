<?php

namespace App\Providers\leverancier\levering;

use App\Models\Leverancier;
use App\Models\Levering as LeveringModel;
use App\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class Levering
{
    public function paginaData(int $leverancierId, int $productId): array
    {
        $leverancier = Leverancier::query()->findOrFail($leverancierId);
        $product = Product::query()
            ->where('Id', $productId)
            ->whereExists(function ($query) use ($leverancierId): void {
                $query->selectRaw('1')
                    ->from('ProductPerLeverancier')
                    ->whereColumn('ProductPerLeverancier.ProductId', 'Product.Id')
                    ->where('ProductPerLeverancier.LeverancierId', $leverancierId);
            })
            ->firstOrFail();

        return ['leverancier' => $leverancier, 'product' => $product];
    }

    public function opslaan(int $leverancierId, int $productId, array $gegevens): RedirectResponse
    {
        ['leverancier' => $leverancier, 'product' => $product] = $this->paginaData($leverancierId, $productId);

        if (! $product->IsActief) {
            return redirect()
                ->route('leveranciers.levering.create', [$leverancierId, $productId])
                ->with('inactive_product', "Het product {$product->Naam} van de leverancier {$leverancier->Naam} wordt niet meer geproduceerd.");
        }

        DB::transaction(function () use ($leverancierId, $productId, $gegevens): void {
            LeveringModel::query()->create([
                'ProductId' => $productId,
                'LeverancierId' => $leverancierId,
                'Aantal' => $gegevens['aantal'],
                'DatumLevering' => now()->toDateString(),
                'DatumEerstVolgendeLevering' => $gegevens['datum_eerst_volgende_levering'],
            ]);

            $aantalRijenBijgewerkt = DB::table('Magazijn')
                ->where('ProductId', $productId)
                ->update([
                    'AantalAanwezig' => DB::raw('COALESCE(AantalAanwezig, 0) + '.(int) $gegevens['aantal']),
                ]);

            if ($aantalRijenBijgewerkt === 0) {
                throw (new ModelNotFoundException())->setModel('Magazijn', [$productId]);
            }
        });

        return redirect()->route('leveranciers.producten', $leverancierId)->with('status', 'De levering is opgeslagen.');
    }
}
