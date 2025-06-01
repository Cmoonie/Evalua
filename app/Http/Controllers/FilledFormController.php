<?php

namespace App\Http\Controllers;

use App\Models\FilledForm;
use App\Models\FilledComponent;
use App\Models\Component;
use App\Models\GradeLevel;
use App\Models\Form;
use App\Http\Requests\StoreFilledFormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\FilledFormHelper;

class FilledFormController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $forms = Form::latest()->get();

        $filledForms = FilledForm::with([
            'form.formCompetencies.competency.components.levels',
            'filledComponents.gradeLevel'
        ])
            ->where('user_id', auth()->id())
            ->latest()
            ->get()
            ->map(fn($filledForm) => tap($filledForm, function($ff) {
                // Bereken alle competenties en map ze
                $competencies = FilledFormHelper::mapCompetencies($ff);
                $ff->competencies = $competencies;

                // om het totale cijfer en status te laten zien in de lijst
                $finalResult      = FilledFormHelper::calcFinalGrade($competencies);
                $ff->finalGrade   = $finalResult['grade'];
                $ff->finalStatus  = $finalResult['status'];

            }));

        return view('filled_forms.index', compact('filledForms', 'forms'));
    }

//    public function index()
//    {
//        $forms = Form::latest()->get();
//
//        $filledForms = FilledForm::with([
//            'form.formCompetencies.competency.components.levels',
//            'filledComponents.gradeLevel'
//        ])
//            ->where('user_id', auth()->id())
//            ->latest()
//            ->get()
//            ->map(fn($filledForm) => tap($filledForm, function($ff) {
//                $competencies = FilledFormHelper::mapCompetencies($ff);
//                $ff->finalGrade = FilledFormHelper::calcFinalGrade($competencies);
//                $ff->competencies = $competencies;
//                $ff->grade = FilledFormHelper::calcGrade(FilledFormHelper::calcGrandTotal($ff));
//            }));
//
//        return view('filled_forms.index', compact('filledForms', 'forms'));
//    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Form $form)
    {
        $gradeLevels = GradeLevel::all();
        $levels = ['onvoldoende' => 0, 'voldoende' => 3, 'goed' => 5]; // Moet apart anders werkt het niet...

        // Laad competencies en componenten ik word gek
        $form->load('formCompetencies.competency.components.levels');

        return view('filled_forms.create', compact('form', 'gradeLevels', 'levels'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFilledFormRequest $request)
    {
        // We pakken de data van de request
        $validatedData = $request->validated();

        // Alles zit in de transactie zodat we geen half ingevulde formulieren hebben als er iets mis gaat
        $filledForm = DB::transaction(function () use ($validatedData) {
            // Stap 1: Maak het ingevulde formulier aan
            $filledForm = FilledForm::create([
                'form_id'      => $validatedData['form_id'],
                'user_id'      => auth()->id(),
                'student_name' => $validatedData['student_name'],
            ]);

            // Stap 2: Voeg de ingevulde componenten toe met helper methode
            $this->saveFilledData($filledForm, $validatedData);

            // Return het zo we buiten de closure direct toegang hebben
            return $filledForm;
        });

        // Gelukt!
        return redirect()
            ->route('filled_forms.show', $filledForm->id)
            ->with('success', 'Formulier ingevuld!');
    }


    /**
     * Display the specified resource.
     */
    public function show(FilledForm $filledForm)
    {
        $gradeLevels = GradeLevel::all();
        $filledForm->load([
            'form.formCompetencies.competency.components.levels',
            'filledComponents.gradeLevel'
        ]);

        // Helper!! totaal punten en competencies mappen
        $grandTotal   = FilledFormHelper::calcGrandTotal($filledForm);
        $competencies = FilledFormHelper::mapCompetencies($filledForm);

        // calcFinalGrade geeft nu een array ipv alleen een nummer
        $finalResult  = FilledFormHelper::calcFinalGrade($competencies);
        $finalGrade   = $finalResult['grade']; // Geef hier de variable naam zodat we dat straks makkelijk kunnen gebruiken
        $finalStatus  = $finalResult['status'];

        return view('filled_forms.show', compact(
            'filledForm',
            'gradeLevels',
            'grandTotal',
            'competencies',
            'finalGrade',
            'finalStatus'
        ));
    }

//    public function show(FilledForm $filledForm)
//    {
//        $gradeLevels = GradeLevel::all();
//        $filledForm->load([ // eager loading
//            'form.formCompetencies.competency.components.levels',
//            'filledComponents.gradeLevel'
//        ]);
//
//        // Helper!!
//        $grandTotal = FilledFormHelper::calcGrandTotal($filledForm);
//        $competencies = FilledFormHelper::mapCompetencies($filledForm);
//        $finalGrade = FilledFormHelper::calcFinalGrade($competencies);
//
//
//        return view('filled_forms.show', compact(
//            'filledForm',
//            'gradeLevels',
//            'grandTotal',
//            'competencies',
//            'finalGrade'
//        ));
//    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FilledForm $filledForm)
    {
        // Haal het ingevulde formulier op
        $filledForm->load([
            'filledComponents.gradeLevel',
            'form.formCompetencies.competency.components.levels'
        ]);

        $levels = ['onvoldoende' => 0, 'voldoende' => 3, 'goed' => 5];

        // Helper functies
        $grandTotal = FilledFormHelper::calcGrandTotal($filledForm);
        $competencies = FilledFormHelper::mapCompetencies($filledForm);

        // calcFinalGrade geeft nu een array ipv alleen een nummer
        $finalResult  = FilledFormHelper::calcFinalGrade($competencies);
        $finalGrade   = $finalResult['grade']; // Geef hier de variable naam zodat we dat straks makkelijk kunnen gebruiken
        $finalStatus  = $finalResult['status'];


        return view('filled_forms.edit',
            compact('filledForm',
                'levels',
                'finalGrade',
                'finalStatus',
                'grandTotal',
                'competencies',
            ));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(StoreFilledFormRequest $request, FilledForm $filledForm)
    {
        // De data van de request
        $validatedData = $request->validated();

        // Weer op dezelfde manier met DB-transactie om onvolledige formulieren te voorkomen
        DB::transaction(function () use ($validatedData, $filledForm) {
            // Stap 1: Update hoofdformulier
            $filledForm->update([
                'form_id' => $validatedData['form_id'],
                'student_name' => $validatedData['student_name'],
            ]);

            // Stap 2: Verwijder oude componenten uit tussentabel
            $filledForm->filledComponents()->delete();

            // Stap 3: Maak nieuwe filled components aan met helper methode
            $this->saveFilledData($filledForm, $validatedData);
        });

        // Gelukt!
        return redirect()->route('filled_forms.show', $filledForm) // Gelijk naar het geupdate formulier
            ->with('success', 'Formulier is bijgewerkt!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FilledForm $filledForm)
    {
        $filledForm->delete(); // Verwijder die shiiiii

        return redirect()->route('filled_forms.index') // Terug naar de lijst van formulieren
            ->with('success', 'Invulling succesvol verwijderd!');
    }

    // Helper methode
    private function saveFilledData(FilledForm $filledForm, array $validatedData): void
    {
        foreach ($validatedData['components'] as $compo) {
            $filledForm->filledComponents()->create([
                'component_id'   => $compo['component_id'],
                'grade_level_id' => $compo['grade_level_id'],
                'comment'        => $compo['comment'],
            ]);
        }
    }

}
