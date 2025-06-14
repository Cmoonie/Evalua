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

    <x-secondary-button id="help-forms-button">
        Begin rondleiding
    </x-secondary-button>


        <div class="p-4 border border-gray-200 mt-2 bg-white rounded-lg">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold text-primary">Alle Formulieren</h1>
                <a href="{{ route('forms.create') }}">
                    <x-secondary-button id="new-form-button">
                        Nieuw Formulier
                    </x-secondary-button>
                </a>
            </div>


            @if($forms->isEmpty())
                <p class="text-primary">Geen formulieren gevonden. Tijd om er een te maken!</p>
            @else
                <table class="w-full bg-white shadow rounded" id="form-table">
                    <thead>
                        <tr class="bg-gray-100 text-primary">
                            <th class="p-3 text-left">Vak</th>
                            <th class="p-3 text-left">Formulier Titel</th>
                            <th class="p-3 text-left">Beschrijving</th>
                            <th class="p-3 text-left">Aangemaakt op</th>
                            <th class="p-3 text-left">Aangepast op</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($forms as $form)
                        <tr class="border-t">
                            <td class="p-3 text-lg font-semibold text-secondary hover:text-windy" id="form-link">
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
                                @if($form->created_at->ne($form->updated_at))
                                    {{ $form->updated_at->format('Y-m-d H:i') }}
                                @else
                                    Niet aangepast
                                @endif
                            </td>
                            <td class="p-3 text-right">
                                <a href="{{ route('filled_forms.create', $form) }}">
                                    <x-secondary-button id="student-beoordelen">
                                        Student Beoordelen
                                    </x-secondary-button>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
@endsection


{{--@push('scripts')--}}
{{--    <script>--}}
{{--        document.addEventListener('DOMContentLoaded', () => {--}}
{{--            if (sessionStorage.getItem('startFormIntro') === 'true') {--}}
{{--                sessionStorage.removeItem('startFormIntro'); // Eenmalig uitvoeren--}}
{{--                setTimeout(() => {--}}
{{--                    if (typeof window.startIntro === 'function') {--}}
{{--                        window.startIntro();--}}
{{--                    } else {--}}
{{--                        console.warn("startIntro niet gevonden.");--}}
{{--                    }--}}
{{--                }, 300); // Kleine vertraging voor de zekerheid--}}
{{--            }--}}
{{--        });--}}
{{--    </script>--}}
{{--@endpush--}}

