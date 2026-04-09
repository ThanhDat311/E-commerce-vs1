<div align="center">
  <h1>🛍️ Project: AI E-Commerce Platform</h1>
  <p>
    <strong>A multi-vendor e-commerce website with AI to check login risk and find fake orders.</strong>
  </p>
</div>

---

## 📖 About the Project
This project has 2 main parts:
1. **Main Website (Laravel):** Built with Laravel 12. It handles the web pages, database, users, and shopping.
2. **AI Service (Python):** A separate service that uses AI to score risks.

## 💻 Technology Used
- **Backend:** Laravel 12 (PHP 8.2+)
- **Database:** MySQL 8.0+
- **Frontend:** Tailwind CSS 3, Alpine.js, Blade Components
- **Asset Bundler:** Vite 7
- **Security & Live Updates:** Laravel Sanctum, Laravel Reverb (WebSockets)
- **Search:** Laravel Scout
- **Testing:** Pest 4 (PHP), Playwright (E2E)

---

## 🚀 How to Install and Run

### What You Need:
- PHP >= 8.2 & Composer
- Node.js >= 18 & NPM
- MySQL >= 8.0 (like XAMPP or Laragon)

### Setup Steps for the Website:

**Step 1: Install Libraries**
We removed the `vendor` and `node_modules` folders to save space. Please run these commands in the `E-commerce` folder:
```bash
composer install
npm install
```

**Step 2: Setup the Environment (.env)**
Copy the `.env.example` file to create a `.env` file. Then make an app key:
```bash
cp .env.example .env
php artisan key:generate
```
> **⚠️ Important:** Please open the `.env` file and change `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD` to match your MySQL setup.

**Step 3: Setup Database and Demo Data**
This system has a lot of test data. Run this command to create the tables and add the data:
```bash
php artisan migrate --seed
```

**Step 4: Link Images**
Run this command to show product images clearly:
```bash
php artisan storage:link
```

**Step 5: Start the Server**
Use this command to start PHP and Vite at the same time:
```bash
npm run dev
```
*(The website will be at: http://localhost:8000)*

---

## 👥 Demo Accounts
The system already has test accounts for you. The password for all accounts is `password`:
- **Super Admin:** `admin@demo.com` (Go to: `/admin/dashboard`)
- **Store Staff:** `staff@demo.com` (Go to: `/staff/dashboard`)
- **Vendor (Seller):** `vendor@demo.com` (Go to: `/vendor/dashboard`)
- **Customer:** `customer@demo.com` (Go to: Homepage)

---

## 🔗 Connect to the AI System
*Note:* To use the smart security features (for login and checkout), you must run the **E-commerce-AI** app too. Please read the `README.md` file in the AI folder for more details.

---
*(You can view the source code here: https://github.com/ThanhDat311/E-commerce-AI.git)*
