@extends('layouts.app')

@php
    $header = 'Beoordeling bewerken';
@endphp

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Beoordeling van {{ $filledForm->student_name }} bewerken</h1>
        <p class="mb-4"><strong>Titel:</strong> {{ $filledForm->form->title }}</p>
        <p class="mb-4"><strong>Vak:</strong> {{ $filledForm->form->subject }}</p>
        <p class="mb-4"><strong>Beschrijving:</strong> {{ $filledForm->form->description }}</p>
        <p class="mb-4"><strong>Datum ingevuld:</strong> {{ $filledForm->created_at->format('Y-m-d H:i') }}</p>
        @if($filledForm->created_at->ne($filledForm->updated_at))
            <p class="mb-4"><strong>Datum aangepast:</strong> {{ $filledForm->updated_at->format('Y-m-d H:i') }}</p>
        @endif
        <p class="mb-4"><strong>Totaal aantal huidige punten:</strong> {{ $grandTotal }}</p>
        <p class="mb-4"><strong>Huidig cijfer:</strong> {{ $grade ?? '–' }}</p>

        <form action="{{ route('filled_forms.update', $filledForm) }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="form_id" value="{{ $filledForm->form_id }}">

            <div class="mb-6">
                <label for="student_name" class="block text-lg font-semibold mb-2">Naam student</label>
                <input type="text" name="student_name" id="student_name"
                       class="max-w-80 border border-gray-300 rounded p-2"
                       value="{{ old('student_name', $filledForm->student_name) }}"
                       required>
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
                        <div class="p-4 border border-gray-200 rounded-lg">
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
                                                                ?'bg-orange-200 border-orange-400'
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
                    </div>
                </div>
            @endforeach

            <div class="mb-4 text-right text-lg font-semibold">
                Totaal aantal nieuwe punten (alle competenties): <span id="total-points">{{ $grandTotal }}</span>
            </div>

            <div class="text-right">
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Opslaan</button>
            </div>
        </form>
    </div>
@endsection




{{--@extends('layouts.app')--}}

{{--@php--}}
{{--    $header = 'Beoordeling bewerken';--}}
{{--@endphp--}}

{{--@section('content')--}}
{{--    <div class="container mx-auto p-6">--}}
{{--        <h1 class="text-2xl font-bold mb-4">Beoordeling van {{ $filledForm->student_name }} bewerken</h1>--}}
{{--        <p class="mb-4"><strong>Titel:</strong> {{ $filledForm->form->title }}</p>--}}
{{--        <p class="mb-4"><strong>Vak:</strong> {{ $filledForm->form->subject }}</p>--}}
{{--        <p class="mb-4"><strong>Beschrijving:</strong> {{ $filledForm->form->description }}</p>--}}
{{--        <p class="mb-4"><strong>Datum ingevuld:</strong> {{ $filledForm->created_at->format('Y-m-d H:i') }}</p>--}}
{{--        @if($filledForm->created_at->ne($filledForm->updated_at))--}}
{{--            <p class="mb-4"><strong>Datum aangepast:</strong>{{ $filledForm->updated_at->format('Y-m-d H:i') }}</p>--}}
{{--        @endif--}}
{{--        <p class="mb-4"><strong>Totaal aantal huidige punten:</strong> {{ $grandTotal }}</p>--}}
{{--        <p class="mb-4"><strong>Huidig cijfer:</strong> {{ $grade ?? '–' }}</p>--}}


{{--        <form action="{{ route('filled_forms.update', $filledForm) }}" method="POST">--}}
{{--            @csrf--}}
{{--            @method('PUT')--}}
{{--            <input type="hidden" name="form_id" value="{{ $filledForm->form_id }}">--}}

{{--            <div class="mb-6">--}}
{{--                <label for="student_name" class="block text-lg font-semibold mb-2">Naam student</label>--}}
{{--                <input type="text" name="student_name" id="student_name"--}}
{{--                       class="max-w-80 border border-gray-300 rounded p-2"--}}
{{--                       value="{{ old('student_name', $filledForm->student_name) }}"--}}
{{--                       required>--}}
{{--            </div>--}}

