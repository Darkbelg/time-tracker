# Installation

# required

- Docker

## install

1. clone the repo
2. do composer install
```
docker run --rm \
-u "$(id -u):$(id -g)" \
-v "$(pwd):/var/www/html" \
-w /var/www/html \
laravelsail/php83-composer:latest \
composer install --ignore-platform-reqs
```
3. `cp .env.example .env`
4. `sail up -d`
5. `sail bash` to enter the container
6. `php artisan migrate --seed`
7. go to localhost and then login with the users in the seed.

Issues:
```
[ERROR] [MY-010259] [Server] Another process with pid 62 is using unix socket file.
[ERROR] [MY-010268] [Server] Unable to setup unix socket lock file.
```
Remove the mysql lock file from the mysql docker volume
https://stackoverflow.com/questions/36103721/docker-db-container-running-another-process-with-pid-id-is-using-unix-socket

Issue with frankenphp
Restart the containers or reload octane