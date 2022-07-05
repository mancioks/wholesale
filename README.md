# Wholesale
Project includes vat invoices, warehouse, roles, imports, image uploads...
## Installation
Clone repository

    git clone https://github.com/mancioks/wholesale.git
Switch to project folder
    
    cd wholesale
Install dependencies

    composer install
Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env
Generate a new application key

    php artisan key:generate
Run migration

    php artisan migrate
Run seeder

    php artisan db:seed
Start local dev server

    php artisan serve
