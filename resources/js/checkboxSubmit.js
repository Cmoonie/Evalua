export function checkboxSubmit() {
// Alles een variabele geven
    const submitButton = document.getElementById('form-submit');
    const warningCheckboxes = document.getElementById('warning-checkboxes');
    const warningComponents = document.getElementById('warning-components');
    const knockoutCheckboxes = document.querySelectorAll('.form-checkbox');
    const gradeInputs = document.querySelectorAll('input[id^="grade-level-"]');
    const gradeButtons = document.querySelectorAll('.grade-button');

// updateSubmitState controleert twee condities
    function updateSubmitState() {
        // Zijn alle knock‐out checkboxes aangevinkt?
        const allKnockoutChecked = Array.from(knockoutCheckboxes).every(cb => cb.checked);

        // Zijn alle componenten ingevuld?
        const allGradesSet = Array.from(gradeInputs).every(input => input.value !== '');

        // Mag alleen als je aan allebei voldoet
        const canSubmit = allKnockoutChecked && allGradesSet;
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
            const compId = btn.dataset.componentId;
            const gradeId = btn.dataset.gradeId;
            const points = btn.dataset.points;

            // zet de hidden input value
            const hidden = document.getElementById(`grade-level-${compId}`);
            hidden.value = gradeId;

            // update de punten
            document.getElementById(`comp-points-${compId}`).textContent = points;

            // trigger de submit state check:
            updateSubmitState();
        });
    });
    updateSubmitState();
}
