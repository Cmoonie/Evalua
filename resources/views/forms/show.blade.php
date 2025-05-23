@extends('layouts.app')

@section('title', 'Ingevulde beoordeling')
@php
    $filledForm = (object) [
        'student_name' => 'Chrystella Agyemang',
        'subject' => 'Web Development',
        'filledComponents' => [
            (object) [
                'component_name' => 'Samenwerken',
                'score' => 5,
                'comment' => 'Toont veel initiatief en goede samenwerking'
            ],
            (object) [
                'component_name' => 'Communicatie',
                'score' => 3,
                'comment' => 'Soms onduidelijk in uitleg'
            ],
            (object) [
                'component_name' => 'Reflectie',
                'score' => 0,
                'comment' => null
            ],
        ],
    ];
@endphp
@section('content')
    {{--
    forms/show.blade.php

    Toont een ingevuld beoordelingsformulier (Forms).
    Dit bevat de naam van de student, het vak, en per component:
    - de ingevulde score (0/3/5)
    - een eventuele opmerking

    Deze view is alleen-lezen en wordt aangeroepen na het opslaan of via een aparte route.
--}}
    {{-- Succesmelding na invullen --}}
    <x-alert-success />

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
