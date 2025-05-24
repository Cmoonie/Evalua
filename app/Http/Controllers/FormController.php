<?php

namespace App\Http\Controllers;

use App\Models\Competency;
use App\Models\Form;
use App\Models\GradeLevel;
use Illuminate\Http\Request;
use App\Http\Requests\StoreFormRequest; // Echt super gaaf dit
use Illuminate\Support\Facades\DB;

class FormController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() // Voor een lijst van alle formulieren
    {
        $forms = Form::latest()->get(); // nieuwste eerst
        return view('forms.index', compact('forms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() // De pagina waar we een nieuw formulier aanmaken
    {
        // Deze data willen we laten zien als we het formulier aanmaken
        $gradeLevels  = GradeLevel::orderByDesc('points')->get(); // Hier pakt hij de beoordelingsniveau's met het aantal punten erbij
        $competencies = Competency::orderBy('name')->get(); // Hier pakt hij de bestaande competenties om uit te kiezen

        return view('forms.create', compact('gradeLevels', 'competencies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFormRequest $request) // De informatie die echt opgeslagen wordt als we op "opslaan" drukken
    {
        // Deze haalt hij op uit de FormRequest!
        $validatedData = $request->validated();

        // DB transactie zodat als het niet lukt, we geen half-opgeslagen formulieren in de db hebben
        DB::transaction(function () use ($validatedData) {
            // Stap 1: Formulier opslaan
            $form = Form::create([
                'user_id'     => auth()->id(),
                'title'       => $validatedData['title'],
                'description' => $validatedData['description'],
                'subject'     => $validatedData['subject'],
            ]);

            // Stap 2: Competenties opslaan
            foreach ($validatedData['competencies'] as $comptData) {
                $competency = Competency::create([
                        'name' => $comptData['name'],
                        'domain_description' => $comptData['domain_description'],
                        'rating_scale' => $comptData['rating_scale'],
                        'complexity' => $comptData['complexity'],
                ]);

                // Stap 3: De tussentabel linken
                $form->formCompetencies()->create([
                    'competency_id' => $competency->id,
                ]);

                // Stap 4: Componenten die bij de competenties horen opslaan
                foreach ($comptData['components'] as $compoData) {
                    $component = $competency->components()->create([
                        'name'        => $compoData['name'],
                        'description' => $compoData['description'],
                    ]);

                    // Stap 5: Beoordelingsniveau's die bij de componenten horen opslaan
                    foreach ($compoData['levels'] as $lvlData) {
                        $component->levels()->create([
                            'grade_level_id' => $lvlData['grade_level_id'],
                            'description'    => $lvlData['description'],
                        ]);
                    }
                }
            }
        });
        // Als het is gelukt!
        return redirect()
            ->route('forms.index') // Terug naar de lijst van formulieren
            ->with('success', 'Formulier is aangemaakt!');
    }

    /**
     * Display the specified resource.
     */
    public function show(form $form)
    {
        // We willen het formulier zien, maar ook alle sub informatie
        $form->load('formCompetencies.competency.components.levels'); // Eager loading!!

        return view('forms.show', compact('form'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(form $form)
    {
        // Deze data willen we laten zien als we het formulier aanpassen
        $gradeLevels  = GradeLevel::orderByDesc('points')->get(); // Net als bij de index()
        $competencies = Competency::orderBy('name')->get();

        // Net als bij show()
        $form->load('formCompetencies.competency.components.levels');

        return view('forms.edit', compact('form', 'gradeLevels', 'competencies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreFormRequest $request, Form $form)
    {
        $validatedData = $request->validated();

        DB::transaction(function () use ($validatedData, $form) {
            // Stap 1: update het formulier zelf
            $form->update([
                'title'       => $validatedData['title'],
                'description' => $validatedData['description'],
                'subject'     => $validatedData['subject'],
            ]);

            // Stap 2: verwijder alle gelinkte data uit de tussentabel
            $form->formCompetencies()->delete();

            // Stap 3: Sla alles opnieuw op! Beginnend met de competenties
            foreach ($validatedData['competencies'] as $comptData) {
                $competency = Competency::create([
                    'name'               => $comptData['name'],
                    'domain_description' => $comptData['domain_description'],
                    'rating_scale'       => $comptData['rating_scale'],
                    'complexity'         => $comptData['complexity'],
                ]);

                // STap 4: Opnieuw de tussentabel linken
                $form->formCompetencies()->create([
                    'competency_id' => $competency->id,
                ]);

                // Stap 5: De componenten opslaan
                foreach ($comptData['components'] as $compoData) {
                    $component = $competency->components()->create([
                        'name'        => $compoData['name'],
                        'description' => $compoData['description'],
                    ]);

                    // Stap 6: De beoordelingsniveau's opslaan
                    foreach ($compoData['levels'] as $lvlData) {
                        $component->levels()->create([
                            'grade_level_id' => $lvlData['grade_level_id'],
                            'description'    => $lvlData['description'],
                        ]);
                    }
                }
            }
        });
        // Gelukt!
        return redirect()
            ->route('forms.index')
            ->with('success', 'Formulier is bijgewerkt!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(form $form)
    {
        // Verwijder die handel uit de db
        $form->delete();

        // Terug naar de lijst met formulieren met een succesbericht
        return redirect()
            ->route('forms.index')
            ->with('success', 'Formulier is verwijderd!');
    }
}
