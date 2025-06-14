export function formBuilder() {
    return {
        competencies: [
            // { id: Date.now(), components: [{ id: Date.now() + 1 }, { id: Date.now() + 2 }] },
            // { id: Date.now() + 3, components: [{ id: Date.now() + 4 }, { id: Date.now() + 5 }] }
            { id: Date.now(), components: [{ id: Date.now() + Math.random() }] }
        ],

        addCompetency() {
            this.competencies.push({
                id: Date.now() + Math.random(),
                components: [{ id: Date.now() + Math.random() }]
            });
        },

        removeCompetency(index) {
            this.competencies.splice(index, 1);
        },

        addComponent(competencyIndex) {
            this.competencies[competencyIndex].components.push({
                id: Date.now() + Math.random()
            });
        },

        removeComponent(competencyIndex, compIndex) {
            this.competencies[competencyIndex].components.splice(compIndex, 1);
        }
    };
}
