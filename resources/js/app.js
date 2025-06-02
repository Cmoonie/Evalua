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

function calculateGrade(points) {
    const scoreMap = [
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
