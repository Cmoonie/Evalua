@extends('layouts.app')

@php
    $header = 'Beoordeling bewerken';
@endphp

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl text-primary font-bold mb-4">
            Beoordelingsformulier {{ $filledForm->form->title }} aanpassen
        </h1>
        <div class="flex flex-wrap justify-between lg:flex-nowrap ">
            <div>
                <!-- Basisinformatie formulier -->
                <p class="mb-4"><strong>Vak:</strong> {{ $filledForm->form->subject }}</p>
                <p class="mb-4"><strong>OE-code:</strong> {{ $filledForm->form->oe_code }}</p>
                <p class="mb-4"><strong>Beschrijving:</strong> {{ $filledForm->form->description }}</p>
                <p class="mb-4">
                    <strong>Datum ingevuld:</strong>
                    {{ $filledForm->created_at->format('Y-m-d H:i') }}
                </p>
                @if($filledForm->created_at->ne($filledForm->updated_at))
                    <p class="mb-4">
                        <strong>Datum aangepast:</strong>
                        {{ $filledForm->updated_at->format('Y-m-d H:i') }}
                    </p>
                @endif
            </div>
            <div>
                <!-- Studentgegevens -->
                <p class="mb-4"><strong>Studentnaam:</strong> {{ $filledForm->student_name }}</p>
                <p class="mb-4"><strong>Studentnummer:</strong> {{ $filledForm->student_number }}</p>
                <p class="mb-4"><strong>Titel opdracht:</strong> {{ $filledForm->assignment ?? '–' }}</p>
                <p class="mb-4"><strong>Bedrijfsnaam:</strong> {{ $filledForm->business_name ?? '–' }}</p>
                <p class="mb-4"><strong>Bedrijfslocatie:</strong> {{ $filledForm->business_location ?? '–' }}</p>
                <p class="mb-4"><strong>Startdatum:</strong> {{ $filledForm->start_date ? $filledForm->start_date->format('Y-m-d') : '–' }}</p>
                <p class="mb-4"><strong>Einddatum:</strong> {{ $filledForm->end_date ? $filledForm->end_date->format('Y-m-d') : '–' }}</p>
            </div>

            <div class="mb-8">
                <table class="mb-4 table-auto border-collapse">
                    <thead>
                    <tr class="bg-gray-200 text-primary">
                        <th class="p-2 text-left">Eindbeoordeling</th>
                        <th class="p-2 text-center">Totaal te behalen punten</th>
                        <th class="p-2 text-center">Behaald</th>
                        <th class="p-2 text-center">Minimale punten eis</th>
                        <th class="p-2 text-center">Status</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($competencies as $comp)
                            <tr class="border-b {{ $comp['stateClass'] }}">
                                <td class="p-2">Comp. {{ $comp['name'] }}</td>
                                <td class="p-2 text-center"> 25 </td>
                                <td class="p-2 text-center"> {{ $comp['total'] }} </td>
                                <td class="p-2 text-center">12</td>
                                <td class="p-2 text-center"> {{ $comp['statusText'] }} </td>
                            </tr>
                        @endforeach
                        <tr class="bg-gray-200 font-semibold text-primary">
                            <td class="p-2 text-start">Cijfer: {{ $finalGrade }}</td>
                            <td class="p-2 text-center"> 150 </td>
                            <td class="p-2 text-center"> {{ $grandTotal }} </td>
                            <td class="p-2 text-center">72</td>
                            <td class="p-2 text-center"> {{ $finalStatus }} </td>
                        </tr>
                    </tbody>
                </table>
                <p class="mb-4  text-sm text-primary">
                    <strong>Toelichting: </strong>
                    <11 = Onvoldoende || 12 - 16 = Voldoende || 17 - 25 = Goed. Max 1 onvoldoende per competentie.
                    <br>
                    Bij twee of meer onvoldoendes in één competentie wordt de competentie automatisch onvoldoende.
                    <br>
                    Bij twee onvoldoende competenties is het maximaal te behalen cijfer een 5,0.
                </p>
            </div>
        </div>



        <form action="{{ route('filled_forms.update', $filledForm) }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="form_id" value="{{ $filledForm->form_id }}">

            <div class="mb-6">
                <!-- Naam student -->
                <label for="student_name" class="block text-lg font-semibold mb-2">Naam student</label>
                <input
                    type="text"
                    name="student_name"
                    id="student_name"
                    class="max-w-80 border border-gray-300 rounded p-2 mb-4 w-full"
                    value="{{ old('student_name', $filledForm->student_name) }}"
                    required
                >

                <!-- Studentnummer -->
                <label for="student_number" class="block text-lg font-semibold mb-2">Studentnummer</label>
                <input
                    type="text"
                    name="student_number"
                    id="student_number"
                    class="max-w-80 border border-gray-300 rounded p-2 mb-4 w-full"
                    value="{{ old('student_number', $filledForm->student_number) }}"
                    required
                >

                <!-- Opdracht -->
                <label for="assignment" class="block text-lg font-semibold mb-2">Opdracht</label>
                <input
                    type="text"
                    name="assignment"
                    id="assignment"
                    class="max-w-80 border border-gray-300 rounded p-2 mb-4 w-full"
                    value="{{ old('assignment', $filledForm->assignment) }}"
                >

                <!-- Bedrijfsnaam (optioneel) -->
                <label for="business_name" class="block text-lg font-semibold mb-2">Bedrijfsnaam (optioneel)</label>
                <input
                    type="text"
                    name="business_name"
                    id="business_name"
                    class="max-w-80 border border-gray-300 rounded p-2 mb-4 w-full"
                    value="{{ old('business_name', $filledForm->business_name) }}"
                >

                <!-- Bedrijfslocatie (optioneel) -->
                <label for="business_location" class="block text-lg font-semibold mb-2">Bedrijfslocatie (optioneel)</label>
                <input
                    type="text"
                    name="business_location"
                    id="business_location"
                    class="max-w-80 border border-gray-300 rounded p-2 mb-4 w-full"
                    value="{{ old('business_location', $filledForm->business_location) }}"
                >

                <!-- Startdatum (optioneel) -->
                <label for="start_date" class="block text-lg font-semibold mb-2">Startdatum (optioneel)</label>
                <input
                    type="date"
                    name="start_date"
                    id="start_date"
                    class="max-w-80 border border-gray-300 rounded p-2 mb-4 w-full"
                    value="{{ old('start_date', $filledForm->start_date?->format('Y-m-d')) }}"

                >

                <!-- Einddatum (optioneel) -->
                <label for="end_date" class="block text-lg font-semibold mb-2">Einddatum (optioneel)</label>
                <input
                    type="date"
                    name="end_date"
                    id="end_date"
                    class="max-w-80 border border-gray-300 rounded p-2 w-full"
                    value="{{ old('end_date', $filledForm->end_date?->format('Y-m-d')) }}"
                >
            </div>


            <div class="mb-6">
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
            </div>

            @foreach($competencies as $competency)
                <div class="mb-8" x-data="{ open: false }">
                    <button
                        @click.prevent="open = !open"
                        class="bg-primary py-2 px-4 text-xl font-bold text-white shadow-lg hover:bg-secondary mb-4 w-full
                    flex items-center justify-between rounded-lg transition-colors duration-300">
                        <span>Competentie: {{ $competency['name'] }}</span>
                        <div class="flex items-center">
                            <span class="text-sm mr-2" id="competency-points-{{ $competency['id'] }}">{{ $competency['total'] }} pts</span>
                            <svg :class="{'transform rotate-180': open}" xmlns="http://www.w3.org/2000/svg"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                 class="w-6 h-6 transition-transform duration-300 text-white">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </button>

                    <div x-show="open" x-collapse>
                        <div class="grid grid-cols-1 gap-6 mt-8">
                            <x-info-card :title="'Competentie-specifieke Knock-out Criteria'">
                                <p>
                                    {{ $competency['complexity'] }}
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

                        <div class="p-4 border mt-8 border-gray-200 bg-white rounded-lg">
                            <table class="w-full table-auto text-center border-collapse">
                                <thead>
                                <tr class="bg-gray-50 font-semibold">
                                    <th class="p-2 text-left">Component</th>
                                    @foreach(['onvoldoende', 'voldoende', 'goed'] as $gradeName)
                                        <th class="p-2">{{ ucfirst($gradeName) }}<br>({{ $levels[$gradeName] }})</th>
                                    @endforeach
                                    <th class="p-2">Punten</th>
                                    <th class="p-2 text-left">Opmerking</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($competency['components'] as $component)
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
                                                                data-competency-id="{{ $competency['id'] }}"
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
                                    <td class="p-2 text-center" id="comp-total-{{ $competency['id'] }}">{{ $competency['total'] }}</td>
                                    <td></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="grid grid-cols-3 gap-6 mt-8">
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
    </div>
@endsection


