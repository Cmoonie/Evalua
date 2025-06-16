# Evalua – Digitale Beoordelingsmatrix

## Inleiding

Welkom bij **Evalua**, een project ontwikkeld binnen de opleiding *Associate degree Software Development* aan Windesheim Almere.  
Dit project focust op het digitaliseren van beoordelingsmatrices die docenten gebruiken bij het beoordelen van studentprestaties.

Deze README biedt een compleet overzicht van het project, inclusief techniek, doelstellingen, documentatie en vervolgstappen. Ideaal voor docenten, stakeholders of toekomstige ontwikkelaars.


## Doel van het project

Het doel van de applicatie is het ontwikkelen van een digitale beoordelingsmatrix die geëxporteerd kan worden naar **PDF** en **Excel**.

Uit interviews met vijf docenten kwamen de volgende knelpunten naar voren:

- Overlap tussen leerdoelen en criteria zorgt voor verwarring
- Holistisch beoordelen is wenselijk, maar vraagt ondersteuning
- Beoordelen in Excel is foutgevoelig en tijdrovend
- Grote verschillen tussen beoordelaars (geen standaardisatie)

---

## Functionaliteiten

| Functionaliteit                 | Status  | Opmerkingen            |
|-------------------------------|---------|-------------------------|
| Beoordelingsmatrix aanmaken   | Werkend |                        |
| Beoordelingsmatrix gebruiken  | Werkend        |                        |
| Automatische cijferberekening | Werkend        |                        |
| PDF-export van matrix         | Werkend        |                        |

---

## Technische informatie

- **Framework:** Laravel 11 (PHP)
- **Architectuur:** MVC (Models, Controllers, Blade Views)
- **Database:** MySQL
- **Belangrijke tabellen:**  
  `users`, `forms`, `competencies`, `components`, `grade_levels`, `component_levels`, `form_competencies`, `filled_forms`, `filled_components`

- **Tools:** PHPStorm, Composer, Git, GitHub

**GitHub-repository:**  
https://github.com/Cmoonie/Evalua](https://github.com/Cmoonie/Evalua)


**Testaccount:**
- **Email:** admin@test.com
- **Wachtwoord:** admin

---

## Gebruik & Opstartinstructie

Volg onderstaande stappen om de applicatie lokaal te draaien:

```bash
# 1. Clone de repository
git clone https://github.com/Cmoonie/Evalua.git

# 2. Installeer PHP- en JS-dependencies
composer install
npm install

# 3. Zet de database op en seed data
php artisan migrate --seed

# 4. Start de Laravel-server
composer run dev
