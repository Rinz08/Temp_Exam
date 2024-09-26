# Temp_Exam
# Admin Dashboard Project

## Requirements
- PHP v8.1.25
- Node.js v20.11.1
- Composer
- XAMPP MySQL (or any MySQL-compatible database)
- Laravel Installer v5.2.1

## Project Setup

### 1. Clone the Repository
Clone the repository from GitHub:
```bash
git clone https://github.com/your-repo-url/admin-dashboard.git

### Run the following commands in order

>cd admin-dashboard
>composer install
>php artisan key:generate
>npm install
>composer require laravel/breeze --dev
>php artisan breeze:install
>php artisan migrate
>php artisan db:seed
>php artisan db:seed --class=UsersTableSeeder (for default admin)
>php artisan serve
>npm run dev (in another terminal)
>visit http://127.0.0.1:8000

Note: The default credentials should be
admin@example.com / adminuser (Username input not yet configured properly)
password123