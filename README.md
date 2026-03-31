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
---
🌐 Live API
```
https://task-manager-production-e95f.up.railway.app
```
> Test all endpoints using [Postman](https://www.postman.com/) or any HTTP client.
---
🔌 How It Works
```
  CLIENT                  LARAVEL API              MySQL DB
  ──────                  ───────────              ────────
    │                          │                       │
    │  POST /api/tasks ────────►│                       │
    │                          │── INSERT tasks ───────►│
    │                          │◄──────────────────────│
    │◄─ 201 Created ───────────│                       │
    │                          │                       │
    │  GET /api/tasks ─────────►│                       │
    │                          │── SELECT * ───────────►│
    │                          │◄── rows ──────────────│
    │◄─ 200 [ ] ───────────────│                       │
    │                          │                       │
    │  PATCH /{id}/status ─────►│                       │
    │                          │── UPDATE status ──────►│
    │◄─ 200 Updated ───────────│                       │
    │                          │                       │
    │  DELETE /{id} ───────────►│                       │
    │                          │── DELETE (if done) ───►│
    │◄─ 200 / 403 Forbidden ───│                       │
```
---
📁 Project Structure
```
task-manager/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── TaskController.php        ← All API logic
│   │   └── Requests/
│   │       ├── StoreTaskRequest.php      ← Create validation
│   │       └── UpdateTaskStatusRequest.php
│   └── Models/
│       └── Task.php                      ← Eloquent model
├── database/
│   └── migrations/
│       └── create_tasks_table.php        ← DB schema
├── routes/
│   └── api.php                           ← API routes
└── README.md
```
---
🗄️ Database Schema
```sql
CREATE TABLE tasks (
  id         INT PRIMARY KEY AUTO_INCREMENT,
  title      VARCHAR(255)                           NOT NULL,
  due_date   DATE                                   NOT NULL,
  priority   ENUM('low', 'medium', 'high')          NOT NULL,
  status     ENUM('pending', 'in_progress', 'done') DEFAULT 'pending',
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  UNIQUE KEY unique_title_due_date (title, due_date)
);
```
---
⚙️ Requirements (Local Setup)
PHP 8.3+
Composer
MySQL 8+
Laravel 13
---
💻 How to Run Locally
1. Clone the Repository
```bash
git clone https://github.com/Faithkanyuki/task-manager.git
cd task-manager
```
2. Install Dependencies
```bash
composer install
```
3. Set Up Environment
```bash
cp .env.example .env
php artisan key:generate
```
4. Configure MySQL in `.env`
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_manager
DB_USERNAME=root
DB_PASSWORD=
```
5. Run Migrations
```bash
php artisan migrate
```
6. Start the Server
```bash
php artisan serve
```
API is now running at `http://127.0.0.1:8000`
---
☁️ How to Deploy (Railway)
Push code to GitHub
Go to https://railway.app
Click "New Project" → "Deploy from GitHub repo"
Select your repository
Add a MySQL database service
Set these environment variables in your Laravel service:
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
Set Start Command:
```bash
php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT
```
---
🚀 API Endpoints
Base URL
```
http://127.0.0.1:8000                                    (local)
https://task-manager-production-e95f.up.railway.app      (live)
```
Method	Endpoint	Description
`POST`	`/api/tasks`	Create a new task
`GET`	`/api/tasks`	List all tasks
`PATCH`	`/api/tasks/{id}/status`	Update task status
`DELETE`	`/api/tasks/{id}`	Delete a task
`GET`	`/api/tasks/report?date=`	Daily summary report
---
1. ➕ Create Task
POST `/api/tasks`
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
    "id": 1,
    "title": "Build Login Page",
    "due_date": "2026-04-05",
    "priority": "high",
    "status": "pending",
    "created_at": "2026-03-30T18:08:31.000000Z",
    "updated_at": "2026-03-30T18:08:31.000000Z"
}
```
---
2. 📋 List Tasks
GET `/api/tasks`
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
---
3. 🔄 Update Task Status
PATCH `/api/tasks/{id}/status`
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
Error `422` (invalid transition):
```json
{
    "message": "Invalid status transition. Expected next status: 'in_progress'."
}
```
---
4. 🗑️ Delete Task
DELETE `/api/tasks/{id}`
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
---
5. 📊 Daily Report (Bonus)
GET `/api/tasks/report?date=YYYY-MM-DD`
```
GET /api/tasks/report?date=2026-04-05
```
Response `200`:
```json
{
    "date": "2026-04-05",
    "summary": {
        "high":   { "pending": 1, "in_progress": 0, "done": 0 },
        "medium": { "pending": 0, "in_progress": 0, "done": 0 },
        "low":    { "pending": 0, "in_progress": 0, "done": 0 }
    }
}
```
---
📏 Business Rules
```
✅  No duplicate title on the same due_date
✅  due_date must be today or in the future
✅  priority must be: low | medium | high
✅  Status flow: pending → in_progress → done (no skipping, no reverting)
✅  Only done tasks can be deleted (403 Forbidden otherwise)
✅  Tasks sorted by priority (high → medium → low) then due_date ascending
```
---
<p align="center">
  Made with ❤️ by <strong>Faith Kanyuki</strong><br>
  <em>Cytonn Software Engineering Internship — 2026</em>
</p>
