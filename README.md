<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  </a>
</p>
<p align="center">
  <a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>
<h1 align="center">⚡ Task Manager API</h1>
<p align="center">
  <strong>Cytonn Software Engineering Internship — Coding Challenge 2026</strong><br>
  Built by <strong>Faith Kanyuki</strong>
</p>
<p align="center">
  <img src="https://img.shields.io/badge/Laravel-13-FF2D20?style=for-the-badge&logo=laravel&logoColor=white"/>
  <img src="https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white"/>
  <img src="https://img.shields.io/badge/MySQL-8.4-4479A1?style=for-the-badge&logo=mysql&logoColor=white"/>
  <img src="https://img.shields.io/badge/Deployed-Railway-0B0D0E?style=for-the-badge&logo=railway&logoColor=white"/>
  <img src="https://img.shields.io/badge/Status-Live-00C896?style=for-the-badge"/>
</p>

# Task Manager API

A RESTful Task Management API built with Laravel 13 and MySQL.

## Live API
```
https://task-manager-production-e95f.up.railway.app
```



## Requirements (Local Setup)
- PHP 8.3+
- Composer
- MySQL 8+
- Laravel 13



## How to Run Locally

### 1. Clone the Repository
```bash
git clone https://github.com/Faithkanyuki/task-manager.git
cd task-manager
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Set Up Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure MySQL in `.env`
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_manager
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Run Migrations
```bash
php artisan migrate
```

### 6. Start the Server
```bash
php artisan serve
```

API is now running at `http://127.0.0.1:8000`



## How to Deploy (Railway)

1. Push code to GitHub
2. Go to [https://railway.app](https://railway.app)
3. Click **"New Project"** → **"Deploy from GitHub repo"**
4. Select your repository
5. Add a **MySQL** database service
6. Set these environment variables in your Laravel service:
```env
APP_ENV=production
APP_DEBUG=false
DB_CONNECTION=mysql
DB_HOST=your-mysql-host
DB_PORT=your-mysql-port
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=your-mysql-password
APP_KEY=base64:your-app-key
```

7. Set Start Command:
```bash
php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT
```



## API Endpoints

### Base URL
```
http://127.0.0.1:8000  (local)
https://task-manager-production-e95f.up.railway.app  (live)
```



### 1. Create Task
**POST** `/api/tasks`

Request Body:
```json
{
    "title": "Build Login Page",
    "due_date": "2026-04-05",
    "priority": "high"
}
```

Response `201`:
```json
{
    "title": "Build Login Page",
    "due_date": "2026-04-05",
    "priority": "high",
    "status": "pending",
    "updated_at": "2026-03-30T18:08:31.000000Z",
    "created_at": "2026-03-30T18:08:31.000000Z",
    "id": 1
}
```



### 2. List Tasks
**GET** `/api/tasks`

Optional filter by status:
```
GET /api/tasks?status=pending
```

Response `200`:
```json
[
    {
        "id": 1,
        "title": "Build Login Page",
        "due_date": "2026-04-05",
        "priority": "high",
        "status": "pending",
        "created_at": "2026-03-30T18:08:31.000000Z",
        "updated_at": "2026-03-30T18:08:31.000000Z"
    }
]
```



### 3. Update Task Status
**PATCH** `/api/tasks/{id}/status`

Status must follow: `pending → in_progress → done`

Request Body:
```json
{
    "status": "in_progress"
}
```

Response `200`:
```json
{
    "id": 1,
    "status": "in_progress"
}
```



### 4. Delete Task
**DELETE** `/api/tasks/{id}`

Only `done` tasks can be deleted.

Response `200`:
```json
{
    "message": "Task deleted successfully."
}
```

Error `403`:
```json
{
    "message": "Only completed (done) tasks can be deleted."
}
```



### 5. Daily Report (Bonus)
**GET** `/api/tasks/report?date=YYYY-MM-DD`

Example:
```
GET /api/tasks/report?date=2026-04-05
```

Response `200`:
```json
{
    "date": "2026-04-05",
    "summary": {
        "high": {"pending": 1, "in_progress": 0, "done": 0},
        "medium": {"pending": 0, "in_progress": 0, "done": 0},
        "low": {"pending": 0, "in_progress": 0, "done": 0}
    }
}
```



## Business Rules

- **Create:** No duplicate title on the same due_date. Due date must be today or future.
- **List:** Sorted by priority (high → medium → low), then due_date ascending.
- **Update Status:** Must follow order: `pending → in_progress → done`. Cannot skip or revert.
- **Delete:** Only `done` tasks can be deleted. Returns `403` otherwise.

  

<p align="center">

  <em>Cytonn Software Engineering Internship — 2026</em>
</p>
