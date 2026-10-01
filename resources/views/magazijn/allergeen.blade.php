<dialog id="allergenen-kaart" class="rounded-lg p-6 shadow-lg" aria-labelledby="allergenen-titel">
    <h2 id="allergenen-titel" class="text-xl font-semibold mb-4">
        Overzicht Allergenen
    </h2>

    <p><strong>Naam:</strong> <span id="allergenen-productnaam"></span></p>
    <p><strong>Barcode:</strong> <span id="allergenen-barcode"></span></p>

    <div id="allergenen-inhoud" class="mt-4" aria-live="polite"></div>

    <form method="dialog" class="mt-4">
        <button type="submit" class="border rounded px-4 py-2">
            Sluiten
        </button>
    </form>
</dialog>

<script>
    let allergenenVerzoek = 0;

    async function toonAllergenen(knop) {
        const verzoek = ++allergenenVerzoek;
        const kaart = document.getElementById('allergenen-kaart');
        const inhoud = document.getElementById('allergenen-inhoud');

        document.getElementById('allergenen-productnaam').textContent = knop.dataset.naam;
        document.getElementById('allergenen-barcode').textContent = knop.dataset.barcode;
        inhoud.textContent = 'Allergenen laden...';
        if (!kaart.open) {
            kaart.showModal();
        }

        try {
            const response = await fetch(knop.dataset.url, {
                headers: { 'Accept': 'application/json' }
            });

            if (!response.ok) {
                throw new Error('Ophalen mislukt');
            }

            const allergenen = await response.json();
            if (verzoek !== allergenenVerzoek) {
                return;
            }

            if (!Array.isArray(allergenen)) {
                throw new Error('Ongeldig antwoord');
            }

            inhoud.replaceChildren();

            if (allergenen.length === 0) {
                inhoud.textContent = 'Geen allergenen geregistreerd voor dit product.';
                return;
            }

            const tabel = document.createElement('table');
            tabel.className = 'w-full border-collapse text-left';
            const kop = tabel.createTHead().insertRow();

            for (const titel of ['Naam', 'Omschrijving']) {
                const cel = document.createElement('th');
                cel.scope = 'col';
                cel.className = 'border p-3';
                cel.textContent = titel;
                kop.appendChild(cel);
            }

            const rijen = tabel.createTBody();
            for (const allergeen of allergenen) {
                const rij = rijen.insertRow();
                for (const waarde of [allergeen.Naam, allergeen.Omschrijving]) {
                    const cel = rij.insertCell();
                    cel.className = 'border p-3';
                    cel.textContent = waarde;
                }
            }

            inhoud.appendChild(tabel);
        } catch (error) {
            if (verzoek === allergenenVerzoek) {
                inhoud.textContent = 'De allergenen konden niet worden geladen. Sluit de kaart en probeer opnieuw.';
            }
        }
    }
</script>
