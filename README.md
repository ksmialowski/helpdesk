# 🎫 Helpdesk Application

The Helpdesk lite management system built with Laravel and Angular.

## 🚀 Quick Start

### Prerequisites

- Docker & Docker Compose

### Installation

**1. Clone and configure environment**

```bash
# Copy the environment file
cp .env.example .env
```

**2. Build and start Docker containers**

```bash
# Build containers
docker-compose build

# Start containers in detached mode
docker-compose up -d
```

**3. Setup backend (Laravel)**

```bash
# Access the backend container
docker exec -it helpdesk_backend /bin/bash

# Inside the container:
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
```

## 🌐 Access Points

| Service | URL | Description |
|---------|-----|-------------|
| 🎨 Frontend | http://localhost:4200 | Angular application |
| ⚙️ Backend | http://localhost:8000 | Laravel API |
| 🗄️ Database Admin | http://localhost:7000 | phpMyAdmin interface |

## 🔧 Configuration Changes

If you modify `.env` or other configuration files, restart the containers:

```bash
docker-compose down
docker-compose up -d
```

## 📦 Tech Stack

- **Backend**: Laravel (PHP)
- **Frontend**: Angular
- **Database**: MySQL
- **Containerization**: Docker