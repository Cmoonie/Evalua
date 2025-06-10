import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

const componentPoints = {};
const componentToCompetency = {};
const competencyPoints = {};
const totalDisplay = document.getElementById('total-points');

document.querySelectorAll('.grade-button').forEach(btn => {
    componentToCompetency[btn.dataset.componentId] = btn.dataset.competencyId;
});

// mappieflappie
function calculateGrade(points) {
    const scoreMap = [
        [0, 71, 5.0],
        [72, 79, 5.5],
        [80, 89, 6.0],
        [90, 97, 6.5],
        [98, 105, 7.0],
        [106, 114, 7.5],
        [115, 123, 8.0],
        [124, 131, 8.5],
        [132, 140, 9.0],
        [141, 148, 9.5],
        [149, 150, 10.0],
    ];

    for (const [min, max, grade] of scoreMap) {
        if (points >= min && points <= max) {
            return grade;
        }
    }
    return null;
}

function updateTotals(compId, pts) {
    document.getElementById(`comp-points-${compId}`).textContent = pts;
    componentPoints[compId] = pts;
    const compCompId = componentToCompetency[compId];
    let sum = 0;
    for (const [cId, p] of Object.entries(componentPoints)) {
        if (componentToCompetency[cId] === compCompId) sum += p;
    }
    competencyPoints[compCompId] = sum;
    document.getElementById(`comp-total-${compCompId}`).textContent = sum;
    document.getElementById(`competency-points-${compCompId}`).textContent = sum + ' pts';
    const grand = Object.values(competencyPoints).reduce((a,b) => a + b, 0);
    totalDisplay.textContent = grand;

    const gradeDisplay = document.getElementById('grade-display');
    if (gradeDisplay) {
        gradeDisplay.textContent = calculateGrade(grand);
    }
}

document.querySelectorAll('.grade-button').forEach(btn => {
    btn.addEventListener('click', () => {
        const compId = btn.dataset.componentId;
        const pts = parseInt(btn.dataset.points, 10);
        const gradeName = btn.dataset.gradeName;

        // Verwijder kleuren van alle knoppen binnen hetzelfde component
        document.querySelectorAll(`.grade-button[data-component-id="${compId}"]`).forEach(b => {
            b.classList.remove(
                'bg-green-200', 'border-green-400',
                'bg-lime-200', 'border-lime-400',
                'bg-red-200', 'border-red-400'
            );
        });

        // Kies nieuwe kleuren
        let bg = '', bd = '';
        if (gradeName === 'goed') {
            bg = 'bg-green-200';
            bd = 'border-green-400';
        } else if (gradeName === 'voldoende') {
            bg = 'bg-lime-200';
            bd = 'border-lime-400';
        } else if (gradeName === 'onvoldoende') {
            bg = 'bg-red-200';
            bd = 'border-red-400';
        }

        // Voeg nieuwe kleur toe aan de aangeklikte knop
        btn.classList.add(bg, bd);

        // Update hidden input
        document.getElementById(`grade-level-${compId}`).value = btn.dataset.gradeId;

        // Update punten
        updateTotals(compId, pts);
    });
});

    document.querySelectorAll('input[id^="grade-level-"]').forEach(input => {
        const compId = input.id.replace('grade-level-', '');
        const savedLevel = input.value;

        if (savedLevel) {
            const btn = document.querySelector(
                `.grade-button[data-component-id="${compId}"][data-grade-id="${savedLevel}"]`
            );
            if (btn) {
                btn.click(); // Simuleer gewoon de klik om kleur toe te passen en punten te tellen
            }
        }
    });

// Alles een variabele geven
const submitButton        = document.getElementById('form-submit');
const warningCheckboxes   = document.getElementById('warning-checkboxes');
const warningComponents   = document.getElementById('warning-components');
const knockoutCheckboxes  = document.querySelectorAll('.form-checkbox');
const gradeInputs         = document.querySelectorAll('input[id^="grade-level-"]');
const gradeButtons        = document.querySelectorAll('.grade-button'); // jouw buttons

// updateSubmitState controleert twee condities
function updateSubmitState() {
    // Zijn alle knock‐out checkboxes aangevinkt?
    const allKnockoutChecked = Array.from(knockoutCheckboxes).every(cb => cb.checked);

    // Zijn alle componenten ingevuld?
    const allGradesSet       = Array.from(gradeInputs).every(input => input.value !== '');

    // Mag alleen als je aan allebei voldoet
    const canSubmit          = allKnockoutChecked && allGradesSet;
    submitButton.disabled = !canSubmit;

    // Wanneer tonen we welke warning?
    if (!allKnockoutChecked) {
        // Als alle checkbixes gecheckt zijn
        warningCheckboxes.classList.remove('hidden');
        warningComponents.classList.add('hidden');
    } else if (!allGradesSet) {
        // als alle componenten beoordeeld zijn
        warningCheckboxes.classList.add('hidden');
        warningComponents.classList.remove('hidden');
    } else {
        // alles goed
        warningCheckboxes.classList.add('hidden');
        warningComponents.classList.add('hidden');
    }
}

