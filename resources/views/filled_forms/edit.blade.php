@extends('layouts.app')

@php
    $header = 'Beoordeling bewerken';
@endphp

@section('content')
        <h1 class="text-2xl text-primary font-bold mb-4">
            Beoordeling "{{ $filledForm->form->title }}" aanpassen
        </h1>

        <form action="{{ route('filled_forms.update', $filledForm) }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="form_id" value="{{ $filledForm->form_id }}">


            <div class="mb-4">
                <x-info-card :title="'Studentgegevens'">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="student_name" class="block text-primary text-lg font-semibold mb-2">Naam student</label>
                            <input
                                type="text"
                                name="student_name"
                                id="student_name"
                                value="{{ old('student_name', $filledForm->student_name) }}"
                                class="max-w-80 border border-gray-300 rounded p-2 mb-4 w-full"
                                required>
                        </div>

                        <div>
                            <label for="student_number" class="block text-primary text-lg font-semibold mb-2">Studentnummer</label>
                            <input
                                type="text"
                                name="student_number"
                                id="student_number"
                                value="{{ old('student_number', $filledForm->student_number) }}"
                                class="max-w-80 border border-gray-300 rounded p-2 mb-4 w-full"
                                required>
                        </div>

                        <div>
                            <label for="assignment" class="block text-primary text-lg font-semibold mb-2">Titel opdracht</label>
                            <input
                                type="text"
                                name="assignment"
                                id="assignment"
                                value="{{ old('assignment', $filledForm->assignment) }}"
                                class="max-w-80 border border-gray-300 rounded p-2 mb-4 w-full">
                        </div>
                    </div>

                    <div class="mb-8" x-data="{ open: false }">
                        <button
                            @click.prevent="open = !open"
                            class="bg-primary py-2 px-4 text-lg font-bold text-white shadow-lg hover:bg-secondary mb-4 w-full flex items-center justify-between rounded-lg transition-colors duration-300">
                            <span>Optionele gegevens</span>
                            <svg
                                :class="{'transform rotate-180': open}"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                class="w-6 h-6 transition-transform duration-300 text-white">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="open" x-transition>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="business_name" class="block text-primary text-lg font-semibold mb-2">Bedrijfsnaam</label>
                                    <input
                                        type="text"
                                        name="business_name"
                                        id="business_name"
                                        value="{{ old('business_name', $filledForm->business_name) }}"
                                        class="max-w-80 border border-gray-300 rounded p-2 mb-4 w-full">
                                    <label for="business_location" class="block text-primary text-lg font-semibold mb-2">Bedrijfslocatie</label>
                                    <input
                                        type="text"
                                        name="business_location"
                                        id="business_location"
                                        value="{{ old('business_location', $filledForm->business_location) }}"
                                        class="max-w-80 border border-gray-300 rounded p-2 mb-4 w-full">
                                </div>

                                <div>
                                    <label for="start_date" class="block text-primary text-lg font-semibold mb-2">Startdatum</label>
                                    <input
                                        type="date"
                                        name="start_date"
                                        id="start_date"
                                        value="{{ old('start_date', $filledForm->start_date?->format('Y-m-d')) }}"
                                        class="max-w-80 border border-gray-300 rounded p-2 mb-4 w-full">
                                    <label for="end_date" class="block text-primary text-lg font-semibold mb-2">Einddatum</label>
                                    <input
                                        type="date"
                                        name="end_date"
                                        id="end_date"
                                        value="{{ old('end_date', $filledForm->end_date?->format('Y-m-d')) }}"
                                        class="max-w-80 border border-gray-300 rounded p-2 w-full">
                                </div>
                            </div>
                        </div>
                    </div>
                </x-info-card>
            </div>

            <div class="flex flex-row justify-between gap-6 mb-4">
                <x-info-card :title="'Globale Knock-out Criteria'">
                    <label class="flex items-center">
                        <input
                            type="checkbox"
                            name="brightspace"
                            class="form-checkbox h-5 w-5 text-windy"
                        />
                        <span class="ml-2">Het projectarchief is compleet (op Brightspace) en voldoet aan de gestelde eisen</span>
                    </label>
                    <br>
                    <label class="flex items-center">
                        <input
                            type="checkbox"
                            name="onstage"
                            class="form-checkbox h-5 w-5 text-windy"
                        />
                        <span class="ml-2">Studenten hebben alle stappen in OnStage afgerond</span>
                    </label>
                </x-info-card>
                <x-info-card :title="'Algemene opmerkingen'">
                        <textarea
                            id="comment"
                            name="comment"
                            rows="3"
                            class="block text-sm w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 resize-none" placeholder="Algemene opmerkingen..."
                        >{{ old('comment', $filledForm->comment) }}</textarea>
                </x-info-card>
            </div>

            @foreach($competencies as $comp)
                <div x-data="{ open: false }">
                    <button
                        @click.prevent="open = !open"
                        class="bg-primary py-2 px-4 text-xl font-bold text-white shadow-lg hover:bg-secondary mb-4 mt-4 w-full
                    flex items-center justify-between rounded-lg transition-colors duration-300">
                        <span>Competentie: {{ $comp['name'] }}</span>
                        <div class="flex items-center">
                            <span class="text-sm mr-2" id="competency-points-{{ $comp['id'] }}">{{ $comp['total'] }} pts</span>
                            <svg :class="{'transform rotate-180': open}" xmlns="http://www.w3.org/2000/svg"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                 class="w-6 h-6 transition-transform duration-300 text-white">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </button>

                    <div x-show="open" x-collapse>
                        <div class="grid grid-cols-1 gap-6">
                            <x-info-card :title="'Competentie-specifieke Knock-out Criteria'">
                                <p>
                                    {{ $comp['complexity'] }}
                                </p>
                                <br>
                                <label class="flex items-center">
                                    <input
                                        type="checkbox"
                                        name="knockout"
                                        class="form-checkbox h-5 w-5 text-windy"
                                    />
                                    <span class="ml-2">Voldoet aan de knock-out criteria</span>
                                </label>
                                <br>
                                <label class="flex items-center">
                                    <input
                                        type="checkbox"
                                        name="deliverables"
                                        class="form-checkbox h-5 w-5 text-windy"
                                    />
                                    <span class="ml-2">Deliverables aanwezig</span>
                                </label>
                            </x-info-card>
                        </div>

                        <div class="p-4 border border-gray-200 bg-white rounded-lg">
                            <table class="w-full table-auto text-center border-collapse">
                                <thead>
                                <tr class="bg-gray-50 font-semibold">
                                    <th class="w-1/6 p-2 text-left">Component</th>
                                    @foreach(['onvoldoende', 'voldoende', 'goed'] as $gradeName)
                                        <th class="w-1/6 p-2">{{ ucfirst($gradeName) }}<br>({{ $levels[$gradeName] }})</th>
                                    @endforeach
                                    <th class="w-1/12 p-2">Punten</th>
                                    <th class="w-1/6 p-2 text-left">Opmerking</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($comp['components'] as $component)
                                    <tr class="border-t">
                                        <td class="p-2 text-left align-top">
                                            <div class="font-semibold text-sm">{{ $component['name'] }}</div>
                                            <div class="italic text-xs text-gray-600">{{ $component['description'] }}</div>
                                        </td>
                                        @foreach(['onvoldoende', 'voldoende', 'goed'] as $gradeName)
                                            <td class="p-2">
                                                @foreach($component['levels'] as $level)
                                                    @if(strtolower($level['name']) === $gradeName)
                                                        <button type="button"
                                                                class="grade-button px-3 py-1 rounded-lg border mb-1 hover:opacity-90
                                                            @if($component['grade_level_id'] == $level['id'])
                                                                {{ $gradeName=='goed'?'bg-green-200 border-green-400'
                                                                :($gradeName=='voldoende'
                                                                ?'bg-lime-200 border-lime-400'
                                                                :'bg-red-200 border-red-400') }}
                                                            @endif"
                                                                data-component-id="{{ $component['id'] }}"
                                                                data-competency-id="{{ $comp['id'] }}"
                                                                data-grade-id="{{ $level['id'] }}"
                                                                data-points="{{ $levels[$gradeName] }}"
                                                                data-grade-name="{{ $gradeName }}"
                                                        >
                                                            <span class="text-xs">{{ $level['description'] }}</span>
                                                        </button>
                                                    @endif
                                                @endforeach
                                            </td>
                                        @endforeach
                                        <td class="p-2" id="comp-points-{{ $component['id'] }}">{{ $component['points'] }}</td>
                                        <td class="p-2 align-top">
                                            <textarea name="components[{{ $component['id'] }}][comment]" rows="2" class="w-full border border-gray-300 rounded p-1" placeholder="Typ een opmerking...">{{ old('components.'.$component['id'].'.comment', $component['comment']) }}</textarea>
                                            <input type="hidden" name="components[{{ $component['id'] }}][grade_level_id]" id="grade-level-{{ $component['id'] }}" value="{{ $component['grade_level_id'] }}" required>
                                            <input type="hidden" name="components[{{ $component['id'] }}][component_id]" value="{{ $component['id'] }}">
                                        </td>
                                    </tr>
                                @endforeach
                                <tr class="border-t bg-gray-100 font-semibold">
                                    <td class="p-2 text-left">Totaal punten</td>
                                    <td class="p-2" colspan="3"></td>
                                    <td class="p-2 text-center" id="comp-total-{{ $comp['id'] }}">{{ $comp['total'] }}</td>
                                    <td></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="grid grid-cols-3 gap-6">
                            <x-info-card :title="'Knock-out Criteria & Deliverables'">
                                <p>
                                    {{ $comp['complexity'] }}
                                </p>
                            </x-info-card>
                            <x-info-card :title="'Beoordelingsschaal'">
                                <p>
                                    {{ $comp['rating_scale'] }}
                                </p>
                            </x-info-card>
                            <x-info-card :title="'Domeinbeschrijving'">
                                <p>
                                    {{ $comp['domain_description'] }}
                                </p>
                            </x-info-card>
                        </div>

                    </div>
                </div>
            @endforeach

            <div>
                <label for="student_number" class="block text-primary text-lg font-semibold mb-2">Tweede examinator</label>
                <input
                    type="text"
                    name="examinator"
                    id="examinator"
                    value="{{ old('examinator', $filledForm->examinator) }}"
                    class="max-w-80 border border-gray-300 rounded p-2 mb-4 w-full"
                    required>
            </div>

            <div class="mb-4 text-right text-lg font-semibold">
                Totaal aantal nieuwe punten (alle competenties): <span id="total-points">{{ $grandTotal }}</span>
                <p class="mb-4">
                    <strong>Nieuw cijfer:</strong> <span id="grade-display">{{ $finalGrade }}</span>
                </p>
            </div>

            <div class="text-right">
                <x-primary-button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Opslaan</x-primary-button>
            </div>
        </form>

@endsection


