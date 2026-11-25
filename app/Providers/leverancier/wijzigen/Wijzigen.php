<?php

namespace App\Providers\leverancier\wijzigen;

use App\Models\Leverancier;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class Wijzigen
{
    public function overzicht(): LengthAwarePaginator
    {
        return DB::table('Leverancier')
            ->select([
                'Id',
                'Naam',
                'ContactPersoon',
                'LeverancierNummer',
                'Mobiel',
                'Straatnaam',
                'Huisnummer',
                'Postcode',
                'Stad',
            ])
            ->orderBy('Naam')
            ->paginate(4);
    }

    public function details(int $leverancierId): Leverancier
    {
        return Leverancier::query()->findOrFail($leverancierId);
    }

    public function wijzigData(int $leverancierId): Leverancier
    {
        return Leverancier::query()->findOrFail($leverancierId);
    }

    public function opslaan(int $leverancierId, array $gegevens): RedirectResponse
    {
        $leverancier = Leverancier::query()->findOrFail($leverancierId);
        $leverancier->update($gegevens);

        return redirect()
            ->route('leveranciers.wijzigen.edit', $leverancier->Id)
            ->with('status', 'De wijzigingen zijn doorgevoerd');
    }
}
