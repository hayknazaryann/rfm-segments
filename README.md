## Installation

Please check the official laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/5.4/installation#installation)


### Clone the repository

    git clone https://github.com/hayknazaryann/rfm-segments.git

### Switch to the repo folder

    cd rfm-segments



### Install all the dependencies using composer

    composer install

### Install Node Modules

    npm install

### Copy the example env file and make the required configuration changes in the .env file
**And set the username and password in env for Vivarolls API**

    cp .env.example .env

### Generate a new application key

    php artisan key:generate

### Start the local development server

    php artisan serve

### Start Vite

    npm run dev


You can now access the server at http://localhost:8000


