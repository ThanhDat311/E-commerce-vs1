# Low Stock Alerts - Implementation Guide

## Overview

The Low Stock Alerts dashboard provides visibility into inventory levels and automated warnings when products fall below minimum thresholds. It helps maintain optimal stock levels, prevent stockouts, and streamline the restocking process with professional warning color schemes and attention-grabbing alerts.

---

## 📋 Feature Specifications

### 1. Alert Table with Status Indicators

**Purpose**: Display products with low inventory in a scannable, action-oriented table format

**Columns**:

- **Status**: Color-coded badge (Critical, Warning, Low)
- **Product Name**: Product title with category
- **Current Stock**: Current quantity with units
- **Minimum Threshold**: Recommended minimum quantity
- **Stock Level**: Progress bar showing percentage of minimum
- **Restock Quantity**: Suggested quantity to order
- **Action**: Primary call-to-action button

**Row Styling**:

- **Critical Rows**: Red background (#FEF2F2), red left border
- **Warning Rows**: Orange background (#FFF7ED), orange left border
- **Low Rows**: Yellow background (#FFFBEB), yellow left border

---

### 2. Status Indicator System

**Three Status Levels**:

#### Critical (Red)

- **Threshold**: 0-50% of minimum stock
- **Characteristics**:
    - Animated red pulse badge
    - Red background row
    - "Restock Now" button (red)
    - Highest priority
- **Action**: Immediate restocking required
- **Visual**: Pulsing animation for attention

#### Warning (Orange)

- **Threshold**: 51-80% of minimum stock
- **Characteristics**:
    - Static orange badge
    - Orange background row
    - "Schedule" button (orange)
    - Medium priority
- **Action**: Plan restocking soon
- **Visual**: Static presentation

#### Low (Yellow)

- **Threshold**: 81-100% of minimum stock
- **Characteristics**:
    - Static yellow badge
    - Yellow background row
    - "Monitor" button (yellow)
    - Low priority
- **Action**: Monitor closely
- **Visual**: Subtle indicator

---

### 3. Summary Cards (4 Metrics)

#### Card 1: Critical Items

- **Value**: Count of critical products
- **Default**: 8
- **Color**: Red-600 border, red-50 background
- **Icon**: Exclamation circle (red)
- **Label**: "Immediate action needed"

#### Card 2: Warning Items

- **Value**: Count of warning products
- **Default**: 14
- **Color**: Orange-500 border, orange-50 background
- **Icon**: Exclamation (orange)
- **Label**: "Plan restocking"

#### Card 3: Low Items

- **Value**: Count of low stock products
- **Default**: 6
- **Color**: Yellow-500 border, yellow-50 background
- **Icon**: Info circle (yellow)
- **Label**: "Monitor closely"

#### Card 4: Total Watched Items

- **Value**: Total products monitored
- **Default**: 28
- **Color**: Blue-500 border, blue-50 background
- **Icon**: Cubes (blue)
- **Label**: "Products monitored"

---

### 4. Stock Level Visualization

**Type**: Horizontal progress bar

**Features**:

- Full width bar with rounded edges
- Color-coded based on status:
    - Critical: Red (#DC2626)
    - Warning: Orange (#F97316)
    - Low: Yellow (#EAB308)
- Percentage text below bar
- Proportional width (0-100%)

**Display**:

```
Current Stock: 12 units
Minimum: 50 units
Level: 24% of minimum
→ 24% filled bar with "24% of minimum" label
```

---

### 5. Restock Action Buttons

**Three Button Types**:

#### Restock Now (Red)

- **For**: Critical items (0-50%)
- **Color**: Red-600 background, white text
- **Icon**: Exclamation circle
- **Action**: Open restock modal immediately
- **Hover**: Red-700

#### Schedule (Orange)

- **For**: Warning items (51-80%)
- **Color**: Orange-500 background, white text
- **Icon**: Box
- **Action**: Schedule restock for later
- **Hover**: Orange-600

#### Monitor (Yellow)

- **For**: Low items (81-100%)
- **Color**: Yellow-500 background, white text
- **Icon**: Clock
- **Action**: Set reminder to monitor
- **Hover**: Yellow-600

---

### 6. Filter & Search Functionality

**Filter Options**:

1. **Status Filter**: Critical, Warning, Low, All
2. **Category Filter**: Electronics, Fashion, Home & Garden, Sports, All
3. **Sort Options**:
    - Urgency (default: critical first)
    - Stock Level (lowest first)
    - Product Name (A-Z)
    - Restock Quantity (highest first)
4. **Search Field**: Product name search (real-time)

**Filter Area**:

- Located in header (below title)
- Orange background with 20% opacity
- Border and backdrop blur effect
- 5-column responsive grid

---

### 7. Quick Actions Panel

**Purpose**: Fast access to bulk actions

**Actions**:

1. **Restock All Critical**: Mark all 8 critical items for immediate restocking
2. **Schedule Warning Items**: Schedule all 14 warning items
3. **Review Low Items**: Focus on all 6 low stock items
4. **Configure Thresholds**: Open settings modal

**Visual**: Red, orange, yellow, and blue buttons respectively

---

### 8. Recent Activity Section

**Purpose**: Show recent inventory actions and alerts

**Activity Types**:

- Critical alerts (red dots)
- Warning alerts (orange dots)
- Completed restocks (green dots)
- Configuration changes (blue dots)

**Details per Activity**:

- Activity type (bold title)
- Description (secondary text)
- Timestamp (gray, relative time)

---

## 🎨 Design Specifications

### Color Palette

**Status Colors** (Primary):

- **Critical**: Red-600 `#DC2626` (alerts, urgent)
- **Warning**: Orange-500 `#F97316` (caution, plan ahead)
- **Low**: Yellow-500 `#EAB308` (monitor, watch)

**Background Colors** (Soft):

- **Critical BG**: Red-50 `#FEF2F2`
- **Warning BG**: Orange-50 `#FFF7ED`
- **Low BG**: Yellow-50 `#FFFBEB`

**Neutrals**:

- **Header**: Orange-600 to Red-600 (gradient)
- **Text Primary**: Gray-900 `#111827`
- **Text Secondary**: Gray-600 `#4b5563`
- **Borders**: Gray-300 `#d1d5db`
- **Background**: Gray-50 `#f9fafb`

**Accents**:

- **Green**: Green-600 `#16a34a` (success, completed)
- **Blue**: Blue-600 `#2563eb` (information, configuration)

---

### Typography

**Font Stack**: System fonts (Tailwind default)

**Type Scale**:

- **Page Title**: 4xl, bold, white (44px)
- **Subtitle**: sm, text-orange-100 (14px)
- **Card Labels**: sm, uppercase, bold, gray-700 (12px)
- **Card Values**: 3xl, bold, red/orange/yellow-700 (30px)
- **Table Headers**: xs, uppercase, bold, gray-700 (12px)
- **Badge Text**: xs, bold, white (12px)
- **Button Text**: sm, bold, white (14px)

---

### Layout Structure

**Header Section**:

- Gradient background (orange-600 to red-600)
- Title with pulsing alert icon
- Settings and Export buttons
- Filter bar with status, category, sort, search

**Summary Cards**:

- 4-column grid (responsive: 1 mobile, 2 tablet, 4 desktop)
- Color-coded borders (red, orange, yellow, blue)
- Icon in top-right corner (20% opacity)

**Alert Table**:

- Full-width table with horizontal scroll on mobile
- Header: Dark gray background
- Rows: Color-coded by status with left border
- Footer: Showing results and "View All" link

**Action Panels**:

- 2-column layout (desktop), 1-column (mobile)
- Quick Actions (left): 4 buttons
- Recent Activity (right): 5 items with activity dots

---

## 🏗️ Technical Implementation

### Database Schema

**Required Tables** (existing):

```
products
- id (primary key)
- name
- category
- stock_quantity
- min_stock_threshold
- restock_quantity
- created_at

inventory_alerts
- id (primary key)
- product_id (foreign key)
- alert_type (critical|warning|low)
- current_stock
- threshold
- created_at
```

**Recommended Indexes**:

```sql
CREATE INDEX idx_products_stock ON products(stock_quantity, min_stock_threshold);
CREATE INDEX idx_inventory_alerts_type ON inventory_alerts(alert_type);
CREATE INDEX idx_inventory_alerts_created ON inventory_alerts(created_at);
```

---

### API Endpoints

#### 1. Dashboard View

**Route**: `GET /admin/low-stock-alerts`
**Controller**: `LowStockAlertsController@index`
**Parameters**:

- `status` (optional): critical|warning|low
- `category` (optional): electronics|fashion|home|sports
- `sort` (optional): urgency|stock|name|restock
- `search` (optional): product name search

**Response**: Blade view with alert data

---

#### 2. Alert Data API

**Route**: `GET /admin/low-stock-alerts/data`
**Controller**: `LowStockAlertsController@alertData`
**Parameters**: Same as dashboard
**Response**:

```json
{
    "data": [
        {
            "id": 1,
            "name": "Premium Wireless Headphones",
            "category": "electronics",
            "current_stock": 12,
            "min_threshold": 50,
            "restock_qty": 100,
            "status": "critical",
            "level_percentage": 24
        }
    ],
    "total": 8,
    "critical": 8,
    "warning": 14,
    "low": 6
}
```

---

#### 3. Category Summary API

**Route**: `GET /admin/low-stock-alerts/categories`
**Controller**: `LowStockAlertsController@categorySummary`
**Response**:

```json
[
    {
        "category": "Electronics",
        "critical": 3,
        "warning": 5,
        "low": 1,
        "total": 9
    }
]
```

---

#### 4. Mark Restocked

**Route**: `POST /admin/low-stock-alerts/{productId}/restocked`
**Controller**: `LowStockAlertsController@markRestocked`
**Parameters**:

- `quantity` (required): Number of units restocked

**Response**:

```json
{
    "success": true,
    "message": "Product marked as restocked",
    "productId": 1,
    "quantity": 100
}
```

---

#### 5. Update Threshold

**Route**: `PUT /admin/low-stock-alerts/{productId}/threshold`
**Controller**: `LowStockAlertsController@updateThreshold`
**Parameters**:

- `threshold` (required): New minimum threshold

**Response**:

```json
{
    "success": true,
    "message": "Threshold updated successfully",
    "productId": 1,
    "newThreshold": 75
}
```

---

#### 6. Export CSV

**Route**: `GET /admin/low-stock-alerts/export`
**Controller**: `LowStockAlertsController@export`
**Parameters**: Same as dashboard
**Response**: CSV file download

---

## ⚡ Performance Optimization

### Query Optimization

1. **Index on Stock Columns**: `stock_quantity`, `min_stock_threshold`
2. **Limit Results**: Show only products below threshold
3. **Category Indexing**: Fast category filtering
4. **Sorting**: Efficient sort operations

### Frontend Optimization

1. **Lazy Load Charts**: Load visualization library on demand
2. **Debounce Search**: 300ms debounce on search input
3. **CSS Optimization**: Use Tailwind JIT mode
4. **Minimal JS**: Pure CSS animations where possible

### Caching Strategy

```
Cache Key: "low_stock_{status}_{category}_{sort}"
Duration: 15 minutes (faster updates for inventory)
Invalidate: On inventory changes
```

---

## 📊 Example Data Structures

### Product Alert Data

```php
[
    'id' => 1,
    'name' => 'Premium Wireless Headphones',
    'category' => 'electronics',
    'current_stock' => 12,
    'min_threshold' => 50,
    'restock_qty' => 100,
    'status' => 'critical',
    'level_percentage' => 24,
]
```

### Alert Summary

```php
[
    'critical' => 8,
    'warning' => 14,
    'low' => 6,
    'total' => 28,
]
```

### CSV Export Format

```
Status,Product Name,Category,Current Stock,Minimum Threshold,Stock Level %,Restock Quantity,Urgency
CRITICAL,Premium Wireless Headphones,electronics,12,50,24%,100,critical
WARNING,Cotton T-Shirt Bundle,fashion,125,150,83%,200,warning
LOW,Running Shoes Classic,sports,189,200,95%,50,low
```

---

## 🛠️ Common Workflows

### Workflow 1: Daily Inventory Check

1. Open Low Stock Alerts dashboard
2. Review summary cards (critical count)
3. Scan table for new critical items
4. Click "Restock All Critical" if urgency
5. Note warning items for planning
6. Check recent activity section

### Workflow 2: Restocking Process

1. Identify critical item in table
2. Click "Restock Now" button
3. Confirm quantity (pre-filled: recommended amount)
4. Submit restock order
5. Wait for confirmation
6. Activity log updates automatically

### Workflow 3: Threshold Adjustment

1. Review product in table
2. Click settings (if available)
3. Update minimum threshold
4. Confirm new threshold
5. System recalculates status
6. Alert updates in real-time

### Workflow 4: Category Analysis

1. Open dashboard
2. Filter by Category (e.g., Electronics)
3. Review critical items in that category
4. Plan restocking by category
5. Export category-specific report
6. Share with suppliers

---

## 🔧 Troubleshooting

### Issue: No Alerts Showing

- **Check**: Do products have `min_stock_threshold` set?
- **Check**: Is current stock below threshold?
- **Solution**: Configure thresholds in product settings

### Issue: Incorrect Status Levels

- **Check**: Threshold calculation: (current / minimum) \* 100
- **Check**: Status mapping: 0-50% = critical, 51-80% = warning, 81-100% = low
- **Solution**: Verify threshold values in database

### Issue: Performance Lag

- **Check**: Database indexes on stock columns
- **Check**: Number of products being queried
- **Solution**: Limit to products below threshold, add indexes

### Issue: Alerts Not Updating

- **Check**: Cache duration (default 15 minutes)
- **Check**: If inventory updated, cache should invalidate
- **Solution**: Clear cache or wait for automatic invalidation

---

## 🚀 Future Enhancements

### Phase 2 Features

1. **Email Notifications**: Alert key staff when critical
2. **Auto-Reorder**: Automatically create purchase orders
3. **Forecasting**: Predict when products will stock out
4. **Supplier Integration**: Send orders directly to suppliers
5. **Mobile Alerts**: Push notifications for critical items

### Phase 3 Features

1. **Predictive Analytics**: Machine learning for restock timing
2. **Multi-location**: Track stock across warehouses
3. **Workflow Automation**: Automated restock workflows
4. **Integration APIs**: Connect to supplier systems
5. **Advanced Reports**: Custom alert reports and analytics

---

## 📞 Support Resources

- **Quick Start Guide**: [QUICK_START.md](./LOW_STOCK_ALERTS_QUICK_START.md)
- **Design Guide**: [DESIGN_GUIDE.md](./LOW_STOCK_ALERTS_DESIGN_GUIDE.md)
- **Feature Showcase**: [FEATURE_SHOWCASE.md](./LOW_STOCK_ALERTS_FEATURE_SHOWCASE.md)
- **Documentation Index**: [INDEX.md](./LOW_STOCK_ALERTS_DOCUMENTATION_INDEX.md)

---

**Version**: 1.0.0  
**Last Updated**: January 29, 2026  
**Status**: Production Ready
