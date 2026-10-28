<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Levering product</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 text-gray-900">
                <div class="mb-6 space-y-1">
                    <p><strong>Product:</strong> {{ $product->Naam }}</p>
                    <p><strong>Leverancier:</strong> {{ $leverancier->Naam }}</p>
                    <p><strong>Contactpersoon:</strong> {{ $leverancier->ContactPersoon }}</p>
                    <p><strong>Mobiel:</strong> {{ $leverancier->Mobiel }}</p>
                </div>
                @if (session('inactive_product'))
                    <div class="mb-4 text-red-700">{{ session('inactive_product') }}</div>
                    <script>setTimeout(() => window.location.href = @json(route('leveranciers.producten', $leverancier->Id)), 4000);</script>
                @endif
                <form method="POST"
                    action="{{ route('leveranciers.levering.store', [$leverancier->Id, $product->Id]) }}"
                    class="space-y-4">
                    @csrf
                    <div><label for="aantal" class="block font-medium">Aantal producteenheden</label><input id="aantal"
                            name="aantal" type="number" min="1" value="{{ old('aantal') }}" required
                            class="mt-1 block w-full rounded border-gray-300">@error('aantal')
                            <p class="text-red-600 text-sm">{{ $message }}</p>@enderror
                    </div>
                    <div><label for="datum_eerst_volgende_levering" class="block font-medium">Datum eerstvolgende
                            levering</label><input id="datum_eerst_volgende_levering"
                            name="datum_eerst_volgende_levering" type="date"
                            value="{{ old('datum_eerst_volgende_levering') }}" required
                            class="mt-1 block w-full rounded border-gray-300">@error('datum_eerst_volgende_levering')
                            <p class="text-red-600 text-sm">{{ $message }}</p>@enderror
                    </div>
                    <div class="flex justify-end gap-3 pt-2"><button type="submit"
                            class="rounded border border-gray-400 px-4 py-2">Sla op</button><a
                            href="{{ route('leveranciers.producten', $leverancier->Id) }}"
                            class="rounded border border-gray-400 px-4 py-2">Terug</a><a href="{{ url('/') }}"
                            class="rounded border border-gray-400 px-4 py-2">Home</a></div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>