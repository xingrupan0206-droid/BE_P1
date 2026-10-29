<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class LeverancierOverzichtTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        DB::statement('CREATE TABLE Leverancier (Id INTEGER PRIMARY KEY, Naam TEXT, ContactPersoon TEXT, LeverancierNummer TEXT, Mobiel TEXT)');
        DB::statement('CREATE TABLE ProductPerLeverancier (Id INTEGER PRIMARY KEY, ProductId INTEGER, LeverancierId INTEGER, DatumLevering TEXT, Aantal INTEGER, DatumEerstVolgendeLevering TEXT)');
        DB::statement('CREATE TABLE Product (Id INTEGER PRIMARY KEY, Naam TEXT, Barcode TEXT, IsActief INTEGER)');
        DB::statement('CREATE TABLE Magazijn (Id INTEGER PRIMARY KEY, ProductId INTEGER, VerpakkingsEenheid NUMERIC, AantalAanwezig INTEGER)');
    }

    public function test_overzicht_sorteert_op_unieke_producten_en_toont_ook_leveranciers_zonder_leveringen(): void
    {
        DB::table('Leverancier')->insert([
            ['Id' => 1, 'Naam' => 'Venco', 'ContactPersoon' => 'Bert van Linge', 'LeverancierNummer' => 'L100', 'Mobiel' => '0612345678'],
            ['Id' => 2, 'Naam' => 'Astra Sweets', 'ContactPersoon' => 'Jasper de Monte', 'LeverancierNummer' => 'L200', 'Mobiel' => '0623456789'],
            ['Id' => 3, 'Naam' => 'Haribo', 'ContactPersoon' => 'Sven Stalman', 'LeverancierNummer' => 'L300', 'Mobiel' => '0634567890'],
            ['Id' => 4, 'Naam' => 'De Bron', 'ContactPersoon' => 'Remco Veenstra', 'LeverancierNummer' => 'L400', 'Mobiel' => '0645678901'],
        ]);
        DB::table('ProductPerLeverancier')->insert([
            ['Id' => 1, 'ProductId' => 10, 'LeverancierId' => 1, 'DatumLevering' => '2026-01-01', 'Aantal' => 5, 'DatumEerstVolgendeLevering' => null],
            ['Id' => 2, 'ProductId' => 10, 'LeverancierId' => 1, 'DatumLevering' => '2026-01-02', 'Aantal' => 3, 'DatumEerstVolgendeLevering' => null],
            ['Id' => 3, 'ProductId' => 20, 'LeverancierId' => 1, 'DatumLevering' => '2026-01-03', 'Aantal' => 4, 'DatumEerstVolgendeLevering' => null],
            ['Id' => 4, 'ProductId' => 30, 'LeverancierId' => 2, 'DatumLevering' => '2026-01-04', 'Aantal' => 2, 'DatumEerstVolgendeLevering' => null],
            ['Id' => 5, 'ProductId' => 40, 'LeverancierId' => 3, 'DatumLevering' => '2026-01-05', 'Aantal' => 2, 'DatumEerstVolgendeLevering' => null],
        ]);
        $user = User::factory()->make(['id' => 1, 'rolename' => 'magazijnmedewerker']);

        $response = $this->actingAs($user)->get(route('leveranciers.index'));

        $response->assertSeeInOrder([
            '<td class="p-3">Venco</td>',
            '<td class="p-3 text-center">2</td>',
            '<td class="p-3">Astra Sweets</td>',
            '<td class="p-3 text-center">1</td>',
            '<td class="p-3">Haribo</td>',
            '<td class="p-3 text-center">1</td>',
            '<td class="p-3">De Bron</td>',
            '<td class="p-3 text-center">0</td>',
        ], false)
            ->assertSee(['Bert van Linge', 'L100', '0612345678', 'Aantal verschillende producten', 'Toon producten']);
    }

    public function test_overzicht_is_alleen_toegankelijk_voor_medewerkers_en_admins(): void
    {
        $this->get(route('leveranciers.index'))->assertRedirect(route('login'));

        $klant = User::factory()->make(['id' => 2, 'rolename' => 'klant']);

        $this->actingAs($klant)->get(route('leveranciers.index'))->assertForbidden();
    }

    public function test_detailscherm_van_leverancier_zonder_leveringen_toont_melding_en_keert_terug(): void
    {
        DB::table('Leverancier')->insert([
            'Id' => 5,
            'Naam' => 'Quality Street',
            'ContactPersoon' => 'Johan Nooij',
            'LeverancierNummer' => 'L500',
            'Mobiel' => '0656789012',
        ]);
        $user = User::factory()->make(['id' => 3, 'rolename' => 'magazijnmedewerker']);

        $response = $this->actingAs($user)->get(route('leveranciers.producten', 5));

        $response->assertSee('Dit bedrijf heeft tot nu toe geen producten geleverd aan Jamin')
            ->assertSee('setTimeout', false)
            ->assertSee('3000', false);
    }

    public function test_detailscherm_met_alleen_inactieve_producten_toont_lege_melding_in_plaats_van_producten(): void
    {
        DB::table('Leverancier')->insert([
            'Id' => 6,
            'Naam' => 'Basset',
            'ContactPersoon' => 'Joyce Stelterberg',
            'LeverancierNummer' => 'L600',
            'Mobiel' => '0667890123',
        ]);
        DB::table('Product')->insert([
            'Id' => 10,
            'Naam' => 'Winegums',
            'Barcode' => '8719587327527',
            'IsActief' => false,
        ]);
        DB::table('ProductPerLeverancier')->insert([
            'Id' => 6,
            'ProductId' => 10,
            'LeverancierId' => 6,
            'DatumLevering' => '2026-01-20',
            'Aantal' => 9,
            'DatumEerstVolgendeLevering' => null,
        ]);
        $user = User::factory()->make(['id' => 4, 'rolename' => 'magazijnmedewerker']);

        $response = $this->actingAs($user)->get(route('leveranciers.producten', 6));

        $response->assertSee('Dit bedrijf heeft tot nu toe geen producten geleverd aan Jamin')
            ->assertDontSee('Winegums')
            ->assertSee('setTimeout', false)
            ->assertSee('3000', false);
    }
}
