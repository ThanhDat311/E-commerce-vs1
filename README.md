# 🛍️ E-Commerce Platform - AI-Powered Smart Shopping Solution

> A modern, intelligent e-commerce platform built with **Laravel 12**, featuring advanced AI decision engine, fraud detection, real-time analytics, and professional payment integration.

[![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=flat-square&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=flat-square&logo=php)](https://www.php.net)
[![TailwindCSS](https://img.shields.io/badge/TailwindCSS-3.1-38B2AC?style=flat-square&logo=tailwind-css)](https://tailwindcss.com)
[![Vite](https://img.shields.io/badge/Vite-7.0-646CFF?style=flat-square&logo=vite)](https://vitejs.dev)
[![License](https://img.shields.io/badge/License-MIT-green.svg?style=flat-square)](LICENSE)

---

## 📋 Table of Contents

- [Key Features](#-key-features)
- [System Architecture](#-system-architecture)
- [Tech Stack](#-tech-stack)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Documentation](#-documentation)
- [Demo Users](#-demo-users)
- [Project Structure](#-project-structure)
- [Testing](#-testing)
- [Security](#-security)
- [License](#-license)

---

## ✨ Key Features

### 👥 Customer Features

- **Smart Shopping Experience**
    - Intelligent product search and filtering
    - Advanced multi-criteria filtering
    - Detailed product views with ratings and reviews
- **Shopping Cart & Checkout**
    - Optimized checkout flow
    - Supported payment methods: **COD**, **VNPay**
    - Cart persistence and recovery
- **Account Management**
    - Comprehensive user profile management
    - Secure password management
    - Address book management (Multiple delivery addresses)
    - Complete order history tracking (Pending → Processing → Shipping → Completed)
    - Order cancellation capability (before processing)

### 🔧 Management & Administration

- **Real-time Dashboard**
    - Revenue statistics and sales KPIs
    - Real-time order monitoring
    - Best-selling products analytics
- **Product & Inventory**
    - Full CRUD for products and categories
    - Inventory management with low-stock alerts
    - Product image management via Laravel Storage
- **Role-Based Access Control (RBAC)**
    - Multi-role support: **Admin**, **Staff**, **Vendor**, **Customer**
    - Fine-grained permission system

### 🤖 AI & Security Features

- **Electro AI Engine**
    - **AI Decision Engine**: Automated order risk analysis
    - **Fraud Detection**: Real-time fraud detection using custom risk rules
    - **Risk Management**: Comprehensive risk assessment for transactions
- **Security Protocols**
    - Detailed authentication logs and audit trails
    - CSRF, XSS, and SQL Injection protection
    - Rate limiting on critical routes

---

## 🛠 Tech Stack

| Component           | Technology                                           |
| ------------------- | ---------------------------------------------------- |
| **Backend**         | Laravel 12, PHP 8.2+                                 |
| **Database**        | MySQL 8.0+                                           |
| **Frontend**        | Blade Templates, Alpine.js 3, Tailwind CSS 3         |
| **Build Tool**      | Vite 7                                               |
| **Real-time**       | Laravel Echo, Pusher/Reverb                          |
| **Payment Gateway** | VNPay API                                            |
| **Testing**         | Pest 4, PHPUnit                                      |

---

## 🚀 Installation

### Prerequisites

- **PHP** >= 8.2
- **Composer** (dependency manager)
- **MySQL** >= 8.0
- **Node.js** >= 18 & **npm**
- **Git**

### Step-by-Step Setup

1. **Clone the Repository**
   ```bash
   git clone https://github.com/yourusername/e-commerce-platform.git
   cd e-commerce-platform
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Setup**
   Update `.env` with your database credentials, then run:
   ```bash
   php artisan migrate --seed
   ```

5. **Storage & Assets**
   ```bash
   php artisan storage:link
   npm run build
   ```

6. **Start the Application**
   ```bash
   php artisan serve
   ```
   Access at: [http://localhost:8000](http://localhost:8000)

---

## 👤 Demo Users

Test the platform with the following credentials:

| Role     | Email              | Password |
| -------- | ------------------ | -------- |
| Admin    | `admin@demo.com`    | `password` |
| Staff    | `staff@demo.com`    | `password` |
| Customer | `customer@demo.com` | `password` |
| Vendor   | `vendor@demo.com`   | `password` |

---

## 📚 Documentation

Detailed guides for core modules:

- **[Electro AI Engine](doc/ELECTRO-AI-ENGINE.md)** - AI system architecture
- **[Order Processing Workflow](doc/ELECTRO-UI-FLOWS.md)** - UI flows and logic
- **[Security & Risk Management](doc/ELECTRO-SECURITY.md)** - Security protocols
- **[System Audit Report](doc/SYSTEM_AUDIT_REPORT.md)** - Audit findings

---

## 🧪 Testing

Run tests using Pest:
```bash
./vendor/bin/pest
```

---

## � License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.
