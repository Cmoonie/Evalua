@extends('layouts.app')

@php
    $header = 'Beoordeling bekijken';
@endphp

@section('content')
    <h1 class="text-2xl font-bold mb-4">Beoordeling van {{ $filledForm->student_name }}</h1>
    <p class="mb-4"><strong>Vak:</strong> {{ $filledForm->form->subject }}</p>

    @foreach ($filledForm->form->formCompetencies as $formCompetency)

        @php
            // Bekijken of er 2 of meer nul/onvoldoendes zijn
            $total = 0;
            $zeroCount = 0;
            foreach ($formCompetency->competency->components as $component) {
                $filled = $filledForm->filledComponents
                    ->firstWhere('component_id', $component->id);
                $points = optional($filled->gradeLevel)->points ?? 0;
                $total += $points; // Totaal punten om te laten zien
                if ($points === 0) {
                    $zeroCount++;
                }
            }
            $isOnvoldoende = $zeroCount >= 2;
        @endphp

        <div class="mb-6" x-data="{ open: false }">
            <button
                @click="open = !open"

                {{-- De competentie roodmaken als er 2 of meer onvoldoendes zijn --}}
                class="{{ $isOnvoldoende
                ? 'bg-red-500 hover:bg-red-600'
                : 'bg-primary hover:bg-secondary'
                }} py-2 px-4 text-xl font-bold text-white shadow-lg mb-2 flex items-center justify-between w-full rounded-lg transition-colors duration-300">

                <span>Competentie: {{ $formCompetency->competency->name }}</span>

                <div class="flex items-center">
                    <span class="text-sm mr-2">
                        {{ $total }} pts
                    </span>
                    <svg :class="{'transform rotate-180': open}" xmlns="http://www.w3.org/2000/svg"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor"
                             class="w-6 h-6 transition-transform duration-300">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
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
                    @php $total = 0; @endphp
                    @foreach ($formCompetency->competency->components as $component)
                        @php
                            $filledComponent = $filledForm->filledComponents
                                ->firstWhere('component_id', $component->id);
                            $points = optional($filledComponent->gradeLevel)->points;
                            $total += $points ?? 0;
                        @endphp
                        <tr class="border-b">
                            <td class="p-2">{{ $component->name }}</td>
                            <td class="p-2 font-bold text-xl text-red-600 text-center">{{ $points === 0 ? '✗' : '' }}</td>
                            <td class="p-2 font-bold text-xl text-yellow-500 text-center">{{ $points === 3 ? '✓' : '' }}</td>
                            <td class="p-2 font-bold text-xl text-green-500 text-center">{{ $points === 5 ? '✓' : '' }}</td>
                            <td class="p-2 text-center">{{ $points ?? '—' }}</td>
                            <td class="p-2">{{ $filledComponent->comment ?? 'Geen' }}</td>
                        </tr>
                    @endforeach

                    <!-- Totaal rij -->
                    <tr class="bg-gray-100 font-bold">
                        <td colspan="4" class="p-2 text-right">Totaal</td>
                        <td class="p-2 text-center">{{ $total }}</td>
                        <td class="p-2 text-center">
                            {{ $isOnvoldoende
                                ? 'Onvoldoende'
                                : (
                                    $total <= 14
                                        ? 'Onvoldoende'
                                        : ($total <= 20
                                            ? 'Voldoende'
                                            : 'Goed'
                                        )
                                )
                            }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    @endforeach

    @php
        // Het totaal aantal punten van alle competenties laten zien
        $grandTotal = 0;
        foreach ($filledForm->form->formCompetencies as $fc) {
            $compTotal = 0;
            foreach ($fc->competency->components as $component) {
                $filled = $filledForm->filledComponents->firstWhere('component_id', $component->id);
                $compTotal += optional($filled->gradeLevel)->points ?? 0;
            }
            $grandTotal += $compTotal;
        }
    @endphp
    <p class="mb-4"><strong>Totaal aantal punten:</strong> {{ $grandTotal }}</p>

@endsection
