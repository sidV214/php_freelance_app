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
