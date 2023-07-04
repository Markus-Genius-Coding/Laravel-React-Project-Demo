## About

This is a dockerized Laravelapplication to demonstrate Laravel with Passport, Rest-APIs and a React-Frontend. 
After you checked out the project via git simple run 
```
cd /your/project/path
```
```
cp .env.example .env 
```
```
docker network create laravel_playground_network
```
```
docker-compose up -d laravel_playground_dev
```
on your commandline. 
Note that all necessary commands for laravel are executed via the docker entrypoint.
Just wait a few seconds, npm need some time. 

You should now be able to access the application in your browser.
Just visit [http://localhost:3100](http://localhost:3100)

To login just use these credentials: 
User: testuser@mw-systems.com
Password: auOeo8237#ä+ß?

You can access the databases via PhpMyAdmin. On port 3101 is the database used for development. 
Just visit [http://localhost:3101](http://localhost:3101)

You can access the databases via PhpMyAdmin. On port 3103 is the database used for unittests.
Just visit [http://localhost:3103](http://localhost:3103)

To login into the databases use these credentials:
User: root
Password: äshnn3LO?1oOß

If you like to run the tests run following commands: 
```
docker-compose exec laravel_playground_dev bash 
```
```
php artisan config:cache --env=testing
```
```
php artisan migrate:fresh --seed &&  y | php artisan passport:install --uuids && vendor/bin/phpunit
```
