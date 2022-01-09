# People Integration Backend
This a fullstack project that has the objective to
integrate the GooglePeople API with a Laravel+ReactJs app

## To set up your local ambient follow this steps:

### `git clone https://github.com/silvasilas99/people-integration-backend.git`
Clone the project to your computer

### `composer install`
Inside the people-integration-backend diretory, run this command to install
all necessaries packages

### `cp .env.example .env`
Create a .env.local file using the .env.example as base
After you must to configure your backend URL

### Create the database, configure the Google API credencials and set it up inside the .env

### `php artisan key:generate`
Generate the key to the project

### `php artisan migrate`
Make the migrations in your database

### `php artisan serve`
Create a WEB server in the development mode.\
Now your API is working on [http://localhost:8000](http://localhost:8000), and your client can be used