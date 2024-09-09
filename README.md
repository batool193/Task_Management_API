Task Management API
Description
This project is a ** Task Management System ** built with Laravel 11 that provides a RESTful API for managing Tasks . The system Has JWT Authentication for ( Register , login , logout ) and Spatie for Roles as we have 3 Roles (admin , manager, user) .

Key Features:
CRUD Operations: Create, read, update, and delete Tasks .
CRUD Operations: Create, read, update, and delete Users.
soft delete for users and tasks 
Filtering : Filter tasks by priority, status of the task .
Repository Design Pattern: Implements repositories and services for clean separation of concerns.
Form Requests: Validation is handled by custom form request classes.
API Response Service: Unified responses for API endpoints.
Seeders: Populate the database with initial data for testing and development.
Technologies Used:
Laravel 11
PHP
MySQL
XAMPP (for local development environment)
Composer (PHP dependency manager)
Postman Collection: Contains all API requests for easy testing and interaction with the API.
Installation
Prerequisites
Ensure you have the following installed on your machine:

XAMPP: For running MySQL and Apache servers locally.
Composer: For PHP dependency management.
PHP: Required for running Laravel.
MySQL: Database for the project
Postman: Required for testing the requestes.

Steps to Run the Project
Clone the Repository
git clone https://github.com/batool193/Task_Management_API.git

Navigate to the Project Directory
cd Task_Management_System_API

Install Dependencies
composer install

Create Environment File
cp .env.example .env
Update the .env file with your database configuration (MySQL credentials, database name, etc.).

Generate Application Key
php artisan key:generate

Run Migrations
php artisan migrate

Run this command to generate JWT Secret
php artisan jwt:secret

Seed the Database
 php artisan migrate:fresh --seed --seeder=RolesAndPermissionsSeede
 
 Run the Application
php artisan serve
Interact with the API and test the various endpoints via Postman collection Get the collection from here: https://documenter.getpostman.com/view/27922320/2sAXjSy8p8