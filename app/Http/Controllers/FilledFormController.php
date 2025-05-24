<?php

namespace App\Http\Controllers;

use App\Models\FilledForm;
use App\Models\FilledComponent;
use App\Models\Form;
use App\Http\Requests\StoreFilledFormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FilledFormController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Haal alle formulieren op
        $filledForms = FilledForm::with('form')
            ->where('user_id', auth()->id()) // Je mag alleen de formulieren die bij jouw account horen zien
            ->latest() // Nieuwste eerst
            ->get();

        return view('filled_forms.index', compact('filledForms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Haal alle formulieren inclusief formCompetencies -> competency -> components -> levels
        $forms = Form::with(['formCompetencies.competency.components.levels'])->get();

        return view('filled_forms.create', compact('forms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFilledFormRequest $request)
    {
        // We pakken de data van de request
        $validatedData = $request->validated();

        // Alles zit in de transactie zodat we geen half ingevulde formulieren hebben als er iets mis gaat
        DB::transaction(function () use ($validatedData) {
            // Stap 1: Maak het ingevulde formulier aan
            $filledForm = FilledForm::create([
                'form_id'      => $validatedData['form_id'],
                'user_id'      => auth()->id(),
                'student_name' => $validatedData['student_name'],
            ]);

            // Stap 2: Voeg de ingevulde componenten toe met helper methode
            $this->saveFilledData($filledForm, $validatedData);
        });

        // Gelukt!
        return redirect()
            ->route('filled_forms.index') // Terug naar de lijst van formulieren
            ->with('success', 'Formulier ingevuld!');
    }


    /**
     * Display the specified resource.
     */
    public function show(FilledForm $filledForm)
    {
        // Eager load componenten en beoordelingsniveau's
        $filledForm->load(['form.formCompetencies.competency.components.levels', 'filledComponents.gradeLevel']);

        return view('filled_forms.show', compact('filledForm'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FilledForm $filledForm)
    {
        // Haal het ingevulde formulier op
        $filledForm->load('filledComponents');

        // Haal de template van het ingevulde formulier op
        $forms = Form::with(['formCompetencies.competency.components.levels'])->get();

        return view('filled_forms.edit', compact('filledForm', 'forms'));
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
