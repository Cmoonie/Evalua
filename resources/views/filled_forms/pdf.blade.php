<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            zoom: 85%;
        }
        main {
            zoom: 80%;
        }
    </style>
    <title>{{ config('app.name', 'Evalua') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased flex-1 mx-auto px-4 py-4">
    <h1 class="text-2xl text-primary font-bold mb-4">
        Beoordelingsformulier {{ $filledForm->form->title }}
    </h1>

        <div class="flex flex-row gap-4 mb-4">
            <!-- Tabel 1: Basisinformatie formulier -->
            <div class="w-1/2">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-sm">
                    <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2 text-left font-semibold text-primary" colspan="2">Basisinformatie formulier</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="border-t">
                        <td class="px-4 py-2 font-medium text-gray-600">Vak</td>
                        <td class="px-4 py-2 text-gray-800">{{ $filledForm->form->subject }}</td>
                    </tr>
                    <tr class="border-t">
                        <td class="px-4 py-2 font-medium text-gray-600">OE-code</td>
                        <td class="px-4 py-2 text-gray-800">{{ $filledForm->form->oe_code }}</td>
                    </tr>
                    <tr class="border-t">
                        <td class="px-4 py-2 font-medium text-gray-600">Beschrijving</td>
                        <td class="px-4 py-2 text-gray-800">{{ $filledForm->form->description }}</td>
                    </tr>
                    <tr class="border-t">
                        <td class="px-4 py-2 font-medium text-gray-600">Datum ingevuld</td>
                        <td class="px-4 py-2 text-gray-800">{{ $filledForm->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                    @if($filledForm->created_at->ne($filledForm->updated_at))
                        <tr class="border-t">
                            <td class="px-4 py-2 font-medium text-gray-600">Datum aangepast</td>
                            <td class="px-4 py-2 text-gray-800">{{ $filledForm->updated_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @endif
                    <tr class="border-t">
                        <td class="px-4 py-2 font-medium text-gray-600">Ingevuld door:</td>
                        <td class="px-4 py-2 text-gray-800">{{ $filledForm->form->user->name }}</td>
                    </tr>
                    <tr class="border-t">
                        <td class="px-4 py-2 font-medium text-gray-600">Tweede examinator:</td>
                        <td class="px-4 py-2 text-gray-800">Functionaliteit komt nog</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="w-1/2">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-sm">
                    <thead>
                    <tr class="bg-gray-200 text-primary px-4 py-2 font-semibold">
                        <th class="p-2 text-left">Eindbeoordeling</th>
                        <th class="p-2 text-center">Max. Punten</th>
                        <th class="p-2 text-center">Behaald</th>
                        <th class="p-2 text-center">Min. Punten</th>
                        <th class="p-2 text-center">Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($competencies as $comp)
                        <tr class="border-b {{ $comp['stateClass'] }}">
                            <td class="p-2">{{ $comp['name'] }}</td>
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
                <p class="text-sm text-primary">
                    <strong>Toelichting: </strong>
                    <11 = Onvoldoende || 12 - 16 = Voldoende || 17 - 25 = Goed.
                    <br>
                    Voldoende alleen mogelijk mits alle activiteiten en competenties behaald zijn met een voldoende.
                </p>
            </div>

        </div>


            <!-- Tabel 2: Studentinformatie -->
            <div class="mb-6 lg:mb-0 lg:w-1/2">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-sm">
                    <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2 text-left font-semibold text-primary" colspan="2">Studentinformatie</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="border-t">
                        <td class="px-4 py-2 font-medium text-gray-600">Studentnaam</td>
                        <td class="px-4 py-2 text-left text-gray-800">{{ $filledForm->student_name }}</td>
                    </tr>
                    <tr class="border-t">
                        <td class="px-4 py-2 font-medium text-gray-600">Studentnummer</td>
                        <td class="px-4 py-2 text-left text-gray-800">{{ $filledForm->student_number }}</td>
                    </tr>
                    <tr class="border-t">
                        <td class="px-4 py-2 font-medium text-gray-600">Titel opdracht</td>
                        <td class="px-4 py-2 text-left text-gray-800">{{ $filledForm->assignment }}</td>
                    </tr>
                    </tbody>
                </table>

                <!-- Tabel 3: Bedrijfsinformatie -->
                <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-sm mt-2">
                    <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2 text-left font-semibold text-primary" colspan="2">Bedrijfsinformatie</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(!empty($filledForm->business_name))
                        <tr class="border-t">
                            <td class="px-4 py-2 font-medium text-gray-600">Bedrijfsnaam</td>
                            <td class="px-4 py-2 text-gray-800">{{ $filledForm->business_name }}</td>
                        </tr>
                    @endif
                    @if(!empty($filledForm->business_location))
                        <tr class="border-t">
                            <td class="px-4 py-2 font-medium text-gray-600">Bedrijfslocatie</td>
                            <td class="px-4 py-2 text-gray-800">{{ $filledForm->business_location }}</td>
                        </tr>
                    @endif
                    @if(!empty($filledForm->start_date))
                        <tr class="border-t">
                            <td class="px-4 py-2 font-medium text-gray-600">Startdatum</td>
                            <td class="px-4 py-2 text-gray-800">{{ $filledForm->start_date->format('Y-m-d') }}</td>
                        </tr>
                    @endif
                    @if(!empty($filledForm->end_date))
                        <tr class="border-t">
                            <td class="px-4 py-2 font-medium text-gray-600">Einddatum</td>
                            <td class="px-4 py-2 text-gray-800">{{ $filledForm->end_date->format('Y-m-d') }}</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>



{{--    </div>--}}

    @pageBreak

    <main>
    @foreach ($competencies as $comp)
        <div class="p-4 border border-gray-200 bg-white rounded-lg mt-2">
            <h2 class="text-xl font-bold text-primary mb-2">Competentie: {{ $comp['name'] }}</h2>
            <table class="w-full table-auto text-center border-collapse">
                <thead>
                <tr class="bg-gray-200 text-primary font-semibold">
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
                            <div class="font-semibold text-secondary text-sm">{{ $component['name'] }}</div>
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
                        <td class="p-2 text-center">{{ $component['comment'] }}</td>
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

        <div class="grid lg:grid-cols-3 grid-cols-1 gap-6 mt-2">
            <div class="bg-white border border-gray-200 rounded-lg mb-2 p-2 w-full">
                <h2 class="text-xl font-bold text-secondary dark:text-secondary">Knock-out Criteria & Deliverables</h2>
                <div class="text-sm">
                    {{ $comp['complexity'] }}
                </div>
            </div>
            <div class="bg-white border border-gray-200 rounded-lg mb-2 p-2 w-full">
                <h2 class="text-xl font-bold text-secondary dark:text-secondary">Beoordelingsschaal</h2>
                <div class="text-sm">
                    {{ $comp['rating_scale'] }}
                </div>
            </div>
            <div class="bg-white border border-gray-200 rounded-lg mb-2 p-2 w-full">
                <h2 class="text-xl font-bold text-secondary dark:text-secondary">Domeinbeschrijving</h2>
                <div class="text-sm">
                    {{ $comp['domain_description'] }}
                </div>
            </div>


{{--                <x-info-card :title="'Knock-out Criteria & Deliverables'">--}}
{{--                    <p>--}}
{{--                        {{ $comp['complexity'] }}--}}
{{--                    </p>--}}
{{--                </x-info-card>--}}
{{--                <x-info-card :title="'Beoordelingsschaal'">--}}
{{--                    <p>--}}
{{--                        {{ $comp['rating_scale'] }}--}}
{{--                    </p>--}}
{{--                </x-info-card>--}}
{{--                <x-info-card :title="'Domeinbeschrijving'">--}}
{{--                    <p>--}}
{{--                        {{ $comp['domain_description'] }}--}}
{{--                    </p>--}}
{{--                </x-info-card>--}}
            </div>
        @pageBreak
    @endforeach
    </main>
</body>


