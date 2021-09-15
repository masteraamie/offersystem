# Laravel Offer System App

# Getting started

## Installation

Please check the official laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/5.4/installation#installation)


Clone the repository

    git clone git@github.com:masteraamie/offersystem.git

Switch to the repo folder

    cd offersystem

Install all the dependencies using composer and npm

    composer install

    npm install

    npm run dev

Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env

Generate a new application key

    php artisan key:generate

Run the database migrations (**Set the database connection in .env before migrating**)

    php artisan migrate

    php artisan db:seed --class UserSeeder

Start the local development server

    php artisan serve

You can now access the server at http://localhost:8000
