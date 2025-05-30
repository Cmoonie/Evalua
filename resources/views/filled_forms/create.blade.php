@extends('layouts.app')

@php
    $header = 'Nieuwe beoordeling invullen';
@endphp

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6">Formulier invullen: {{ $form->title }}</h1>

        <form action="{{ route('filled_forms.store') }}" method="POST">
            @csrf
            <input type="hidden" name="form_id" value="{{ $form->id }}">

            <div class="mb-6">
                <label for="student_name" class="block text-lg font-semibold mb-2">Naam student</label>
                <input type="text" name="student_name" id="student_name" class="max-w-80 border border-gray-300 rounded p-2" required>
            </div>

            @foreach($form->formCompetencies as $formCompetency)
                <div class="mb-8" x-data="{ open: false }">
                    <button
                        @click.prevent="open = !open"
                        class="bg-primary py-2 px-4 text-xl font-bold text-white shadow-lg hover:bg-secondary mb-4 w-full flex items-center justify-between rounded-lg transition-colors duration-300">
                        <span>Competentie: {{ $formCompetency->competency->name }}</span>
                        <div class="flex items-center">
                            <span class="text-sm mr-2" id="competency-points-{{ $formCompetency->competency->id }}">0 pts</span>
                            <svg :class="{'transform rotate-180': open}" xmlns="http://www.w3.org/2000/svg"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                 class="w-6 h-6 transition-transform duration-300 text-white">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </button>

                    <div x-show="open" x-collapse>
                        <div class="p-4 border border-gray-200 rounded-lg">
                            <table class="w-full table-auto text-center border-collapse">
                                <thead>
                                <tr class="bg-gray-50 font-semibold">
                                    <th class="p-2 text-left">Component</th>
                                    <th class="p-2">Onvoldoende<br>(0)</th>
                                    <th class="p-2">Voldoende<br>(3)</th>
                                    <th class="p-2">Goed<br>(5)</th>
                                    <th class="p-2">Punten</th>
                                    <th class="p-2 text-left">Opmerking</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($formCompetency->competency->components as $component)
                                    <tr class="border-t">
                                        <td class="p-2 text-left align-top">
                                            <div class="font-semibold">{{ $component->name }}</div>
                                            <div class="text-xs italic text-gray-600">{{ $component->description }}</div>
                                        </td>
                                        @foreach(['onvoldoende','voldoende','goed'] as $grade)
                                            <td class="p-2">
                                                @foreach($component->levels as $level)
                                                    @if(strtolower($level->gradeLevel->name) === $grade)
                                                        <button type="button"
                                                                class="grade-button px-3 py-1 rounded-lg border mb-1 hover:opacity-90"
                                                                data-component-id="{{ $component->id }}"
                                                                data-competency-id="{{ $formCompetency->competency->id }}"
                                                                data-grade-id="{{ $level->grade_level_id }}"
                                                                data-points="{{ $levels[$grade] }}"
                                                                data-grade-name="{{ $grade }}"
                                                        >
                                                            <span class="text-xs">{{ $level->description }}</span>
                                                        </button>
                                                    @endif
                                                @endforeach
                                            </td>
                                        @endforeach
                                        <td class="p-2" id="comp-points-{{ $component->id }}">0</td>
                                        <td class="p-2 align-top">
                                            <textarea name="components[{{ $component->id }}][comment]" rows="2" class="w-full border border-gray-300 rounded p-1" placeholder="Typ een opmerking..."></textarea>
                                            <input type="hidden" name="components[{{ $component->id }}][grade_level_id]" id="grade-level-{{ $component->id }}" required>
                                            <input type="hidden" name="components[{{ $component->id }}][component_id]" value="{{ $component->id }}">
                                        </td>
                                    </tr>
                                @endforeach
                                <tr class="border-t bg-gray-100 font-semibold">
                                    <td class="p-2 text-left">Totaal punten</td>
                                    <td class="p-2" colspan="3"></td>
                                    <td class="p-2 text-center" id="comp-total-{{ $formCompetency->competency->id }}">0</td>
                                    <td></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="mb-4 text-right text-lg font-semibold">
                Totaal punten (alle competenties): <span id="total-points">0</span>
            </div>

            <div class="text-right">
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Verzenden</button>
            </div>
        </form>
    </div>
@endsection
