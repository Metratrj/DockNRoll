# Vorschläge für CI/CD-Pipelines mit GitHub Actions

Hier ist eine Liste von möglichen GitHub Actions Workflows, die für dieses Projekt sinnvoll wären, um die Codequalität zu sichern und den Deployment-Prozess zu automatisieren.

## 1. Continuous Integration (CI) Workflow

Dieser Workflow wird bei jedem Push auf einen Branch oder bei jedem Pull Request ausgeführt. Das Ziel ist es, sicherzustellen, dass der neue Code die bestehenden Konventionen einhält und keine Fehler verursacht.

**Trigger:** `on: [push, pull_request]`

**Jobs:**

### a) Code-Stil und Linting

*   **PHP Code Style (PHP-CS-Fixer):** Überprüft, ob der PHP-Code dem definierten Stil entspricht.
    *   **Aktion:** `friendsofphp/php-cs-fixer` oder ein einfacher `run` Befehl.
    *   **Befehl:** `php-cs-fixer fix --dry-run --diff`
*   **Frontend Code Style (Prettier):** Überprüft, ob JavaScript, CSS und andere Frontend-Dateien korrekt formatiert sind.
    *   **Aktion:** Man kann `actions/setup-node` verwenden und dann Prettier ausführen.
    *   **Befehl:** `npx prettier --check .`

### b) Abhängigkeiten validieren

*   **Composer:** Stellt sicher, dass die `composer.json` und `composer.lock` Dateien valide sind und die Abhängigkeiten installiert werden können. (Dies ist bereits im bestehenden `php.yml` Workflow vorhanden).
    *   **Befehl:** `composer validate --strict` und `composer install --no-progress --no-suggest`
*   **NPM:** Stellt sicher, dass die `package.json` und `package-lock.json` valide sind und die Frontend-Abhängigkeiten installiert werden können.
    *   **Befehl:** `npm ci`

### c) Frontend-Assets bauen

*   **Tailwind CSS:** Kompiliert die `style.css` zu `out.css`, um sicherzustellen, dass der Build-Prozess funktioniert.
    *   **Befehl:** `npx tailwindcss -i ./public/css/style.css -o ./public/css/out.css`

### d) Statische Analyse (Optional, aber empfohlen)

*   **PHPStan / Psalm:** Führt eine statische Analyse des PHP-Codes durch, um potenzielle Fehler zu finden, die nicht durch Linting abgedeckt werden.
    *   **Befehl (PHPStan):** `vendor/bin/phpstan analyse`

### e) Testing (Zukünftig)

*   **PHPUnit:** Führt die Unit- und Integration-Tests aus. Da es aktuell keine Tests gibt, wäre dies ein Platzhalter für die Zukunft. Es ist sehr zu empfehlen, Tests hinzuzufügen.
    *   **Befehl:** `vendor/bin/phpunit`

---

## 2. Continuous Delivery (CD) Workflow

Dieser Workflow wird ausgeführt, wenn Änderungen auf den `main`-Branch gemerged werden. Das Ziel ist es, eine neue Version der Anwendung zu bauen und bereitzustellen.

**Trigger:** `on: push: branches: [ main ]`

**Jobs:**

### a) Docker-Image bauen und pushen

*   **Bauen:** Baut ein Docker-Image der Anwendung basierend auf dem `Dockerfile`.
*   **Taggen:** Taggt das Image mit der Git-Commit-SHA und `latest`.
*   **Pushen:** Lädt das Image in eine Container Registry wie GitHub Container Registry (ghcr.io) oder Docker Hub hoch.
    *   **Aktionen:** `docker/login-action`, `docker/build-push-action`

### Beispiel-Snippet für den Build & Push Job:

```yaml
name: Build and Push Docker Image

on:
  push:
    branches:
      - main

jobs:
  build-and-push:
    runs-on: ubuntu-latest
    permissions:
      contents: read
      packages: write
    steps:
      - name: Checkout repository
        uses: actions/checkout@v3

      - name: Log in to the Container registry
        uses: docker/login-action@v2
        with:
          registry: ghcr.io
          username: ${{ github.actor }}
          password: ${{ secrets.GITHUB_TOKEN }}

      - name: Build and push Docker image
        uses: docker/build-push-action@v4
        with:
          context: .
          push: true
          tags: |
            ghcr.io/${{ github.repository }}:${{ github.sha }}
            ghcr.io/${{ github.repository }}:latest
```

### b) Deployment (Optional)

*   **Auf Server ausrollen:** Nach dem Pushen des Docker-Images könnte ein weiterer Job eine Verbindung zu einem Server herstellen (z.B. via SSH) und die neue Version der Anwendung starten.
    *   **Beispiel:** `docker-compose pull && docker-compose up -d` auf dem Zielserver ausführen.
    *   **Aktion:** `appleboy/ssh-action` könnte hierfür verwendet werden.

---

Diese Workflows würden eine solide Grundlage für die Automatisierung und Qualitätssicherung in diesem Projekt bilden.
