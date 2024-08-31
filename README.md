
# Post Platform

A blog platform where users can register, log in, and log out. Users can create, read, update, and delete their posts ,also add and delete their comments. The platform sends a notification email to the author when a user comments on their post.



## Features

- User Authentication
- Post Management
- Comment Management
- Email Notification


## Requirements
- PHP 8.x
- Laravel 10.x
- MySQL
- Composer
- Node.js & NPM
- Mailtrap Account

## Installation

1- Clone the Repository

```bash
    git clone https://github.com/gnhihab/posts-platform.git
```
2- Install Dependencies

```bash
    composer install
    npm install
    npm run dev
```
3- Environment

```bash
    cp .env.example .env
```
update the .env file with your database credentials:

```bash
    DB_DATABASE=your_database
    DB_USERNAME=your_username
    DB_PASSWORD=your_password
```
Set up Mailtrap for email notifications:

```bash
    MAIL_MAILER=smtp
    MAIL_HOST=smtp.mailtrap.io
    MAIL_PORT=2525
    MAIL_USERNAME=your_mailtrap_username
    MAIL_PASSWORD=your_mailtrap_password
    MAIL_ENCRYPTION=null
```
4- Generation Key

```bash
    php artisan key:generate
```
5- Run Migration

```bash
    php artisan migrate
```

## Runnig Project

```bash
    php artisan serve
```
Visit http://localhost:8000 in your web browser or use it in postman to test your RESTful APIs.

## Swagger Installiton

Install Swagger Package

```bash
  composer require darkaonline/l5-swagger tymon/jwt-auth
```

Configure JWT Authentication

```bash
  php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
```
```bash
    php artisan jwt:secret
```

Configure Swagger

```bash
  php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider"
```
Generate Swagger Documentation

```bash
    php artisan l5-swagger:generate
```
View
[Documentation](http://localhost:8000/api/documentation)
