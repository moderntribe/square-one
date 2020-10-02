# Tests for Square 1

Square one tests are fully dockerized, including a container called `chrome` which provides a selenium-chrome
standalone image to power chromedriver for acceptance tests that need to use JavaScript.

## Setup

To go from a fresh clone to running the tests, follow these steps:

(1) Start the global containers (if you don't already have them running)

```
so global:start
```

(2) Create SSH keys for this project:

```
so global:cert square1.tribe
so global:cert square1test.tribe
```

(3) Create the project databases:

```
docker exec tribe-mysql mysql -uroot -ppassword -e "CREATE DATABASE tribe_square1"
docker exec tribe-mysql mysql -uroot -ppassword -e "CREATE DATABASE tribe_square1_tests"
docker exec tribe-mysql mysql -uroot -ppassword -e "CREATE DATABASE tribe_square1_acceptance"
```

(4) Create the .env files for composer and codeception:

```
cp .env.sample .env
cp dev/tests/.env-dist dev/tests/.env
```

(5) Update .env with keys from 1Password (look in the file for instructions)

(6) Create local config files:

```
cp local-config-sample.php local-config.php
cp local-config-sample.json local-config.json
```

(7) Start the project containers:

```
so start
```

(8) Follow the prompt to add a GitHub token, then sit back while a `composer install` runs automatically

(9) Build the front-end assets:

```
nvm use
yarn install
gulp dist
```

(10) Run the tests:

```
so test -- run unit
so test -- run integration
so test -- run acceptance
so test -- run webdriver
```

### Pro-tips

Set up an alias in your `.bashrc` or `.zshrc` file as follows:
`alias integration="so test -- run integration"`. Then you can simply run
`integration` to run the integration suite. This can be anything that makes your life easy,
and you can create other commands for other test suites (such as `unit`)

### Testing tips

1. Do not run multiple test suites in one command. One suite may corrupt the global state
   preventing another from running correctly.

