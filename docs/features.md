# Features Documentation

This document details the functional capabilities of the E-commerce system.

## Core E-commerce Features

### Product Management
- Multi-vendor support.
- Image management (storage and galleries).
- Full-text search using Laravel Scout.
- Slug-based URL optimization.

### Order Processing
- Flexible checkout flow with support for multiple payment methods (including VNPay).
- Order history and real-time status updates.
- Automated tax and commission calculation for vendors.

### Shopping Experience
- Cart management with dynamic price calculation.
- Wishlist functionality.
- Localization (Multi-language) and Currency switching.

## Advanced Features

### Deals & Promotions
- Complex discount logic: Percentage, Fixed, and BOGO.
- Priority-based deal application.
- Vendor-specific deals requiring admin approval.
- Flash sales with time-limited availability.

### AI & Risk Management
- **AI Risk Analysis**: Evaluates orders and user behavior for potential fraud.
- **Price Suggestions**: AI-driven price optimization recommendations.
- **Audit Logs**: Detailed tracking of sensitive actions and data mutations.

### Support & Dispute Resolution
- Integrated support ticket system for customer-admin communication.
- Formal dispute and refund workflow for order-related issues.

## Vendor Ecosystem
- Dedicated vendor dashboard.
- Commission management system.
- Payout tracking for vendors.
