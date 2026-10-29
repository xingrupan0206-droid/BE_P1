<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Geleverde producten</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6 space-y-1">
                        <p><strong>Naam leverancier:</strong> {{ $leverancier->Naam }}</p>
                        <p><strong>Contactpersoon:</strong> {{ $leverancier->ContactPersoon }}</p>
                        <p><strong>Leverancier NR:</strong> {{ $leverancier->LeverancierNummer }}</p>
                        <p><strong>Mobiel:</strong> {{ $leverancier->Mobiel }}</p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse text-left">
                            <thead>
                                <tr class="border-b border-gray-300">
                                    <th class="p-3">Naam product</th>
                                    <th class="p-3">Aantal in magazijn</th>
                                    <th class="p-3">Verpakkingseenheid</th>
                                    <th class="p-3">Laatste levering</th>
                                    <th class="p-3 text-center">Nieuwe levering</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="5" class="p-3 text-center font-medium">
                                        Dit bedrijf heeft tot nu toe geen producten geleverd aan Jamin
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <a href="{{ route('leveranciers.index') }}" class="rounded border border-gray-400 px-4 py-2">Terug</a>
                        <a href="{{ url('/') }}" class="rounded border border-gray-400 px-4 py-2">Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        setTimeout(() => window.location.href = @json(route('leveranciers.index')), 3000);
    </script>
</x-app-layout>
