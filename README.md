<div align="center">
  <img src="public/favicon.ico" alt="Logo" width="80" height="auto" />
  <h1>🛍️ Electro - AI-Powered Multi-Vendor E-Commerce Platform</h1>
  <p>
    <strong>A modern, intelligent marketplace built with Laravel 12 and powered by an advanced AI Decision Engine for fraud detection and risk management.</strong>
  </p>

  <p>
    <a href="https://laravel.com"><img src="https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel" alt="Laravel 12"></a>
    <a href="https://www.php.net"><img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php" alt="PHP 8.2"></a>
    <a href="https://tailwindcss.com"><img src="https://img.shields.io/badge/Tailwind_CSS-3.1-38B2AC?style=for-the-badge&logo=tailwind-css" alt="Tailwind CSS"></a>
    <a href="https://alpinejs.dev/"><img src="https://img.shields.io/badge/Alpine.js-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=white" alt="Alpine JS"></a>
    <a href="https://pestphp.com/"><img src="https://img.shields.io/badge/Pest-F7CA18?style=for-the-badge&logo=testing-library&logoColor=black" alt="Pest PHP"></a>
  </p>
</div>

---

## 📖 Welcome to Electro
Electro isn't just another e-commerce site; it's a **secure, multi-vendor marketplace** designed for scale. By integrating a dedicated AI Risk Engine, it protects both buyers and sellers from fraudulent transactions and suspicious logins while providing a seamless, real-time shopping experience.

---

## ✨ Outstanding Features

### 🛒 Multi-Vendor Marketplace
- **Vendor Dashboards:** Dedicated panels for sellers to manage products, view sales, and request payouts.
- **Commission System:** Automated commission calculation and tiered `CommissionSettings`.
- **Payout Management:** Secure handling of `VendorPayout` and wallet balances.

### 🤖 AI-Powered Risk & Security Engine
- **Transaction Fraud Detection:** Real-time AI evaluation of every checkout attempt.
- **Smart Login Risk:** Adaptive MFA triggers based on `UserBehaviorProfile` and `LoginHistory` anomalies.
- **Dynamic Risk Rules:** Configurable `RiskRule` and `RiskList` management via an intuitive admin dashboard.

### 💰 Dynamic Promotions & Pricing
- **Flash Sales & Deals:** Time-bound `FlashSale` events with countdown timers.
- **Smart Coupons:** Advanced `Coupon` usage restrictions and user-specific `UserCoupon` tracking.
- **Price Suggestions:** Interactive `PriceSuggestion` system for B2B or wholesale negotiations.

### 📊 Real-Time Analytics & Operations
- **Live Dashboards:** Powered by **Laravel Reverb**, experience real-time order monitoring.
- **Inventory Alerts:** Automated `LowStockAlerts` to keep sellers informed.
- **Revenue Analytics:** Deep dive into `TopSellingProducts` and sales KPIs with interactive charts.

### 🛡️ Customer Support & Trust
- **Ticketing System:** Integrated `SupportTicket` and `TicketMessage` threading.
- **Dispute Resolution:** Built-in `Dispute` and `Refund` tracking for buyer protection.
- **Verified Reviews:** Product `Review` system tied directly to verified purchases.

---

## 🏗️ System Architecture

The project utilizes a modern decoupled architecture:
1. **Core Application (Laravel 12):** Handles routing, ORM, multi-auth, queue processing, and real-time broadcasting via **Laravel Reverb**.
2. **AI Microservice (Python/FastAPI):** A dedicated service located in `../E-commerce-AI` that provides real-time risk scoring, behavior profiling, and fraud detection using machine learning models. It communicates with Laravel via a REST API.

---

## 💻 Tech Stack

| Domain | Technologies |
| :--- | :--- |
| **Backend Framework** | Laravel 12, PHP 8.2+ |
| **Database** | MySQL 8.0+ |
| **Frontend Styling** | Tailwind CSS 3.1, PostCSS |
| **Frontend Logic** | Alpine.js 3, Blade Components |
| **Asset Bundling** | Vite 7 |
| **Real-Time WebSockets**| Laravel Reverb, Laravel Echo |
| **Search Engine** | Laravel Scout |
| **Testing** | Pest 4 (PHP), Playwright (E2E) |

---

## 🚀 Getting Started

### Prerequisites
- PHP >= 8.2
- Composer
- Node.js >= 18 & NPM
- MySQL >= 8.0
- Python (for the AI Microservice)

### Step-by-step Installation

1. **Clone the Repository**
   ```bash
   git clone https://github.com/ThanhDat311/E-commerce-vs1.git
   cd E-commerce-vs1
   ```

2. **Install PHP and Node Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Configure Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   > ⚠️ **Important**: Update your `.env` file with your specific `DB_*` credentials. Provide configuration if using external services like VNPay or Pusher.

4. **Database Migration & Seeding**
   ```bash
   php artisan migrate --seed
   ```
   *This will set up the massive schema including multi-vendor tables, risk rules, and seed demo users.*

5. **AI Microservice Setup**
   Clone the **E-commerce-AI** repository into a sibling directory or any preferred location:
   ```bash
   git clone https://github.com/ThanhDat311/E-commerce-AI.git
   cd E-commerce-AI
   # Ensure you are inside the E-commerce-AI directory
   python -m venv venv
   source venv/bin/activate  # Or venv\Scripts\activate on Windows
   pip install -r requirements.txt
   ```

6. **Symlink Storage**
   ```bash
   php artisan storage:link
   ```

7. **Start the Servers**
   To run both the Laravel application and the AI Microservice concurrently, use our custom NPM script:
   ```bash
   npm run dev
   ```
   *(This launches the Vite dev server and the Python AI service simultaneously).*

---

## 👥 Demo Users

Jump right in with our pre-seeded roles:

| Role | Email | Password | Access Area |
| :--- | :--- | :--- | :--- |
| **Super Admin** | `admin@demo.com` | `password` | `/admin/dashboard` |
| **Store Staff** | `staff@demo.com` | `password` | `/staff/dashboard` |
| **Vendor** | `vendor@demo.com` | `password` | `/vendor/dashboard` |
| **Customer** | `customer@demo.com` | `password` | `/` (Storefront) |

---

## 🧪 Testing

We take reliability seriously. The project utilizes **Pest** for unit & feature testing.

```bash
# Run all PHP tests
php artisan test --compact

# Or run specific test suites
php artisan test --filter=RiskEngineTest
```

For End-to-End browser testing, Playwright is configured in the `/e2e` directory.

---

<div align="center">
  <i>Built with ❤️ by the development team. Fully licensed under the MIT License.</i>
</div>
