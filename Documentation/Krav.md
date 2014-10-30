Krav till projket i PHP
=======================
1. Det ska gå att skapa användare och logga in med dessa.
2. Användaren ska kunna skapa quiz.
3. Användaren kan skapa frågor och ge dessa svarsalternativ.
4. Användaren ska kunna välja vilka användare som får använda quizen, eller låta alla göra dessa.
5. En fråga kan ha 0 eller fler rätt svarsalternativ.
6. Användaren ska kunna få en sammanställning av resultaten i de olika quizen, olika statistik och liknande.
7. Användaren ska kunna se vad denne har gjort för quiz och se sina resultat samt vilka quiz som denne har tillgängliga.

Use-cases
=========
UC 1 Registrera användare
* Personen går till registreringssidan.
* Personen fyller i uppgifter på sidan.
* Systemet validerar uppgifterna så att de är korrekta.
* Systemet skapar en användare till personen.
* Om uppgifterna inte validerar så får personen möjlighet att rätta till dessa uppgifter och försöka igen.

UC 2 Skapa quiz
* Användaren skapar ett quiz.
* Användaren skriver en beskrivande text till quizet.
* Systemet skapar ett quiz.

UC 3 Skapa fråga och svarsalternativ
* Användaren går in i ett quiz.
* Användaren skapar en ny fråga.
* Användaren fyller i frågetexten och tillhandahåller ett antal svarsalternativ.
* Användaren väljer vilka av svarsalternativen som är rätt.
* När användaren är nöjd så avslutar han skapandet.
* Systemet skapar frågan i quizet.

UC 4 Välja användare till quizen
* Användaren går in i ett quiz.
* Användaren kan där välja om quizet ska vara öppet för alla eller vara begränsat till ett antal användare som han kan välja via en lista.

UC 5 Statistik över quiz
* Användaren som skapade quizet går in i ett quiz.
* Här presenteras statistik över quizet, hur många som har gjort quizet, hur många som har fått rätt på varje fråga och liknande.

UC 6 Se quiz historik
* Användaren går in på sin sida.
* Två listor presenteras med tillgängliga och gjorda quiz.
* Historik presenteras över de quiz som användaren har gjort, hur väl användaren har preseterat och liknande.

UC 7 Delning av quizet
* Användaren går in på ett quiz.
* Urlen som är tillgänglig är den som man kan använda för att dela quizet till valda användare, eller vem som helst om det var valet vid skapandet av quizet.
* Quizet kommer också att finnas på användarnas lista över tillgängliga quiz om quizet är öppet för dem att göra.