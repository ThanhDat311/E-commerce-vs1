# Admin Components - Usage Guide

## Quick Reference

All admin components are located in `resources/views/components/admin/` and can be used with the `<x-admin-*>` syntax in Blade templates.

---

## 🔘 Button Component

### Basic Usage

```blade
<x-admin-button>Click Me</x-admin-button>
```

### Properties

| Property   | Type    | Default   | Description                                                       |
| ---------- | ------- | --------- | ----------------------------------------------------------------- |
| `variant`  | string  | `primary` | Button style: primary, secondary, danger, success, warning, ghost |
| `size`     | string  | `md`      | Button size: sm, md, lg                                           |
| `disabled` | boolean | `false`   | Disable the button                                                |
| `loading`  | boolean | `false`   | Show loading spinner                                              |
| `icon`     | string  | `null`    | Font Awesome icon name (without `fa-` prefix)                     |
| `href`     | string  | `null`    | Convert to link (shows as `<a>` instead of `<button>`)            |

### Examples

```blade
<!-- Primary Button -->
<x-admin-button variant="primary" size="md">Save Changes</x-admin-button>

<!-- Secondary Button -->
<x-admin-button variant="secondary">Cancel</x-admin-button>

<!-- Danger Button with Icon -->
<x-admin-button variant="danger" icon="trash">Delete Item</x-admin-button>

<!-- Success Button, Large -->
<x-admin-button variant="success" size="lg">Confirm</x-admin-button>

<!-- Warning Button -->
<x-admin-button variant="warning" icon="calendar">Schedule</x-admin-button>

<!-- Loading State -->
<x-admin-button loading>Processing...</x-admin-button>

<!-- Disabled State -->
<x-admin-button disabled>Disabled</x-admin-button>

<!-- As Link -->
<x-admin-button href="/dashboard" icon="home">Go Home</x-admin-button>

<!-- Button Group -->
<div class="flex gap-2">
    <x-admin-button variant="primary">Save</x-admin-button>
    <x-admin-button variant="secondary">Cancel</x-admin-button>
</div>
```

---

## 🏷️ Badge Component

### Basic Usage

```blade
<x-admin-badge>New</x-admin-badge>
```

### Properties

| Property   | Type    | Default | Description                                            |
| ---------- | ------- | ------- | ------------------------------------------------------ |
| `variant`  | string  | `info`  | Badge style: critical, warning, success, info, neutral |
| `animated` | boolean | `false` | Add pulsing animation (for critical items)             |
| `icon`     | string  | `null`  | Font Awesome icon (auto-selected based on variant)     |

### Examples

```blade
<!-- Critical Badge (Red) -->
<x-admin-badge variant="critical">CRITICAL</x-admin-badge>

<!-- Critical with Animation -->
<x-admin-badge variant="critical" animated>URGENT</x-admin-badge>

<!-- Warning Badge (Orange) -->
<x-admin-badge variant="warning">WARNING</x-admin-badge>

<!-- Success Badge (Green) -->
<x-admin-badge variant="success">COMPLETED</x-admin-badge>

<!-- Info Badge (Blue) -->
<x-admin-badge variant="info">PROCESSING</x-admin-badge>

<!-- Neutral Badge (Gray) -->
<x-admin-badge variant="neutral">NEW</x-admin-badge>

<!-- Custom Icon -->
<x-admin-badge variant="warning" icon="hourglass">Pending</x-admin-badge>

<!-- In Table Row -->
<tr>
    <td><x-admin-badge variant="critical" animated>CRITICAL</x-admin-badge></td>
    <td>Product Name</td>
</tr>
```

---

## 🎴 Card Component

### Basic Usage

```blade
<x-admin-card>
    <h3>Card Title</h3>
    <p>Card content goes here</p>
</x-admin-card>
```

### Properties

| Property      | Type   | Default | Description                                        |
| ------------- | ------ | ------- | -------------------------------------------------- |
| `variant`     | string | `white` | Background: white, light, red, orange, green, blue |
| `border`      | string | `left`  | Border position: left, top, full                   |
| `borderColor` | string | `gray`  | Border color: gray, red, orange, green, blue       |

### Examples

