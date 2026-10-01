<?php

namespace App\Http\Controllers;

use App\Models\Levering;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LeveringController extends Controller
{
    public function show(int $productId): View
    {
        $product = DB::table('Product')->where('Id', $productId)->first();
        abort_if($product === null, 404);

        $voorraad = DB::table('Magazijn')->where('ProductId', $productId)->sum('AantalAanwezig');
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
