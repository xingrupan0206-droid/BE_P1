<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;

beforeEach(function () {
    $this->withoutVite();

    DB::statement('CREATE TABLE Product (Id INTEGER PRIMARY KEY, Naam VARCHAR(100) NOT NULL, Barcode VARCHAR(13) NOT NULL UNIQUE)');
    DB::statement('CREATE TABLE Magazijn (Id INTEGER PRIMARY KEY, ProductId INTEGER NOT NULL REFERENCES Product(Id), VerpakkingsEenheid DECIMAL(5,2) NOT NULL, AantalAanwezig INTEGER NULL)');
});

test('magazijn toont voorraad op barcode met lege informatiekolommen', function () {
    $user = User::factory()->create(['rolename' => 'magazijnmedewerker']);
    DB::table('Product')->insert([
        ['Id' => 1, 'Naam' => 'Mintnopjes', 'Barcode' => '0000000000002'],
        ['Id' => 2, 'Naam' => 'Drop', 'Barcode' => '0000000000001'],
        ['Id' => 3, 'Naam' => 'Winegums', 'Barcode' => '0000000000003'],
    ]);
    foreach ([1, 2, 3] as $id) {
        DB::table('Magazijn')->insert(['ProductId' => $id, 'VerpakkingsEenheid' => 2.5, 'AantalAanwezig' => $id === 3 ? null : 42]);
    }

    $response = $this->actingAs($user)->get(route('magazijn.index'));

    $response->assertSeeInOrder(['Drop', 'Mintnopjes', 'Winegums'])
        ->assertSee(['2,50', '42', 'Onbekend', 'Allergeen Info', 'Leverantie Info']);
    expect(substr_count($response->getContent(), '<td class="p-3"></td>'))->toBe(6);
});

test('magazijn toont een melding zonder voorraadregels', function () {
    $user = User::factory()->create(['rolename' => 'admin']);

    $this->actingAs($user)->get(route('magazijn.index'))
        ->assertSee('Er zijn geen producten in het magazijn.');
});

test('magazijn vereist login en magazijnrechten', function () {
    $this->get(route('magazijn.index'))->assertRedirect(route('login'));

    $user = User::factory()->create(['rolename' => 'klant']);
    $this->actingAs($user)->get(route('magazijn.index'))->assertForbidden();
});
