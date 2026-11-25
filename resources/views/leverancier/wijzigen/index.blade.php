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
                                    <th scope="col" class="p-3 text-center">Leverancier Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($leveranciers as $leverancier)
                                    <tr class="border-b border-gray-200">
                                        <td class="p-3">{{ $leverancier->Naam }}</td>
                                        <td class="p-3">{{ $leverancier->ContactPersoon }}</td>
                                        <td class="p-3">{{ $leverancier->LeverancierNummer }}</td>
                                        <td class="p-3">{{ $leverancier->Mobiel }}</td>
                                        <td class="p-3 text-center">
                                            <a href="{{ route('leveranciers.wijzigen.show', $leverancier->Id) }}"
                                               aria-label="Details van {{ $leverancier->Naam }} bekijken"
                                               class="inline-flex h-10 w-10 items-center justify-center rounded-md border border-gray-300 bg-white text-gray-700 shadow-sm transition hover:border-gray-400 hover:bg-gray-50 hover:text-gray-900">
                                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                     fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125" />
                                                </svg>
                                                <span class="sr-only">Details van {{ $leverancier->Naam }} bekijken</span>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-3">Er zijn geen leveranciers.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $leveranciers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
