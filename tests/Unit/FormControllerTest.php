<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Form;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FormControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function index_displays_forms_index_view_with_forms()
    {
        $user = User::factory()->create();

        Form::factory()->count(3)->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get(route('forms.index'));

        $response->assertStatus(200);

        $response->assertViewIs('forms.index');

        $response->assertViewHas('forms', function ($forms) {
            return $forms->count() === 3;
        });
    }
}
