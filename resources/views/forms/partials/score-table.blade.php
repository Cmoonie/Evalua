

{{-- Beoordelingstabel overzicht --}}
{{--
    Beoordelingstabel overzicht
    Dynamische beoordelingsmatrix met Alpine.js:
    -Klik op een score om deze te selecteren
    -Totaalpunten worden automatische herberekend
    Tabelweergave van de beoordeling: toont alle competenties met
    hun scores, toelichting per scorecategorie en een totaal.
--}}

<h2 class="text-xl font-bold mt-12 mb-4">Overzicht in tabelvorm</h2>

<div x-data="{
    components: @js($filledForm->filledComponents),
    get totalScore() {
        return this.components.reduce((sum, c) => sum + c.score, 0);
    },
    setScore(index, score) {
        this.components[index].score = score;}}"
     class="overflow-x-auto">
<table class="table-auto w-full text-sm border border-collapse border-gray-300">
    {{-- Tabelkop in primaire kleur --}}
    <thead class="bg-primary text-white">
    <tr>
        <th class="border p-2">Competentie</th>
        <th class="border p-2">Onvoldoende (0)</th>
        <th class="border p-2">Voldoende (3)</th>
        <th class="border p-2">Goed (5)</th>
        <th class="border p-2">Punten</th>
        <th class="border p-2">Opmerking</th>
    </tr>
    </thead>

    {{-- Rijen: afwisselend kleuren met bg-neutral --}}
    <tbody>
{{--    @php $totalScore = 0; @endphp--}}
{{--    @foreach ($filledForm->filledComponents as $index => $component)--}}
{{--        @php $totalScore += $component->score; @endphp--}}
<template x-for="(component, index) in components" :key="index">
        <tr :class="index % 2 === 0 ? 'bg-neutral' : 'bg-white'">
            <td class="border p-2 font-bold">{{ $component->component_name }}</td>
            <td class="border p-2 text-center">
                <button @click="setScore(index, 0)" :class="component.score === 0 ? 'text-red-600 font-bold' : ''">✔</button>
            </td>
            <td class="border p-2 text-center">
                <button @click="setScore(index, 3)" :class="component.score === 3 ? 'text-yellow-600 font-bold' : ''">✔</button>
            </td>
            <td class="border p-2 text-center">
                <button @click="setScore(index, 5)" :class="component.score === 5 ? 'text-green-600 font-bold' : ''">✔</button>
            </td>
            <td class="border p-2 text-center font-bold" x-text="component.score"></td>
            <td class="border p-2" x-text="component.comment ?? 'Geen opmerking'"></td>
        </tr>
</template>
    </tbody>

    {{-- Voet: totaal in opvallende 'windy'-kleur --}}
    <tfoot>
    <tr :class="
                totalScore >= 15 ? 'bg-green-100' :
                totalScore >= 10 ? 'bg-yellow-100' :
                'bg-red-100'
            ">
        <td colspan="4" class="text-right p-2 font-bold">Totaal behaalde punten</td>
        <td colspan="2" class="text-center p-2 font-bold text-black" x-text="totalScore"></td>
    </tr>
    </tfoot>
</table>
