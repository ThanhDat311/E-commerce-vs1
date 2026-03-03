# Data Model

This document describes the database schema and the relationships between key Eloquent models in the E-commerce system.

## Core Models

### User
- Handles authentication and profile information.
- **Relationships**:
    - `HasMany` Addresses
    - `HasMany` Orders
    - `HasMany` Reviews
    - `HasMany` SupportTickets
    - `BelongsTo` Role

### Product
- Represents items for sale.
- **Fields**: `name`, `slug`, `description`, `price`, `stock_quantity`, `vendor_id`.
- **Relationships**:
    - `BelongsTo` Category
    - `BelongsTo` Vendor (User)
    - `HasMany` ProductImages
    - `HasMany` Reviews
    - `BelongsToMany` Deals

### Category
- Hierarchical structure for grouping products.
- **Relationships**:
    - `HasMany` Products
    - `BelongsToMany` Deals

### Order
- Records customer transactions.
- **Fields**: `order_number`, `total_amount`, `status`, `payment_status`.
- **Relationships**:
    - `BelongsTo` User
    - `HasMany` OrderItems
    - `HasMany` Histories
    - `HasOne` Dispute

## Specialized Models

### Deal
- Manages discounts and promotions.
- **Fields**: `discount_type`, `discount_value`, `apply_scope`, `start_date`, `end_date`.
- **Relationships**:
    - `BelongsToMany` Products
    - `BelongsToMany` Categories

### RiskRule & AuditLog
- Security and monitoring components.
- `RiskRule`: Defines criteria for flagging suspicious behavior.
- `AuditLog`: Polymorphic tracking of model changes (`auditable`).

### AiFeatureStore & UserBehaviorProfile
- AI-driven data analysis components.
- `UserBehaviorProfile`: Aggregates user activity for risk and personalization.
- `AiFeatureStore`: Stores processed features for AI model consumption.

## Global Relationships
The system heavily utilizes pivot tables for many-to-many relationships, such as `role_permissions`, `deal_products`, and `deal_categories`.
