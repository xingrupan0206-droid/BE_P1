<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class LeverancierLeveringTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        DB::statement('CREATE TABLE Product (Id INTEGER PRIMARY KEY, Naam TEXT, Barcode TEXT, IsActief INTEGER)');
        DB::statement('CREATE TABLE Leverancier (Id INTEGER PRIMARY KEY, Naam TEXT, ContactPersoon TEXT, LeverancierNummer TEXT, Mobiel TEXT)');
        DB::statement('CREATE TABLE Magazijn (Id INTEGER PRIMARY KEY, ProductId INTEGER UNIQUE, VerpakkingsEenheid NUMERIC, AantalAanwezig INTEGER)');
        DB::statement('CREATE TABLE ProductPerLeverancier (Id INTEGER PRIMARY KEY, ProductId INTEGER, LeverancierId INTEGER, DatumLevering TEXT, Aantal INTEGER, DatumEerstVolgendeLevering TEXT)');
    }

    public function test_productoverzicht_toont_actieve_en_inactieve_producten_met_voorraad_en_meest_recente_levering(): void
    {
        $this->actingAs(User::factory()->make(['id' => 1, 'rolename' => 'magazijnmedewerker']));
        DB::table('Leverancier')->insert([
            'Id' => 1,
            'Naam' => 'Venco',
            'ContactPersoon' => 'Bert',
            'LeverancierNummer' => 'L100',
            'Mobiel' => '0612345678',
        ]);
        DB::table('Product')->insert([
            ['Id' => 1, 'Naam' => 'Mintnopjes', 'Barcode' => '100', 'IsActief' => true],
            ['Id' => 2, 'Naam' => 'Schoolkrijt', 'Barcode' => '200', 'IsActief' => true],
            ['Id' => 3, 'Naam' => 'Winegums', 'Barcode' => '300', 'IsActief' => false],
        ]);
        DB::table('Magazijn')->insert([
            ['Id' => 1, 'ProductId' => 1, 'VerpakkingsEenheid' => 5, 'AantalAanwezig' => 40],
            ['Id' => 2, 'ProductId' => 2, 'VerpakkingsEenheid' => 2, 'AantalAanwezig' => 15],
            ['Id' => 3, 'ProductId' => 3, 'VerpakkingsEenheid' => 1, 'AantalAanwezig' => null],
        ]);
        DB::table('ProductPerLeverancier')->insert([
            ['Id' => 1, 'ProductId' => 1, 'LeverancierId' => 1, 'DatumLevering' => '2026-01-01', 'Aantal' => 5, 'DatumEerstVolgendeLevering' => null],
            ['Id' => 2, 'ProductId' => 1, 'LeverancierId' => 1, 'DatumLevering' => '2026-02-01', 'Aantal' => 8, 'DatumEerstVolgendeLevering' => null],
            ['Id' => 3, 'ProductId' => 2, 'LeverancierId' => 1, 'DatumLevering' => '2026-01-15', 'Aantal' => 6, 'DatumEerstVolgendeLevering' => null],
            ['Id' => 4, 'ProductId' => 3, 'LeverancierId' => 1, 'DatumLevering' => '2026-01-20', 'Aantal' => 9, 'DatumEerstVolgendeLevering' => null],
        ]);

        $this->get(route('leveranciers.producten', 1))
            ->assertSeeInOrder(['Mintnopjes', 'Schoolkrijt', 'Winegums'])
            ->assertSee('2026-02-01')
            ->assertSee('40')
            ->assertSee('15')
            ->assertSee('Winegums')
            ->assertSee(route('leveranciers.levering.create', [1, 3]));
    }

    public function test_actieve_productlevering_wordt_opgeslagen_en_verhoogt_ook_null_voorraad(): void
    {
        $this->maakLeverancierProduct();
        DB::table('Magazijn')->insert([
            'Id' => 1,
            'ProductId' => 1,
            'VerpakkingsEenheid' => 3,
            'AantalAanwezig' => null,
        ]);
        $this->actingAs(User::factory()->make(['id' => 2, 'rolename' => 'magazijnmedewerker']));
        $datumLevering = now()->toDateString();

        $response = $this->post(route('leveranciers.levering.store', [1, 1]), [
            'aantal' => 25,
            'datum_eerst_volgende_levering' => '2026-11-05',
        ]);

        $response->assertRedirect(route('leveranciers.producten', 1))
            ->assertSessionHas('status', 'De levering is opgeslagen.');
        $this->assertDatabaseHas('ProductPerLeverancier', [
            'ProductId' => 1,
            'LeverancierId' => 1,
            'Aantal' => 25,
            'DatumLevering' => $datumLevering,
            'DatumEerstVolgendeLevering' => '2026-11-05',
        ]);
        $this->assertDatabaseHas('Magazijn', ['ProductId' => 1, 'AantalAanwezig' => 25]);
    }

    public function test_inactief_product_toont_melding_en_keert_na_vier_seconden_terug_naar_producten(): void
    {
        $this->maakLeverancierProduct(false);
        DB::table('Leverancier')->where('Id', 1)->update(['Naam' => 'Basset']);
        DB::table('Product')->where('Id', 1)->update(['Naam' => 'Winegums']);
        DB::table('Magazijn')->insert([
            'Id' => 1,
            'ProductId' => 1,
            'VerpakkingsEenheid' => 3,
            'AantalAanwezig' => 8,
        ]);
        $this->actingAs(User::factory()->make(['id' => 3, 'rolename' => 'magazijnmedewerker']));

        $this->get(route('leveranciers.producten', 1))
            ->assertSee('Winegums')
            ->assertSee(route('leveranciers.levering.create', [1, 1]));

        $response = $this->followingRedirects()->post(route('leveranciers.levering.store', [1, 1]), [
            'aantal' => 30,
            'datum_eerst_volgende_levering' => now()->toDateString(),
        ]);

        $response->assertSee('Het product Winegums van de leverancier Basset wordt niet meer geproduceerd.')
            ->assertSee('setTimeout', false)
            ->assertSee('4000', false)
            ->assertSee(route('leveranciers.producten', 1), false);
        $this->assertDatabaseCount('ProductPerLeverancier', 1);
        $this->assertDatabaseHas('Magazijn', ['ProductId' => 1, 'AantalAanwezig' => 8]);
    }

    public function test_levering_kan_niet_worden_opgeslagen_voor_een_product_van_een_andere_leverancier(): void
    {
        $this->maakLeverancierProduct();
        DB::table('Leverancier')->insert([
            'Id' => 2,
            'Naam' => 'Basset',
            'ContactPersoon' => 'Joyce',
            'LeverancierNummer' => 'L200',
            'Mobiel' => '0698765432',
        ]);
        DB::table('Magazijn')->insert([
            'Id' => 1,
            'ProductId' => 1,
            'VerpakkingsEenheid' => 3,
            'AantalAanwezig' => 8,
        ]);
        $this->actingAs(User::factory()->make(['id' => 4, 'rolename' => 'magazijnmedewerker']));

        $this->get(route('leveranciers.levering.create', [2, 1]))->assertNotFound();
        $this->post(route('leveranciers.levering.store', [2, 1]), [
            'aantal' => 30,
            'datum_eerst_volgende_levering' => '2026-11-05',
        ])->assertNotFound();

        $this->assertDatabaseCount('ProductPerLeverancier', 1);
        $this->assertDatabaseHas('Magazijn', ['ProductId' => 1, 'AantalAanwezig' => 8]);
    }

    public function test_levering_met_ongeldige_invoer_geeft_validatiefouten_en_verandert_de_database_niet(): void
    {
        $this->maakLeverancierProduct();
        DB::table('Magazijn')->insert([
            'Id' => 1,
            'ProductId' => 1,
            'VerpakkingsEenheid' => 3,
            'AantalAanwezig' => 8,
        ]);
        $this->actingAs(User::factory()->make(['id' => 5, 'rolename' => 'magazijnmedewerker']));

        $this->post(route('leveranciers.levering.store', [1, 1]), [
            'aantal' => 0,
            'datum_eerst_volgende_levering' => 'geen-datum',
        ])->assertSessionHasErrors(['aantal', 'datum_eerst_volgende_levering']);

        $this->assertDatabaseCount('ProductPerLeverancier', 1);
        $this->assertDatabaseHas('Magazijn', ['ProductId' => 1, 'AantalAanwezig' => 8]);
    }

    private function maakLeverancierProduct(bool $actief = true): void
    {
        DB::table('Leverancier')->insert([
            'Id' => 1,
            'Naam' => 'Venco',
            'ContactPersoon' => 'Bert',
            'LeverancierNummer' => 'L100',
            'Mobiel' => '0612345678',
        ]);
        DB::table('Product')->insert([
            'Id' => 1,
            'Naam' => $actief ? 'Mintnopjes' : 'Winegums',
            'Barcode' => '100',
            'IsActief' => $actief,
        ]);
        DB::table('ProductPerLeverancier')->insert([
            'Id' => 1,
            'ProductId' => 1,
            'LeverancierId' => 1,
            'DatumLevering' => '2026-01-01',
            'Aantal' => 5,
            'DatumEerstVolgendeLevering' => null,
        ]);
    }
}
