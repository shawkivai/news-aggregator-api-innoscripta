# Project Title

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

5. **Access the application** at `http://localhost:<port>` (replace `<port>` with the port specified in your `docker-compose.yml`).

6. **Stop the services** when you're done:
   ```bash
   docker compose down
   ```
7. **Go inside container**

```bash
docker exec -it <container_id> bash
```

8. Run the following commands to install dependencies, run tests, migrations and seeders

## Install dependencies

```bash
composer install
```

## Run tests

```bash
php artisan test
```

## Run migrations

```bash
php artisan migrate
```

## Run seeders

```bash
php artisan db:seed
```

## Test Console Command

1. Run `php artisan queue:work` to run the default queue
2. Run `php artisan download:articles` to fetch the articles from the API and store in the database