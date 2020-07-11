## Tapolog

## Installation

Clone the repository

    git clone git@github.com:mpartarrieu/taplog.git

Install composer dependencies

    composer install

Install node modules and run assets compilation

    npm ci
    npm run prod

Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env

Generate a new application key

    php artisan key:generate

Run the database migrations (**Set the database connection in .env before migrating**)

    php artisan migrate

To create a user:

    php artisan user:create

