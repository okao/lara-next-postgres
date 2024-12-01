# ${PROJECT_NAME} - Laravel & Next.js Application

A modern web application using Laravel (Backend), Next.js (Frontend), PostgreSQL, Redis, and more, all dockerized for easy development.

## Prerequisites

- Docker
- Docker Compose
- Git

## Quick Start

1. **Clone and enter the project**
   ```bash
   git clone <repository-url>
   cd <project-directory>
   ```

2. **Set up environment**
   ```bash
   cp .env.example .env
   ```
   Configure your `.env` file with appropriate values

3. **Pull required Docker images**
   ```bash
   # Pull all required images
   docker pull php:8.2-fpm
   docker pull node:18-alpine
   docker pull nginx:alpine
   docker pull postgres:15-alpine
   docker pull dpage/pgadmin4
   docker pull redis:alpine
   docker pull rediscommander/redis-commander:latest
   ```

4. **Create project structure**
  # Create necessary directories
   ```bash mkdir -p backend frontend docker/nginx docker/php```

   # Create Laravel project
   ```bash docker run --rm -v $(pwd):/app composer create-project laravel/laravel backend```

   # Create Next.js project
   ```bash npx create-next-app frontend --typescript```

5. **Start the containers**
  # Build and start all services
   ```bash docker compose up -d --build```

   # Check if all containers are running
   ```bash docker compose ps```

6. **Set up Laravel**
   ```bash
   # Generate application key
   ```bash docker compose exec backend php artisan key:generate```

   # Run migrations
   ```bash docker compose exec backend php artisan migrate```

   # Set proper permissions
   ```bash docker compose exec backend chown -R www-data:www-data storage bootstrap/cache```

## Accessing Services

After starting the containers, you can access:

- Frontend (Next.js): http://localhost:7052
- Backend API (Laravel): http://localhost:7051
- pgAdmin: http://localhost:7054
- Redis Commander: http://localhost:7056

## Common Commands

**Container Management:**
# Start all services
```bash docker compose up -d```

# Stop all services
```bash docker compose down```

# Rebuild and start services
```bash docker compose up -d --build```

# View logs
```bash docker compose logs -f```

# View specific service logs
```bash docker compose logs -f backend```

**Laravel Commands:**
```bash
# Run migrations
```bash docker compose exec backend php artisan migrate```

# Clear cache
```bash docker compose exec backend php artisan cache:clear```

# Access Laravel shell
```bash docker compose exec backend bash```

**Next.js Commands:**
```bash
# Install new package
```bash docker compose exec frontend npm install <package-name>```

# Access Next.js shell
```bash docker compose exec frontend sh```

**Database:**
```bash
# Access PostgreSQL CLI
```bash docker compose exec db psql -U postgres```

# Create database backup
```bash docker compose exec db pg_dump -U postgres -d ${DB_NAME} > backup.sql```

## Troubleshooting

1. **Permission Issues**
   ```bash
   # Fix Laravel storage permissions
   ```bash docker compose exec backend chown -R www-data:www-data storage bootstrap/cache```

2. **Container Access Issues**
   ```bash
   # Check container status
   ```bash docker compose ps```

   # Check container logs
   ```bash docker compose logs <service-name>```

3. **Port Conflicts**
   - Check if ports defined in `.env` are not in use
   - You can modify port numbers in `.env` file

4. **Database Connection Issues**
   - Ensure PostgreSQL container is running
   - Check database credentials in `.env`
   - Wait a few seconds after container startup for database initialization

## Clean Up

### Quick Cleanup (Everything)
```bash
# Remove everything: unused containers, networks, images, and volumes
docker system prune -a --volumes
```

### Project-Specific Cleanup
```bash
# Stop and remove project containers and volumes
docker compose down -v
```

### Selective Cleanup
```bash
# Remove unused volumes only
docker volume prune

# Remove unused networks only
docker network prune

# Remove unused images only
docker image prune -a
```

### Check Current Docker Usage
```bash
# List all containers (running and stopped)
docker ps -a

# List all volumes
docker volume ls

# List all networks
docker network ls

# List all images
docker images
```

## Container Names

All containers are prefixed with the project name defined in `.env` (`PROJECT_NAME`):
- `${PROJECT_NAME}_laravel_app`
- `${PROJECT_NAME}_nextjs_app`
- `${PROJECT_NAME}_nginx`
- `${PROJECT_NAME}_postgres_db`
- `${PROJECT_NAME}_pgadmin`
- `${PROJECT_NAME}_redis`
- `${PROJECT_NAME}_redis_commander`

## Tech Stack

- **Backend**: Laravel
- **Frontend**: Next.js
- **Database**: PostgreSQL
- **Cache**: Redis
- **Web Server**: Nginx
- **Tools Included**:
  - pgAdmin (PostgreSQL Management)
  - Redis Commander (Redis Management)

## Project Structure

project/
├── .env
├── docker-compose.yml
├── backend/ # Laravel application
├── frontend/ # Next.js application
└── docker/
└── nginx/
└── default.conf

## Contributing

[Add your contribution guidelines here]

## License

[Add your license information here]