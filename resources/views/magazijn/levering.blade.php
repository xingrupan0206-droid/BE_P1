<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Levering Informatie</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
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
                            @if ($geenVoorraad)
                                <tr>
                                    <td colspan="4" class="border p-3">Er is van dit product op dit moment geen voorraad aanwezig, de verwachte eerstvolgende levering is: {{ $volgendeLevering ? \Carbon\Carbon::parse($volgendeLevering)->format('d-m-Y') : 'nog niet bekend' }}</td>
                                </tr>
                            @else
                                @forelse ($leveringen as $levering)
                                    <tr>
                                        <td class="border p-3">{{ $product->Naam }}</td>
                                        <td class="border p-3">{{ \Carbon\Carbon::parse($levering->DatumLevering)->format('d-m-Y') }}</td>
                                        <td class="border p-3">{{ $levering->Aantal }}</td>
                                        <td class="border p-3">{{ $levering->DatumEerstVolgendeLevering ? \Carbon\Carbon::parse($levering->DatumEerstVolgendeLevering)->format('d-m-Y') : 'Nog niet bekend' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="border p-3">Er zijn nog geen leveringen geregistreerd.</td></tr>
                                @endforelse
                            @endif
                        </tbody>
                    </table>
                </div>

                <a class="inline-block mt-4 text-blue-700 underline" href="{{ route('magazijn.index') }}">Terug naar Overzicht Magazijn Jamin</a>
            </div>
        </div>
    </div>

    @if ($geenVoorraad)
        <script>
            setTimeout(() => window.location.assign(@json(route('magazijn.index'))), 4000);
        </script>
    @endif
</x-app-layout>
