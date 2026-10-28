<?php

namespace App\Http\Controllers;

use App\Providers\leverancier\index\Index;
use Illuminate\View\View;

class LeverancierController extends Controller
{
    public function __construct(private Index $provider) {}

    public function index(): View
    {
        return view('leverancier.index', [
            'leveranciers' => $this->provider->overzicht(),
        ]);
    }

}
