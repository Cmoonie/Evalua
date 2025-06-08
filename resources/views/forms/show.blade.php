@extends('layouts.app')

@php
    $header = 'Formulier bekijken';
@endphp

@section('content')
        <h1 class="text-2xl text-primary font-bold mb-4">Formulier "{{ $form->title }}"</h1>
        <div class="mb-6 lg:w-1/2">
            <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-sm">
                <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 text-left font-semibold text-primary" colspan="2">Basisinformatie formulier</th>
                </tr>
                </thead>
                <tbody>
                <tr class="border-t">
                    <td class="px-4 py-2 font-medium text-gray-600">Vak</td>
                    <td class="px-4 py-2 text-gray-800">{{ $form->subject }}</td>
                </tr>
                <tr class="border-t">
                    <td class="px-4 py-2 font-medium text-gray-600">OE-code</td>
                    <td class="px-4 py-2 text-gray-800">{{ $form->oe_code }}</td>
                </tr>
                <tr class="border-t">
                    <td class="px-4 py-2 font-medium text-gray-600">Beschrijving</td>
                    <td class="px-4 py-2 text-gray-800">{{ $form->description }}</td>
                </tr>
                <tr class="border-t">
                    <td class="px-4 py-2 font-medium text-gray-600">Datum aangemaakt</td>
                    <td class="px-4 py-2 text-gray-800">{{ $form->created_at->format('Y-m-d H:i') }}</td>
                </tr>
                @if($form->created_at->ne($form->updated_at))
                    <tr class="border-t">
                        <td class="px-4 py-2 font-medium text-gray-600">Datum aangepast</td>
                        <td class="px-4 py-2 text-gray-800">{{ $form->updated_at->format('Y-m-d H:i') }}</td>
                    </tr>
                @endif
                <tr class="border-t">
                    <td colspan="2" class="px-4 py-3 text-right space-x-2">
                        <x-secondary-button>
                            <a href="{{ route('forms.edit', $form) }}">Bewerk</a>
                        </x-secondary-button>
                        <form action="{{ route('forms.destroy', $form) }}" method="POST" class="inline" onsubmit="return confirm('Weet je het zeker?');">
                            @csrf
                            @method('DELETE')
                            <x-secondary-button type="submit">Verwijder</x-secondary-button>
                        </form>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>


        @foreach($form->formCompetencies as $formCompetency)
{{--            <div x-data="{ open: false }">--}}
{{--                <button--}}
{{--                    @click.prevent="open = !open"--}}
{{--                    class="bg-primary py-2 px-4 text-xl font-bold text-white shadow-lg hover:bg-secondary mb-4 mt-4 w-full--}}
{{--                    flex items-center justify-between rounded-lg transition-colors duration-300">--}}
{{--                    <span>Competentie: {{ $formCompetency->competency->name }}</span>--}}
{{--                    <div class="flex items-center">--}}
{{--                        <svg :class="{'transform rotate-180': open}" xmlns="http://www.w3.org/2000/svg"--}}
{{--                             fill="none" viewBox="0 0 24 24" stroke="currentColor"--}}
{{--                             class="w-6 h-6 transition-transform duration-300 text-white">--}}
{{--                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>--}}
{{--                        </svg>--}}
{{--                    </div>--}}
{{--                </button>--}}

{{--                <div x-show="open" x-transition>--}}
                    <div class="p-4 border border-gray-200 bg-white rounded-lg mb-4">
                        <h1 class="text-4xl text-primary mb-4">
                            Competentie: {{ $formCompetency->competency->name }}
                        </h1>
                        <table class="w-full table-fixed text-center border-collapse">
                            <thead>
                            <tr class="bg-gray-200 text-primary font-semibold">
                                <th class="w-1/4 p-2 text-left">Component</th>
                                <th class="w-1/4 p-2">Onvoldoende (0)</th>
                                <th class="w-1/4 p-2">Voldoende (3)</th>
                                <th class="w-1/4 p-2">Goed (5)</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($formCompetency->competency->components as $component)
                                <tr class="border-t">
                                    <td class="p-2 text-left align-top">
                                        <div class="font-semibold text-secondary">{{ $component->name }}</div>
                                        <div class="text-xs italic text-gray-600">{{ $component->description }}</div>
                                    </td>
                                    @foreach(['onvoldoende','voldoende','goed'] as $grade)
                                        <td class="p-2">
                                            @foreach($component->levels as $level)
                                                @if(strtolower($level->gradeLevel->name) === $grade)
                                                    <div
                                                        class="group relative w-full h-24 border border-gray-300 rounded-lg p-2"
                                                        data-component-id="{{ $component->id }}"
                                                        data-competency-id="{{ $formCompetency->competency->id }}"
                                                        data-grade-id="{{ $level->grade_level_id }}"
                                                        data-points="{{ $levels[$grade] }}"
                                                        data-grade-name="{{ $grade }}">
                                                            <span class="text-xs block max-h-full overflow-hidden text-ellipsis group-hover:overflow-auto group-hover:whitespace-normal">
                                                                {{ $level->description }}
                                                            </span>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    <div class="grid lg:grid-cols-3 grid-cols-1 gap-6">
                        <x-info-card :title="'Knock-out Criteria & Deliverables'">
                            <p>
                                {{ $formCompetency->competency->complexity }}
                            </p>
                        </x-info-card>
                        <x-info-card :title="'Beoordelingsschaal'">
                            <p>
                                {{ $formCompetency->competency->rating_scale }}
                            </p>
                        </x-info-card>
                        <x-info-card :title="'Domeinbeschrijving'">
                            <p>
                                {{ $formCompetency->competency->domain_description }}
                            </p>
                        </x-info-card>
                    </div>
                </div>

        @endforeach

{{--        @foreach($form->formCompetencies as $index => $fc)--}}
{{--            <div x-data="{ open: false }" class="mb-6 border rounded-lg shadow-sm">--}}
{{--                <button--}}
{{--                    @click="open = !open"--}}
{{--                    class="w-full text-left bg-primary text-white px-4 py-3 rounded-t-lg font-semibold flex justify-between items-center">--}}
{{--                    <span>Competentie {{ $index + 1 }}: {{ $fc->competency->name }}</span>--}}
{{--                    <svg :class="{'rotate-180': open}" class="w-5 h-5 transform transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">--}}
{{--                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />--}}
{{--                    </svg>--}}
{{--                </button>--}}

{{--                <div x-show="open" class="p-4 bg-gray-50">--}}
{{--                    <div class="mb-3">--}}
{{--                        <p><span class="bg-secondary text-white px-2 py-1 rounded font-semibold">Domeinbeschrijving:</span> <em>{{ $fc->competency->domain_description }}</em></p>--}}
{{--                    </div>--}}
{{--                    <div class="mb-3">--}}
{{--                        <p><span class="bg-secondary text-white px-2 py-1 rounded font-semibold">Beoordelingsschaal:</span> {{ $fc->competency->rating_scale }}</p>--}}
{{--                    </div>--}}
{{--                    <div class="mb-3">--}}
{{--                        <p><span class="bg-secondary text-white px-2 py-1 rounded font-semibold">Knock-out Criteria & Deliverables:</span> {{ $fc->competency->complexity }}</p>--}}
{{--                    </div>--}}

{{--                    @foreach($fc->competency->components as $component)--}}
{{--                        <div class="pl-4 mb-4">--}}
{{--                            <h4 class="mb-2 bg-windy text-white px-2 py-1 font-semibold">Component: {{ $component->name }}</h4>--}}
{{--                            <p class=" bg-tertiary text-white px-2 ">Beschrijving : {{ $component->description }}</p>--}}
{{--                            <ul class="list-disc list-inside mt-1">--}}
{{--                                @foreach($component->levels as $level)--}}
{{--                                    <li><strong>({{ $level->gradeLevel->points }} pts)</strong> {{ $level->description }}</li>--}}
{{--                                @endforeach--}}
{{--                            </ul>--}}
{{--                        </div>--}}
{{--                    @endforeach--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        @endforeach--}}

        <a href="{{ route('forms.index') }}"><x-primary-button>
                Terug naar lijst
            </x-primary-button>
        </a>
@endsection

