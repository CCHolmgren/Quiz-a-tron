Krav till projket i PHP
=======================
1. Det ska gå att skapa användare och logga in med dessa.
2. Administratören ska kunna skapa quiz.
3. Adminstratören kan skapa frågor och ge dessa svarsalternativ.
4. Administratören ska kunna välja vilka användare som får använda quizen, eller låta alla göra dessa.
5. En fråga kan ha 0 eller fler rätt svarsalternativ.
6. Administratören ska kunna få en sammanställning av resultaten i de olika quizen, olika statistik och liknande.
7. Användaren ska kunna se vad denne har gjort för quiz och se sina resultat samt vilka quiz som denne har tillgängliga.

Use-cases
=========
UC 1 Registrera användare
Personen går till registreringssidan.
Personen fyller i uppgifter på sidan.
Systemet validerar uppgifterna så att de är korrekta.
Systemet skapar en användare till personen.
Om uppgifterna inte validerar så får personen möjlighet att rätta till dessa uppgifter och försöka igen.

UC 2 Skapa quiz
Administratören skapar ett quiz.
Administratören skriver en beskrivande text till quizet.
Systemet skapar ett quiz.

UC 3 Skapa fråga och svarsalternativ
Administratören går in i ett quiz.
Administratören skapar en ny fråga.
Administratören fyller i frågetexten och tillhandahåller ett antal svarsalternativ.
Administratören väljer vilka av svarsalternativen som är rätt.
När administratören är nöjd så avslutar han skapandet.
Systemet skapar frågan i quizet.

UC 4 Välja användare till quizen
Administratören går in i ett quiz.
Administratören kan där välja om quizet ska vara öppet för alla eller vara begränsat till ett antal användare.

UC 5 Statistik över quiz
Administratören går in i ett quiz.
Här presenteras statistik över quizet, hur många som har gjort quizet, hur många som har fått rätt på varje fråga och liknande.

UC 6 Se quiz historik
Användaren går in på sin sida.
Historik presenteras med alla quiz som användaren har gjort, hur väl användaren har preseterat och liknande.

