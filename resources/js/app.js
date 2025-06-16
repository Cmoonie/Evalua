import './bootstrap';
import Alpine from 'alpinejs';
import { formBuilder } from './formBuilder.js';
import { startFormsTour, startDashboardTour, startGradelistTour } from './introTour.js';
import { gradeCalculator } from "./gradeCalculator.js";
import { checkboxSubmit } from "./checkboxSubmit.js";
import { formEditor } from "./formEditor.js";

window.Alpine = Alpine;

Alpine.data('formBuilder', formBuilder);

Alpine.start();

// Intro tour knoppen binden
document.querySelector('#help-forms-button')?.addEventListener('click', startFormsTour);
document.querySelector('#help-dashboard-button')?.addEventListener('click', startDashboardTour);
document.querySelector('#help-gradelist-button')?.addEventListener('click', startGradelistTour);

// Starten die handel
gradeCalculator();
formEditor();
checkboxSubmit();




