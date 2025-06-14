export function gradeCalculator() {
// Bestaande mapieflappies
    const componentPoints = {};
    const componentToCompetency = {};
    const competencyPoints = {};
    const totalDisplay = document.getElementById('total-points');

// hidden input voor server
    const hiddenGradeInput = document.createElement('input');
    hiddenGradeInput.type = 'hidden';
    hiddenGradeInput.name = 'calculated_grade';
    hiddenGradeInput.id = 'calculated-grade-input';
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
            const compId = btn.dataset.componentId;
            const pts = parseInt(btn.dataset.points, 10);

            // reset kleuren
            document.querySelectorAll(`.grade-button[data-component-id="${compId}"]`)
                .forEach(b => b.classList.remove(
                    'bg-green-200', 'border-green-400',
                    'bg-lime-200', 'border-lime-400',
                    'bg-red-200', 'border-red-400'
                ));

            // kies kleur
            const gradeName = btn.dataset.gradeName;
            const bg = gradeName === 'goed' ? 'bg-green-200'
                : gradeName === 'voldoende' ? 'bg-lime-200'
                    : 'bg-red-200';
            const bd = bg.replace('bg-', 'border-').replace('-200', '-400');
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
}
