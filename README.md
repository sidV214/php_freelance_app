# PHP Freelance App

Mini freelancing-style CRUD web application built with core PHP, MySQL, and Bootstrap.

## Setup

1. Place the project inside `C:\xampp\htdocs\php_freelance_app`
2. Start Apache and MySQL from XAMPP
3. Copy `.env.example` to `.env`
4. Set your database values in `.env`
5. Create/import the database using [database.sql](c:\xampp\htdocs\php_freelance_app\database.sql)
6. The app reads DB settings from environment variables in [db.php](c:\xampp\htdocs\php_freelance_app\config\db.php)
7. Open `http://localhost/php_freelance_app/`

Example local `.env`:

```env
APP_ENV=local
APP_BASE_URL=/php_freelance_app
DB_HOST=localhost
DB_NAME=freelancer_app
DB_USER=root
DB_PASS=
```

## Main URLs

- `/` redirects to login or home
- `/auth/register.php`
- `/auth/login.php`
- `/pages/home.php`
- `/pages/view_works.php`
- `/pages/post_requirement.php`
- `/pages/profile.php`

## Features

- User registration and login
- Secure password hashing
- Session-based authentication
- Create, read, update, and delete works
- Search and pagination
- Profile page with password change
- Profile image upload

## Deployment Notes

Recommended option: Railway

1. Push this repository to GitHub
2. Create a new Railway project from the GitHub repo
3. Add a MySQL service in Railway
4. Set these app environment variables in Railway:

```env
APP_ENV=production
APP_BASE_URL=
DB_HOST=${{MySQL.MYSQLHOST}}
DB_NAME=${{MySQL.MYSQLDATABASE}}
DB_USER=${{MySQL.MYSQLUSER}}
DB_PASS=${{MySQL.MYSQLPASSWORD}}
```

5. Import [database.sql](c:\xampp\htdocs\php_freelance_app\database.sql) into the Railway MySQL database
6. Deploy the web service

This repo includes a [Dockerfile](c:\xampp\htdocs\php_freelance_app\Dockerfile) and [health.php](c:\xampp\htdocs\php_freelance_app\health.php) to make container deployment straightforward.
