<?php

namespace App\Providers\magazijn\levering;

use App\Models\Levering as LeveringModel;
use App\Models\Magazijn;
use App\Models\Product;

class Levering
{
    public function paginaData(int $productId): array
    {
        $product = Product::query()->findOrFail($productId);
        $voorraad = Magazijn::voorraadVoorProduct($productId);
        $leveringen = LeveringModel::voorProduct($productId);

        return [
            'product' => $product,
            'geenVoorraad' => $voorraad <= 0,
            'leveringen' => $leveringen,
            'leveranciers' => $leveringen->unique('LeverancierId'),
            'volgendeLevering' => $leveringen->last()?->DatumEerstVolgendeLevering,
        ];
    }
}
