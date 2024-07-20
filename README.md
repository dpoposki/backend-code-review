# Backend Code-Challenge

This is a dummy project, which is used to demonstrate knowledge of symfony and backend development in general.
It serves as an example with some bad practices included.

## Install

The proposed setup with Nix did not work for me for some reason, so I have decided to use the Symfony Docker setup.

1. Run `docker compose build --no-cache` to build fresh images
2. Run `docker compose up --pull always -d --wait` to set up and start the project
3. Open `https://localhost`
4. Run the following commands
   - `bin/console doctrine:database:create`
   - `bin/console doctrine:schema:update --force`
   - `bin/console doctrine:fixtures:load --no-interaction`
   - `APP_ENV=test bin/console doctrine:database:create`
   - `APP_ENV=test bin/console doctrine:schema:update --force`
