# 🛍️ E-Commerce Platform - AI-Powered Smart Shopping Solution

> A modern, intelligent e-commerce platform built with **Laravel**, featuring advanced AI decision engine, fraud detection, real-time analytics, and professional payment integration.

[![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?style=flat-square&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=flat-square&logo=php)](https://www.php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat-square&logo=mysql)](https://www.mysql.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg?style=flat-square)](LICENSE)

---

## 📋 Table of Contents

- [Key Features](#-key-features)
- [System Architecture](#%EF%B8%8F-system-architecture)
- [Tech Stack](#-tech-stack)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Documentation](#-documentation)
- [Demo Users](#-demo-users)
- [Project Structure](#-project-structure)
- [Contributing](#-contributing)
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
    - Multiple payment methods (COD, VNPAY)
    - Cart persistence and recovery
- **Account Management**
    - Comprehensive user profile management
    - Secure password management
    - Unlimited address book (Multiple delivery addresses)
    - Complete order history tracking (Pending → Processing → Shipping → Completed)
    - Order cancellation capability (before processing)
    - Order status notifications
- **Product Interaction**
    - Rate and review purchased products
    - View community ratings
    - Track favorite products

### 🔧 Admin Management

- **Real-time Dashboard**
    - Revenue statistics and KPIs
    - Real-time order monitoring
    - Best-selling products analytics
    - User activity insights
- **Product & Category Management**
    - Full CRUD operations for products and categories
    - Inventory management system
    - Bulk product uploads
    - Product image management
- **Order Fulfillment**
    - Professional order processing workflow
    - Status management and tracking
    - Invoice generation and management
    - Order history and detailed logs
- **User Management**
    - Role-based access control (RBAC)
    - Fine-grained permission system
    - User activity monitoring
    - Admin action logging
- **Advanced Analytics**
    - Revenue reports with detailed breakdowns
    - Low stock alerts and inventory warnings
    - Sales trends and forecasting
    - Customer behavior analytics

### 🤖 AI & Security Features

- **Electro AI Engine** - Advanced artificial intelligence system
    - **AI Decision Engine**: Automated order acceptance/rejection based on risk analysis
    - **Fraud Detection**: Real-time fraud detection using custom risk rules
    - **Risk Management**: Comprehensive risk assessment and mitigation strategies
    - **User Behavior Profiling**: Behavioral analysis for security and personalization
    - **Authentication Logs**: Detailed security audit trails and login monitoring

---

## 🏗️ System Architecture

```
┌─────────────┐         ┌──────────────┐         ┌─────────────┐
│   Browser   │◄───────►│ Laravel App  │◄───────►│  Database   │
│ (Frontend)  │         │  (Backend)   │         │   (MySQL)   │
└─────────────┘         └──────────────┘         └─────────────┘
                               │
                    ┌──────────┼──────────┐
                    │          │          │
            ┌───────▼──┐  ┌────▼─────┐  ┌───▼──────┐
            │ VNPAY    │  │ AI Engine│  │ Analytics│
            │ Gateway  │  │ Service   │  │ Service  │
            └──────────┘  └──────────┘  └──────────┘
```

---

## 🛠 Tech Stack

| Component           | Technology                                           |
| ------------------- | ---------------------------------------------------- |
| **Backend**         | Laravel 10/11, PHP 8.2+                              |
| **Database**        | MySQL 8.0+                                           |
| **Frontend**        | Blade Templates, Bootstrap 5, Tailwind CSS           |
| **Build Tool**      | Vite                                                 |
| **CSS Framework**   | Tailwind CSS + Bootstrap 5                           |
| **UI Libraries**    | jQuery, OwlCarousel (Carousels), Wow.js (Animations) |
| **Payment Gateway** | VNPAY API                                            |
| **Testing**         | PHPUnit, Pest                                        |
| **Task Queue**      | Laravel Queue (optional)                             |

---

## 🚀 Installation

### Prerequisites

- **PHP** >= 8.2
- **Composer** (dependency manager for PHP)
- **MySQL** >= 8.0
- **Node.js** >= 16 & **npm** >= 8
- **Git**

### Step 1: Clone the Repository

```bash
git clone https://github.com/yourusername/e-commerce-platform.git
cd e-commerce-platform
```

### Step 2: Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### Step 3: Environment Configuration

```bash
# Create .env file from example
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 4: Database Setup

```bash
# Update .env with database credentials
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=ecommerce_db
# DB_USERNAME=root
# DB_PASSWORD=

# Run migrations
php artisan migrate

# Seed demo data (optional)
php artisan db:seed
```

### Step 5: Build Frontend Assets

```bash
# Development build
npm run dev

# Production build
npm run build
```

### Step 6: Start the Application

```bash
# Start development server
php artisan serve

# Access at: http://localhost:8000
```

---

## ⚙️ Configuration

### Environment Variables (.env)

Key configuration variables:

```env
# Application
APP_NAME="E-Commerce Platform"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecommerce_db
DB_USERNAME=root
DB_PASSWORD=

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS=noreply@ecommerce.com

# VNPAY Payment Gateway
VNPAY_TMN_CODE=your_tmn_code
VNPAY_HASH_SECRET=your_hash_secret
VNPAY_URL=https://sandbox.vnpayment.vn/paygate

# AI Engine Configuration
AI_ENGINE_ENABLED=true
AI_FRAUD_DETECTION=true
AI_RISK_THRESHOLD=0.7
```

### VNPAY Payment Setup

1. Register at [VNPAY](https://www.vnpay.vn)
2. Get your TMN Code and Hash Secret
3. Update `.env` with VNPAY credentials
4. Configure webhook for payment callbacks

---

## 📚 Documentation

Comprehensive documentation for core modules:

- **[Electro AI Engine](doc/ELECTRO-AI-ENGINE.md)** - AI system architecture and overview
- **[AI Decision Engine Logic](doc/AI_DECISION_ENGINE.md)** - How the AI makes decisions
- **[AI Rules & Risk Assessment](doc/AI_RULES.md)** - Risk rules and fraud detection patterns
- **[Security & Risk Management](doc/ELECTRO-SECURITY.md)** - Security measures and protocols
- **[UI Flows & Design System](doc/ELECTRO-UI-FLOWS.md)** - User interface workflows
- **[Troubleshooting Guide](doc/CSRF_419_FIX.md)** - Common issues and solutions
- **[System Audit Report](doc/SYSTEM_AUDIT_REPORT.md)** - Security audit findings
- **[Design System](doc/design-system.md)** - UI components and guidelines

---

## 👤 Demo Users

Test the platform with these credentials:

| Role      | Email              | Password    |
| --------- | ------------------ | ----------- |
| Customer  | customer@demo.com  | password123 |
| Admin     | admin@demo.com     | admin123    |
| Moderator | moderator@demo.com | mod123      |

See [DEMO_USERS.md](DEMO_USERS.md) for more details.

---

## 📁 Project Structure

```
e-commerce-platform/
├── app/
│   ├── Console/              # Artisan commands
│   ├── Http/
│   │   ├── Controllers/      # Application controllers
│   │   ├── Middleware/       # HTTP middleware
│   │   └── Requests/         # Form request validation
│   ├── Mail/                 # Email classes
│   ├── Models/               # Eloquent models
│   │   ├── User.php
│   │   ├── Product.php
│   │   ├── Order.php
│   │   └── ...
│   ├── Policies/             # Authorization policies
│   ├── Providers/            # Service providers
│   ├── Repositories/         # Data access layer
│   └── Services/             # Business logic
│       ├── AIDecisionEngine.php
│       ├── RiskManagementService.php
│       ├── CartService.php
│       ├── OrderService.php
│       └── ...
├── bootstrap/                # Application bootstrap files
├── config/                   # Configuration files
├── database/
│   ├── factories/            # Model factories
│   ├── migrations/           # Database migrations
│   └── seeders/              # Database seeders
├── doc/                      # Documentation
├── public/                   # Web root
│   ├── css/                  # Compiled stylesheets
│   ├── js/                   # Compiled JavaScript
│   └── img/                  # Images and assets
├── resources/
│   ├── css/                  # Source CSS (Tailwind)
│   ├── js/                   # Source JavaScript
│   └── views/                # Blade templates
├── routes/                   # Route definitions
├── storage/                  # Application storage
├── tests/                    # Test files
│   ├── Feature/
│   └── Unit/
├── vendor/                   # Composer dependencies
├── artisan                   # Artisan CLI
├── composer.json
├── package.json
├── tailwind.config.js
├── vite.config.js
└── README.md
```

---

## 🧪 Testing

Run automated tests with:

```bash
# Run all tests
./vendor/bin/pest

# Run specific test file
./vendor/bin/pest tests/Feature/OrderTest.php

# Run with coverage
./vendor/bin/pest --coverage
```

---

## 🔒 Security

This project implements multiple security layers:

- **CSRF Protection**: Token-based CSRF protection on all forms
- **SQL Injection Prevention**: Parameterized queries via Eloquent ORM
- **XSS Protection**: Blade template escaping
- **Password Hashing**: Bcrypt password hashing
- **Rate Limiting**: IP-based rate limiting on login attempts
- **Two-Factor Authentication**: Optional 2FA support
- **Audit Logging**: Comprehensive action logging
- **AI Fraud Detection**: Real-time fraud detection system

See [Security Documentation](doc/ELECTRO-SECURITY.md) for details.

---

## 🤝 Contributing

We welcome contributions! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Development Guidelines

- Follow PSR-12 coding standards
- Write tests for new features
- Update documentation as needed
- Ensure all tests pass before submitting PR

---

## 🐛 Bug Reports & Feature Requests

Found a bug or have a feature idea? Please [open an issue](https://github.com/yourusername/e-commerce-platform/issues) with:

- Clear description of the issue
- Steps to reproduce (for bugs)
- Expected vs actual behavior
- Environment details (OS, PHP version, etc.)

---

## 📊 Roadmap

- [ ] Mobile app (React Native)
- [ ] Multi-language support
- [ ] Advanced inventory management
- [ ] Subscription products
- [ ] Affiliate system
- [ ] Enhanced analytics dashboard
- [ ] API rate limiting improvements
- [ ] GraphQL API support

---

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 👨‍💼 Author

**Your Name / Organization**

- GitHub: [@yourusername](https://github.com/yourusername)
- Email: your.email@example.com

---

## 🙏 Acknowledgments

- Built with [Laravel](https://laravel.com) - The web artisan framework
- Payment integration with [VNPAY](https://www.vnpay.vn)
- UI components from [Bootstrap](https://getbootstrap.com) and [Tailwind CSS](https://tailwindcss.com)
- Icons from [Font Awesome](https://fontawesome.com)

---

## 📞 Support

- 📧 Email: support@ecommerce-platform.com
- 💬 Discord: [Join our community](https://discord.gg/yourserver)
- 📖 Documentation: [Read the docs](doc/)
- 🐛 Issues: [GitHub Issues](https://github.com/yourusername/e-commerce-platform/issues)

---

**Last Updated**: January 2026
**Version**: 1.0.0
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ten_database_cua_ban
DB_USERNAME=root
DB_PASSWORD=

# Cấu hình VNPAY (Demo)

VNP_TMN_CODE=your_tmn_code
VNP_HASH_SECRET=your_secret_key
VNP_URL=[https://sandbox.vnpayment.vn/paymentv2/vpcpay.html](https://sandbox.vnpayment.vn/paymentv2/vpcpay.html)

4. Khởi tạo Dữ liệu

# Chạy migration và seed dữ liệu mẫu (Admin, User, Products, Settings)

php artisan migrate --seed

# Link thư mục ảnh ra public

php artisan storage:link

# Build assets cho frontend

npm run build

5. Chạy Server

php artisan serve

Truy cập trang web tại: http://localhost:8000
