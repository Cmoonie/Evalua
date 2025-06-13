@extends('layouts.app')

@php
    $header = 'Beoordelingsformulieren';
@endphp

@section('content')
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="p-4 border border-gray-200 bg-white rounded-lg">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold text-primary">Beoordelingsformulieren</h1>
                <a href="{{ route('forms.create') }}">
                    <x-primary-button>
                        Nieuw Formulier
                    </x-primary-button>
                </a>
            </div>

        <x-primary-button onclick="startIntroBeoordelingen()">
            Uitleg over deze pagina
        </x-primary-button>


            @if($forms->isEmpty())
                <p class="text-primary">Geen formulieren gevonden. Tijd om er een te maken!</p>
            @else
                    <table class="w-full bg-white shadow rounded">
                        <thead>
                        <tr class="bg-gray-100 text-primary">
                            <th class="p-3 text-left">Vak</th>
                            <th class="p-3 text-left">Formulier Titel</th>
                            <th class="p-3 text-left">Beschrijving</th>
                            <th class="p-3 text-left">
                                Aangemaakt op
                            </th>
                            <th class="p-3 text-left"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($forms as $form)
                            <tr class="border-t">
                                <td class="p-3 text-lg font-semibold text-secondary hover:text-windy">
                                    <a href="{{ route('forms.show', $form) }}"> {{ $form->subject }}</a>
                                </td>
                                <td class="p-3">
                                    {{ $form->title }}
                                </td>
                                <td>
                                    {{ $form->description }}
                                </td>
                                <td class="p-3">
                                    {{ $form->created_at->format('d-m-Y H:i') }}
                                </td>
                                <td>
                                    <a href="{{ route('filled_forms.create', $form) }}">
                                        <x-primary-button>
                                            Beoordeling maken
                                        </x-primary-button>
                                    </a>
                                </td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
            @endif
        </div>

@endsection
