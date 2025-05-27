# 📝 Notes App - MVC PHP Application in Docker

This project is an MVC-based note-taking application, built with PHP 8.2, MySQL, and the Smarty templating engine. It runs entirely within Docker containers, providing a modern, isolated environment for development and testing.

The system supports user authentication (registration, login, logout), as well as full note management features including creation, editing, deletion, archiving, and color tagging.

## HU

Ez a projekt egy **MVC alapú jegyzet alkalmazás**, amely PHP 8.2, MySQL és Smarty sablonmotor segítségével készült, és teljes mértékben **Docker konténerekben fut**. Készen áll a fejlesztésre és tesztelésre egy modern környezetben. A rendszer támogatja a felhasználói hitelesítést (regisztráció, bejelentkezés), valamint a jegyzetek kezelését (létrehozás, módosítás, törlés, archiválás, színválasztás).

---

## 🇬🇧 English Version

### 🚀 Technologies Used

* PHP 8.2
* Nginx
* MySQL 8.0
* Adminer (custom CSS)
* Composer
* Smarty Template Engine
* Docker & Docker Compose

---

### 📁 Project Structure

```
notes-app/
│
├── app/                # MVC components: Controllers, Models, Views
│  ├── Controllers/     # Request handling logic
│  ├── Models/          # Database interaction and business logic
│  ├── Views/           # Smarty templates
│  ├── Core/            # Base classes and core framework logic
│  ├── Helpers/         # Utility functions
│  └── bootstrap.php    # Application bootstrap
│
├── config/              # Application configuration (config.php, routes.php, logging.php)
│
├── public/              # Web root served by nginx (index.php, static assets)
│
├── storage/             # Log files, Smarty cache and compiled templates
│  ├── logs/
│  └── smarty/
│
├── docker/             # Docker build contexts for services
│  ├── php/             # PHP-FPM container setup
│  ├── nginx/           # Nginx server configuration
│  └── adminer/         # Adminer DB management interface setup
│
├── .env                 # Environment variable definitions
├── composer.json        # PHP package dependencies
├── docker-compose.yml   # Docker service definitions
├── README.md            # This file 🙂
└── .gitignore           # Git ignored files
```

---

### 🔧 Quick Start

1. Clone the repository:

   ```bash
   git clone https://github.com/your-user/notes-app.git
   cd notes-app
   ```

2. Start the Docker environment:

   ```bash
   docker-compose up --build -d
   ```

3. Open the app:

   * App: [http://localhost:8080](http://localhost:8080)
   * Adminer: [http://localhost:8081](http://localhost:8081)

> MySQL connection in Adminer:
>
> * Server: `mysql`
> * User: `user`
> * Password: `secret`
> * Database: `app`

To rebuild or debug:

```bash
docker-compose down && docker-compose up --build -d
docker-compose logs -f php
```

---

### 📊 Database Schema

#### `users` table:

```sql
CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nickname VARCHAR(50) NOT NULL UNIQUE,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    email_verified_at DATETIME DEFAULT NULL,
    password_hash VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100) DEFAULT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL,
    modified_at DATETIME DEFAULT NULL,
    deleted_at DATETIME DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### `notes` table:

```sql
CREATE TABLE notes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT,
    color VARCHAR(7) DEFAULT '#FFFFFF',
    is_pinned BOOLEAN DEFAULT FALSE,
    is_archived BOOLEAN DEFAULT FALSE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    modified_at DATETIME DEFAULT NULL,
    deleted_at DATETIME DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

---

### ✅ Features

* 👤 User registration and login
* 📋 User-specific note listing
* ✏️ Create, edit, delete (soft), archive notes
* 🎨 Color selector for notes
* 🧰 Adminer DB GUI with custom design
* 🐳 Fully dockerized development environment

---

### ℹ️ Author

Developed by Luho – Full-stack Engineer

---

### ✉️ License

This project is open-source and licensed under the MIT License.
