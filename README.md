<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Property Management API

This API manages the hierarchy of corporations, buildings, properties, tenancy periods, and tenants.
It provides endpoints to add, view, and update nodes while enforcing relationships and business rules.
Designed for backend system design practice, it ensures data integrity and proper API structure.

---

## API Endpoints

**Authentication:**

-   `POST /api/login` — Obtain an API token (see Authentication section below)

**Corporations, Buildings, Properties, Tenancy Periods, Tenants:**

-   `GET /api/corporations`, `POST /api/corporations`, `GET /api/corporations/{id}`, etc. (standard CRUD)
-   `GET /api/buildings`, `POST /api/buildings`, ...
-   `GET /api/properties`, `POST /api/properties`, ...
-   `GET /api/tenancy-periods`, `POST /api/tenancy-periods`, ...
-   `GET /api/tenants`, `POST /api/tenants`, ...

**Tree Node Operations:**

-   `GET /api/nodes/{type}/{id}/children` — Get all direct children of a node (one layer only)
-   `POST /api/nodes/{type}/{id}/move` — Change the parent node of a given node

---

## Authentication

This API uses [Laravel Sanctum](https://laravel.com/docs/10.x/sanctum) for authentication.

1. Register a user (if registration is enabled) or use a seeded user.
2. Obtain a token via:

    ```http
    POST /api/login
    Content-Type: application/json

    {
      "email": "user@example.com",
      "password": "your_password"
    }
    ```

3. Use the returned token as a Bearer token in the `Authorization` header for all requests.

---

## Business Rules

-   **Properties** can only have Buildings as parents.
-   **Tenancy Periods** can only have Properties as parents.
-   **Tenants** can only have Tenancy Periods as parents.
-   **Only one Tenancy Period can be active in a Property at a time.**
-   **A Tenancy Period can have a maximum of 4 tenants at any time.**

These rules are enforced on create, update, and move operations.

---

## Example Requests

**Get all direct children of a Corporation (Buildings):**

```http
GET /api/nodes/Corporation/1/children
Authorization: Bearer {token}
```

**Move a Property to a new Building:**

```http
POST /api/nodes/Property/5/move
Authorization: Bearer {token}
Content-Type: application/json

{
  "new_parent_id": 2
}
```

**Create a new Tenancy Period (will fail if another is active):**

```http
POST /api/tenancy-periods
Authorization: Bearer {token}
Content-Type: application/json

{
  "property_id": 1,
  "name": "2025 Lease",
  "start_date": "2025-08-01",
  "active": true
}
```

---

## Database Design

The database for this API models the hierarchy of corporations, buildings, properties, tenancy periods, and tenants. Each entity includes specific fields relevant to its type, and relationships are strictly enforced to maintain data integrity.

For a visual representation of the database schema, see the diagram:

[Database Diagram](./docs/db_diagram.pdf)

This diagram shows all tables, their fields, and the relationships between them, including extra fields like `zip_code` for buildings, `monthly_rent` for properties, `active` for tenancy periods, and `move_in_date` for tenants.

## Installation

1.  Clone the repository:

    ```bash
    git clone git@github.com:qaiswardag/property_management_api.git
    ```

2.  Install dependencies

    ```bash
    composer install
    ```

3.  Create a new database and configure environment variables

    -   Copy .env.example to .env and update the database connection settings accordingly:

    ```
    DB_DATABASE=your_database_name
    DB_USERNAME=your_database_user
    DB_PASSWORD=your_database_password
    ```

4.  Generate the application key

    ```bash
    php artisan key:generate
    ```

5.  Run migrations

    ```bash
    php artisan migrate
    ```

6.  Start server
    ```bash
    php artisan serve
    ```
