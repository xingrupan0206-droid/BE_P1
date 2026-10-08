<?php

namespace App\Http\Controllers;

use App\Models\Levering;
use App\Models\Magazijn;
use App\Models\Product;
use Illuminate\View\View;

class LeveringController extends Controller
{
    public function show(int $productId): View
    {
        $product = Product::query()->find($productId);
        abort_if($product === null, 404);

        $voorraad = Magazijn::voorraadVoorProduct($productId);
        $leveringen = Levering::voorProduct($productId);

        return view('magazijn.levering', [
            'product' => $product,
            'geenVoorraad' => $voorraad <= 0,
            'leveringen' => $leveringen,
            'leveranciers' => $leveringen->unique('LeverancierId'),
            'volgendeLevering' => $leveringen->last()?->DatumEerstVolgendeLevering,
        ]);
    }
}
