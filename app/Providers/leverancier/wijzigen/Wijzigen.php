<?php

namespace App\Providers\leverancier\wijzigen;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class Wijzigen
{
    public function overzicht(): Collection
    {
        return DB::table('Leverancier as l')
            ->join('Contact as c', 'c.Id', '=', 'l.ContactId')
            ->select([
                'l.Id',
                'l.Naam',
                'l.ContactPersoon',
                'l.LeverancierNummer',
                'l.Mobiel',
                'l.ContactId',
                'c.Straat as Straatnaam',
                'c.Huisnummer',
                'c.Postcode',
                'c.Stad',
            ])
            ->orderBy('l.Naam')
            ->get();
    }

    public function details(int $leverancierId): object
    {
        $leverancier = DB::table('Leverancier as l')
            ->join('Contact as c', 'c.Id', '=', 'l.ContactId')
            ->where('l.Id', $leverancierId)
            ->select([
                'l.Id',
                'l.Naam',
                'l.ContactPersoon',
                'l.LeverancierNummer',
                'l.Mobiel',
                'l.ContactId',
                'c.Straat as Straatnaam',
                'c.Huisnummer',
                'c.Postcode',
                'c.Stad',
            ])
            ->first();

        abort_if($leverancier === null, 404);

        return $leverancier;
    }

    public function wijzigData(int $leverancierId): object
    {
        return $this->details($leverancierId);
    }

    public function opslaan(Request $request, int $leverancierId): RedirectResponse
    {
        $gegevens = $request->validate([
            'Naam' => ['required', 'string', 'max:100'],
            'ContactPersoon' => ['required', 'string', 'max:100'],
            'LeverancierNummer' => ['required', 'string', 'max:20', Rule::unique('Leverancier', 'LeverancierNummer')->ignore($leverancierId, 'Id')],
            'Mobiel' => ['required', 'string', 'max:20'],
            'Straatnaam' => ['required', 'string', 'max:100'],
            'Huisnummer' => ['required', 'integer', 'min:1'],
            'Postcode' => ['required', 'string', 'max:10'],
            'Stad' => ['required', 'string', 'max:100'],
        ]);

        $leverancier = DB::table('Leverancier')->where('Id', $leverancierId)->first();
        abort_if($leverancier === null, 404);

        DB::transaction(function () use ($leverancier, $leverancierId, $gegevens): void {
            DB::table('Leverancier')
                ->where('Id', $leverancierId)
                ->update([
                    'Naam' => $gegevens['Naam'],
                    'ContactPersoon' => $gegevens['ContactPersoon'],
                    'LeverancierNummer' => $gegevens['LeverancierNummer'],
                    'Mobiel' => $gegevens['Mobiel'],
                ]);

            DB::table('Contact')
                ->where('Id', $leverancier->ContactId)
                ->update([
                    'Straat' => $gegevens['Straatnaam'],
                    'Huisnummer' => $gegevens['Huisnummer'],
                    'Postcode' => $gegevens['Postcode'],
                    'Stad' => $gegevens['Stad'],
                ]);
        });

        return redirect()
            ->route('leveranciers.wijzigen.edit', $leverancierId)
            ->with('status', 'De wijzigingen zijn doorgevoerd');
    }
}
