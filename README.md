## Supreme Engine

## Setup Instructions

**Requirements:**

> - PHP >= 8.1
> - Composer >= 2.4.3
> - MySQL >= 8.0

**Step 1:** Clone the repository in your terminal using `https://github.com/victorive/supreme-engine.git`

**Step 2:** Navigate to the project’s directory using `cd supreme-engine`

**Step 3:** Run `composer install` to install the project’s dependencies.

**Step 4:** Run `cp .env.example .env` to create the .env file for the project’s configuration
and `cp .env.example .env.testing` to create the .env file for the testing environment.

**Step 5:** Run `php artisan key:generate` to set the application key.

**Step 6:** Create a database with the name **supreme-engine** or any name of your choice in your current database
server and configure the DB_DATABASE, DB_USERNAME and DB_PASSWORD credentials respectively, in the .env files located in
the project’s root folder. eg.

> DB_DATABASE={{your database name}}
>
> DB_USERNAME= {{your database username}}
>
> DB_PASSWORD= {{your database password}}

Also, you can set your `CACHE_DRIVER` to `database` or `file` depending on your preference.

**Step 7:** Run `php artisan migrate --seed` to create your database tables. This command also seeds your database with default users.

**Step 8:** Run `php artisan update:user-attributes` to update the existing user attributes.

**Step 9:** Setup `php artisan schedule:work` to run the commands to sync the user attributes every minute provided that we're still within the api threshold and also reset the api request counter hourly.

Happy coding!
