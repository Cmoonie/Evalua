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
        const compId = btn.dataset.componentId;
        const pts = parseInt(btn.dataset.points, 10);
        const gradeName = btn.dataset.gradeName;
        document.querySelectorAll(`.grade-button[data-component-id="${compId}"]`).forEach(b => {
            b.classList.remove('bg-green-200','border-green-400','bg-orange-200','border-orange-400','bg-red-200','border-red-400');
        });
        let bg='', bd='';
        if (gradeName==='goed') { bg='bg-green-200'; bd='border-green-400'; }
        else if (gradeName==='voldoende') { bg='bg-orange-200'; bd='border-orange-400'; }
        else if (gradeName==='onvoldoende') { bg='bg-red-200'; bd='border-red-400'; }
        btn.classList.add(bg,bd);
        document.getElementById(`grade-level-${compId}`).value = btn.dataset.gradeId;
        updateTotals(compId, pts);
    });
});
