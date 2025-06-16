<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Form;
use App\Models\User;
use App\Models\GradeLevel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\FormController;

class SaveDataTest extends TestCase
{
    use RefreshDatabase;

    public function test_save_data_saves_competency_structure(): void
    {
        // Arrange: maak gebruiker, formulier en gradelevel aan
        $user = User::factory()->create();

        $form = Form::create([
            'user_id' => $user->id,
            'title' => 'Form X',
            'subject' => 'Testvak',
            'description' => 'Omschrijving',
            'oe_code' => 'OE999',
        ]);

        $gradeLevel = GradeLevel::create([
            'name' => 'Voldoende',
            'points' => 3,
        ]);

        $validatedData = [
            'competencies' => [
                [
                    'name' => 'Samenwerken',
                    'domain_description' => 'Samenwerkingsvaardigheden',
                    'rating_scale' => '1-5',
                    'complexity' => 'MBO 4',
                    'components' => [
                        [
                            'name' => 'Luisteren',
                            'description' => 'Luistert actief',
                            'levels' => [
                                [
                                    'grade_level_id' => $gradeLevel->id,
                                    'description' => 'Luistert voldoende',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        // Act: roep saveData() direct aan
        $controller = new FormController();
        $controller->saveData($form, $validatedData);

        // Assert: controleer of alles in de database staat
        $this->assertDatabaseHas('competencies', [
            'name' => 'Samenwerken',
        ]);

        $this->assertDatabaseHas('components', [
            'name' => 'Luisteren',
        ]);

        $this->assertDatabaseHas('component_levels', [
            'description' => 'Luistert voldoende',
        ]);
    }
}
