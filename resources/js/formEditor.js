export function formEditor() {
    // Haal gradeLevels in vanuit de hidden div
    const lvlEl = document.getElementById('grade-levels');
    const gradeLevels = lvlEl ? JSON.parse(lvlEl.dataset.levels) : [];

    // Helpers
    function capitalize(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }
    function renumberComponents(container) {
        const comps = container.querySelectorAll('div[data-component-index]');
        comps.forEach((comp, i) => {
            comp.dataset.componentIndex = i;
            const header = comp.querySelector('h3');
            if (header) header.textContent = `Component ${i + 1}`;
            comp.querySelectorAll('input, textarea').forEach(input => {
                input.name = input.name.replace(/\[components]\[\d+]/, `[components][${i}]`);
            });
        });
    }
    function renumberCompetencies() {
        const comps = document.querySelectorAll('div[data-competency-index]');
        comps.forEach((comp, i) => {
            comp.dataset.competencyIndex = i;
            const h2 = comp.querySelector('h2');
            if (h2) h2.textContent = `Competentie ${i + 1}`;
            comp.querySelectorAll('input, textarea').forEach(input => {
                input.name = input.name.replace(/competencies\[\d+]/, `competencies[${i}]`);
            });
            const sub = comp.querySelector(`#competency-${i}-components`);
            if (sub) renumberComponents(sub);
        });
    }

    // Component verwijderen
    function removeComponent(btn) {
        const div = btn.closest('div[data-component-index]');
        if (!div) return;
        const parent = div.parentElement;
        div.remove();
        renumberComponents(parent);
    }

    // Competentie verwijdeeren
    function removeCompetency(btn) {
        const div = btn.closest('div[data-competency-index]');
        if (!div) return;
        div.remove();
        renumberCompetencies();
    }

    //Component toevoegen
    function addComponent(cIndex) {
        const container = document.getElementById(`competency-${cIndex}-components`);
        if (!container) return;
        const count = container.children.length;
        // bouw levels HTML
        let levelsHtml = '';
        ['onvoldoende','voldoende','goed'].forEach((grade, idx) => {
            const gl = gradeLevels.find(g => g.name.toLowerCase() === grade);
            if (!gl) return;
            levelsHtml += `
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">
            ${capitalize(grade)} (${gl.points} pt)
          </label>
          <input type="hidden"
            name="competencies[${cIndex}][components][${count}][levels][${idx}][grade_level_id]"
            value="${gl.id}">
          <textarea
            name="competencies[${cIndex}][components][${count}][levels][${idx}][description]"
            rows="2"
            class="block text-sm w-full min-h-24 border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 resize-none"
          ></textarea>
        </div>`;
        });

        const html = `
      <div class="bg-gray-50 rounded-lg border border-gray-200 p-6 space-y-6"
           data-component-index="${count}">
        <div class="flex items-center justify-between">
          <h3 class="text-lg font-semibold text-gray-800">Component ${count + 1}</h3>
          <button type="button" onclick="removeComponent(this)"
                  class="text-red-600 hover:text-red-800 text-2xl font-extrabold">✗
          </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Naam</label>
            <input type="text"
                   name="competencies[${cIndex}][components][${count}][name]"
                   required
                   class="block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Beschrijving</label>
            <textarea name="competencies[${cIndex}][components][${count}][description]"
                      rows="4" required
                      class="block text-sm w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 resize-none">
            </textarea>
          </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          ${levelsHtml}
        </div>
      </div>`;
        container.insertAdjacentHTML('beforeend', html);
    }

    // Competentie toevoegen
    function addCompetency() {
        const container = document.getElementById('competencies-container');
        const count = container.children.length;
        const html = `
      <div class="bg-white shadow-sm rounded-lg divide-y divide-gray-200"
           data-competency-index="${count}">
        <div class="px-6 py-4 flex justify-between items-center">
          <h2 class="text-2xl font-bold text-gray-800">Competentie ${count + 1}</h2>
          <button type="button" onclick="removeCompetency(this)"
                  class="text-red-600 hover:text-red-800 text-2xl font-extrabold">✗
          </button>
        </div>
        <div class="px-6 py-6 space-y-6">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Naam</label>
            <input type="text"
                   name="competencies[${count}][name]" required
                   class="block w-1/2 border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
          </div>
          <div class="grid grid-cols-3 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Domeinbeschrijving</label>
              <textarea name="competencies[${count}][domain_description]"
                        rows="5" required
                        class="block text-sm w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 resize-none">
              </textarea>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Beoordelingsschaal</label>
              <textarea name="competencies[${count}][rating_scale]"
                        rows="5" required
                        class="block text-sm w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 resize-none">
              </textarea>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Knock-out Criteria &amp; Deliverables</label>
              <textarea name="competencies[${count}][complexity]"
                        rows="5" required
                        class="block text-sm w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 resize-none">
              </textarea>
            </div>
          </div>
          <div id="competency-${count}-components" class="space-y-6"></div>
          <div>
            <x-secondary-button
                                onclick="addComponent(${count})">
              + Component toevoegen
            </x-secondary-button>
          </div>
        </div>
      </div>`;
        container.insertAdjacentHTML('beforeend', html);
        addComponent(count);
    }

    // Exporteer naar window zodat onclick attributes gewoon werkennnn
    window.removeComponent   = removeComponent;
    window.removeCompetency  = removeCompetency;
    window.addComponent      = addComponent;
    window.addCompetency     = addCompetency;
}
