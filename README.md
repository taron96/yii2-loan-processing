# Loan Management API (Yii2 & PostgreSQL)

A high-performance REST API for submitting and processing loan applications, built with **PHP (Yii2)**, **PostgreSQL**, and **Docker**.

## 🚀 Features

* **PostgreSQL with Row-Level Locking**: Uses `FOR UPDATE` transactions to prevent race conditions and ensure "single approved loan per user" constraints.
* **Dockerized Environment**: Fully containerized setup with Nginx, PHP-FPM, and PostgreSQL.
* **RESTful Compliance**: Strict adherence to the provided endpoint specifications and status codes.
* **Scalable Architecture**: Designed to handle simultaneous `/processor` requests safely.

## 🛠 Tech Stack

* **Framework**: Yii 2.0 (Basic Template)
* **Web Server**: Nginx
* **Database**: PostgreSQL 15
* **PHP Version**: 8.2-fpm
* **Containerization**: Docker Compose

---

## 📥 Installation & Setup

1.  **Clone the repository:**
    ```bash
    git clone [https://github.com/taron96/yii2-loan-processing.git](https://github.com/taron96/yii2-loan-processing.git)
    cd yii2-loan-processing
    ```

2.  **Start the containers:**
    ```bash
    docker-compose up -d --build
    ```

3.  **Install dependencies:**
    ```bash
    docker-compose exec app composer install
    ```

4.  **Run migrations:**
    ```bash
    docker-compose exec app php yii migrate --interactive=0
    ```

> The API will be available at: `http://localhost`

---

## 📡 API Endpoints

### 1. Submit Loan Request
**URL:** `POST /requests`

**Request Body:**
```json
{
  "user_id": 1,
  "amount": 3000,
  "term": 30
}
```

**Responses:**
* **Success (201):** `{"result": true, "id": 42}`
* **Failure (400):** `{"result": false}` (Validation fails or user already has an approved loan)

### 2. Run Processor
**URL:** `GET /processor?delay=5`

**Description:**
Processes all pending requests. Includes a 10% approval probability and simulates processing time using `sleep(delay)`.

---

## 🗄 Database Configuration

The application is pre-configured to connect to PostgreSQL with the following credentials:

| Key | Value |
| :--- | :--- |
| **Host** | `localhost` (Internal Docker alias: `db`) |
| **Port** | `5432` |
| **DB Name** | `loans` |
| **User** | `user` |
| **Password** | `password` |

---

## ⏱ Time Spent

* **Initial Setup & Docker Config:** 45 mins
* **Database Schema & Migrations:** 30 mins
* **API Logic & Race Condition Protection:** 1.5 hours
* **Testing & Documentation:** 45 mins
* **Total:** **~3.5 hours**