// Bind de knock out checkbox events
knockoutCheckboxes.forEach(cb => {
    cb.addEventListener('change', updateSubmitState);
});

// Bind de hidden inputs
gradeInputs.forEach(input => {
    input.addEventListener('input', updateSubmitState);
});

// bind de grade buttons
gradeButtons.forEach(btn => {
    btn.addEventListener('click', () => {
        const compId  = btn.dataset.componentId;
        const gradeId = btn.dataset.gradeId;
        const points  = btn.dataset.points;

        // zet de hidden input value
        const hidden = document.getElementById(`grade-level-${compId}`);
        hidden.value = gradeId;

        // update de punten cell
        document.getElementById(`comp-points-${compId}`).textContent = points;

        // trigger de submit state check:
        updateSubmitState();
    });
});

// 6. Start‐state direct correct zetten
updateSubmitState();


// const submitButton        = document.getElementById('form-submit');
// const warningCheckboxes   = document.getElementById('warning-checkboxes');
// const warningComponents   = document.getElementById('warning-components');
// const knockoutCheckboxes  = document.querySelectorAll('.form-checkbox');
//
// // Haal alle grade-inputs meteen op
// const gradeInputs = document.querySelectorAll('input[id^="grade-level-"]');
//
// function updateSubmitState() {
//     const allKnockoutChecked = Array.from(knockoutCheckboxes).every(cb => cb.checked);
//     const allGradesSet       = Array.from(gradeInputs).every(input => input.value !== '');
//     const canSubmit          = allKnockoutChecked && allGradesSet;
//
//     submitButton.disabled = !canSubmit;
//
//     if (!allKnockoutChecked) {
//         warningCheckboxes.classList.remove('hidden');
//         warningComponents.classList.add('hidden');
//     } else if (!allGradesSet) {
//         warningCheckboxes.classList.add('hidden');
//         warningComponents.classList.remove('hidden');
//     } else {
//         warningCheckboxes.classList.add('hidden');
//         warningComponents.classList.add('hidden');
//     }
// }
//
// // Bind change‐event op alle knock‐out checkboxes
// knockoutCheckboxes.forEach(cb => {
//     cb.addEventListener('change', updateSubmitState);
// });
//
// // Bind input‐event op alle grade-inputs
// gradeInputs.forEach(input => {
//     input.addEventListener('input', updateSubmitState);
// });
//
// // Initial run zodat de button meteen de juiste state heeft
// updateSubmitState();



// const submitButton        = document.getElementById('form-submit');
// const warningCheckboxes   = document.getElementById('warning-checkboxes');
// const warningComponents   = document.getElementById('warning-components');
// const knockoutCheckboxes  = document.querySelectorAll('.form-checkbox');
//
// // updateSubmitState controleert twee condities
// function updateSubmitState() {
//     // Zijn alle knock‐out checkboxes aangevinkt?
//     const allKnockoutChecked = Array.from(knockoutCheckboxes).every(cb => cb.checked);
//
//     // Zijn alle component‐grades ingevuld?
//     const gradeInputs = document.querySelectorAll('input[id^="grade-level-"]');
//     const allGradesSet = Array.from(gradeInputs).every(input => input.value !== '');
//
//     // Button mag alleen als beide waar zijn
//     const canSubmit = allKnockoutChecked && allGradesSet;
//     submitButton.disabled = !canSubmit;
//
//     // Toon of verberg waarschuwingen
//     if (!allKnockoutChecked) {
//         // niet alle checkboxes
//         warningCheckboxes.classList.remove('hidden');
//         warningComponents.classList.add('hidden');
//     } else if (!allGradesSet) {
//         // componenten missen
//         warningCheckboxes.classList.add('hidden');
//         warningComponents.classList.remove('hidden');
//     } else {
//         // Alles goed, dan beide verbergen
//         warningCheckboxes.classList.add('hidden');
//         warningComponents.classList.add('hidden');
//     }
// }
//
// // Bind change‐event op alle knock‐out checkboxes
// knockoutCheckboxes.forEach(cb => {
//     cb.addEventListener('change', updateSubmitState);
// });
//
// // Roep de functie 1 keer aan voor init‐state yolo
// updateSubmitState();
