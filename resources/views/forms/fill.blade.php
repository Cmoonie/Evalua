@extends('layouts.app')

@section('title', 'Nieuwe beoordeling invullen')

@section('content')

    <h1 class="text-2xl font-bold mb-6">Nieuwe beoordeling invullen</h1>
    {{--
        forms/fill.blade.php

        Dit is het formulier voor het invullen van een bestaande beoordelingsmatrix (Form).
        Een docent selecteert of kiest een student, vult per component een score in (0, 3, 5),
        en eventueel opmerkingen. De gegevens worden opgeslagen via FormController.

        Dit is géén formulierstructuur (die zit in 'forms/create'), maar een ingevulde versie (beoordeling).
    --}}
    <form action="{{ route('forms.submit', $form) }}" method="POST" class="space-y-6">
        @csrf

        <!-- Verborgen formulier-ID -->
        <input type="hidden" name="form_id" value="{{ $form->id }}">

        <!-- Studentgegevens -->
        <div>
            <label for="student_name" class="block font-medium">Naam student</label>
            <input type="text" name="student_name" id="student_name" class="w-full p-2 border rounded" required>
        </div>

        <div>
            <label for="subject" class="block font-medium">Vak</label>
            <input type="text" name="subject" id="subject" class="w-full p-2 border rounded" required>
        </div>

        <!-- Competenties en componenten -->
        <hr class="my-6">

        <h2 class="text-xl font-semibold">Competentie: Samenwerken</h2>

        @include('forms._component', ['label' => 'Werkt effectief samen met anderen'])
        @include('forms._component', ['label' => 'Neemt verantwoordelijkheid in de groep'])

        <h2 class="text-xl font-semibold mt-6">Competentie: Communicatie</h2>

        @include('forms._component', ['label' => 'Drukt zich duidelijk uit'])
        @include('forms._component', ['label' => 'Luistert actief naar feedback'])

        <!-- Verzenden -->
        <div>
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">
                Beoordeling opslaan
            </button>
        </div>
    </form>
@endsection
