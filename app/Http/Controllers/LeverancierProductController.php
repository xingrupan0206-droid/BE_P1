<?php

namespace App\Http\Controllers;

use App\Providers\leverancier\product\Product;
use Illuminate\View\View;

class LeverancierProductController extends Controller
{
    public function __construct(private Product $provider) {}

    public function index(int $leverancierId): View
    {
        return view('leverancier.producten', $this->provider->paginaData($leverancierId));
    }
}
