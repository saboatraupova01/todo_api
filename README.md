# 🚀 Todo API (Laravel + Docker)

![Laravel](https://img.shields.io/badge/Laravel-10-red?style=for-the-badge&logo=laravel)
![Docker](https://img.shields.io/badge/Docker-Enabled-blue?style=for-the-badge&logo=docker)
![MySQL](https://img.shields.io/badge/MySQL-8.0-orange?style=for-the-badge&logo=mysql)

---

## 📌 Описание проекта

Todo API — это backend-приложение на Laravel, полностью контейнеризированное с помощью Docker.

Включает:

- ⚙️ Laravel API
- 🐳 Docker окружение
- 🌐 Nginx
- 🗄 MySQL
- 🔁 Автоматические миграции

---

## ⚡ Быстрый старт

### 📥 1. Клонирование проекта

```bash
git clone <repo-url>
cd todo_api
```
### 2. Запуск проекта
```
docker compose up -d --build
```
### 🌐 3. Открыть в браузере
http://localhost:8080

## 💥 ВАЖНО: ПОРТЫ
### ⚠️ Возможная ошибка
### Bind for 0.0.0.0:8080 failed: port is already allocated

### 🧠 Причина
Порт 8080 уже используется другим контейнером или проектом.

## ✔ Решение
Изменить порт в docker-compose.yml:
```
ports:
  - "8081:80"
```

### 📌 Примеры:

| Проект   | Порт |
| -------- | ---- |
| основной | 8080 |
| копия    | 8081 |
| тест     | 8082 |

### 🐳 Docker команды
```
docker compose up -d --build   # запуск
docker compose down            # остановка
docker ps                      # список контейнеров
```

### 🗄 База данных
| Параметр | Значение |
| -------- | -------- |
| DB       | todo_api |
| User     | user     |
| Password | password |
| Host     | db       |

### 🧱 Архитектура
- [ Browser ]
      ↓
- [ Nginx (8080) ]
      ↓
- [ PHP-FPM (Laravel) ]
      ↓
- [ MySQL ]

### ✨ Особенности
- 📦 полностью dockerized проект
- 🔄 автоматический запуск окружения
- 🧩 Laravel + Nginx + MySQL
- 🚀 готов к клонированию и запуску одной командой

### 🧠 Заметки
- Если порт занят — поменяйте 8080 → 8081
- Первый запуск может занять время (composer install + migrations)
- .env создаётся автоматически при старте контейнера
