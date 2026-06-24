# 📝 Todo API (Laravel + Passport + RBAC)

REST API для управления задачами с системой ролей и разрешений (RBAC), авторизацией через Laravel Passport и документацией Swagger.

---

## 🚀 Технологии

- Laravel
- Laravel Passport (OAuth2 Authentication)
- MySQL
- Swagger / L5-Swagger
- RBAC (Roles & Permissions)

---

## 🔐 Основные возможности

### 👤 Users
- Регистрация пользователя
- Авторизация (JWT token через Passport)
- Получение списка пользователей
- Обновление и удаление пользователя
- Назначение ролей пользователю
- Назначение разрешений пользователю

---

### 🧑‍💼 Roles
- Создание ролей
- Просмотр ролей
- Обновление ролей
- Удаление ролей
- Назначение permissions к роли

---

### 🔑 Permissions
- Создание permissions
- Просмотр permissions
- Обновление permissions
- Удаление permissions

---

### 📌 Tasks
- CRUD задач
- Привязка задач к пользователю

---

## 🔐 RBAC логика

- Пользователь может иметь несколько ролей
- Роль может иметь несколько permissions
- Permissions могут назначаться напрямую пользователю
- Проверка доступа через middleware `permission`

---

## ⚙️ Установка проекта

```bash id="r2"
git clone https://github.com/USERNAME/REPO_NAME.git
cd REPO_NAME
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan passport:install
php artisan serve

🔑 Авторизация
Login
POST /api/login

Header для запросов
Authorization: Bearer {token}

📚 Swagger документация
http://localhost:8000/api/documentation

📦 Примеры API
1. Создание пользователя
POST /api/users
2. Назначение роли пользователю
POST /api/users/{id}/roles
3. Назначение permissions пользователю
POST /api/users/{id}/permissions

🧠 Архитектура проекта
Controller → API logic
Middleware → permission checks
Models → relationships (User ↔ Roles ↔ Permissions)
Passport → authentication
Swagger → API documentation

⚠️ Важно
Перед запуском убедитесь, что:
Passport установлен (php artisan passport:install)
.env настроен
База данных подключена
Кеш очищен при изменениях (php artisan optimize:clear)
