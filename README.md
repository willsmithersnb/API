# NB Media Configurator App

## About NB Media Configurator


## Installation

Please check the official laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/7.x/installation#installation)

Clone the repository

    git clone git@bitbucket.org:atlas-labs/39-nb-laravel-media-customizer.git

Switch to the repo folder

    cd 39-nb-laravel-media-customizer

Install all the dependencies using composer

    composer install

Install Libra Office

##### Linux

    sudo apt install libreoffice

##### Mac

    brew install libreoffice

Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env

Generate a new application key

    php artisan key:generate

Run the database migrations (**Set the database connection in .env before migrating**)
(**This is not production friendly and will delete your existing data if you run it**)

    php artisan migrate --seed

Install NVM

    wget -qO- https://raw.githubusercontent.com/nvm-sh/nvm/v0.35.3/install.sh | bash

after installing NVM

    nvm use

Install node packages using Yarn

    yarn

If you use windows please use the below command

    npm install --no-bin-links

Before running the application please use yarn to build the files using

    yarn watch

or

    npm run watch

Now to serve the Laravel backend run

    php artisan serve
