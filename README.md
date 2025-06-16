# Evalua – Digitale Beoordelingsmatrix

## Inleiding

Welkom bij **Evalua**, een project ontwikkeld binnen de opleiding *Associate degree Software Development* aan Windesheim Almere.  
Dit project focust op het digitaliseren van beoordelingsmatrices die docenten gebruiken bij het beoordelen van studentprestaties.

Deze README biedt een compleet overzicht van het project, inclusief techniek, doelstellingen, documentatie en vervolgstappen. Ideaal voor docenten, stakeholders of toekomstige ontwikkelaars.

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
https://github.com/Cmoonie/Evalua


**Testaccount:**
- **Email:** admin@test.com
- **Wachtwoord:** admin

---

## Gebruik & Opstartinstructie

Volg onderstaande stappen om de applicatie lokaal te draaien:

```bash
# 1. Clone de repository
    git clone https://github.com/Cmoonie/Evalua.git

# 2. cd naar de directory
    cd Evalua

# 3. Installeer PHP- en JS-dependencies
    composer install
    npm install
    npm run build

# 4. Open het bestaande .env bestand 
Het .env bestand zou al aanwezig moeten zijn in het gedownloade project. Open dit bestand in een teksteditor.
Mocht het bestand niet bestaan, maak het dan zo:

    cp .env.example .env

Pas de database-instellingen aan indien nodig, zoals een ander poortnummer of DB wachtwoord.

# 5. Genereer de applicatiesleutel
Voer het volgende commando uit om de applicatiesleutel van Laravel te genereren:

    php artisan key:generate

# 6. Maak de database aan
Voordat je de migraties uitvoert, moet je ervoor zorgen dat de database bestaat. Dit kan handmatig via de MySQL CLI of phpMyAdmin:

    mysql -u root -p
    CREATE DATABASE evalua;
    EXIT;

Zorg ervoor dat de gegevens in de .env file overeen komen met jouw database instellingen.

# 7. Zet de database op en seed data
    php artisan migrate --seed

# 8. Start de Laravel-server
    composer run dev
