# TODO-Liste: Globale Such- und Befehlsfunktion

Dieses Dokument listet den Status der Implementierung der globalen Such- und Befehlsfunktion für Dock'n'Roll auf.

## 1. Vision & Ziel
- Zentrale Suchleiste für Docker-Ressourcen und kontextbezogene Aktionen. (Umgesetzt)

## 2. Architektur & Kernkomponenten

### 2.1. Caching-Service (Backend)
- **Technologie:** Redis mit RediSearch (Umgesetzt)
- **Worker/Crawler:** Hintergrundprozess zum Abfragen der Docker-API und Speichern in Redis (Umgesetzt)
  - Speicherungs-Struktur in Redis (JSON-Dokumente) (Umgesetzt)
  - Volltext-Indexierung des gesamten Objekts (Umgesetzt)
- **Zukünftige Optimierung:** RediSearch-Modul evaluieren (Erledigt)

### 2.2. Such-API (Backend)
- **Endpunkt:** `POST /api/search` (Umgesetzt)
- **Controller:** `SearchController` (Umgesetzt)
- **Logik:** Abfrage des RediSearch-Index (Umgesetzt)
- **Sortierung:** Relevanz-Sortierung durch RediSearch-Gewichtung (Umgesetzt)

### 2.3. Befehls-API (Backend)
- **Endpunkt:** `POST /api/command` (Umgesetzt)
- **Controller:** `CommandController` (Umgesetzt)
- **Logik:** Befehlsparser, Auflösung von Namen zu IDs, Ausführung von Aktionen (start/stop) (Umgesetzt)
- **Autocomplete-Endpunkt:** `GET /api/commands` zur Bereitstellung von Befehlen (Umgesetzt)

### 2.4. Such-Interface (Frontend)
- **JavaScript-Modul:** `search.js` (Umgesetzt)
- **UI-Komponente:** Modales Overlay für Suchergebnisse (Umgesetzt)
- **Funktionalität:**
  - Debouncing (Umgesetzt)
  - Dynamisches Rendering der Ergebnisse (Umgesetzt)
  - Befehlserkennung (ohne `>`-Präfix) und -ausführung (Umgesetzt)
  - **Befehls-Autocomplete:** Vorschläge für Befehle während der Eingabe (Umgesetzt)
  - **Ziel-Autocomplete:** Vorschläge für Container nach der Befehlseingabe (Umgesetzt)
  - Aktions-Buttons in Suchergebnissen (Umgesetzt)
  - Tastaturnavigation (Pfeiltasten, Enter) (Umgesetzt)

## 3. Besondere Herausforderungen & Lösungen
- **Performance bei der Suche:** Caching-Service mit RediSearch (Gelöst)
- **Durchsuchen von Logs:** Globale Suche durchsucht keine Logs, Verweis auf dedizierte Ansicht (Design-Entscheidung, umgesetzt)
- **Relevanz der Ergebnisse:** Priorisierungslogik durch RediSearch (Gelöst)
- **Infix-Suche:** Indexierung aller Substrings (Ersetzt durch RediSearch-Volltextsuche)

## 4. Umsetzungsplan (Phasen)
- **Phase 0: Infrastruktur & Caching** (Abgeschlossen)
- **Phase 1: Backend-API (Liest aus dem Cache)** (Abgeschlossen)
- **Phase 2: Frontend-Implementierung (Read-Only)** (Abgeschlossen)
- **Phase 3: Befehlsausführung & Aktionen** (Abgeschlossen)
- **Phase 4: Verfeinerung & UX-Verbesserungen** (Abgeschlossen)
  - Tastaturnavigation (Umgesetzt)
  - Relevanz-Sortierung (Umgesetzt)
  - UI-Feedback (Ladeindikatoren, Erfolgs-/Fehlermeldungen) (Umgesetzt)
  - Befehls- und Ziel-Autocomplete (Umgesetzt)

## Offene Punkte / Nächste Schritte
- **Fehlerbehebung:** Aktuelle Regression beheben (Suche/Commands funktionieren nicht mehr). (Gelöst)
- **UI-Feedback:** Detailliertere Ladeindikatoren und Statusmeldungen im Frontend. (Umgesetzt)
- **Weitere Befehle:** Implementierung weiterer Docker-Befehle (z.B. `remove`, `inspect`).
- **Image-Aktionen:** Aktionen für Images (z.B. `pull`, `remove`).
- **Detailseiten-Links:** Sicherstellen, dass Links zu Detailseiten korrekt funktionieren.
- **Fehlerbehandlung:** Robusteres Error-Handling im Frontend für API-Fehler.
- **Tests:** Unit- und Integrationstests für alle neuen Komponenten.
- **Dokumentation:** Aktualisierung der `GEMINI.md` mit den neuen Features.
