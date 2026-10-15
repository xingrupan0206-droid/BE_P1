<?php

namespace App\Http\Controllers;

use App\Models\Leverancier;
use Illuminate\View\View;

class LeverancierController extends Controller
{
    public function index(): View
    {
        return view('leverancier.index', [
            'leveranciers' => Leverancier::overzicht(),
        ]);
    }
}
