

# Routes Documentation

This project organizes routes in a modular way under the `routes/` directory.
Each file is responsible for a specific feature or area.
Routes are grouped by **type** (`web` or `api`) and **purpose** (`auth`, `custom`, `laravel`).

---

## Directory Structure

```
routes/
├── web.php
├── api.php
├── auth/
│   ├── api/
│   │   └── auth.php
│   └── web/
│       └── auth.php
├── custom/
│   ├── api/
│   │   └── mobile.php
│   └── web/
│       ├── back/
│       │   ├── customer.php
│       │   ├── dashboard.php
│       │   └── payment.php
│       └── front/
│           └── frontendOLD.php
├── laravel/
│   └── console.php
```

---

## Top-level files

### `web.php`

Main entry point for all web routes.
Includes:

* Default home page route
* Loads additional web route files from `auth/web` and `custom/web`

### `api.php`

Main entry point for all API routes.
Includes:

* Loads additional API route files from `auth/api` and `custom/api`

### `laravel/console.php`

Defines routes for Artisan console commands (if needed).
Not usually modified.

---

## Auth Routes

### `auth/api/auth.php`

API authentication routes.
For example: login, logout, register via API (token-based).
Used by mobile apps and external API clients.

### `auth/web/auth.php`

Web authentication routes.
For example: login, logout, register via web (session-based).
Used by web users and admins.

---

## Custom Routes

These files define application-specific features.

### `custom/api/mobile.php`

API routes for mobile clients.
Examples: order submission, product listing, tracking orders.

---

### `custom/web/back/`

Backend (admin) web routes.

| File            | Description                                                |
| --------------- | ---------------------------------------------------------- |
| `customer.php`  | Customer account management routes.                        |
| `dashboard.php` | Admin dashboard routes, including sections, orders, stats. |
| `payment.php`   | Payment integration routes, such as Paymob.                |

---

### `custom/web/front/frontendOLD.php`

Legacy or alternative frontend web routes for customers.
Includes routes like browsing products, shopping cart, and checkout.

---

## Usage Notes

* `web.php` loads all web-related routes:

  ```php
  require __DIR__.'/auth/web/auth.php';
  require __DIR__.'/custom/web/back/dashboard.php';
  require __DIR__.'/custom/web/back/customer.php';
  require __DIR__.'/custom/web/back/payment.php';
  require __DIR__.'/custom/web/front/frontendOLD.php';
  ```

* `api.php` loads all API-related routes:

  ```php
  require __DIR__.'/auth/api/auth.php';
  require __DIR__.'/custom/api/mobile.php';
  ```

* Each route file is independent and focused on one feature.

* Keep `web.php` and `api.php` clean by only including the required route files.
