<?php

namespace App\Http\Controllers;

use App\Providers\leverancier\wijzigen\Wijzigen;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeverancierWijzigenController extends Controller
{
    public function __construct(private Wijzigen $provider) {}

    public function index(): View
    {
        return view('leverancier.wijzigen.index', [
            'leveranciers' => $this->provider->overzicht(),
        ]);
    }

    public function show(int $leverancierId): View
    {
        return view('leverancier.wijzigen.details', [
            'leverancier' => $this->provider->details($leverancierId),
        ]);
    }

    public function edit(int $leverancierId): View
    {
        return view('leverancier.wijzigen.edit', [
            'leverancier' => $this->provider->wijzigData($leverancierId),
        ]);
    }

    public function update(Request $request, int $leverancierId): RedirectResponse
    {
        return $this->provider->opslaan($request, $leverancierId);
    }
}