```blade
<!-- Basic White Card -->
<x-admin-card>
    <h3 class="text-lg font-semibold">Card Title</h3>
    <p class="text-gray-600 mt-2">Card content</p>
</x-admin-card>

<!-- Colored Card (Light variant) -->
<x-admin-card variant="light">
    <p>Light background card</p>
</x-admin-card>

<!-- Critical Card (Red background, red left border) -->
<x-admin-card variant="red" border="left" borderColor="red">
    <p class="text-red-700">This is critical information</p>
</x-admin-card>

<!-- Warning Card -->
<x-admin-card variant="orange" border="left" borderColor="orange">
    <p class="text-orange-600">Warning message</p>
</x-admin-card>

<!-- Success Card -->
<x-admin-card variant="green" border="left" borderColor="green">
    <p class="text-green-700">Success message</p>
</x-admin-card>

<!-- Info Card -->
<x-admin-card variant="blue" border="left" borderColor="blue">
    <p class="text-blue-700">Information</p>
</x-admin-card>

<!-- Top Border Card -->
<x-admin-card border="top" borderColor="blue">
    <h3>Card with top border</h3>
</x-admin-card>

<!-- Full Border Card -->
<x-admin-card border="full" borderColor="gray">
    <h3>Card with full border</h3>
</x-admin-card>
```

---

## 📊 Metric Card Component

### Basic Usage

```blade
<x-admin-metric-card
    count="8"
    label="Critical Items"
    variant="red"
>
</x-admin-metric-card>
```

### Properties

| Property  | Type   | Default | Description                                        |
| --------- | ------ | ------- | -------------------------------------------------- |
| `count`   | number | `0`     | The metric number to display                       |
| `label`   | string | `''`    | Label for the metric                               |
| `variant` | string | `red`   | Color variant: red, orange, yellow, green, blue    |
| `icon`    | string | `null`  | Font Awesome icon (auto-selected based on variant) |

### Examples

```blade
<!-- Critical Items Card -->
<x-admin-metric-card
    count="8"
    label="Critical Items (0-50%)"
    variant="red"
>
    <div class="text-red-600 text-xs font-semibold mt-2">
        <i class="fas fa-arrow-up"></i> Immediate action needed
    </div>
</x-admin-metric-card>

<!-- Warning Items Card -->
<x-admin-metric-card
    count="14"
    label="Warning Items (51-80%)"
    variant="orange"
>
    <div class="text-orange-600 text-xs font-semibold mt-2">
        <i class="fas fa-arrow-right"></i> Plan restocking
    </div>
</x-admin-metric-card>

<!-- Low Items Card -->
<x-admin-metric-card
    count="6"
    label="Low Items (81-100%)"
    variant="yellow"
>
    <div class="text-yellow-600 text-xs font-semibold mt-2">
        <i class="fas fa-arrow-down"></i> Monitor closely
    </div>
</x-admin-metric-card>

<!-- Total Card -->
<x-admin-metric-card
    count="28"
    label="Total Watched Items"
    variant="blue"
    icon="cubes"
>
    <div class="text-blue-600 text-xs font-semibold mt-2">
        Overall inventory health
    </div>
</x-admin-metric-card>

<!-- Grid of Metric Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <x-admin-metric-card count="8" label="Critical" variant="red" />
    <x-admin-metric-card count="14" label="Warning" variant="orange" />
    <x-admin-metric-card count="6" label="Low" variant="yellow" />
    <x-admin-metric-card count="28" label="Total" variant="blue" />
</div>
```

---

## 💾 Stat Card Component

### Basic Usage

```blade
<x-admin-stat-card
    title="Total Revenue"
    stat="$45,231.89"
    icon="dollar-sign"
>
</x-admin-stat-card>
```

### Properties

| Property   | Type   | Default | Description                                             |
| ---------- | ------ | ------- | ------------------------------------------------------- |
| `title`    | string | `null`  | Card title/label                                        |
| `stat`     | string | `null`  | Main statistic to display                               |
| `subtitle` | string | `null`  | Subtitle text                                           |
| `trend`    | string | `null`  | Trend text (e.g., "+12.5% from last month")             |
| `icon`     | string | `null`  | Font Awesome icon                                       |
| `iconBg`   | string | `blue`  | Icon background color: red, orange, green, blue, yellow |

### Examples

