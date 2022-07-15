# JB E-commerce Platform

## Frame Work

[Laravel Lumen](https://lumen.laravel.com/docs)

## Database Diagram

[DB Diagram](https://dbdiagram.io/d/62cf6e50cc1bc14cc5b42210)

## Installation

 1. Git clone or download the repository to local
 2. Go to the project folder
 3. <code>cp .env.example .env</code>
 4. <code>composer install</code>
 5. Create local database and input credentials into the <code>.env</code> file
 6. <code>php artisan migrate:refresh --seed</code>
 7. <code>php -S localhost:8000 -t public</code>
 8. If all goes well you should see the Lumen version in http://localhost:8000
 9. Go to http://localhost:8000/key to get the application key and put it into the <code>APP_KEY</code> in <code>.env</code>

## API Endpoints

All API endpoints are in <code>web.php</code>

## Notes

### Customized Command and Cron Job

I have created a command <code>NotificationsPrune</code> to remove out dated (7 days by default) notifications from the database. To run this command, you can use it by typing <code>php artisan notifications:prune</code> from the terminal. 

This command is also included in Laravel Scheduler. It is in the <code>app/Console/Kernel.php</code> file. The job runs daily.
