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
}

document.querySelectorAll('.grade-button').forEach(btn => {
    btn.addEventListener('click', () => {
        const isPrefill = btn.dataset.prefill === 'true';
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