```blade
<!-- Revenue Stat Card -->
<x-admin-stat-card
    title="Total Revenue"
    stat="$45,231.89"
    trend="+12.5% from last month"
    icon="dollar-sign"
    iconBg="blue"
>
</x-admin-stat-card>

<!-- Orders Stat Card -->
<x-admin-stat-card
    title="Total Orders"
    stat="1,289"
    trend="+8% from last week"
    icon="shopping-cart"
    iconBg="green"
>
</x-admin-stat-card>

<!-- Products Stat Card -->
<x-admin-stat-card
    title="Active Products"
    stat="456"
    trend="+5 new products"
    icon="box"
    iconBg="orange"
>
</x-admin-stat-card>

<!-- Customers Stat Card -->
<x-admin-stat-card
    title="Total Customers"
    stat="2,456"
    trend="+23% growth"
    icon="users"
    iconBg="purple"
>
</x-admin-stat-card>

<!-- With Slot Content -->
<x-admin-stat-card
    title="Conversion Rate"
    stat="3.24%"
    icon="chart-line"
>
    <div class="mt-4">
        <x-admin-progress-bar color="green" :percentage="32" label="Target: 5%" />
    </div>
</x-admin-stat-card>

<!-- Grid of Stat Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <x-admin-stat-card title="Revenue" stat="$45K" icon="dollar-sign" iconBg="blue" />
    <x-admin-stat-card title="Orders" stat="1,289" icon="shopping-cart" iconBg="green" />
    <x-admin-stat-card title="Products" stat="456" icon="box" iconBg="orange" />
    <x-admin-stat-card title="Customers" stat="2,456" icon="users" iconBg="purple" />
</div>
```

---

## 📈 Progress Bar Component

### Basic Usage

```blade
<x-admin-progress-bar color="blue" percentage="75" />
```

### Properties

| Property         | Type    | Default | Description                                          |
| ---------------- | ------- | ------- | ---------------------------------------------------- |
| `color`          | string  | `blue`  | Progress bar color: red, orange, yellow, green, blue |
| `percentage`     | number  | `50`    | Progress percentage (0-100)                          |
| `label`          | string  | `null`  | Label text (shown on left)                           |
| `showPercentage` | boolean | `true`  | Show percentage on right                             |

### Examples

```blade
<!-- Basic Progress Bar -->
<x-admin-progress-bar color="blue" percentage="75" />

<!-- With Label -->
<x-admin-progress-bar
    color="green"
    percentage="85"
    label="Completion Status"
/>

<!-- Critical (Red) -->
<x-admin-progress-bar
    color="red"
    percentage="24"
    label="Stock Level"
/>

<!-- Warning (Orange) -->
<x-admin-progress-bar
    color="orange"
    percentage="65"
    label="Inventory"
/>

<!-- Success (Green) -->
<x-admin-progress-bar
    color="green"
    percentage="92"
    label="Production"
/>

<!-- In Table -->
<tr>
    <td>Stock Level</td>
    <td>
        <x-admin-progress-bar
            color="red"
            percentage="24"
            label="24% of minimum"
        />
    </td>
</tr>

<!-- Multiple Progress Bars -->
<div class="space-y-4">
    <div>
        <p class="text-sm font-semibold mb-2">Task 1</p>
        <x-admin-progress-bar color="green" percentage="100" :showPercentage="false" />
    </div>
    <div>
        <p class="text-sm font-semibold mb-2">Task 2</p>
        <x-admin-progress-bar color="green" percentage="75" :showPercentage="false" />
    </div>
    <div>
        <p class="text-sm font-semibold mb-2">Task 3</p>
        <x-admin-progress-bar color="orange" percentage="50" :showPercentage="false" />
    </div>
    <div>
        <p class="text-sm font-semibold mb-2">Task 4</p>
        <x-admin-progress-bar color="red" percentage="25" :showPercentage="false" />
    </div>
</div>
```

---

## ⚠️ Alert Component

### Basic Usage

```blade
<x-admin-alert status="critical" title="Important">
    This is an important message
</x-admin-alert>
```

### Properties

| Property      | Type    | Default | Description                                  |
| ------------- | ------- | ------- | -------------------------------------------- |
| `status`      | string  | `info`  | Alert type: critical, warning, success, info |
| `title`       | string  | `null`  | Alert title                                  |
| `dismissible` | boolean | `false` | Show close button                            |

### Examples

```blade
<!-- Critical Alert -->
<x-admin-alert status="critical" title="Critical Alert">
    You have 8 critical items that require immediate restocking
</x-admin-alert>

<!-- Warning Alert -->
<x-admin-alert status="warning" title="Warning">
    14 items are approaching minimum stock levels
</x-admin-alert>

<!-- Success Alert -->
<x-admin-alert status="success" title="Success">
    200 units received and added to inventory
</x-admin-alert>

<!-- Info Alert -->
<x-admin-alert status="info" title="Information">
    Inventory thresholds have been updated
</x-admin-alert>

<!-- Dismissible Alert -->
<x-admin-alert status="warning" title="Warning" dismissible>
    This alert can be closed by clicking the X button
</x-admin-alert>

<!-- Without Title -->
<x-admin-alert status="critical">
    Immediate action required
</x-admin-alert>
```

