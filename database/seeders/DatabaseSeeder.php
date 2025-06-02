<?php

namespace Database\Seeders;

use App\Models\Form;
use App\Models\ComponentLevel;
use App\Models\Competency;
use App\Models\Component;
use App\Models\GradeLevel;
use App\Models\FormCompetency;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Een test account voor ons <3
        $user = User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('admin'),
        ]);

        // Beoordelingsniveau's in een array, dit is niet random informatie dus geen factory
        $gradeLevels = [
            ['name' => 'Onvoldoende', 'points' => 0],
            ['name' => 'Voldoende', 'points' => 3],
            ['name' => 'Goed', 'points' => 5],
        ];

        // Deze maken we eerst aan zodat ze later gebruikt kunnen worden
        foreach ($gradeLevels as $level) {
            GradeLevel::create($level);
        }

        // We pakken hier alle gradelevels want dat is handiger dan elke keer in de loop later
        $gradeLevels = GradeLevel::all();

        // We maken 1 test formulier, en we willen dat deze informatie NIET random is, dus gebruiken we geen factory
        $form = Form::factory()->create([
            'user_id' => $user->id,
            'title' => 'Comakership',
            'subject' => 'Comakership 1',
            'oe_code' => 'WFSDAD.CM1.01',
            'description' => 'ADSD Comakership Jaar 2025/2026',
        ]);

        // We willen deze zes competenties gebruiken in ons voorbeeld formulier
        $names = ['Analyseren', 'Adviseren', 'Ontwerpen', 'Realiseren', 'Manage & Control', 'Professional Skills'];
        // Voor elke competentie willen we dat de factory de rest van de informatie die nodig is aanmaakt
        foreach ($names as $name) {
            $competency = Competency::factory()
                ->has(Component::factory()->count(5)) // Elke competentie heeft vijf componenten, gemaakt door de factory
                ->create([
                    'name' => $name, // Hier pakt hij dus de namen uit de array van eerder
                ]);

            // Hier vullen we de tussentabel zodat de competenties aan het formulier gelinkt zijn!
            FormCompetency::create([
                'form_id' => $form->id,
                'competency_id' => $competency->id,
            ]);

            // En hier maken we de tussentabel, voor elke competentie en beoordelingsniveau seeden we de beschrijving
            foreach ($competency->components as $component) {
                foreach ($gradeLevels as $gradeLevel) { // We pakken alle beoordelingsniveau's die we eerder gemaakt hebben
                    ComponentLevel::factory()->create([ // De beschrijving komt uit de factory
                        'component_id' => $component->id, // We linken ze aan elkaar
                        'grade_level_id' => $gradeLevel->id,
                    ]);
                }
            }
        }
    }
}
