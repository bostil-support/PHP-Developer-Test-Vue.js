# Test

Git - https://github.com/bostil-support/PHP-Developer-Test-Vue.js \
Url - https://bostil-test.cf/

## Installation

- ```shell
  mkdir test-project && cd test-project
  ```
- ```shell
  git clone git@github.com:stealthpro/bostil-test.git .
  ```
- ```shell
  cp .env.docker .env
  ```
- ```shell
  docker run --rm \
    -v $(pwd):/opt \
    -w /opt \
    laravelsail/php80-composer:latest \
    composer install
  ```
- ```shell
  ./vendor/bin/sail up
  ```
- ```shell
  ./vendor/bin/sail artisan migrate
  ```
- ```shell
  ./vendor/bin/sail npm i
  ```
- ```shell
  ./vendor/bin/sail npm run prod
  ```

## Seeding data

Run `./vendor/bin/sail artisan db:seed` for seeding test data
or run `./vendor/bin/sail artisan import-data` for importing data from CSV file.


## Run application

Open  url [http://127.0.0.1](http://127.0.0.1) in your browser
