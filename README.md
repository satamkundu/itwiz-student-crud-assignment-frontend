
# Laravel Student Management System + Frontend

A simple student management backend (Laravel) running in 8000 with a separate frontend (Laravel) running on port 8001.

# Backend Setup Instructions (Laravel)
```bash
git clone https://github.com/satamkundu/itwiz-student-crud-assignment-backend.git
```
cd itwiz-student-crud-assignment-backend

# Frontend Setup Instructions (Laravel)
```bash
git clone https://github.com/satamkundu/itwiz-student-crud-assignment-frontend.git
```
cd itwiz-student-crud-assignment-frontend

Install PHP Dependencies in both projects
```bash
composer install
```

Copy and Configure
```bash
cp .env.example .env
```

Migrate Database and Seed Admin User for backend project
```bash
php artisan migrate
php artisan db:seed
```

Start Laravel Server Backend
```bash
php artisan serve
```

Start Frontend
```bash
php artisan serve --port=8001
```
Notes
```bash
Make sure backend runs on http://localhost:8000
Make sure frontend runs on http://localhost:8001
```