{{--            @foreach($filledForm->form->formCompetencies as $formCompetency)--}}
{{--                <div class="mb-8" x-data="{ open: false }">--}}
{{--                    <button--}}
{{--                        @click.prevent="open = !open"--}}
{{--                        class="bg-primary py-2 px-4 text-xl font-bold text-white shadow-lg hover:bg-secondary mb-4 w-full--}}
{{--                        flex items-center justify-between rounded-lg transition-colors duration-300">--}}
{{--                        <span>Competentie: {{ $formCompetency->competency->name }}</span>--}}
{{--                        <div class="flex items-center">--}}
{{--                            <span class="text-sm mr-2" id="competency-points-{{ $formCompetency->competency->id }}">0 pts</span>--}}
{{--                            <svg :class="{'transform rotate-180': open}" xmlns="http://www.w3.org/2000/svg"--}}
{{--                                 fill="none" viewBox="0 0 24 24" stroke="currentColor"--}}
{{--                                 class="w-6 h-6 transition-transform duration-300 text-white">--}}
{{--                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>--}}
{{--                            </svg>--}}
{{--                        </div>--}}
{{--                    </button>--}}

{{--                    <div x-show="open" x-collapse>--}}
{{--                        <div class="p-4 border border-gray-200 rounded-lg">--}}
{{--                            <table class="w-full table-auto text-center border-collapse">--}}
{{--                                <thead>--}}
{{--                                <tr class="bg-gray-50 font-semibold">--}}
{{--                                    <th class="p-2 text-left">Component</th>--}}
{{--                                    <th class="p-2">Onvoldoende<br>(0)</th>--}}
{{--                                    <th class="p-2">Voldoende<br>(3)</th>--}}
{{--                                    <th class="p-2">Goed<br>(5)</th>--}}
{{--                                    <th class="p-2">Punten</th>--}}
{{--                                    <th class="p-2 text-left">Opmerking</th>--}}
{{--                                </tr>--}}
{{--                                </thead>--}}
{{--                                <tbody>--}}

{{--                                @foreach($formCompetency->competency->components as $component)--}}
{{--                                    @php--}}
{{--                                        $filled = $filledForm->filledComponents->firstWhere('component_id', $component->id);--}}
{{--                                    @endphp--}}
{{--                                    <tr class="border-t">--}}
{{--                                        <td class="p-2 text-left align-top">--}}
{{--                                            <div class="font-semibold">{{ $component->name }}</div>--}}
{{--                                            <div class="text-sm italic text-gray-600">{{ $component->description }}</div>--}}
{{--                                        </td>--}}
{{--                                        @foreach(['onvoldoende','voldoende','goed'] as $gradeName)--}}
{{--                                            <td class="p-2">--}}
{{--                                                @foreach($component->levels as $level)--}}
{{--                                                    @if(strtolower($level->gradeLevel->name) === $gradeName)--}}
{{--                                                        <button type="button"--}}
{{--                                                                class="grade-button px-3 py-1 rounded-lg border mb-1 hover:opacity-90--}}
{{--                                                                @if(optional($filled)->grade_level_id == $level->grade_level_id)--}}
{{--                                                                {{ $gradeName=='goed'?'bg-green-200 border-green-400'--}}
{{--                                                                :($gradeName=='voldoende'--}}
{{--                                                                ?'bg-orange-200 border-orange-400'--}}
{{--                                                                :'bg-red-200 border-red-400') }}--}}
{{--                                                                @endif"--}}
{{--                                                                data-component-id="{{ $component->id }}"--}}
{{--                                                                data-competency-id="{{ $formCompetency->competency->id }}"--}}
{{--                                                                data-grade-id="{{ $level->grade_level_id }}"--}}
{{--                                                                data-points="{{ $levels[$gradeName] }}"--}}
{{--                                                                data-grade-name="{{ $gradeName }}"--}}
{{--                                                        >--}}
{{--                                                            <span class="text-sm">{{ $level->description }}</span>--}}
{{--                                                        </button>--}}
{{--                                                    @endif--}}
{{--                                                @endforeach--}}
{{--                                            </td>--}}
{{--                                        @endforeach--}}
{{--                                        <td class="p-2" id="comp-points-{{ $component->id }}">0</td>--}}
{{--                                        <td class="p-2 align-top">--}}
{{--                                            <textarea name="components[{{ $component->id }}][comment]" rows="2" class="w-full border border-gray-300 rounded p-1" placeholder="Typ een opmerking...">{{ old('components.'.$component->id.'.comment', optional($filled)->comment) }}</textarea>--}}
{{--                                            <input type="hidden" name="components[{{ $component->id }}][grade_level_id]" id="grade-level-{{ $component->id }}" value="{{ optional($filled)->grade_level_id }}" required>--}}
{{--                                            <input type="hidden" name="components[{{ $component->id }}][component_id]" value="{{ $component->id }}">--}}
{{--                                        </td>--}}
{{--                                    </tr>--}}
{{--                                @endforeach--}}
{{--                                <tr class="border-t bg-gray-100 font-semibold">--}}
{{--                                    <td class="p-2 text-left">Totaal punten</td>--}}
{{--                                    <td class="p-2" colspan="3"></td>--}}
{{--                                    <td class="p-2 text-center" id="comp-total-{{ $formCompetency->competency->id }}">0</td>--}}
{{--                                    <td></td>--}}
{{--                                </tr>--}}
{{--                                </tbody>--}}
{{--                            </table>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            @endforeach--}}

{{--            <div class="mb-4 text-right text-lg font-semibold">--}}
{{--                Totaal aantal nieuwe punten (alle competenties): <span id="total-points">0</span>--}}
{{--            </div>--}}

{{--            <div class="text-right">--}}
{{--                <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Opslaan</button>--}}
{{--            </div>--}}
{{--        </form>--}}
{{--    </div>--}}
{{--@endsection--}}
