# myWellness Bot

Der **myWellness Bot** ist ein Laravel-Projekt, das darauf abzielt, Nutzern dabei zu helfen, passende Zeitslots für Auszeiten bei myWellness zu finden. Aufgrund der hohen Nachfrage sind die verfügbaren Slots oft ausgebucht. Allerdings gibt es Fälle, in denen Personen ihre Buchungen stornieren, wodurch kurzfristig Slots frei werden. Dieser Bot überwacht die Verfügbarkeit von Slots und benachrichtigt die Nutzer über Telegram, sobald ein passender Slot frei wird.

## Funktionen

- Überwachung der Verfügbarkeit von Zeitslots bei myWellness in Echtzeit.
- Benachrichtigung der Nutzer über Telegram, wenn ein passender Slot frei wird.


## Systemvoraussetzungen

- PHP >= 8.0.2
- Composer

## Installation

1. Klone das Repository:
```bash
git clone https://github.com/johanneslo1/mywellness-bot.git
```

2. Installiere die Abhängigkeiten mit Composer:
```bash
cd mywellness-bot
composer install
```

3. Kopiere die `.env.example` Datei und passe die Konfiguration an:
```bash
cp .env.example .env
```

4. Generiere den Application Key:
```bash
php artisan key:generate
```

5. Migriere die Datenbank:
```bash
php artisan migrate
```

6. Installiere die Node.js-Abhängigkeiten & Baue die Frontend-Assets:
```bash
npm install && npm run build
```

## Benutzung

- Starte den Laravel Scheduler, um die Verfügbarkeit von Slots zu überwachen:
```bash
php artisan schedule:work
```

### Disclaimer

Dieser Bot und das dazugehörige Projekt sind unabhängig und stehen in keiner Weise in Verbindung mit der offiziellen myWellness.de Website oder den Betreibern von myWellness. Die Nutzung dieses Bots geschieht auf eigene Gefahr und die Entwickler übernehmen keine Haftung für etwaige Probleme oder Schäden, die durch die Nutzung dieses Bots entstehen könnten. Bitte stellen Sie sicher, dass Sie die Nutzungsbedingungen und Datenschutzrichtlinien von myWellness sowie die lokalen Gesetze und Vorschriften bezüglich der Nutzung solcher Bots verstehen und einhalten.
