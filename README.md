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

## Screenshots

![login](https://github.com/divyeshz/expense-manager/assets/146099072/5b100231-ea67-437f-8469-e96e0e9a843a)
![register](https://github.com/divyeshz/expense-manager/assets/146099072/61663858-e8d4-4f09-a43e-27316f54881a)
![User Profile](https://github.com/divyeshz/expense-manager/assets/146099072/ffee5400-4532-4383-b13c-6e86c2fa5701)
![Dashboard](https://github.com/divyeshz/expense-manager/assets/146099072/d17f78d1-de38-4a95-8ed1-bb9dcacaefc1)
![Account List](https://github.com/divyeshz/expense-manager/assets/146099072/556e59c9-64d6-4678-bb14-fd734e4e3b33)
![Add Account](https://github.com/divyeshz/expense-manager/assets/146099072/1030ecc0-944d-44e3-95d6-a067870c5cb2)
![Edit Account](https://github.com/divyeshz/expense-manager/assets/146099072/84747532-d0b5-4cce-a0a8-61e4098c210e)
![Account Delete](https://github.com/divyeshz/expense-manager/assets/146099072/26ce5711-4454-4321-920c-2c488f7686d4)
![Add Balance](https://github.com/divyeshz/expense-manager/assets/146099072/9dae3f48-d371-4ffb-be6b-1f7324a26cc2)
![Category List](https://github.com/divyeshz/expense-manager/assets/146099072/8d595eea-0940-4e68-abe2-5690cf3282af)
![Add Category](https://github.com/divyeshz/expense-manager/assets/146099072/c94e82df-5fd6-4e72-aadd-b5aff360fdd0)
![Edit Category](https://github.com/divyeshz/expense-manager/assets/146099072/a69a0ff4-b264-4716-9084-87bb3a2a1830)
![Transaction List](https://github.com/divyeshz/expense-manager/assets/146099072/196abfaa-60e7-4a0e-b422-b4e47eb7fe33)
![Add Transaction](https://github.com/divyeshz/expense-manager/assets/146099072/d3b53efc-405e-46ca-b989-50475494f160)
![Edit Transaction](https://github.com/divyeshz/expense-manager/assets/146099072/e4c6f6d4-bf3b-4ee1-9055-35f8413176ad)
![Change Password](https://github.com/divyeshz/expense-manager/assets/146099072/868d1d1f-c368-4c6c-893b-eb24a7da651a)
![forgot password](https://github.com/divyeshz/expense-manager/assets/146099072/fe0f019b-f5fa-4ef8-880c-71b09207218c)
![Logout](https://github.com/divyeshz/expense-manager/assets/146099072/cdc0e8d3-3c79-4f96-85c7-231cbbbb069e)

## Contributing

- Pull requests are welcome.

---
