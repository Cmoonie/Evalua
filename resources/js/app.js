import './bootstrap';
import introJs from 'intro.js';
import 'intro.js/introjs.css';

document.querySelector('#help-forms-button')?.addEventListener('click', () => {
    introJs().setOptions({
        nextLabel: 'Volgende',
        prevLabel: 'Terug',
        doneLabel: 'Klaar',
        steps: [
            {
                element: document.querySelector('#new-form-button'),
                intro: "Klik hier om een nieuw formulier aan te maken."
            },
            {
                element: document.querySelector('#form-table'),
                intro: "Hier zie je een overzicht van alle bestaande formulieren."
            }
        ]
    }).start();
});


import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// Bestaande mapieflappies
const componentPoints      = {};
const componentToCompetency = {};
const competencyPoints     = {};
const totalDisplay         = document.getElementById('total-points');

// hidden input voor server
const hiddenGradeInput     = document.createElement('input');
hiddenGradeInput.type      = 'hidden';
hiddenGradeInput.name      = 'calculated_grade';
hiddenGradeInput.id        = 'calculated-grade-input';
document.querySelector('form').appendChild(hiddenGradeInput);

// vul component-to-competency map
document.querySelectorAll('.grade-button').forEach(btn => {
    componentToCompetency[btn.dataset.componentId] = btn.dataset.competencyId;
});

// DYNAMIC calculateGrade such dynamic so wow
function calculateGrade(points) {
    const total = Object.keys(componentToCompetency).length;
    const mid = total * 3;
    const max = total * 5;

    const grade = points <= mid
        ? 1 + (points / mid) * 4.5
        : 5.5 + ((points - mid) / (max - mid)) * 4.5;

    return Math.round(grade * 10) / 10;
}

function updateTotals(compId, pts) {
    // Update component-score
    document.getElementById(`comp-points-${compId}`).textContent = pts;
    componentPoints[compId] = pts;

    // Opnieuw
    const compIdGroup = componentToCompetency[compId];
    let sum = 0;
    for (const [cId, p] of Object.entries(componentPoints)) {
        if (componentToCompetency[cId] === compIdGroup) sum += p;
    }
    competencyPoints[compIdGroup] = sum;
    document.getElementById(`comp-total-${compIdGroup}`).textContent = sum;

    // grand total & grade
    const grand = Object.values(competencyPoints).reduce((a, b) => a + b, 0);
    totalDisplay.textContent = grand;

    const dynamicGrade = calculateGrade(grand);
    document.getElementById('grade-display').textContent = dynamicGrade;
    hiddenGradeInput.value = dynamicGrade;
}

// bind kliks
document.querySelectorAll('.grade-button').forEach(btn => {
    btn.addEventListener('click', () => {
        const compId    = btn.dataset.componentId;
        const pts       = parseInt(btn.dataset.points, 10);

        // reset kleuren
        document.querySelectorAll(`.grade-button[data-component-id="${compId}"]`)
            .forEach(b => b.classList.remove(
                'bg-green-200','border-green-400',
                'bg-lime-200','border-lime-400',
                'bg-red-200','border-red-400'
            ));

        // kies kleur
        const gradeName = btn.dataset.gradeName;
        const bg = gradeName === 'goed'      ? 'bg-green-200'
            : gradeName === 'voldoende'? 'bg-lime-200'
                :                               'bg-red-200';
        const bd = bg.replace('bg-', 'border-').replace('-200','-400');
        btn.classList.add(bg, bd);

        // hidden input component-grade
        document.getElementById(`grade-level-${compId}`).value = btn.dataset.gradeId;

        // update puntentelling
        updateTotals(compId, pts);
    });
});

// init bestaande waardes
document.querySelectorAll('input[id^="grade-level-"]').forEach(input => {
    const compId = input.id.replace('grade-level-', '');
    if (input.value) {
        const btn = document.querySelector(
            `.grade-button[data-component-id="${compId}"][data-grade-id="${input.value}"]`
        );
        if (btn) btn.click();
    }
});

// submit-safety: refresh hiddenGrade net voor verzenden
document.querySelector('form').addEventListener('submit', () => {
    const grand = Object.values(competencyPoints).reduce((a, b) => a + b, 0);
    hiddenGradeInput.value = calculateGrade(grand);
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

// Start‐state direct correct zetten
updateSubmitState();




// INTRO DOORLOOP ~~~~~~~~~~
// VOOR HET DASHBOARD
window.startIntroDashboard = () => {
    introJs().setOptions({
            nextLabel: 'Volgende',
            prevLabel: 'Terug',
            doneLabel: 'Klaar',
        steps: [
            {
                element: document.querySelector('#forms-link'),
                intro: "Hier kun je alle formulieren bekijken en aanmaken."
            },
            {
                element: document.querySelector('#filledforms-link'),
                intro: "Hier zie je alle beoordelingen terug."
            }
        ]
    }).start();
};

// Bindbindbind
document.querySelector('#help-dashboard-button')?.addEventListener('click', () => {
    window.startIntroDashboard();
});



// VOOR DE CIJFERLIJSTENPAGINA
window.startIntroGradelist = () => {
    introJs().setOptions({
        nextLabel: 'Volgende',
        prevLabel: 'Terug',
        doneLabel: 'Klaar',
        steps: [
            {
                element: document.querySelector('#beoordelingen-title'),
                intro: "Op deze pagina zie je de beoordelingen die bij een vak horen."
            },
            {
                element: document.querySelector('#vak-title'),
                intro: "Hier zie je de naam van het vak. Klik erop om de beoordelingen te tonen."
            },
            {
                element: document.querySelector('#new-beoordeling-btn'),
                intro: "Klik hier om een nieuwe beoordeling te starten."
            }
        ]
    }).start();
};

// Bind button
document.querySelector('#help-gradelist-button')?.addEventListener('click', () => {
    window.startIntroGradelist();
});


// // Bepaal welke pagina we op zitten
//     function getIntroPageKey() {
//         const p = window.location.pathname;
//         if (p.includes('/dashboard')) return 'dashboard';
//         if (p.includes('/forms'))     return 'forms';
//         if (p.includes('/gradelist')) return 'gradelist';
//         return null;
//     }
//
//
// // Roep de juiste tour functie aan op basis van pageKey
//     function runIntroForPage(pageKey) {
//         const fnName = `startIntro${pageKey.charAt(0).toUpperCase() + pageKey.slice(1)}`;
//         const fn = window[fnName];
//         if (typeof fn === 'function') {
//             fn();
//         } else {
//             console.warn(`Intro-functie ${fnName} niet gevonden.`);
//         }
//     }
//
// // autorun als je voor het eerst op de pagina komt
//     (function autoRunIntro() {
//         const pageKey = getIntroPageKey();
//         if (!pageKey) return;  // geen tour op deze pagina
//
//         const storageKey = `startIntro_${pageKey}`;
//         if (sessionStorage.getItem(storageKey) === 'true') {
//             sessionStorage.removeItem(storageKey);  // eenmalig
//             runIntroForPage(pageKey);
//         }
//     })();





