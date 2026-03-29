# Project Setup Guide

This project is a simpe job portal api system based on laravel with authentication, API endpoints
Below is the documentation of the completed tasks, available commands, routes, and setup instructions.

## Features Implemented

### Core Functionalities

-   Register, and login for company and applicant
-   View profile for company and applicant
-   CRUD job vacancy for company
-   Apply job for applicant

### Dummy Account
```bash
- company
    email: cp1@mail.com
    password: password
- applicant
    email: ap1@mail.com
    password: password
```

> ⚠️ **Important:**  
> You **must** run `php artisan migrate --seed` before using the system.  
> This command creates the required tables and inserts the dummy data.


### Route List API

| Method   | URI                                    | Auth | Auth Type |
| :------- | :--------------------------------------|------|-----------|
| **POST** | `api/applicant/login`                  |  No  |           |
| **POST** | `api/applicant/logout`                 |  Yes | Applicant |
| **GET**  | `api/applicant/profile`                |  Yes | Applicant |
| **POST** | `api/applicant/profile`                |  Yes | Applicant |
| **POST** | `api/applicant/register`               |  No  |           |
| **POST** | `api/company/login`                    |  No  |           |
| **POST** | `api/company/logout`                   |  Yes | Company   |
| **GET**  | `api/company/profile`                  |  Yes | Company   |
| **POST** | `api/company/profile`                  |  Yes | Company   |
| **POST** | `api/company/register`                 |  No  |           |
| **GET**  | `api/vacancies`                        | All  | All       |
| **POST** | `api/vacancies`                        |  Yes |           |
| **POST** | `api/vacancies/apply`                  |  Yes | Applicant |
| **POST** | `api/vacancies/{vacancy}`              |  Yes | Company   |
| **POST** | `api/vacancies/{vacancy}/inactivate`   |  Yes | Company   |
| **POST** | `api/vacancies/{vacancy}/publish`      |  Yes | Company   |
| **GET**  | `api/vacancies/{vacancy}/applied`      |  Yes | Company   |


## Requirements

Before starting, make sure you have the following installed:

-   **PHP** >= 8.3
-   **Composer** >= 2.0
-   **MySQL** or **PostgreSQL** or **Sqlite**


> ⚠️ **Important:**  
> You **must** already installed all requirement for this project to run

## 1. Clone the Repository

```bash
git clone https://github.com/galeant/car-rental.git
cd car-rental
```

## 2. Install Dependencies

```bash
composer install
```

## 3. Setup Environment

```bash
cp .env.example .env
```

Then open .env and update the necessary values:

```bash
APP_NAME="Project Name"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=sqlite
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=root
DB_PASSWORD=
```

## 4. Generate Application Key

```bash
php artisan key:generate
```

## 5. Run Database Migrations and Seeders

```bash
php artisan migrate --seed
```

> ⚠️ **Important:**  
> You **must** run `php artisan migrate --seed` before using the system.  
> This command creates the required tables and inserts the dummy data.

## 6. Run Database Migrations and Seeders

```bash
php artisan serve
```

Access it at: http://localhost:8000

## 7. Run Tests

```bash
php artisan test
```

Or directly run PHPUnit:

```bash
vendor/bin/phpunit
```