---

## 📋 Table Component

### Basic Usage

```blade
<x-admin-table title="Products">
    <thead>
        <tr>
            <th>Product Name</th>
            <th>Stock</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Product 1</td>
            <td>100</td>
        </tr>
    </tbody>
</x-admin-table>
```

### Properties

| Property    | Type    | Default | Description            |
| ----------- | ------- | ------- | ---------------------- |
| `title`     | string  | `null`  | Table title/header     |
| `striped`   | boolean | `true`  | Striped row styling    |
| `hoverable` | boolean | `true`  | Highlight row on hover |

### Examples

```blade
<!-- Basic Table -->
<x-admin-table title="Low Stock Products">
    <thead>
        <tr>
            <th>Status</th>
            <th>Product</th>
            <th>Stock</th>
            <th>Minimum</th>
        </tr>
    </thead>
    <tbody>
        <tr class="bg-red-50 border-l-4 border-red-600">
            <td><x-admin-badge variant="critical" animated>CRITICAL</x-admin-badge></td>
            <td>Headphones</td>
            <td>12</td>
            <td>50</td>
        </tr>
        <tr class="bg-orange-50 border-l-4 border-orange-500">
            <td><x-admin-badge variant="warning">WARNING</x-admin-badge></td>
            <td>T-Shirt</td>
            <td>125</td>
            <td>150</td>
        </tr>
    </tbody>
</x-admin-table>

<!-- Table with Progress Bars -->
<x-admin-table>
    <thead>
        <tr>
            <th>Product</th>
            <th>Stock Level</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Product 1</td>
            <td>
                <x-admin-progress-bar color="red" percentage="24" />
            </td>
        </tr>
        <tr>
            <td>Product 2</td>
            <td>
                <x-admin-progress-bar color="orange" percentage="65" />
            </td>
        </tr>
    </tbody>
</x-admin-table>

<!-- Table with Actions -->
<x-admin-table title="Products">
    <thead>
        <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Product 1</td>
            <td>$99.99</td>
            <td>
                <div class="flex gap-2">
                    <x-admin-button variant="primary" size="sm">Edit</x-admin-button>
                    <x-admin-button variant="danger" size="sm">Delete</x-admin-button>
                </div>
            </td>
        </tr>
    </tbody>
</x-admin-table>
```

---

## 🎨 Header Component

### Basic Usage

```blade
<x-admin-header
    title="Low Stock Alerts"
    subtitle="Monitor inventory levels"
    icon="exclamation-triangle"
>
</x-admin-header>
```

### Properties

| Property     | Type   | Default  | Description                                      |
| ------------ | ------ | -------- | ------------------------------------------------ |
| `title`      | string | `null`   | Page title                                       |
| `subtitle`   | string | `null`   | Page subtitle                                    |
| `icon`       | string | `null`   | Font Awesome icon                                |
| `background` | string | `orange` | Gradient color: orange, blue, green, red, purple |

### Examples

```blade
<!-- Low Stock Alerts Header -->
<x-admin-header
    title="Low Stock Alerts"
    subtitle="Monitor inventory levels & manage restocking"
    icon="exclamation-triangle"
    background="orange"
>
    <div class="flex gap-2">
        <x-admin-button variant="secondary" size="md">Settings</x-admin-button>
        <x-admin-button variant="warning" size="md" icon="download">Export</x-admin-button>
    </div>
</x-admin-header>

<!-- Revenue Analytics Header -->
<x-admin-header
    title="Revenue Analytics"
    subtitle="Financial performance overview"
    icon="chart-line"
    background="blue"
>
    <x-admin-button icon="calendar">Date Range</x-admin-button>
</x-admin-header>

<!-- Products Header -->
<x-admin-header
    title="Products"
    subtitle="Manage your product catalog"
    icon="box"
    background="green"
>
    <x-admin-button variant="primary">Add Product</x-admin-button>
</x-admin-header>
```

---

## 📝 Form Components

### Input

```blade
<x-admin-input
    label="Product Name"
    name="product_name"
    required
/>
```

### Select

```blade
<x-admin-select
    label="Category"
    name="category"
    :options="['electronics' => 'Electronics', 'fashion' => 'Fashion']"
/>
```

### Examples

