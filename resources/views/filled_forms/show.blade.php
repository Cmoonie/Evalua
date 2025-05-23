@if (session('success'))
    <div class="mb-4 p-4 bg-green-100 text-green-800 rounded shadow">
        {{ session('success') }}
    </div>
@endif

@extends('layouts.app')

@section('title', 'Ingevulde beoordeling')

@section('content')
    {{--
    filled_forms/show.blade.php

    Toont een ingevuld beoordelingsformulier (FilledForm).
    Dit bevat de naam van de student, het vak, en per component:
    - de ingevulde score (0/3/5)
    - een eventuele opmerking

    Deze view is alleen-lezen en wordt aangeroepen na het opslaan of via een aparte route.
--}}
    <h1 class="text-2xl font-bold mb-4">Beoordeling van {{ $filledForm->student_name }}</h1>
    <p><strong>Vak:</strong> {{ $filledForm->subject }}</p>

    <div class="mt-6 space-y-4">
        @foreach ($filledForm->filledComponents as $component)
            <div class="border p-4 bg-white rounded shadow">
                <p class="font-semibold">{{ $component->component_name }}</p>
                <p>Score: <strong>{{ $component->score }}</strong></p>
                <p>Opmerking: {{ $component->comment ?? 'Geen' }}</p>
            </div>
        @endforeach
    </div>
@endsection
