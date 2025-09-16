# Planung: Globale Such- und Befehlsfunktion

Dieses Dokument beschreibt die Planung und Konzeption für eine globale Such- und Befehlsfunktion ("Command Palette") für Dock'n'Roll.

## 1. Vision & Ziel

Ziel ist die Implementierung einer zentralen Suchleiste in der Hauptnavigation, die es dem Benutzer ermöglicht, schnell und effizient alle relevanten Docker-Ressourcen zu durchsuchen und kontextbezogene Aktionen auszuführen. Dies verbessert die User Experience erheblich, indem es die Navigation durch verschiedene Menüs überflüssig macht.

**Beispiele für Suchanfragen:**
- `nginx`: Findet alle Container, Images, Volumes etc., die "nginx" im Namen oder Tag haben.
- `start my-db`: Startet den Container mit dem Namen `my-db`.
- `a8b3d...`: Findet den Container oder das Image mit dieser ID.

## 2. Architektur & Kernkomponenten

### 2.1. Caching-Service (Backend)

Um Performance-Engpässe durch ständige Abfragen der Docker-API zu vermeiden, wird ein Caching-Mechanismus eingeführt.

- **Technologie:** Eine In-Memory-Datenbank wie **Redis** ist hierfür ideal geeignet.
- **Worker/Crawler:** Ein Hintergrundprozess (ein PHP-Skript) wird implementiert.
  - **Aufgabe:** Dieser "Crawler" fragt die Docker-API ab, um den Zustand von Containern, Images, etc. zu erfassen.
  - **Speicherungs-Struktur in Redis:** Es werden native Datenstrukturen von Redis genutzt, um einen performanten Index aufzubauen:
    - **Objekte als Hashes:** Jedes Docker-Objekt wird als Redis-Hash gespeichert.
    - **Indizes mit Sets:** Für Suchbegriffe und Typen werden Sekundärindizes mit Redis-Sets erstellt.
- **Zukünftige Optimierung:** Für noch mächtigere Suchen könnte später das **RediSearch**-Modul evaluiert werden.

### 2.2. Such-API (Backend)

- **Endpunkt:** Ein API-Endpunkt `POST /api/search` wird geschaffen.
- **Controller:** Ein `SearchController` dient als zentrale Anlaufstelle.
- **Logik:**
  1.  Nimmt eine Suchanfrage (z.B. `{"query": "nginx"}`) vom Frontend entgegen. Kann optional nach Typ filtern.
  2.  Fragt die Redis-Indizes ab, um passende Objekt-IDs zu finden.
  3.  Holt die Objektdaten aus den Redis-Hashes.
  4.  Aggregiert die Ergebnisse und sendet sie als JSON an das Frontend.

### 2.3. Befehls-API (Backend)

- **Endpunkt:** Ein Endpunkt `POST /api/command` für die Ausführung von Aktionen.
- **Logik:** Parst den Befehl und das Ziel und ruft den entsprechenden Service auf.
- **Autocomplete Endpunkt:** Ein Endpunkt `GET /api/commands` liefert eine Liste der verfügbaren Befehle für die Autocomplete-Funktion.

### 2.4. Such-Interface (Frontend)

- **JavaScript-Modul:** Ein Modul (`search.js`) steuert die gesamte Frontend-Logik.
- **UI-Komponente:** Ein modales Overlay wird für die Anzeige der Suchergebnisse und Vorschläge verwendet.
- **Funktionalität:**
  - **Debouncing:** Sendet API-Anfragen mit einer leichten Verzögerung.
  - **Dynamisches Rendering:** Stellt die vom Backend erhaltenen Ergebnisse und Vorschläge dar.
  - **Befehlserkennung:** Erkennt, ob die Eingabe ein Suchbegriff oder ein Befehl ist. Befehle werden ohne spezielles Präfix erkannt.
  - **Autocomplete:**
    - Schlägt passende Befehle während der Eingabe vor.
    - Schlägt passende Container als Ziele für Befehle vor (z.B. nach `start `).
  - **Tastaturnavigation:** Ermöglicht die Auswahl von Ergebnissen und Vorschlägen über die Pfeiltasten und Enter.

## 3. Besondere Herausforderungen & Lösungen

- **Performance bei der Suche:** Gelöst durch den Caching-Service.
- **Ambiguität zwischen Suche und Befehl:** Gelöst durch eine kontextsensitive Logik im Frontend, die zwischen der Eingabe eines Befehls, eines Ziels oder einer allgemeinen Suchanfrage unterscheidet.

## 4. Umsetzungsplan (Phasen)

**Phase 0: Infrastruktur & Caching** (Abgeschlossen)

**Phase 1: Backend-API** (Abgeschlossen)

**Phase 2: Frontend-Implementierung** (Abgeschlossen)

**Phase 3: Befehlsausführung & Aktionen** (Abgeschlossen)

**Phase 4: Verfeinerung & UX-Verbesserungen** (Abgeschlossen)
- Implementierung der Tastaturnavigation.
- Hinzufügen von Autocomplete für Befehle und deren Ziele.
- Diverse Bugfixes zur Stabilisierung der Funktionalität.
