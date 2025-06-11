import './bootstrap';

import Alpine from 'alpinejs';

import 'intro.js/introjs.css';
import introJs from 'intro.js';

window.introJs = introJs;

window.Alpine = Alpine;

Alpine.start();

const componentPoints = {};
const componentToCompetency = {};
const competencyPoints = {};
const totalDisplay = document.getElementById('total-points');

document.querySelectorAll('.grade-button').forEach(btn => {
    componentToCompetency[btn.dataset.componentId] = btn.dataset.competencyId;
});

function calculateGrade(points) {
    const scoreMap = [
        [72, 77, 5.5],
        [78, 83, 6],
        [84, 89, 6.5],
        [90, 95, 7],
        [96, 100, 7.5],
        [101, 106, 8],
        [107, 112, 8.5],
        [113, 118, 9],
        [119, 124, 9.5],
        [125, 125, 10],
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
                'bg-orange-200', 'border-orange-400',
                'bg-red-200', 'border-red-400'
            );
        });

        // Kies nieuwe kleuren
        let bg = '', bd = '';
        if (gradeName === 'goed') {
            bg = 'bg-green-200';
            bd = 'border-green-400';
        } else if (gradeName === 'voldoende') {
            bg = 'bg-orange-200';
            bd = 'border-orange-400';
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

document.addEventListener('DOMContentLoaded', () => {
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
});


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

window.startIntroForms = () => {
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
};

window.startIntroBeoordelingen = () => {
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

