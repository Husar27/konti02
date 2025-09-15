# Backend Node.js/Express do bezpiecznego przechowywania profili użytkowników

Ten projekt umożliwia rejestrację i logowanie użytkowników z bezpiecznym haszowaniem haseł (bcrypt) oraz przechowywaniem profili w pliku JSON.

## Funkcje
- Rejestracja użytkownika (POST /register)
- Logowanie użytkownika (POST /login)
- Haszowanie haseł (bcrypt)
- Przechowywanie profili w pliku users.json

## Jak uruchomić
1. Zainstaluj zależności: `npm install`
2. Uruchom serwer: `node index.js`

## Wymagania
- Node.js 18+

## Uwaga
To rozwiązanie jest przykładowe i nie powinno być używane w środowisku produkcyjnym bez dodatkowych zabezpieczeń.
