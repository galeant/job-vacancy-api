# Project Setup Guide

This project is a simpe job portal api system based on laravel with authentication, API endpoints
Below is the documentation of the completed tasks, available commands, routes, and setup instructions.

## Features Implemented

### Core Functionalities

-   Register, and login for company and applicant
-   View profile for company and applicant
-   CRUD job vacancy for company
-   Apply job for applicant
-   View list applicant on specified job vacancy company
-   View list job vacancy has already applied applicant

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

| Method   | URI                                     | Auth | Auth Type | Description                                               |
| :------- | :-------------------------------------- | :--- | :-------- | :-------------------------------------------------------- |
| **POST** | `api/applicant/login`                   | No   |           | Applicant login to obtain an access token.                |
| **POST** | `api/applicant/logout`                  | Yes  | Applicant | Revoke access token and end the applicant session.         |
| **GET** | `api/applicant/profile`                 | Yes  | Applicant | Retrieve detailed profile data of the active applicant.    |
| **POST** | `api/applicant/profile`                 | Yes  | Applicant | Update profile information (including CV/Photo upload).    |
| **POST** | `api/applicant/register`                | No   |           | Register a new account for a job applicant.               |
| **POST** | `api/company/login`                     | No   |           | Company login to obtain an access token.                  |
| **POST** | `api/company/logout`                    | Yes  | Company   | Revoke access token and end the company session.           |
| **GET** | `api/company/profile`                   | Yes  | Company   | Retrieve information of the currently active company.      |
| **POST** | `api/company/profile`                   | Yes  | Company   | Update company profile details.  |
| **POST** | `api/company/register`                  | No   |           | Register a new account for a company entity.              |
| **GET** | `api/vacancies`                         | All  | All       | View all job vacancies (publicly accessible).             |
| **POST** | `api/vacancies`                         | Yes  | Company   | Create a draft or post a new job vacancy.                 |
| **POST** | `api/vacancies/apply`                   | Yes  | Applicant | Submit a job application for a specific vacancy.           |
| **POST** | `api/vacancies/{vacancy}`               | Yes  | Company   | Update data or content of an existing job vacancy.        |
| **POST** | `api/vacancies/{vacancy}/inactivate`    | Yes  | Company   | Set vacancy status to inactive (close the job post).       |
| **POST** | `api/vacancies/{vacancy}/publish`       | Yes  | Company   | Set vacancy status to publish (make it visible to public). |
| **GET** | `api/vacancies/{vacancy}/applied`       | Yes  | Company   | View the list of applicants for a specific vacancy.       |
| **GET** | `api/vacancies/job-apply`               | Yes  | Applicant | View the list of jobs the applicant has applied for.      |

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
