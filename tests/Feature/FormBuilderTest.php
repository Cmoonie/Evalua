<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Tests\TestCase;

/**
 * @runInSeparateProcess
 */
class FormBuilderTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);

        $now = Carbon::now();
        DB::table('grade_levels')->insert([
            ['name' => 'Onvoldoende', 'points' => 0, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Voldoende',  'points' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Goed',       'points' => 5, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    /** @test */
    public function create_view_is_accessible_and_shows_expected_fields()
    {
        $response = $this->get(route('forms.create'));

        $response->assertStatus(200);
        $response->assertSee('Nieuw beoordelingsformulier');

        foreach (['Onvoldoende','Voldoende','Goed'] as $lvl) {
            $response->assertSeeText($lvl);
        }
    }

    /** @test */
    public function store_fails_when_required_fields_are_missing()
    {
        $response = $this->post(route('forms.store'), [
            'description' => 'Enige beschrijving',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['title', 'subject', 'oe_code']);
    }

    /** @test */
    public function store_creates_form_and_redirects_on_success()
    {
        $payload = [
            'title'       => 'Toetsformulier Wiskunde',
            'subject'     => 'Wiskunde 101',
            'oe_code'     => 'WIS101',
            'description' => 'Basis toets voor calculus.',
            'competencies' => [
                [
                    'name'               => 'Analyseren',
                    'domain_description' => 'Domein A',
                    'rating_scale'       => 'Schaal A',
                    'complexity'         => 'Complex A',
                    'components'         => [
                        [
                            'name'        => 'Component 1',
                            'description' => 'Omschrijving 1',
                            'levels'      => [
                                ['grade_level_id' => 1, 'description' => 'Onvoldoende desc'],
                                ['grade_level_id' => 2, 'description' => 'Voldoende desc'],
                                ['grade_level_id' => 3, 'description' => 'Goed desc'],
                            ],
                        ],
                    ],
                ],
            ],
        ];


        $response = $this->post(route('forms.store'), $payload);

        $response->assertStatus(302);
        $response->assertRedirect();

        $this->assertDatabaseHas('forms', [
            'title'   => 'Toetsformulier Wiskunde',
            'subject' => 'Wiskunde 101',
            'oe_code' => 'WIS101',
            'user_id' => $this->user->id,
        ]);
    }
}
