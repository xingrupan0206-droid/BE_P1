<?php

namespace App\Http\Controllers;

use App\Providers\leverancier\levering\Levering;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeverancierLeveringController extends Controller
{
    public function __construct(private Levering $provider) {}

    public function create(int $leverancierId, int $productId): View
    {
        return view('leverancier.levering', $this->provider->paginaData($leverancierId, $productId));
    }

    public function store(Request $request, int $leverancierId, int $productId): RedirectResponse
    {
        $validated = $request->validate([
            'aantal' => ['required', 'integer', 'min:1'],
            'datum_eerst_volgende_levering' => ['required', 'date'],
        ]);

        return $this->provider->opslaan($leverancierId, $productId, $validated);
    }
}
