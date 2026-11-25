<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Wijzig Leveranciergegevens
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('status'))
                        <div class="mb-4 text-green-700">{{ session('status') }}</div>
                        <script>
                            setTimeout(() => window.location.href = @json(route('leveranciers.wijzigen.show', $leverancier->Id)), 3000);
                        </script>
                    @endif

                    <form method="POST" action="{{ route('leveranciers.wijzigen.update', $leverancier->Id) }}" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="Naam" class="block font-medium">Naam</label>
                            <input id="Naam" name="Naam" type="text" value="{{ old('Naam', $leverancier->Naam) }}" required
                                   class="mt-1 block w-full rounded border-gray-300">
                            @error('Naam')
                                <p class="text-red-600 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="ContactPersoon" class="block font-medium">Contactpersoon</label>
                            <input id="ContactPersoon" name="ContactPersoon" type="text" value="{{ old('ContactPersoon', $leverancier->ContactPersoon) }}" required
                                   class="mt-1 block w-full rounded border-gray-300">
                            @error('ContactPersoon')
                                <p class="text-red-600 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="LeverancierNummer" class="block font-medium">Leveranciernummer</label>
                            <input id="LeverancierNummer" name="LeverancierNummer" type="text" value="{{ old('LeverancierNummer', $leverancier->LeverancierNummer) }}" required
                                   class="mt-1 block w-full rounded border-gray-300">
                            @error('LeverancierNummer')
                                <p class="text-red-600 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="Mobiel" class="block font-medium">Mobiel</label>
                            <input id="Mobiel" name="Mobiel" type="text" value="{{ old('Mobiel', $leverancier->Mobiel) }}" required
                                   class="mt-1 block w-full rounded border-gray-300">
                            @error('Mobiel')
                                <p class="text-red-600 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="Straatnaam" class="block font-medium">Straatnaam</label>
                            <input id="Straatnaam" name="Straatnaam" type="text" value="{{ old('Straatnaam', $leverancier->Straatnaam) }}"
                                   class="mt-1 block w-full rounded border-gray-300">
                            @error('Straatnaam')
                                <p class="text-red-600 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="Huisnummer" class="block font-medium">Huisnummer</label>
                            <input id="Huisnummer" name="Huisnummer" type="text" value="{{ old('Huisnummer', $leverancier->Huisnummer) }}"
                                   class="mt-1 block w-full rounded border-gray-300">
                            @error('Huisnummer')
                                <p class="text-red-600 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="Postcode" class="block font-medium">Postcode</label>
                            <input id="Postcode" name="Postcode" type="text" value="{{ old('Postcode', $leverancier->Postcode) }}"
                                   class="mt-1 block w-full rounded border-gray-300">
                            @error('Postcode')
                                <p class="text-red-600 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="Stad" class="block font-medium">Stad</label>
                            <input id="Stad" name="Stad" type="text" value="{{ old('Stad', $leverancier->Stad) }}"
                                   class="mt-1 block w-full rounded border-gray-300">
                            @error('Stad')
                                <p class="text-red-600 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end gap-3 pt-2">
                            <button type="submit" class="rounded border border-gray-400 px-4 py-2">Sla op</button>
                            <a href="{{ route('leveranciers.wijzigen.show', $leverancier->Id) }}"
                               class="rounded border border-gray-400 px-4 py-2">Terug</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
