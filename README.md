# News-Aggregator API

## Setup with Docker

To set up this project using Docker, follow these steps:

1. **Ensure Docker is installed** on your machine. You can download it from [Docker's official website](https://www.docker.com/get-started).

2. **Clone the repository** to your local machine:
   ```bash
   git clone <repository-url>
   cd <repository-directory>
   ```

3. **Build the Docker images** using the provided Dockerfile:
   ```bash
   docker compose build
   ```

4. **Start the services** defined in the `docker-compose.yml` file:
   ```bash
   docker compose up
   ```

5. **Access the application** at `http://localhost:<port>` (replace `<port>` with the port specified in your `docker-compose.yml` file and create an environment variable `HTTP_PUBLISH_PORT` in your `.env` file).

6. **Stop the services** when you're done:
   ```bash
   docker compose down
   ```
7. **Go inside container**

```bash
docker exec -it <container_id> bash
```

8. Run the following commands to install dependencies, run tests, migrations and seeders

### Install dependencies

```bash
composer install
composer dump-autoload
```

### Run migrations

```bash
php artisan migrate
```

### Run seeders

```bash
php artisan db:seed
```

### Run tests

```bash
php artisan test
```

### Run PHPStan

```bash
./vendor/bin/phpstan analyse
```
### Generate Swagger Documentation

```bash
php artisan l5-swagger:generate
```

## Test the console commands

To run the console commands, you need to go inside the container and run the following commands:

1. Run `php artisan queue:work` to run the default queue
2. Run `php artisan download:articles` to fetch the articles from the 3rd party API and store in the database

## Scheduled Commands

The scheduled command is defined in the `routes/console.php` file.

To run the scheduled commands, you need to go inside the container and run the following commands:

```bash
php artisan queue:work
php artisan schedule:work
```
I have configured the scheduled commands to run everyday at 1:00 AM to download the articles from the 3rd party API and store in the database.

## Swagger Documentation

You can access the swagger documentation at `http://<host>/api/documentation`.

## Implemented Features

- API Documentation with Swagger
- Scheduler to download the articles from the 3rd party API
- Queue to process and store the articles
- API Rate Limiter
- Caching using Redis
- Feature and Unit Testing
- Authentication with Sanctum
- Error Handling
- Laravel Pint to indent the code
- PHPStan to check the code quality
- Dockerizing the application
- 3rd Party News API Sources implemented - NewsAPI, The New York Times, The Guardian
