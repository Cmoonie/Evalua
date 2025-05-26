<h2 class="text-xl font-bold mt-12 mb-4">Overzicht in tabelvorm</h2>

<div x-data="{
    // initialiseer hier de array van componenten, met name en descriptions en lege scores/comments
    components: selectedForm.form_competencies.flatMap(fc =>
        fc.competency.components.map(c => ({
            component_name: c.name,
            score: 0,
            comment: '',
        }))
    ),
    get totalScore() {
        return this.components.reduce((sum, c) => sum + c.score, 0)
    },
    setScore(i, s) {
        this.components[i].score = s
    }
}">
    <div class="overflow-x-auto">
        <table class="table-auto w-full text-sm border border-collapse border-gray-300">
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
            <tbody>
            <template x-for="(component, index) in components" :key="index">
                <tr :class="index % 2 === 0 ? 'bg-neutral' : 'bg-white'">
                    <td class="border p-2 font-bold" x-text="component.component_name"></td>
                    <td class="border p-2 text-center">
                        <button @click="setScore(index, 0)"
                                :class="component.score === 0
                                    ? 'bg-red-100 ring-2 ring-red-400'
                                    : 'hover:bg-red-50'"
                                class="w-full h-full p-2">0</button>
                    </td>
                    <td class="border p-2 text-center">
                        <button @click="setScore(index, 3)"
                                :class="component.score === 3
                                    ? 'bg-yellow-100 ring-2 ring-yellow-400'
                                    : 'hover:bg-yellow-50'"
                                class="w-full h-full p-2">3</button>
                    </td>
                    <td class="border p-2 text-center">
                        <button @click="setScore(index, 5)"
                                :class="component.score === 5
                                    ? 'bg-green-100 ring-2 ring-green-400'
                                    : 'hover:bg-green-50'"
                                class="w-full h-full p-2">5</button>
                    </td>
                    <td class="border p-2 text-center font-bold" x-text="component.score"></td>
                    <td class="border p-2" x-text="component.comment || 'Geen opmerking'"></td>
                </tr>
            </template>
            </tbody>
            <tfoot>
            <tr :class="
                    totalScore >= 15 ? 'bg-green-100' :
                    totalScore >= 10 ? 'bg-yellow-100' :
                    'bg-red-100'
                ">
                <td colspan="4" class="text-right p-2 font-bold">Totaal behaalde punten</td>
                <td colspan="2" class="text-center p-2 font-bold" x-text="totalScore"></td>
            </tr>
            </tfoot>
        </table>
    </div>
</div>
