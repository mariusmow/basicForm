# Basic Form

A small full-stack contact demo: a **Vue 3** front end (Vite, Tailwind CSS) talks to a **PHP** API that stores submissions in **MySQL**. The same app lists entries with search and pagination.

## What it does

- **Home (`/`)** — Renders the SPA: contact form and a searchable, paginated list of saved contacts.
- **POST `/api/submit`** — Validates and inserts a new contact (CSRF-protected).
- **GET `/api/entries`** — JSON list with optional `page` and `search` query parameters.

Validation includes South African mobile-style phone rules and server-side sanitisation before insert.

## Requirements

- PHP **8.0+** with the **PDO** MySQL extension (`ext-pdo`)
- MySQL **5.7+** or **8.x**
- [Composer](https://getcomposer.org/)
- [Node.js](https://nodejs.org/) (current LTS is fine) and npm

## Database

Create an empty MySQL database (name it whatever you will set as `DB_DATABASE` in `.env`), then load the schema and sample contacts:

```bash
mysql -u your_username -p your_database_name < contacts.sql
```

Add `-h 127.0.0.1` and `-P 3306` if your server is not local or uses a non-default port. The dump creates the `contacts` table and inserts demo rows so the list view has data on first load.

Alternatively, create the table manually (empty database, no seed data):

```sql
CREATE TABLE contacts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(32) NOT NULL,
    message VARCHAR(255) NOT NULL,
    created_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

## Configuration

Copy the environment template and set your MySQL credentials:

```bash
cp .env.example .env
```

Edit `.env` and fill in at least `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD` (and adjust `DB_HOST` / `DB_PORT` if needed).

## Install

```bash
composer install
npm install
npm run build
```

For local development with **hot module replacement**, build is optional if you run the Vite dev server (see below).

## Run the app

The document root must be the `public/` directory (for example with [Laravel Herd](https://herd.laravel.com/), nginx, or `php -S` from `public`).

**Production-style (built assets):**

```bash
npm run build
```

Then open the site URL that serves `public/index.php`.

**Development (Vite + PHP):**

1. Start Vite: `npm run dev`
2. Ensure Vite writes the dev-server URL to `public/hot` (the default Vite setup does this when the server starts).
3. Serve `public/` with PHP as above and reload the app; asset tags are injected via `public/hot` for HMR.

If you see an error about a missing Vite manifest, run `npm run build` or start `npm run dev` so `public/hot` exists.

## Project layout

| Path | Role |
|------|------|
| `contacts.sql` | MySQL dump: `contacts` table + sample rows |
| `public/index.php` | Front controller, routes, session, CSRF |
| `src/` | PHP: router, database, controllers, middleware, Vite helper |
| `resources/js/` | Vue app and components |
| `views/` | PHP shell that loads Vite assets |
