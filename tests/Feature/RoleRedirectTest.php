<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_klant_gaat_na_login_naar_klant_page(): void
    {
        $user = User::factory()->create([
            'email' => 'klant@test.nl',
            'password' => 'password',
            'rolename' => 'klant',
        ]);

        $response = $this->post('/login', [
            'email' => 'klant@test.nl',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('klant.index'));
        $response->assertSessionHasNoErrors();
    }

    public function test_ingelogde_klant_die_login_opent_gaat_naar_klant_page(): void
    {
        $user = User::factory()->create([
            'email' => 'klant2@test.nl',
            'password' => 'password',
            'rolename' => 'klant',
        ]);

        $this->actingAs($user);

        $response = $this->get('/login');

        $response->assertRedirect(route('klant.index'));
    }

    public function test_klant_registreren_gaat_naar_klant_page(): void
    {
        $user = User::factory()->create([
            'email' => 'nieuw@test.nl',
            'password' => 'password',
            'rolename' => 'klant',
        ]);

        $response = $this->post('/login', [
            'email' => 'nieuw@test.nl',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('klant.index'));
    }
}