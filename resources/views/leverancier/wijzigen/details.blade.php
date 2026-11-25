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
                    <div class="mb-6 space-y-2">
                        <p><strong>Naam:</strong> {{ $leverancier->Naam }}</p>
                        <p><strong>Contactpersoon:</strong> {{ $leverancier->ContactPersoon }}</p>
                        <p><strong>Leveranciernummer:</strong> {{ $leverancier->LeverancierNummer }}</p>
                        <p><strong>Mobiel:</strong> {{ $leverancier->Mobiel }}</p>
                        <p><strong>Straatnaam:</strong> {{ $leverancier->Straatnaam ?? '-' }}</p>
                        <p><strong>Huisnummer:</strong> {{ $leverancier->Huisnummer ?? '-' }}</p>
                        <p><strong>Postcode:</strong> {{ $leverancier->Postcode ?? '-' }}</p>
                        <p><strong>Stad:</strong> {{ $leverancier->Stad ?? '-' }}</p>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('leveranciers.wijzigen.edit', $leverancier->Id) }}"
                           class="rounded border border-gray-400 px-4 py-2">Wijzig</a>
                        <a href="{{ route('leveranciers.wijzigen.index') }}"
                           class="rounded border border-gray-400 px-4 py-2">Terug</a>
                        <a href="{{ url('/') }}"
                           class="rounded border border-gray-400 px-4 py-2">Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
