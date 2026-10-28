<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Overzicht leveranciers
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse text-left">
                            <thead>
                                <tr class="border-b border-gray-300">
                                    <th scope="col" class="p-3">Naam</th>
                                    <th scope="col" class="p-3">Contactpersoon</th>
                                    <th scope="col" class="p-3">Leveranciersnummer</th>
                                    <th scope="col" class="p-3">Mobiel</th>
                                    <th scope="col" class="p-3 text-center">Aantal verschillende producten</th>
                                    <th scope="col" class="p-3">Toon producten</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($leveranciers as $leverancier)
                                    <tr class="border-b border-gray-200">
                                        <td class="p-3">{{ $leverancier->Naam }}</td>
                                        <td class="p-3">{{ $leverancier->ContactPersoon }}</td>
                                        <td class="p-3">{{ $leverancier->LeverancierNummer }}</td>
                                        <td class="p-3">{{ $leverancier->Mobiel }}</td>
                                        <td class="p-3 text-center">{{ $leverancier->AantalVerschillendeProducten }}</td>
                <td class="p-3 text-center">
                    <a href="{{ route('leveranciers.producten', $leverancier->Id) }}"
                       aria-label="Producten van {{ $leverancier->Naam }} bekijken"
                       class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-sm transition hover:border-gray-400 hover:bg-gray-50 hover:text-gray-900">
                        Producten bekijken
                    </a>
                </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="p-3">Er zijn geen leveranciers.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
