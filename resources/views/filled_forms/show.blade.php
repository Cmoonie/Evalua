@extends('layouts.app')

@php
    $header = 'Beoordeling bekijken';
@endphp

@section('content')
    <h1 class="text-2xl font-bold mb-4">Beoordeling van {{ $filledForm->student_name }}</h1>
    <p class="mb-8"><strong>Vak:</strong> {{ $filledForm->form->subject }}</p>

    @foreach ($filledForm->form->formCompetencies as $formCompetency)
        <div class="mb-6" x-data="{ open: false }">

            <button
                @click="open = !open"
                class="bg-primary py-2 px-4 text-xl font-bold text-white shadow-lg hover:bg-secondary mb-2 flex items-center justify-between w-full rounded-lg transition-colors duration-300">
                <span>{{ $formCompetency->competency->name }}</span>
                <svg :class="{'transform rotate-180': open}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6 transition-transform duration-300">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>


            <div x-show="open" x-transition class="p-4 bg-white rounded shadow">
                <table class="w-full table-auto border-collapse mb-2">
                    <thead>
                    <tr class="bg-gray-200 text-gray-800">
                        <th class="p-2 text-left">Component</th>
                        <th class="p-2 text-center">Onvoldoende (0)</th>
                        <th class="p-2 text-center">Voldoende (3)</th>
                        <th class="p-2 text-center">Goed (5)</th>
                        <th class="p-2 text-center">Punten</th>
                        <th class="p-2">Opmerking</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php $totaal = 0; @endphp
                    @foreach ($formCompetency->competency->components as $component)
                        @php
                            $filledComponent = $filledForm->filledComponents->firstWhere('component_id', $component->id);
                            $points = optional($filledComponent->gradeLevel)->points;
                            $totaal += $points ?? 0;
                        @endphp
                        <tr class="border-b">
                            <td class="p-2">{{ $component->name }}</td>
                            <td class="p-2 text-center">
                                {{ $points === 0 ? '✔' : '' }}
                            </td>
                            <td class="p-2 text-center">
                                {{ $points === 3 ? '✔' : '' }}
                            </td>
                            <td class="p-2 text-center">
                                {{ $points === 5 ? '✔' : '' }}
                            </td>
                            <td class="p-2 text-center">{{ $points ?? '—' }}</td>
                            <td class="p-2">{{ $filledComponent->comment ?? 'Geen' }}</td>
                        </tr>
                    @endforeach

                    <!-- Totaal rij -->
                    <tr class="bg-gray-100 font-bold">
                        <td colspan="4" class="p-2 text-right">Totaal</td>
                        <td class="p-2 text-center">{{ $totaal }}</td>
                        <td></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
@endsection
