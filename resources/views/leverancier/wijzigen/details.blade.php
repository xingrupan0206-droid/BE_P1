<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Leverancier Details
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse text-left">
                            <tbody>
                                <tr class="border-b border-gray-200">
                                    <th scope="row" class="p-3 w-1/3 font-semibold">Naam</th>
                                    <td class="p-3">{{ $leverancier->Naam }}</td>
                                </tr>
                                <tr class="border-b border-gray-200">
                                    <th scope="row" class="p-3 font-semibold">Contactpersoon</th>
                                    <td class="p-3">{{ $leverancier->ContactPersoon }}</td>
                                </tr>
                                <tr class="border-b border-gray-200">
                                    <th scope="row" class="p-3 font-semibold">Leveranciernummer</th>
                                    <td class="p-3">{{ $leverancier->LeverancierNummer }}</td>
                                </tr>
                                <tr class="border-b border-gray-200">
                                    <th scope="row" class="p-3 font-semibold">Mobiel</th>
                                    <td class="p-3">{{ $leverancier->Mobiel }}</td>
                                </tr>
                                <tr class="border-b border-gray-200">
                                    <th scope="row" class="p-3 font-semibold">Straatnaam</th>
                                    <td class="p-3">{{ $leverancier->Straatnaam ?? '-' }}</td>
                                </tr>
                                <tr class="border-b border-gray-200">
                                    <th scope="row" class="p-3 font-semibold">Huisnummer</th>
                                    <td class="p-3">{{ $leverancier->Huisnummer ?? '-' }}</td>
                                </tr>
                                <tr class="border-b border-gray-200">
                                    <th scope="row" class="p-3 font-semibold">Postcode</th>
                                    <td class="p-3">{{ $leverancier->Postcode ?? '-' }}</td>
                                </tr>
                                <tr class="border-b border-gray-200">
                                    <th scope="row" class="p-3 font-semibold">Stad</th>
                                    <td class="p-3">{{ $leverancier->Stad ?? '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 flex items-center justify-between gap-3">
                        <a href="{{ route('leveranciers.wijzigen.edit', $leverancier->Id) }}"
                           class="rounded border border-gray-400 px-4 py-2">Wijzig</a>
                        <div class="flex gap-3">
                            <a href="{{ route('leveranciers.wijzigen.index') }}"
                               class="rounded border border-gray-400 px-4 py-2">Terug</a>
                            <a href="{{ url('/') }}"
                               class="rounded border border-gray-400 px-4 py-2">Home</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
