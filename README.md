# PHP_Laravel12_Impersonate
## Project Title

Laravel 12 User Impersonation System (Admin Login As User)

---

## Project Goal

Allow an **Admin** to temporarily log in as another user without knowing their password. This is useful for:

* Customer Support
* Debugging Issues
* QA Testing
* Monitoring User Problems

Package Used: **lab404/laravel-impersonate**

---

## Step 1 – Create New Laravel Project

```bash
composer create-project laravel/laravel laravel-impersonate
cd laravel-impersonate
```

This installs a fresh Laravel 12 project.

---

## Step 2 – Install Impersonate Package

```bash
composer require lab404/laravel-impersonate
```

This package provides ready‑made impersonation logic.

---

## Step 3 – Publish Config (Optional)

```bash
php artisan vendor:publish --provider="Lab404\Impersonate\ImpersonateServiceProvider"
```

Creates configuration file:

```
config/impersonate.php
```

You can customize session keys, route names, and behavior later.

---

## Step 4 – Database Setup (.env)

Open `.env` file and configure database:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_impersonate
DB_USERNAME=root
DB_PASSWORD=
```

Create the database first in MySQL or phpMyAdmin.

---

## Step 5 – Add Admin Column in Users Table

Laravel already has a `users` table. Add an `is_admin` column.

```bash
php artisan make:migration add_is_admin_to_users_table
```

Migration change:

```php
$table->boolean('is_admin')->default(false);
```

Purpose: Identify which users are administrators.

---

## Step 6 – Run Migration

```bash
php artisan migrate
```

All tables will be created/updated.

---

## Step 7 – Seed Test Users

Create seeder:

```bash
php artisan make:seeder UsersTableSeeder
```

Insert:

* 1 Admin User
* 2 Normal Users
* 5 Random Users

Run Seeder:

```bash
php artisan db:seed
```

---

## Step 8 – Update User Model

File: `app/Models/User.php`

Add Trait:

```php
use Lab404\Impersonate\Models\Impersonate;

class User extends Authenticatable
{
    use Impersonate;
```

Add Methods:

```php
public function canImpersonate()
{
    return $this->is_admin;
}

public function canBeImpersonated()
{
    return !$this->is_admin;
}
```

Meaning:

* Only Admin can impersonate
* Admin cannot impersonate another Admin

---

## Step 9 – Controllers

### Admin UserController

Handles:

* User List
* Start Impersonation
* Leave Impersonation

Key Logic:

```php
Auth::user()->impersonate($user);
Auth::user()->leaveImpersonation();
```

### DashboardController

Displays different dashboards:

* Admin → Admin Panel
* User → User Panel

---

## Step 10 – Middleware (Optional)

Purpose: Show a banner when impersonation is active.

Check session:

```php
session()->has('impersonated_by');
```

Register alias in `bootstrap/app.php`.

---

## Step 11 – Routes

| Route                   | Purpose              |
| ----------------------- | -------------------- |
| /dashboard              | User/Admin Dashboard |
| /admin/users            | User List            |
| /users/{id}/impersonate | Start Impersonation  |
| /leave-impersonate      | Stop Impersonation   |

Protected by middleware:

* auth
* verified
* check.impersonation

---

## Step 12 – Views (UI)

### Layout

* Common Header
* Success/Error Alerts

### Navigation

* Dashboard Link
* Manage Users (Admin Only)
* Yellow Banner When Impersonating

### Dashboards

**User Dashboard**

* Basic User Info

**Admin Dashboard**

* Instructions and Controls

### Admin Users Page

Table Columns:

* ID
* Name
* Email
* Role
* Impersonate Button

---

## Step 13 – Install Breeze Authentication

```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
php artisan migrate
npm install
npm run build
```

Provides:

* Login
* Register
* Forgot Password
* Profile Management

---

## Step 14 – Testing

```bash
php artisan serve
```

<img width="1574" height="872" alt="image" src="https://github.com/user-attachments/assets/c616269e-f51b-4530-8706-1a9b7b36c7f1" />

<img width="970" height="226" alt="image" src="https://github.com/user-attachments/assets/9790f7a8-e34f-40bc-b210-22425a8859e2" />

<img width="1591" height="560" alt="image" src="https://github.com/user-attachments/assets/329933a6-b82c-4cfa-a848-565d94f1eb5b" />


### Login Credentials Example

**Admin**

```
admin@example.com
password
```

**User**

```
john@example.com
password
```

Flow:

* Login as Admin
* Go to Manage Users
* Click Impersonate
* Yellow Banner Appears
* Click Leave → Return to Admin

---

## How Impersonation Works Internally

1. Admin clicks impersonate
2. Package stores admin ID in session
3. Logs in as selected user
4. Leaving restores admin session
5. No password required

---

## Security Rules

* Allow only Admin
* Prevent Admin → Admin impersonation
* Show visual indicator
* Provide leave button

---

## Benefits

* Customer Support Debugging
* QA Testing
* Admin Monitoring
* No Password Sharing

---

## Features Implemented

* Role System (Admin/User)
* User Management
* Impersonation Start/Stop
* Separate Dashboards
* Middleware Banner
* Breeze Authentication
* Tailwind UI

---

## Optional Improvements

* Audit Log (Who impersonated whom)
* Time Limit for Impersonation
* Email/Notification Alerts
* Permission System (Spatie)

---

## Conclusion

This project demonstrates secure admin control, real‑world debugging tools, and professional Laravel package integration. It is suitable for academic projects, portfolios, and production‑level admin panels.