```blade
<!-- Form with Components -->
<form method="POST" action="/products">
    @csrf
    <div class="space-y-4">
        <x-admin-input
            label="Product Name"
            name="name"
            required
        />

        <x-admin-select
            label="Category"
            name="category"
            required
            :options="[
                '' => 'Select Category',
                'electronics' => 'Electronics',
                'fashion' => 'Fashion',
                'home' => 'Home & Garden',
                'sports' => 'Sports'
            ]"
        />

        <x-admin-input
            label="Stock Quantity"
            name="stock"
            type="number"
            required
        />

        <div class="flex gap-2 pt-4">
            <x-admin-button variant="primary" type="submit">Save Product</x-admin-button>
            <x-admin-button variant="secondary">Cancel</x-admin-button>
        </div>
    </div>
</form>
```

---

## 🎯 Complete Page Example

```blade
@extends('admin.layout.admin')

@section('content')
    <!-- Header -->
    <x-admin-header
        title="Low Stock Alerts"
        subtitle="Monitor inventory levels & manage restocking"
        icon="exclamation-triangle"
        background="orange"
    >
        <div class="flex gap-2">
            <x-admin-button variant="secondary">Settings</x-admin-button>
            <x-admin-button variant="warning" icon="download">Export</x-admin-button>
        </div>
    </x-admin-header>

    <div class="max-w-7xl mx-auto px-6 py-8">
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <x-admin-metric-card count="8" label="Critical Items" variant="red" />
            <x-admin-metric-card count="14" label="Warning Items" variant="orange" />
            <x-admin-metric-card count="6" label="Low Items" variant="yellow" />
            <x-admin-metric-card count="28" label="Total Watched" variant="blue" />
        </div>

        <!-- Alert Message -->
        <x-admin-alert status="critical" title="Action Required" dismissible>
            You have 8 critical items that require immediate restocking
        </x-admin-alert>

        <!-- Data Table -->
        <x-admin-table title="Low Stock Products" class="mt-6">
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Product</th>
                    <th>Current</th>
                    <th>Minimum</th>
                    <th>Stock Level</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr class="bg-red-50 border-l-4 border-red-600">
                    <td><x-admin-badge variant="critical" animated>CRITICAL</x-admin-badge></td>
                    <td>Premium Headphones</td>
                    <td>12 units</td>
                    <td>50 units</td>
                    <td><x-admin-progress-bar color="red" percentage="24" /></td>
                    <td><x-admin-button variant="danger" size="sm">Restock Now</x-admin-button></td>
                </tr>
                <tr class="bg-orange-50 border-l-4 border-orange-500">
                    <td><x-admin-badge variant="warning">WARNING</x-admin-badge></td>
                    <td>T-Shirt Bundle</td>
                    <td>125 units</td>
                    <td>150 units</td>
                    <td><x-admin-progress-bar color="orange" percentage="83" /></td>
                    <td><x-admin-button variant="warning" size="sm">Schedule</x-admin-button></td>
                </tr>
            </tbody>
        </x-admin-table>
    </div>
@endsection
```

---

## 🎨 Component Combinations

### Alert + Button Group

```blade
<x-admin-alert status="warning" title="Batch Action">
    <div class="mt-4 flex gap-2">
        <x-admin-button variant="warning">Confirm</x-admin-button>
        <x-admin-button variant="secondary">Cancel</x-admin-button>
    </div>
</x-admin-alert>
```

### Card + Stat Card

```blade
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <x-admin-stat-card title="Revenue" stat="$45K" icon="dollar-sign" />
    <x-admin-stat-card title="Orders" stat="1,289" icon="shopping-cart" />
    <x-admin-stat-card title="Customers" stat="2,456" icon="users" />
</div>
```

### Table + Badges + Progress

```blade
<table>
    <tbody>
        <tr>
            <td><x-admin-badge variant="critical" animated>URGENT</x-admin-badge></td>
            <td><x-admin-progress-bar color="red" percentage="24" /></td>
            <td><x-admin-button variant="danger" size="sm">Act</x-admin-button></td>
        </tr>
    </tbody>
</table>
```

---

## ✅ Best Practices

1. **Always use components** - Never style buttons/badges inline
2. **Consistent variants** - Use same color variants across pages
3. **Responsive grids** - Use `grid-cols-1 md:grid-cols-2 lg:grid-cols-4`
4. **Proper spacing** - Use gap classes for consistency
5. **Accessible icons** - Pair icons with text labels
6. **Form validation** - Use error props on inputs
7. **Consistent grouping** - Group related buttons with `flex gap-2`

---

**Last Updated**: January 29, 2026
