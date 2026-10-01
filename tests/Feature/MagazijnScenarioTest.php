<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class MagazijnScenarioTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        DB::statement('CREATE TABLE Product (Id INTEGER PRIMARY KEY, Naam TEXT, Barcode TEXT)');
        DB::statement('CREATE TABLE Magazijn (ProductId INTEGER, VerpakkingsEenheid NUMERIC, AantalAanwezig INTEGER)');
        DB::statement('CREATE TABLE Leverancier (Id INTEGER PRIMARY KEY, Naam TEXT, ContactPersoon TEXT, LeverancierNummer TEXT, Mobiel TEXT)');
        DB::statement('CREATE TABLE ProductPerLeverancier (Id INTEGER PRIMARY KEY, ProductId INTEGER, LeverancierId INTEGER, DatumLevering TEXT, Aantal INTEGER, DatumEerstVolgendeLevering TEXT)');
        DB::statement('CREATE TABLE Allergeen (Id INTEGER PRIMARY KEY, Naam TEXT, Omschrijving TEXT)');
        DB::statement('CREATE TABLE ProductPerAllergeen (ProductId INTEGER, AllergeenId INTEGER)');
    }

    private function medewerker(): void
    {
        $this->actingAs(User::factory()->make(['id' => 1, 'rolename' => 'magazijnmedewerker']));
    }

    public function test_leveringen_staan_op_datum_met_leveranciergegevens(): void
    {
        $this->medewerker();
        DB::table('Product')->insert(['Id' => 1, 'Naam' => 'Mintnopjes', 'Barcode' => '8719587231278']);
        DB::table('Magazijn')->insert(['ProductId' => 1, 'AantalAanwezig' => 453]);
        DB::table('Leverancier')->insert(['Id' => 1, 'Naam' => 'Venco', 'ContactPersoon' => 'Bert', 'LeverancierNummer' => 'L100', 'Mobiel' => '0612345678']);
        DB::table('ProductPerLeverancier')->insert([
            ['Id' => 2, 'ProductId' => 1, 'LeverancierId' => 1, 'DatumLevering' => '2023-04-18', 'Aantal' => 21, 'DatumEerstVolgendeLevering' => '2023-04-25'],
            ['Id' => 1, 'ProductId' => 1, 'LeverancierId' => 1, 'DatumLevering' => '2023-04-09', 'Aantal' => 23, 'DatumEerstVolgendeLevering' => '2023-04-16'],
            ['Id' => 3, 'ProductId' => 2, 'LeverancierId' => 1, 'DatumLevering' => '2023-04-01', 'Aantal' => 999, 'DatumEerstVolgendeLevering' => null],
        ]);

        $this->get(route('magazijn.leveringen', 1))
            ->assertOk()
            ->assertSee(['Mintnopjes', 'Venco', 'Bert', 'L100', '0612345678'])
            ->assertSeeInOrder(['09-04-2023', '23', '16-04-2023', '18-04-2023', '21', '25-04-2023'])
            ->assertDontSee('999')
            ->assertDontSee('setTimeout');
    }

    public function test_winegums_zonder_voorraad_toont_datum_en_terugkeer(): void
    {
        $this->medewerker();
        DB::table('Product')->insert(['Id' => 10, 'Naam' => 'Winegums', 'Barcode' => '8719587327527']);
        DB::table('Magazijn')->insert(['ProductId' => 10, 'AantalAanwezig' => null]);
        DB::table('Leverancier')->insert(['Id' => 4, 'Naam' => 'Basset']);
        DB::table('ProductPerLeverancier')->insert(['Id' => 1, 'ProductId' => 10, 'LeverancierId' => 4, 'DatumLevering' => '2023-04-16', 'Aantal' => 24, 'DatumEerstVolgendeLevering' => '2023-04-30']);

        $this->get(route('magazijn.leveringen', 10))
            ->assertOk()
            ->assertSee('Er is van dit product op dit moment geen voorraad aanwezig, de verwachte eerstvolgende levering is: 30-04-2023')
            ->assertSee('4000', false)
            ->assertSee('window.location.assign', false);
    }

    public function test_zoute_ruitjes_allergenen_zijn_gesorteerd_en_cola_is_leeg(): void
    {
        $this->medewerker();
        DB::table('Allergeen')->insert([
            ['Id' => 5, 'Naam' => 'Soja', 'Omschrijving' => 'Bevat soja'],
            ['Id' => 1, 'Naam' => 'Gluten', 'Omschrijving' => 'Bevat gluten'],
            ['Id' => 4, 'Naam' => 'Lactose', 'Omschrijving' => 'Bevat lactose'],
        ]);
        foreach ([5, 1, 4] as $id) {
            DB::table('ProductPerAllergeen')->insert(['ProductId' => 13, 'AllergeenId' => $id]);
        }

        $this->getJson(route('magazijn.allergenen', 13))->assertExactJson([
            ['Naam' => 'Gluten', 'Omschrijving' => 'Bevat gluten'],
            ['Naam' => 'Lactose', 'Omschrijving' => 'Bevat lactose'],
            ['Naam' => 'Soja', 'Omschrijving' => 'Bevat soja'],
        ]);
        $this->getJson(route('magazijn.allergenen', 5))->assertExactJson([]);
    }

    public function test_overzicht_sorteert_op_barcode_en_bevat_beide_acties(): void
    {
        $this->medewerker();
        DB::table('Product')->insert([
            ['Id' => 1, 'Naam' => 'Mintnopjes', 'Barcode' => '200'],
            ['Id' => 5, 'Naam' => 'Cola Flesjes', 'Barcode' => '100'],
        ]);
        foreach ([1, 5] as $id) {
            DB::table('Magazijn')->insert(['ProductId' => $id, 'VerpakkingsEenheid' => 5, 'AantalAanwezig' => 10]);
        }

        $this->get(route('magazijn.index'))->assertOk()
            ->assertSeeInOrder(['Cola Flesjes', 'Mintnopjes'])
            ->assertSee(route('magazijn.leveringen', 1))
            ->assertSee(route('magazijn.allergenen', 1))
            ->assertSee('In dit product zitten geen stoffen die een allergische reactie kunnen veroorzaken')
            ->assertSee('4000', false);
    }

    public function test_detailroutes_zijn_afgeschermd_en_onbekend_product_geeft_404(): void
    {
        foreach (['magazijn.leveringen', 'magazijn.allergenen'] as $route) {
            $this->get(route($route, 1))->assertRedirect(route('login'));
        }
        $this->actingAs(User::factory()->make(['id' => 2, 'rolename' => 'klant']));
        foreach (['magazijn.leveringen', 'magazijn.allergenen'] as $route) {
            $this->get(route($route, 1))->assertForbidden();
        }
        $this->medewerker();
        $this->get(route('magazijn.leveringen', 999))->assertNotFound();
    }
}
