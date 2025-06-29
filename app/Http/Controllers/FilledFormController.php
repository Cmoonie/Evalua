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
use Spatie\LaravelPdf\Facades\Pdf;
use App\Helpers\FilledFormHelper;

class FilledFormController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Haal alle formulieren op, inclusief hun ingevulde formulieren
        $forms = Form::latest()
            ->with([
                'filledForms' => fn($query) => $query->with([
                    'form.formCompetencies.competency.components.levels',
                    'filledComponents.gradeLevel'
                ])
                    ->where('user_id', auth()->id())
                    ->latest()
            ])
            ->get();

        // Helper methodes
        foreach ($forms as $form) {
            foreach ($form->filledForms as $ff) {
                $competencies = FilledFormHelper::mapCompetencies($ff);
                $ff->competencies  = $competencies;
                $finalResult       = FilledFormHelper::calcFinalGrade($competencies);
                $ff->finalGrade    = $finalResult['grade'];
                $ff->finalStatus   = $finalResult['status'];
            }
        }

        return view('filled_forms.index', compact('forms'));
    }

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
                'student_number'     => $validatedData['student_number'],
                'assignment'         => $validatedData['assignment'] ?? null,
                'business_name'      => $validatedData['business_name'] ?? null,
                'business_location'  => $validatedData['business_location'] ?? null,
                'start_date'         => $validatedData['start_date'] ?? null,
                'end_date'           => $validatedData['end_date'] ?? null,
                'examinator'         => $validatedData['examinator'] ?? null,
                'comment'            => $validatedData['comment'] ?? null,
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
        $mapped = FilledFormHelper::mapCompetencies($filledForm);
        $competencies = $mapped['competencies'];
        $numComponents = $mapped['numComponents'];
        $numCompetencies = $mapped['numCompetencies'];

        $grandTotal   = FilledFormHelper::calcGrandTotal($filledForm);

        $gradeData    = FilledFormHelper::calcGrade($grandTotal, $numComponents, $numCompetencies);
        $mid         = $gradeData['mid'];
        $max         = $gradeData['max'];

        // calcFinalGrade geeft nu een array ipv alleen een nummer
        $finalResult = FilledFormHelper::calcFinalGrade($competencies, $numComponents, $numCompetencies);
        $finalGrade   = $finalResult['grade']; // Geef hier de variable naam zodat we dat straks makkelijk kunnen gebruiken
        $finalStatus  = $finalResult['status'];

        return view('filled_forms.show', compact(
            'filledForm',
            'gradeLevels',
            'grandTotal',
            'competencies',
            'finalGrade',
            'finalStatus',
            'mid',
            'max'
        ));
    }

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
        $mapped = FilledFormHelper::mapCompetencies($filledForm);
        $competencies = $mapped['competencies'];
        $numComponents = $mapped['numComponents'];
        $numCompetencies = $mapped['numCompetencies'];

        $grandTotal = FilledFormHelper::calcGrandTotal($filledForm);

        // calcFinalGrade geeft nu een array ipv alleen een nummer
        $finalResult = FilledFormHelper::calcFinalGrade($competencies, $numComponents, $numCompetencies);
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
                'student_number'     => $validatedData['student_number'],
                'assignment'      => $validatedData['assignment'] ?? null,
                'business_name'      => $validatedData['business_name'] ?? null,
                'business_location'  => $validatedData['business_location'] ?? null,
                'start_date'         => $validatedData['start_date'] ?? null,
                'end_date'           => $validatedData['end_date'] ?? null,
                'examinator'         => $validatedData['examinator'] ?? null,
                'comment'            => $validatedData['comment'] ?? null,
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


    // PDF
    public function downloadPDF(FilledForm $filledForm)
    {
        $filledForm->load([
            'form.formCompetencies.competency.components.levels',
            'filledComponents.gradeLevel',
        ]);

        $levels = ['onvoldoende' => 0, 'voldoende' => 3, 'goed' => 5];

        $mapped = FilledFormHelper::mapCompetencies($filledForm);
        $competencies = $mapped['competencies'];
        $numComponents = $mapped['numComponents'];
        $numCompetencies = $mapped['numCompetencies'];

        $grandTotal   = FilledFormHelper::calcGrandTotal($filledForm);

        $gradeData    = FilledFormHelper::calcGrade($grandTotal, $numComponents, $numCompetencies);
        $mid         = $gradeData['mid'];
        $max         = $gradeData['max'];

        // calcFinalGrade geeft nu een array ipv alleen een nummer
        $finalResult = FilledFormHelper::calcFinalGrade($competencies, $numComponents, $numCompetencies);
        $finalGrade   = $finalResult['grade']; // Geef hier de variable naam zodat we dat straks makkelijk kunnen gebruiken
        $finalStatus  = $finalResult['status'];

        return Pdf::view('filled_forms.pdf', [
            'filledForm'   => $filledForm,
            'grandTotal'   => $grandTotal,
            'competencies' => $competencies,
            'finalGrade'   => $finalResult['grade'],
            'finalStatus'  => $finalResult['status'],
            'levels'       => $levels,
            'mid'         => $mid,
            'max'         => $max,
        ])
            ->format('a4')
            ->landscape()
            ->name("Beoordeling_{$filledForm->student_name}.pdf")
            ->download();
    }


    // Geen crud maar wel controller dingen
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


    public function gradeList()
    {
        // Haal alle formulieren op, inclusief hun ingevulde formulieren
        $forms = Form::latest()
            ->with([
                'filledForms' => fn($query) => $query->with([
                    'form.formCompetencies.competency.components.levels',
                    'filledComponents.gradeLevel'
                ])
                    ->where('user_id', auth()->id())
                    ->latest()
            ])
            ->get();

        // Helper methodes
        foreach ($forms as $form) {
            foreach ($form->filledForms as $ff) {
                // Haal competencies en numComponents op uit mapCompetencies
                $mapped = FilledFormHelper::mapCompetencies($ff);
                $competencies = $mapped['competencies'];
                $numComponents = $mapped['numComponents'];
                $numCompetencies = $mapped['numCompetencies'];

                // Bereken het eindcijfer met beide parameters
                $finalResult = FilledFormHelper::calcFinalGrade($competencies, $numComponents, $numCompetencies);


                // Zet properties om in het model voor gebruik in de view
                $ff->competencies = $competencies;
                $ff->finalGrade = $finalResult['grade'];
                $ff->finalStatus = $finalResult['status'];
            }
        }

        return view('filled_forms.gradelist', compact('forms'));
    }

}
