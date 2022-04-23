# Documentation

## Requirements

- PHP minimum version 8.0.2
- MySQL (or any other laravel supported db server)
- SMTP (Something like Mailtrap should work)
- Nodejs and NPM

## Setup

- Clone github repository `git clone https://github.com/arifhp86/hiring-platform.git`
- Install composer dependency `composer install`
- Install npm dependency `npm install`
- Create .env file with `cp .env.example .env`
- Generate application key `php artisan key:generate`
- Update .env file with database and SMTP credentials
- Migrate and seed database `php artisan migrate --seed`
- Run webpack to bundle vuejs code `npm run dev` or `npm run watch`
- Run local php server `php artisan serve`
- To enable grumphp `php ./vendor/bin/grumphp git:init`
