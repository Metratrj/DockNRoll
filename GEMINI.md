# Project Overview

This project is a minimal Docker management tool written in PHP. It provides a web-based interface to view and manage Docker containers. The backend is built with PHP and uses the Guzzle HTTP client to interact with the Docker Engine API. The frontend uses Tailwind CSS for styling.

**Key Technologies:**

*   **Backend:** PHP 8.2
*   **Frontend:** Tailwind CSS, vanilla JavaScript
*   **HTTP Client:** Guzzle
*   **Docker API Client:** A local, OpenAPI-generated client
*   **Dependency Management:** Composer for PHP, npm for frontend

**Architecture:**

The application follows a simple Model-View-Controller (MVC) pattern.

*   **Controllers:** Located in `app/Controllers`, they handle incoming requests, interact with services, and render views.
*   **Services:** Located in `app/Services`, they contain the business logic for interacting with the Docker Engine API.
*   **Views:** Located in `app/Views`, they are simple PHP templates responsible for the presentation layer.
*   **Router:** The `app/Http/Router.php` file handles routing of incoming requests to the appropriate controller methods.
*   **Entry Point:** The `public/index.php` file is the single entry point for the application.

# Building and Running

**Prerequisites:**

*   PHP 8.2 or higher
*   Composer
*   Node.js and npm
*   Docker Engine API accessible at `http://localhost:2375`

**Installation:**

1.  **Install PHP dependencies:**
    ```bash
    composer install
    ```
2.  **Install frontend dependencies:**
    ```bash
    npm install
    ```

**Running the application:**

1.  **Build the CSS:**
    ```bash
    npx @tailwindcss/cli -i ./public/css/style.css -o ./public/css/out.css
    ```
2.  **Run the PHP built-in web server:**
    ```bash
    php -S localhost:8000 -t public
    ```

The application will be available at `http://localhost:8000`.

**Testing:**

There are no tests in the project yet.

# Development Conventions

*   **Code Style:** The project uses `prettier` for code formatting and `php_codesniffer` for PHP code style.
*   **CI/CD:** The `.github/workflows/php.yml` file defines a simple CI pipeline that validates the `composer.json` file and installs dependencies.
*   **Contributing:** There are no explicit contribution guidelines, but the presence of a `.github` directory suggests that pull requests are welcome.
