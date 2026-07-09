# 🚀 Todo API (Laravel + Docker)

![Laravel](https://img.shields.io/badge/Laravel-13-red?style=for-the-badge&logo=laravel)
![Docker](https://img.shields.io/badge/Docker-Enabled-blue?style=for-the-badge&logo=docker)
![MySQL](https://img.shields.io/badge/MySQL-8.0-orange?style=for-the-badge&logo=mysql)
![Swagger](https://img.shields.io/badge/Swagger-API%20Docs-green?style=for-the-badge&logo=swagger)
![Kafka](https://img.shields.io/badge/Apache%20Kafka-Enabled-black?style=for-the-badge&logo=apachekafka)

---

# 📌 Описание проекта

**Todo API** — это backend REST API приложение, разработанное на Laravel и полностью контейнеризированное с помощью Docker.

Проект реализует систему управления задачами с авторизацией пользователей, ролями и разрешениями, фоновой обработкой задач и автоматической отправкой email-уведомлений.

---

# ✨ Возможности проекта

## 🔐 Authentication & Authorization

Реализовано:

- регистрация пользователей
- авторизация через username и password
- генерация API токенов через Laravel Passport
- защита API маршрутов
- система ролей и permissions

Примеры ролей:

- Super Admin
- Admin
- Manager
- User

---
## Авторизация и контроль доступа

В проекте реализована многоуровневая система авторизации, которая включает аутентификацию пользователей, роли, разрешения и проверку доступа к конкретным ресурсам.

### Аутентификация

Для аутентификации используется Laravel Passport.

После успешного входа пользователь получает access token, который используется для доступа к защищённым API-эндпоинтам.

---

### Роли и разрешения (RBAC)

В проекте реализована ролевая модель доступа (Role-Based Access Control).

Пользователь может иметь одну роль. Каждая роль содержит набор разрешений, которые определяют доступные действия.

Примеры разрешений:

* `users.view`
* `users.create`
* `users.update`
* `users.delete`
* `tasks.view`
* `tasks.create`
* `tasks.update`
* `tasks.delete`

Перед выполнением действия middleware проверяет, есть ли у пользователя необходимое разрешение.

Например:

```
permission:tasks.update
```

Если у пользователя нет соответствующего разрешения, API возвращает ошибку `403 Forbidden`.

---

### Владение задачами (Task Ownership)

Каждая задача связана с конкретным пользователем через поле `user_id`.

При создании задачи владелец определяется автоматически из текущего авторизованного пользователя.

Пример:

```
Пользователь A
 |
 ├── Задача 1
 └── Задача 2


Пользователь B
 |
 └── Задача 3
```

Пользователь может просматривать, изменять и удалять только свои задачи.

Проверка владельца реализована через Laravel Policy.

Пример:

```php
public function update(User $user, Task $task): bool
{
    return $user->id === $task->user_id;
}
```

Если пользователь пытается изменить или удалить чужую задачу, API возвращает ошибку `403 Forbidden`.

---

### Поток проверки доступа

Запрос пользователя проходит следующие этапы:

```
HTTP Request
      |
      ↓
Passport Authentication
      |
      ↓
Permission Middleware
      |
      ↓
Controller
      |
      ↓
Task Policy
      |
      ↓
Database
```

Таким образом, в проекте используются два уровня проверки:

1. **Permissions** — проверяют, может ли пользователь выполнять определённое действие.
2. **Policies** — проверяют, имеет ли пользователь доступ к конкретному объекту.


## ✅ Task Management

CRUD операции для задач:

- создание задачи
- просмотр списка задач
- просмотр одной задачи
- обновление задачи
- удаление задачи

Каждая задача содержит:

- title
- description
- status

Статусы задач:

- new
- in_progress
- done

---

# 🐳 Docker Environment

Проект полностью работает внутри Docker.

Используемые сервисы:

| Container | Назначение |
|-----------|------------|
| nginx | Web server |
| app | Laravel + PHP-FPM |
| queue | Laravel Queue Worker |
| db | MySQL 8.0 |
| kafka | Apache Kafka Broker |
| kafka-consumer | Kafka Event Consumer |


---
## Apache Kafka

Kafka используется для обработки событий приложения.

Реализованные события:

### Tasks
- task.created
- task.updated
- task.deleted

### Users
- user.created
- user.updated
- user.role.assigned
- user.permission.assigned

Kafka topic:

- task.events
- user.events

### Kafka Consumer
Consumer запускается автоматически через Docker:

```
php artisan kafka:consume
```

## 📧 Email Configuration

По умолчанию используется:

#### MAIL_MAILER=log

Письма сохраняются в:

#### storage/logs/laravel.log

Для реальной отправки через SMTP необходимо настроить собственные SMTP данные в `.env`.


## ⚡ Быстрый старт

### 1. Клонирование проекта

```bash
git clone <repo-url>

cd todo_api
```
### 2. Запуск Docker
```
docker compose up -d --build
```

### После запуска автоматически выполняется:

 - создание .env из .env.example
 - установка Composer зависимостей
 - ожидание готовности MySQL
 - проверка APP_KEY
 - выполнение миграций
 - генерация Swagger документации
 - настройка Laravel Passport
 - запуск PHP-FPM
 - запуск Queue Worker

## 3. Открыть приложение
```
http://localhost:8080
```

## 📚 Swagger API Documentation

Для документации API используется:
```
darkaonline/l5-swagger
```

Swagger автоматически генерируется при запуске контейнера.

После запуска проекта документация доступна:

```
http://localhost:8080/api/documentation
```

Swagger позволяет:

 - просматривать API endpoints
 - отправлять тестовые запросы
 - проверять авторизацию
 - тестировать CRUD операции
 - 
## 🔑 Laravel Passport

Для API авторизации используется Laravel Passport.

При запуске Docker автоматически:

 - создаются OAuth keys
 - создаётся Personal Access Client
 - проверяется наличие существующих ключей

Это позволяет получать API токены после авторизации пользователя.

## 🔄 Queue & Background Jobs

В проекте используется Laravel Queue для выполнения фоновых задач.

Реализовано:

- отдельный Docker контейнер для queue worker
- обработка Jobs в фоне
- отправка email после регистрации пользователя

Пример процесса:

User Registration

        ↓

Create Job

        ↓

Queue Worker

        ↓

Send Email

### Queue автоматически запускается:
```
php artisan queue:work --tries=3 --verbose
```

## 🗄 База данных

Используется:

MySQL 8.0

Настройки:

Параметр	Значение
Database	todo_api
User	user
Password	password
Host	db
Port	3306

## 🌐 Порты

По умолчанию:

Service	Port
Nginx	8080
MySQL	3306

### ⚠️ Возможная ошибка: порт занят

Ошибка:

Bind for 0.0.0.0:8080 failed: port is already allocated

Причина:

Порт уже используется другим контейнером или приложением.

Решение:

Изменить порт в docker-compose.yml:

```
ports:
  - "8081:80"
```

Пример:

Проект	Порт
основной	8080
копия	8081
тест	8082

## 🐳 Docker Commands

### Запуск:

```
docker compose up -d --build
```

### Остановка:

```
docker compose down
```

### Просмотр контейнеров:

```
docker ps
```

### Логи приложения:
```
docker compose logs -f app
```

### Логи очереди:
```
docker compose logs -f queue
```

### Логи Consumer:
```
docker compose logs -f kafka-consumer
```

### Вход в Laravel контейнер:
```
docker compose exec app bash
```

## 📁 Project Structure
todo_api/

├── app/
├── routes/
├── database/
├── storage/
│
├── docker/
│   └── php/
│       └── Dockerfile
│
├── docker-compose.yml
├── entrypoint.sh
├── composer.json
└── README.md

## ⚙️ Environment

.env создаётся автоматически при первом запуске.

Основные переменные:

 - DB_CONNECTION=mysql
 - DB_HOST=db
 - DB_DATABASE=todo_api
 - DB_USERNAME=user
 - DB_PASSWORD=password
 - KAFKA_BROKERS=kafka:9092
 - KAFKA_SECURITY_PROTOCOL=PLAINTEXT
 - KAFKA_CONSUMER_GROUP_ID=group


## 🚀 Features
- ✅ Laravel REST API
- ✅ Dockerized environment
- ✅ Nginx + PHP-FPM
- ✅ MySQL database
- ✅ Laravel Passport authentication
- ✅ Roles & Permissions system
- ✅ Swagger API documentation
- ✅ Laravel Queue Worker
- ✅ Apache Kafka integration
- ✅ Event-driven architecture
- ✅ Kafka Consumer processing
- ✅ Ready for cloning and deployment

## 🧠 Notes
- Первый запуск может занять больше времени из-за установки Composer зависимостей.
- .env создаётся автоматически.
- Миграции запускаются автоматически.
- Swagger документация генерируется автоматически.
- Queue worker запускается автоматически.
- Для изменения портов необходимо изменить docker-compose.yml.
