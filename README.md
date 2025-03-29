<p align="center"><a href="" target="_blank">Laravel Module Framework</a></p>

# Laravel Module Framework

This repository provides a modular framework for building scalable Laravel applications. It is designed to simplify the development process by offering a clean structure, pre-configured tools, and support for modular development.

## Features

- Modular architecture for better scalability and maintainability.
- Pre-configured Laravel Mix for asset compilation.
- Tailwind CSS integration for modern UI design.
- Docker support for containerized development.
- Comprehensive testing setup with PHPUnit.
- Environment-based configuration management.

## Directory Structure

- **app/**: Core application logic, including controllers, models, and services.
- **bootstrap/**: Handles application bootstrapping.
- **config/**: Configuration files for various services and components.
- **database/**: Migrations, seeders, and factories for database management.
- **public/**: Publicly accessible files, including the entry point (`index.php`).
- **resources/**: Views, language files, and frontend assets.
- **routes/**: Application route definitions.
- **storage/**: Logs, cache, and other generated files.
- **tests/**: Unit and feature tests.

## Requirements

- PHP >= 8.0
- Node.js 16+
- Mysql 8.0

## Installation

1. Clone the repository:
   - git clone https://github.com/mehedihasanahad/laravel-module-frame.git
   - cd laravel-module-framework

2. Install PHP dependencies
   - composer install

3. Install Node.js dependencies
   - npm install

4. Set up the environment file
   - cp .env.example .env

5. Generate the application key
   - php artisan key:generate

6. Start the development server
   - php artisan serve