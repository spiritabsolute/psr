To run an application, use composer script:
 ```
 composer install
 composer serve
 ```
This will launch the php built-in web server.

If you are using docker, run the command:
```
docker-compose -p psr-framework up --build
```
To use xdebug, set the settings in the file: .docker/php/conf.d/xdebug.ini

The application will be available at http://127.0.0.1:8000