# Architecture Overview

This document provides a high-level overview of the E-commerce system's architecture, technology stack, and security model.

## Technology Stack

- **Framework**: Laravel 12
- **PHP**: 8.2+
- **Database**: MySQL / SQLite (Development)
- **Frontend**: Blade Templates, Tailwind CSS v3, Alpine.js v3
- **Bundling**: Vite
- **Testing**: Pest PHP v4
- **AI Integration**: Custom AI Feature Store, OpenAI (implied by recent conversions)
- **Real-time**: Laravel Reverb, Laravel Echo
- **Search**: Laravel Scout

## Directory Structure

- `app/Http/Controllers`: Application logic separated by roles (Admin, Staff, Vendor, Customer).
- `app/Models`: Eloquent models representing the data schema.
- `app/Services`: Business logic extracted from controllers (e.g., `DealService`, `PriceCalculatorService`).
- `database/migrations`: Evolution of the database schema.
- `routes/`: Route definitions (web, api, auth, console).
- `resources/views`: Blade templates for the UI.

## Security & Authentication

- **Authentication**: Laravel Breeze (Starter Kit), Socialite (Google Auth).
- **Authorization**: Role-Based Access Control (RBAC) using roles and permissions.
    - **Admin (role_id = 1)**: Full system access.
    - **Staff (role_id = 2)**: Partial management access (orders, products, etc.).
    - **Vendor (role_id = 4)**: Manage own products and deals.
    - **Customer (role_id = 3/default)**: Browsing, purchasing, and account management.
- **Middleware**: Custom role checks (`role:admin`, `role.check:staff`, `role.check:vendor`).

## System Integration

- **Payment**: VNPay Integration.
- **AI Features**: Risk assessment rules, price suggestions, and user behavior profiling.
- **Audit Logs**: Comprehensive tracking of system changes and user actions.
