# ReuseMart — Backend

**ReuseMart** is a secondhand e-commerce platform developed as a university group project by a team of three students. The platform aims to facilitate the buying and selling of pre-owned products, encouraging product reuse and more sustainable consumption.

This repository contains the backend application, which handles business logic, database operations, and API functionality to support the platform's client applications.

## Project Overview

ReuseMart provides an e-commerce platform for managing secondhand products and related transactions. The backend serves as the central component connecting the application's business logic, database, and frontend clients.

### Key Features

- **Product Management** — Manage secondhand product information and inventory.
- **Product Discovery** — Support product browsing and searching.
- **Order Management** — Handle customer orders and order-related information.
- **Transaction Management** — Support transaction processing and management.
- **Delivery Management** — Handle delivery-related information and tracking.
- **Consignment Management** — Support the management of consigned products.
- **Donation and Rewards** — Support donation-related functionality and reward mechanisms.
- **Database Management** — Store and manage application data using a relational database.
- **API Integration** — Provide backend functionality for client applications.

*Features listed above should be adjusted to match the functionality implemented in the current version of the repository.*

## Technology Stack

| Technology | Purpose |
|---|---|
| Laravel | Backend framework |
| PHP | Backend programming language |
| MySQL | Relational database |
| REST API | Communication between backend and client applications |
| React | Web frontend |
| Flutter | Mobile application |

## System Architecture

ReuseMart separates its backend from its web and mobile client applications.

```text
┌──────────────────────┐
│     React Web App    │
└──────────┬───────────┘
           │
           ▼
┌──────────────────────┐
│                      │
│   Laravel Backend    │
│                      │
│      REST API        │
│                      │
└──────────┬───────────┘
           │
           ▼
┌──────────────────────┐
│    MySQL Database    │
└──────────────────────┘
           ▲
           │
┌──────────┴───────────┐
│   Flutter Mobile App │
└──────────────────────┘
```

The Laravel backend processes requests, applies application logic, and interacts with the database. The React web application and Flutter mobile application communicate with the backend to access the functionality they require.

## Getting Started

### Prerequisites

Make sure the following tools are installed:

- PHP version compatible with the project
- Composer
- MySQL
- Node.js and npm, if required by the project's asset tooling

### Installation

**1. Clone the repository**

```bash
git clone <repository-url>
cd <repository-directory>
```

Replace the placeholders with your actual repository URL and directory name.

**2. Install dependencies**

```bash
composer install
```

**3. Configure environment variables**

Create a local environment configuration file:

```bash
cp .env.example .env
```

Configure the database connection and other required environment variables in `.env`.

Never commit your `.env` file or publish passwords, API keys, or other credentials.

**4. Generate the application key**

```bash
php artisan key:generate
```

**5. Create the database**

Create a MySQL database for ReuseMart and configure the database name and credentials in `.env`.

**6. Run database migrations**

```bash
php artisan migrate
```

If the project requires initial data, review the available seeders before running them.

**7. Start the development server**

```bash
php artisan serve
```

The application is typically available at:

`http://localhost:8000`

Refer to the project's configuration and dependency files for any additional setup requirements.

## Project Structure

The repository follows a Laravel application structure.

```text
.
├── app/             # Application logic, models, and controllers
├── bootstrap/       # Application bootstrap files
├── config/          # Application configuration
├── database/        # Migrations, factories, and seeders
├── public/          # Public assets and application entry point
├── resources/       # Views and frontend resources
├── routes/          # Route definitions
├── storage/         # Logs, cache, and generated files
├── tests/           # Automated tests
├── .env.example     # Example environment configuration
├── artisan          # Laravel command-line interface
├── composer.json    # PHP dependencies
└── README.md
```

The exact contents may vary depending on the repository's implementation.


## Learning Objectives

This project provided an opportunity to apply software development concepts in a collaborative environment, including:

- Backend application development using Laravel.
- Relational database design and management.
- API development and frontend integration.
- Implementation of e-commerce business logic.
- Collaboration and integration across multiple application components.

## Disclaimer

ReuseMart was developed as a university group project for educational purposes.

The application is intended to demonstrate software development concepts and should undergo appropriate security, validation, and performance reviews before being used in a production environment.

## License

No license has been specified for this repository. Unless a license is added, others should not assume they have permission to reuse, modify, or redistribute the code.
