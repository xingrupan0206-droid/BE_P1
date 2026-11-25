<?php

namespace App\Http\Controllers;

use App\Providers\magazijn\levering\Levering;
use Illuminate\View\View;

class LeveringController extends Controller
{
    public function __construct(private Levering $provider) {}

    public function show(int $productId): View
    {
        return view('magazijn.levering', $this->provider->paginaData($productId));
    }
}
