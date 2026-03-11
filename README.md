Library Loan API

Technical test project built with Symfony and API Platform to manage a simple library loan system.

The API allows:

Creating users

Creating books

Creating loans

Limiting the number of loans per user

Generating a loan report

The project also includes Docker support and automated tests with PHPUnit.

Tech Stack

PHP 8.2

Symfony

API Platform

Doctrine ORM

MySQL

Docker

PHPUnit

Project Setup
1. Clone the repository
git clone <repository-url>
cd library-loan-api
Run with Docker

The project is configured to run using Docker Compose.

2. Start containers
docker-compose up -d --build

This will start:

PHP container

MySQL database

Symfony application

Install Dependencies

Enter the PHP container:

docker exec -it php_container bash

Then install dependencies:

composer install
Database Setup

Run migrations to create the database and database test schema.

php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate


php bin/console doctrine:database:create --env=test
php bin/console doctrine:migrations:migrate --env=test

This will create the tables:

user

book

loan

API Documentation

API Platform automatically provides documentation.

Open in your browser:

http://localhost/api


Main Endpoints
Create User

POST

/api/users

Example body:

{
  "name": "John Doe",
  "email": "john@example.com"
}
Create Book

POST

/api/books

Example body:

{
  "title": "Clean Code",
  "author": "Robert C. Martin"
}
Create Loan

POST

/api/loans

Example body:

{
  "loanDate": "2025-01-01",
  "client": "/api/users/1",
  "book": "/api/books/1"
}
Business Rule

A user cannot have more than 3 active loans.

If a fourth loan is attempted, the API returns:

HTTP 400 Bad Request

Example response:

{
  "detail": "User cannot have more than 3 loans"
}
Loan Report Endpoint

A custom endpoint was implemented to generate a loan report.

GET

/loans/report

This endpoint returns aggregated loan data.

Running Tests

The project includes automated tests using PHPUnit.

Run tests inside the container:

php bin/phpunit

Example output:

OK (3 tests, 7 assertions)

Tests included:

Loan creation

Loan limit validation

Loan report endpoint

Design Pattern Used
Service Pattern

The project uses the Service Pattern to encapsulate business logic.

Example:

LoanService

This service is responsible for enforcing business rules related to loans, such as:

Limiting the number of loans per user

Example method:

checkLoanLimit(User $user)

Using a service keeps business logic separated from controllers and API logic, improving maintainability and testability.

Processor Pattern (API Platform)

The project also uses the Processor pattern provided by API Platform.

Example:

LoanProcessor

The processor intercepts the creation of a Loan resource and delegates business validation to the LoanService.

Responsibilities:

LoanProcessor:

Handles API request lifecycle

Delegates business rules

LoanService:

Contains business logic

This follows the Single Responsibility Principle and keeps the code clean and modular.

Project Structure
api/
 ├─ config/
 ├─ src/
 │   ├─ Controller/
 │   │    └─ LoanReportController.php
 │   ├─ Entity/
 │   │    ├─ User.php
 │   │    ├─ Book.php
 │   │    └─ Loan.php
 │   ├─ Repository/
 │   │    └─ LoanRepository.php
 │   ├─ Service/
 │   │    └─ LoanService.php
 │   └─ State/
 │        └─ LoanProcessor.php
 ├─ migrations/
 ├─ tests/
 └─ public/
docker-compose.yml
docker/
README.md