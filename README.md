# 📝 Todo API (Laravel + Passport + Swagger)

REST API для управления задачами с аутентификацией через Laravel Passport.

---

## 🚀 Технологии

- PHP 8.3+
- Laravel 13.x
- MySQL
- Laravel Passport (OAuth2 Authentication)
- L5-Swagger (API Documentation)

---

## 📦 Функционал

### 🔐 Авторизация
- Регистрация пользователя
- Логин пользователя
- Получение Bearer Token через Passport

### 📋 Tasks (CRUD)
- Создание задачи
- Получение списка задач (pagination)
- Просмотр одной задачи
- Обновление задачи
- Удаление задачи

---

## 📊 Модель Task

| Поле        | Тип     | Описание                 |
|------------|--------|--------------------------|
| id         | int    | ID задачи                |
| title      | string | Заголовок (обязательно) |
| description | text   | Описание (необязательно)|
| status     | string | new / in_progress / done|
| created_at | date   | Дата создания           |
| updated_at | date   | Дата обновления         |

---

## ⚙️ Установка проекта

### 1. Клонировать репозиторий
```bash
git clone https://github.com/your-repo/todo_api.git
cd todo_api

### 2. Установить зависимости
``` composer install

### 3. Создать .env файл
cp .env.example .env

### 4. Настроить базу данных

В .env:

DB_DATABASE=todo_api
DB_USERNAME=root
DB_PASSWORD=

### 5. Сгенерировать ключ
php artisan key:generate

### 6. Запустить миграции
php artisan migrate

### 7. Установить Passport
php artisan install:api --passport

### 8. Сгенерировать Swagger
php artisan l5-swagger:generate

### 9. Запустить сервер
php artisan serve

🔐 Аутентификация

После логина или регистрации API возвращает:
{
  "data": {
    "user": {},
    "token": "Bearer token here"
  }
}
Использование токена:

В каждом защищённом запросе:
Authorization: Bearer YOUR_TOKEN

📌 Swagger документация
После запуска проекта:

http://127.0.0.1:8000/api/documentation
📡 API Endpoints
Auth
POST /api/register
POST /api/login
Tasks (Protected 🔒)
GET /api/tasks
POST /api/tasks
GET /api/tasks/{id}
PUT /api/tasks/{id}
DELETE /api/tasks/{id}

📌 Особенности реализации
1. Валидация через Form Request
2. Ответы через Resource
3. Статусы задач через Enum
4. Пагинация в index
Унифицированный JSON формат:
{
  "data": {},
  "message": ""
}
Swagger документация через attributes (#[OA\...])
Авторизация через Laravel Passport

