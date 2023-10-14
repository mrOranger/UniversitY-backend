<h1 align=center>UniversitY (backend)</h1>

Using Laravel 10.0, I've impkemented a backend system to manage an hypothethical university. The project is not finished yet, on the other hand, I will add new features in the future. If you are interesed in learning Laravel, I hope that this simple and small project will help you. 

## Techologies
Implementing this system requires different technologies and techniques to be used. First of all there is __Laravel__ in its 10.0 version, probabily the most popular backend framework offering a simple and small solution for implementing REST API's services. 

Then I used __MySQL__ in its latest version as DBMS, and deployed locally using __Docker__ (I attached also the __.Dockerfile__ in the project's root).

Each feature has been tested by using __PhpUnit__, the test's framework provided in Laravel. However, in order to use a CI/CD approach, I also used the __GitHub Actions__ for integration tests, and the file is in the _.github_ folder.

## Install
To install correctly the software, make sure to have __Php 8.2__ installed in your local machine, and then rename the _.env.example_ file simply in _.env__. Install MySQL using the Dockerfile and then execute the following commands:
```
    php -r "file_exists('.env') || copy('.env.example', '.env');"
```
to copy the _.env.example_ file in the _.env_
```
composer install -q --no-ansi --no-interaction --no-scripts --no-progress --prefer-dist
```
will install the dependencies required
```
php artisan key:generate
```
to generate the keys for authentication
```
    php artisan migrate --seed
```
will run all the migrations and the seeders. Then, if everything works well, you are ready to start the service by using:
```
    php artisan serve
```