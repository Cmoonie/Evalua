@extends('layouts.app')

@section('content')
        <h1 class="text-2xl font-bold mb-4">{{ $form->title }}</h1>
        <p class="mb-2"><strong>Onderwerp:</strong> {{ $form->subject }}</p>
        <p class="mb-4"><strong>OE-code:</strong> {{ $form->oe_code }}</p>
        <p class="mb-4"><strong>Beschrijving:</strong> {{ $form->description }}</p>

        <div class="flex gap-4 mt-4">
            <x-secondary-button>
                <a href="{{ route('forms.edit', $form) }}">Bewerk</a>
            </x-secondary-button>
            <form action="{{ route('forms.destroy', $form) }}" method="POST" class="inline" onsubmit="return confirm('Weet je het zeker?');">
                @csrf
                @method('DELETE')
                <x-secondary-button type="submit">Verwijder</x-secondary-button>
            </form>
        </div>

        <h2 class="text-xl font-semibold mt-6 mb-4">Competenties</h2>

        @foreach($form->formCompetencies as $formCompetency)
            <div x-data="{ open: false }">
                <button
                    @click.prevent="open = !open"
                    class="bg-primary py-2 px-4 text-xl font-bold text-white shadow-lg hover:bg-secondary mb-4 w-full
                    flex items-center justify-between rounded-lg transition-colors duration-300">
                    <span>Competentie: {{ $formCompetency->competency->name }}</span>
                    <div class="flex items-center">
                        <svg :class="{'transform rotate-180': open}" xmlns="http://www.w3.org/2000/svg"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor"
                             class="w-6 h-6 transition-transform duration-300 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </button>

                <div x-show="open" x-transition>
                    <div class="grid grid-cols-1 gap-6 mt-2 mb-6">
                        <x-info-card :title="'Competentie-specifieke Knock-out Criteria & Deliverables'">
                            <p>
                                {{ $formCompetency->competency->complexity }}
                            </p>
                        </x-info-card>
                    </div>

                    <div class="p-4 border mt-8 border-gray-200 bg-white rounded-lg">
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
                    </div>

                    <div class="grid grid-cols-2 gap-6 mt-6 mb-6">
                        <x-info-card :title="'Beoordelingsschaal'">
                            <p class="break-words">
                                {{ $formCompetency->competency->rating_scale }}
                            </p>
                        </x-info-card>
                        <x-info-card :title="'Domeinbeschrijving'">
                            <p class="break-words">
                                {{ $formCompetency->competency->domain_description }}
                            </p>
                        </x-info-card>
                    </div>
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

