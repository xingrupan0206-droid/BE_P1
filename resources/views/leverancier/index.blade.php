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
                       class="inline-flex h-10 w-10 items-center justify-center rounded-md border border-gray-300 bg-white text-gray-700 shadow-sm transition hover:border-gray-400 hover:bg-gray-50 hover:text-gray-900">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="m3.75 7.5 8.25-4.5 8.25 4.5v9L12 21l-8.25-4.5v-9Z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="m3.75 7.5 8.25 4.5 8.25-4.5M12 12v9" />
                        </svg>
                        <span class="sr-only">Producten van {{ $leverancier->Naam }} bekijken</span>
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
