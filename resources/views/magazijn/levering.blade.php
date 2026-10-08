<x-app-layout>
    {{-- Deze titel verschijnt bovenaan de pagina in de bestaande applicatielayout. --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Levering Informatie</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                {{-- Toon de contactgegevens van de leveranciers van het gekozen product. --}}
                @foreach ($leveranciers as $leverancier)
                    <dl class="mb-4">
                        <div class="mb-2"><dt class="inline font-semibold">Naam leverancier:</dt> <dd class="inline">{{ $leverancier->Naam }}</dd></div>
                        <div class="mb-2"><dt class="inline font-semibold">Contactpersoon leverancier:</dt> <dd class="inline">{{ $leverancier->ContactPersoon }}</dd></div>
                        <div class="mb-2"><dt class="inline font-semibold">Leveranciernummer:</dt> <dd class="inline">{{ $leverancier->LeverancierNummer }}</dd></div>
                        <div class="mb-2"><dt class="inline font-semibold">Mobiel:</dt> <dd class="inline">{{ $leverancier->Mobiel }}</dd></div>
                    </dl>
                @endforeach

                <div class="overflow-x-auto">
                    <table class="w-full border-collapse text-left">
                        <thead>
                            <tr>
                                <th scope="col" class="border p-3">Naam Product</th>
                                <th scope="col" class="border p-3">Datum laatste levering</th>
                                <th scope="col" class="border p-3">Aantal</th>
                                <th scope="col" class="border p-3">Eerstvolgende levering</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Scenario 2: zonder voorraad tonen we de melding in plaats van de leveringsregels. --}}
                            @if ($geenVoorraad)
                                <tr>
                                    <td colspan="4" class="border p-3">Er is van dit product op dit moment geen voorraad aanwezig, de verwachte eerstvolgende levering is: {{ $volgendeLevering ? \Carbon\Carbon::parse($volgendeLevering)->format('d-m-Y') : 'nog niet bekend' }}</td>
                                </tr>
                            @else
                                {{-- Scenario 1: elke levering krijgt een rij. Het model sorteert op leverdatum, van oud naar nieuw. --}}
                                @forelse ($leveringen as $levering)
                                    <tr>
                                        <td class="border p-3">{{ $product->Naam }}</td>
                                        {{-- Carbon toont de datum als dag-maand-jaar. Een ontbrekende volgende datum wordt Nog niet bekend. --}}
                                        <td class="border p-3">{{ \Carbon\Carbon::parse($levering->DatumLevering)->format('d-m-Y') }}</td>
                                        <td class="border p-3">{{ $levering->Aantal }}</td>
                                        <td class="border p-3">{{ $levering->DatumEerstVolgendeLevering ? \Carbon\Carbon::parse($levering->DatumEerstVolgendeLevering)->format('d-m-Y') : 'Nog niet bekend' }}</td>
                                    </tr>
                                {{-- Deze melding verschijnt als het product voorraad heeft, maar geen geregistreerde leveringen. --}}
                                @empty
                                    <tr><td colspan="4" class="border p-3">Er zijn nog geen leveringen geregistreerd.</td></tr>
                                @endforelse
                            @endif
                        </tbody>
                    </table>
                </div>

                {{-- Deze link ziet eruit als een knop en gaat via de benoemde route naar het magazijnoverzicht. --}}
                <a class="inline-block mt-4 border border-gray-300 rounded-md px-4 py-2 text-gray-900 hover:bg-gray-100" href="{{ route('magazijn.index') }}">Terug</a>
            </div>
        </div>
    </div>

    {{-- Alleen bij geen voorraad: stuur de bezoeker na 4000 milliseconden (4 seconden) terug. --}}
    @if ($geenVoorraad)
        <script>
            setTimeout(() => window.location.assign(@json(route('magazijn.index'))), 4000);
        </script>
    @endif
</x-app-layout>
