<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class FormCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_a_form(): void
    {
        // Arrange: maak een gebruiker aan
        $user = User::factory()->create();

        // Act: voer een POST-request uit als ingelogde gebruiker
        $response = $this->actingAs($user)->post('/forms', [
            'title'       => 'Testformulier',
            'subject'     => 'Softwareontwikkeling',
            'description' => 'Dit is een testformulier voor de opleiding.',
            'oe_code'     => 'OE1234',
        ]);

        // Assert: controleer dat de gebruiker wordt doorgestuurd (302)
        $response->assertStatus(302);

        // Assert: controleer dat het formulier in de database staat
        $this->assertDatabaseHas('forms', [
            'title'       => 'Testformulier',
            'subject'     => 'Softwareontwikkeling',
            'description' => 'Dit is een testformulier voor de opleiding.',
            'oe_code'     => 'OE1234',
        ]);
    }
}
