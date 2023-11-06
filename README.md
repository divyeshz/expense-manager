<a href="https://github.com/divyeshz/expense-manager"> <h1 align="center">Expense Manager</h1></a>

## About

Expense Manager is a web application built using Laravel that allows users to manage their expenses and income. This README provides information on how to set up and run the project, configure the database, and set up email functionality.

> **Note**
> Work in Progress

## Requirements

Package | Version
--- | ---
[Composer](https://getcomposer.org/)  | V2.6.3+
[Php](https://www.php.net/)  | V8.0.17
[Laravel](https://laravel.com/)  | V10.28.0

## Getting Started

To get the Expense Manager project up and running, follow these steps:

### Prerequisites

Before you begin, make sure you have the following software installed:

- PHP
- Composer
- MySQL database

### Installation

> **Warning**
> Make sure to follow the requirements first.

1. Clone the repository to your local machine:

   ```bash
   git clone https://github.com/divyeshz/expense-manager.git
   ```

2. Change the working directory:

   ```bash
   cd expense-manager
   ```

3. Install PHP dependencies using Composer:

   ```bash
   composer install
   ```

4. Create a copy of the `.env.example` file and rename it to `.env`. Update the file with your database configuration and mail settings.

5. Generate an application key:

   ```bash
   php artisan key:generate
   ```

6. Migrate the database:

   ```bash
   php artisan migrate
   ```

7. Database Seeding:

   ```bash
   php artisan db:seed
   ```

8. Start the development server:

   ```bash
   php artisan serve
   ```

The Expense Manager application should now be accessible at [http://localhost:8000](http://localhost:8000).

## Database Setup

You will need to configure your database connection in the `.env` file. Here's an example configuration:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=expense_manager
DB_USERNAME=root
DB_PASSWORD=yourpassword
```

Make sure you create the `expense_manager` database in your MySQL server before running migrations.

## Email Configuration

For sending welcome emails to users, you need to configure your email settings in the `.env` file. Here's an example configuration using Mailtrap as the SMTP provider:

```dotenv
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=youremail@gmail.com
MAIL_PASSWORD=yourpassword
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=youremail@gmail.com
MAIL_FROM_NAME=Expense Manager
```

## Features

- User login and registration with email confirmation.
- User profile and password management.
- Dashboard with quick links to accounts and categories.
- Account management with CRUD operations.
- Transaction history with income, expenses, and transfers.
- Category management for transactions.

## Contributing

- Pull requests are welcome.

---
