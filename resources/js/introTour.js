import introJs from 'intro.js';
import 'intro.js/introjs.css';


function startDashboardTour() {
    introJs().setOptions({
        nextLabel: 'Volgende',
        prevLabel: 'Terug',
        doneLabel: 'Klaar',
        steps: [
            { element: '#forms-link',     intro: "Hier kun je formulieren aanmaken, bekijken en studenten beoordelen." },
            { element: '#gradelist-link', intro: "Hier zie je alle ingevulde beoordelingen terug." },
            { element: '#account-link',   intro: "Hier kan je je accountgegevens bewerken." }
        ]
    }).start();
}

function startFormsTour() {
    introJs().setOptions({
        nextLabel: 'Volgende',
        prevLabel: 'Terug',
        doneLabel: 'Klaar',
        steps: [
            { element: '#new-form-button',     intro: "Klik hier om een nieuw formulier aan te maken." },
            { element: '#form-table',          intro: "Overzicht van alle bestaande formulieren." },
            { element: '#form-link',           intro: "Klik hier om het formulier te bekijken en bewerken." },
            { element: '#student-beoordelen',  intro: "Klik hier om een student te beoordelen." }
        ]
    }).start();
}


function startGradelistTour() {
    introJs().setOptions({
        nextLabel: 'Volgende',
        prevLabel: 'Terug',
        doneLabel: 'Klaar',
        steps: [
            { element: '#beoordelingen-title', intro: "Op deze pagina zie je de ingevulde beoordelingen bij een vak." },
            { element: '#vak-title',           intro: "Naam van het vak; klikken toont ingevulde beoordelingen." },
            { element: '#cijferlijst',         intro: "Hier is de cijferlijst." }
        ]
    }).start();
}

export { startFormsTour, startDashboardTour, startGradelistTour };
