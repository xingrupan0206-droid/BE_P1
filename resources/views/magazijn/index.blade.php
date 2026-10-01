<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Overzicht Magazijn Jamin
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
                                    <th scope="col" class="p-3">Barcode</th>
                                    <th scope="col" class="p-3">Naam</th>
                                    <th scope="col" class="p-3">Verpakkingseenheid</th>
                                    <th scope="col" class="p-3">Aantal aanwezig</th>
                                    <th scope="col" class="p-3">Allergeen Info</th>
                                    <th scope="col" class="p-3">Leverantie Info</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($producten as $product)
                                    <tr class="border-b border-gray-200">
                                        <td class="p-3">{{ $product->Barcode }}</td>
                                        <td class="p-3">{{ $product->Naam }}</td>
                                        <td class="p-3">{{ number_format($product->VerpakkingsEenheid, 2, ',', '.') }}</td>
                                        <td class="p-3">{{ $product->AantalAanwezig ?? 'Onbekend' }}</td>
                                        <td class="p-3">
                                                <button
                                                    type="button"
                                                    onclick="toonAllergenen(this)"
                                                    class="text-red-600 text-xl font-bold" aria-label="Allergenen bekijken"
                                                    data-naam="{{ $product->Naam }}"
                                                    data-barcode="{{ $product->Barcode }}"
                                                    data-url="{{ route('magazijn.allergenen', $product->Id) }}"
                                                >
                                                    <span aria-hidden="true" style="color: #dc2626;">×</span>
                                                </button>
                                        </td>
                                        <td class="p-3"><a class="text-blue-700 text-xl font-bold" href="{{ route('magazijn.leveringen', $product->Id) }}" aria-label="Leveringsinformatie bekijken"><span aria-hidden="true">?</span></a></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="p-3">Er zijn geen producten in het magazijn.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('magazijn.allergeen')
</x-app-layout>
