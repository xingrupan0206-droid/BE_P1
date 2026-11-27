<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Wijzig Leveranciergegevens
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('status'))
                        <div class="mb-4 text-green-700">{{ session('status') }}</div>
                        <script>
                            setTimeout(() => window.location.href = @json(route('leveranciers.wijzigen.show', $leverancier->Id)), 3000);
                        </script>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 text-red-700">{{ session('error') }}</div>
                        <script>
                            setTimeout(() => window.location.href = @json(route('leveranciers.wijzigen.show', $leverancier->Id)), 3000);
                        </script>
                    @endif

                    <form method="POST" action="{{ route('leveranciers.wijzigen.update', $leverancier->Id) }}">
                        @csrf
                        @method('PUT')

                        <table class="w-full border-collapse">
                            <tbody>
                                <tr class="border-b border-gray-200">
                                    <td class="p-3 w-1/3 font-medium">
                                        <label for="Naam">Naam</label>
                                    </td>
                                    <td class="p-3">
                                        <input id="Naam" name="Naam" type="text" value="{{ old('Naam', $leverancier->Naam) }}" required
                                               class="block w-full rounded border-gray-300">
                                        @error('Naam')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </td>
                                </tr>

                                <tr class="border-b border-gray-200">
                                    <td class="p-3 font-medium">
                                        <label for="ContactPersoon">Contactpersoon</label>
                                    </td>
                                    <td class="p-3">
                                        <input id="ContactPersoon" name="ContactPersoon" type="text" value="{{ old('ContactPersoon', $leverancier->ContactPersoon) }}" required
                                               class="block w-full rounded border-gray-300">
                                        @error('ContactPersoon')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </td>
                                </tr>

                                <tr class="border-b border-gray-200">
                                    <td class="p-3 font-medium">
                                        <label for="LeverancierNummer">Leveranciernummer</label>
                                    </td>
                                    <td class="p-3">
                                        <input id="LeverancierNummer" name="LeverancierNummer" type="text" value="{{ old('LeverancierNummer', $leverancier->LeverancierNummer) }}" required
                                               class="block w-full rounded border-gray-300">
                                        @error('LeverancierNummer')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </td>
                                </tr>

                                <tr class="border-b border-gray-200">
                                    <td class="p-3 font-medium">
                                        <label for="Mobiel">Mobiel</label>
                                    </td>
                                    <td class="p-3">
                                        <input id="Mobiel" name="Mobiel" type="text" value="{{ old('Mobiel', $leverancier->Mobiel) }}" required
                                               class="block w-full rounded border-gray-300">
                                        @error('Mobiel')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </td>
                                </tr>

                                <tr class="border-b border-gray-200">
                                    <td class="p-3 font-medium">
                                        <label for="Straatnaam">Straatnaam</label>
                                    </td>
                                    <td class="p-3">
                                        <input id="Straatnaam" name="Straatnaam" type="text" value="{{ old('Straatnaam', $leverancier->Straatnaam) }}" required
                                               class="block w-full rounded border-gray-300">
                                        @error('Straatnaam')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </td>
                                </tr>

                                <tr class="border-b border-gray-200">
                                    <td class="p-3 font-medium">
                                        <label for="Huisnummer">Huisnummer</label>
                                    </td>
                                    <td class="p-3">
                                        <input id="Huisnummer" name="Huisnummer" type="number" min="1" value="{{ old('Huisnummer', $leverancier->Huisnummer) }}" required
                                               class="block w-full rounded border-gray-300">
                                        @error('Huisnummer')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </td>
                                </tr>

                                <tr class="border-b border-gray-200">
                                    <td class="p-3 font-medium">
                                        <label for="Postcode">Postcode</label>
                                    </td>
                                    <td class="p-3">
                                        <input id="Postcode" name="Postcode" type="text" value="{{ old('Postcode', $leverancier->Postcode) }}" required
                                               class="block w-full rounded border-gray-300">
                                        @error('Postcode')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </td>
                                </tr>

                                <tr class="border-b border-gray-200">
                                    <td class="p-3 font-medium">
                                        <label for="Stad">Stad</label>
                                    </td>
                                    <td class="p-3">
                                        <input id="Stad" name="Stad" type="text" value="{{ old('Stad', $leverancier->Stad) }}" required
                                               class="block w-full rounded border-gray-300">
                                        @error('Stad')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="mt-6 flex items-center justify-between gap-3">
                            <button type="submit" class="rounded border border-gray-400 px-4 py-2">Sla op</button>
                            <div class="flex gap-3">
                                <a href="{{ route('leveranciers.wijzigen.show', $leverancier->Id) }}"
                                   class="rounded border border-gray-400 px-4 py-2">Terug</a>
                                <a href="{{ url('/') }}"
                                   class="rounded border border-gray-400 px-4 py-2">Home</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
