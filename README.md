## Instalation

Clone repository, go to it's directory and then type in the terminal:

`docker-compose up -d`

Next go into backend container to install dependencies:

`docker-compose exec backend sh`

In container backend type:

`composer.phar install`

You can exit the container.

`exit`

Change storage files permitions:

`chmod -R 777 storage/logs`
`chmod -R 777 storage/framework/`

Now create a .env file in the application root directory. You can copy .env.example but remember to add app key.

You can change default amount of dummy data created with seeds in config/test.php

`'random_rows' => 100`

Now you can make the migrations. Go to backend container:

`docker-compose exec backend sh`

In container type:

`php artisan migrate --seed`

If sql authentication method error is thrown do:

In new terminal go to database container:
    
`docker-compose exec database mysql -u root -p test`

And change password method:

`ALTER USER 'root'@'%' IDENTIFIED WITH mysql_native_password BY '1234';`

Go back to backend container and try again:

`php artisan migrate --seed`

Now wait for seeds. Thats all. You can leave the container or run tests.

If you want to shut down the container type:

`docker-compose down`

## Tests

To run tests go to backend container:

`docker-compose exec backend sh`

And type:

`php artisan test`

## React app

To run react app go to test-app directory:

`cd test-app`

And type:

`npm install`

`npm start`

## Links

Laravel app is running at [http://localhost:4040](http://localhost:4040). 

React app is running at [http://localhost:3000](http://localhost:3000). 

## Documentation

Swager api for create action can be found here [https://app.swaggerhub.com/apis-docs/MartaKicza/teb/1.0.0](https://app.swaggerhub.com/apis-docs/MartaKicza/teb/1.0.0).

## License

MIT

