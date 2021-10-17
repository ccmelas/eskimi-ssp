## Eskimi SSP Task

Submission for the Eskimi SSP Task


### Setup
- Using your favorite terminal, clone this project into your local machine using `git clone https://github.com/ccmelas/eskimi-ssp.git`
- Make sure you have the latest version of docker installed
- Change Directory (CD) into the cloned project and update the laradock submodule using `git submodule init && git submodule update`
- In the laradock folder, duplicate the .env.example file and rename the duplicate to .env
- Run `docker compose up -d nginx mysql` to start up the required containers. If your laptop uses the Apple Silicon chip, run `docker compose up -d nginx mariadb` instead.
- Log into the mysql/mariadb container using `docker-compose exec mysql bash` or `docker-compose exec mariadb bash`
- Log into mysql using `mysql -u root -p`. Enter the mysql/mariadb password from the .env file in the laradock folder.
- Create a database for the project using `create database eskimi_ssp`
- Exit the container
- In the project root folder, duplicate the .env.example folder and rename to .env.
- Update the mysql credentials 
```
DB_HOST=mariadb (or mysql, depending on what you installed)
DB_PORT=3306
DB_DATABASE=eskimi_ssp
DB_USERNAME=root
DB_PASSWORD=password set in laradock .env
``` 
- Again in the laradock folder, log into the workspace container using `docker-compose exec workspace bash`
- In the workspace container, run the following to install and compile dependencies 
```
composer install
php artisan key:generate
php artisan migrate
php artisan db:seed (to seed database)
npm install
npm run prod 

* if you get an EAccess error running npm run prod, set the npm user to the current user using
npm config set user 0
```
- Visit http://localhost to access application

### Testing
- To run existing tests, use `php artisan test`
