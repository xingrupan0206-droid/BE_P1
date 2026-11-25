<?php

namespace App\Http\Controllers;

use App\Providers\leverancier\wijzigen\Wijzigen;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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
        $validated = $request->validate([
            'Naam' => ['required', 'string', 'max:100'],
            'ContactPersoon' => ['required', 'string', 'max:100'],
            'LeverancierNummer' => ['required', 'string', 'max:20', Rule::unique('Leverancier', 'LeverancierNummer')->ignore($leverancierId, 'Id')],
            'Mobiel' => ['required', 'string', 'max:20'],
            'Straatnaam' => ['nullable', 'string', 'max:100'],
            'Huisnummer' => ['nullable', 'string', 'max:10'],
            'Postcode' => ['nullable', 'string', 'max:10'],
            'Stad' => ['nullable', 'string', 'max:100'],
        ]);

        return $this->provider->opslaan($leverancierId, $validated);
    }
}
