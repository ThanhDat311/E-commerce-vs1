IMPLEMENTATION PLAN – DEALS FUNCTION

(E-commerce System – Laravel)

1️⃣ System Preparation & Requirement Confirmation
1.1 Define Scope

The Deals module allows:

Admin create and manage all deals

Staff manage limited deal actions

Vendor create own deals (require approval)

Store automatically apply active deals

Cart & Checkout validate deal before order creation

1.2 Define Deal Types

System supports:

Percentage Discount

Fixed Amount Discount

Buy X Get Y (BOGO)

Flash Sale (time limited)

2️⃣ Database Design & Migration
2.1 Create Tables
deals

Fields:

id

name

slug

description

discount_type

discount_value

start_date

end_date

usage_limit

usage_count

apply_scope (product / category / vendor / global)

vendor_id (nullable)

priority

status (draft / pending / active / expired)

created_by

approved_by

timestamps

deal_products (Pivot)

id

deal_id

product_id

special_price (optional)

timestamps

deal_categories (Optional)

id

deal_id

category_id

Update order_items

add deal_id

add discount_amount

This ensures discount is locked at purchase time.

3️⃣ Core Business Logic Development
3.1 Create DealService

Responsibilities:

Check if deal is active

Validate time range

Validate usage limit

Validate product eligibility

Main functions:

isDealActive()

isValidForProduct()

increaseUsage()

expireDeal()

3.2 Create PriceCalculatorService

Responsibilities:

Calculate discount

Apply best deal if multiple exist

Prevent negative price

Prevent double discount stacking

Main functions:

calculatePercent()

calculateFixed()

calculateBOGO()

applyBestDeal()

3.3 Conflict Handling Logic

System rules:

Only one best deal per product

Higher priority overrides lower

Vendor deal cannot override Admin global deal

3.4 Auto Expire Mechanism

Create Scheduler (Laravel Cron)

Automatically update expired deals

Update status to expired

4️⃣ Admin Panel Implementation
4.1 Deal Management CRUD

Admin can:

Create deal

Edit deal

Delete deal

Activate / Deactivate deal

Approve vendor deal

Set priority

Validation:

End date must be greater than start date

Discount value must be positive

Usage limit cannot be negative

4.2 Assign Products / Categories

Admin can:

Assign specific products

Assign categories

Assign global scope

Bulk assign products

4.3 Deal Monitoring

Admin dashboard displays:

Total usage count

Total revenue generated

Total discount amount

Status (Active / Pending / Expired)

Export report to CSV.

5️⃣ Staff Panel Implementation

Staff has limited permissions:

Staff can:

View deals

Edit description & banner

Activate / Deactivate deal (if permitted)

Assign products (if allowed)

Staff cannot:

Delete deal

Approve vendor deal

Modify priority

Role-based middleware must be applied.

6️⃣ Vendor Panel Implementation
6.1 Vendor Deal Creation

Vendor can:

Create deal

Edit own deal

Delete own draft deal

Rules:

Status default = pending

Cannot activate directly

Cannot assign other vendor products

Cannot create global deal

6.2 Admin Approval Workflow

Process:

Vendor creates deal

Status = pending

Admin reviews

Admin approve → status = active

7️⃣ Store (Frontend) Integration
7.1 Product Listing Page

System must:

Show original price

Show discounted price

Show SALE badge

Show discount percentage

Allow filter "On Sale"

Sort by discount level

7.2 Product Detail Page

Display:

Deal description

Countdown timer (Flash Sale)

Quantity remaining (if limited)

Bundle information (if BOGO)

7.3 Cart Page

When product added:

System fetch active deals

Apply best deal

Show discount breakdown

Show total saved

Re-validate on quantity change

7.4 Checkout Validation

Before order creation:

Validate deal still active

Validate usage limit not exceeded

Lock final price

Save deal_id into order_items

8️⃣ Security & Data Integrity

System must ensure:

Server-side price calculation only

Prevent price tampering from frontend

Validate vendor ownership

Prevent negative price

Prevent deal stacking abuse

Lock price at order time

9️⃣ Testing Phase
9.1 Unit Testing

Test:

Percent calculation

Fixed discount calculation

BOGO logic

Priority conflict logic

Expire logic

9.2 Feature Testing

Test scenarios:

Deal applied in cart

Deal expired during checkout

Vendor cannot edit other vendor deal

Staff permission validation

Usage limit reached case

10️⃣ Optimization & Enhancement

Optional advanced improvements:

Flash Sale section like Shopee

Real-time countdown

Analytics dashboard

Push notification when deal starts

Highlight best deal automatically

11️⃣ Final Validation & Deployment

Before production:

Run migration check

Clear cache

Test scheduler

Review role permission

Perform full purchase flow testing

Conclusion

This implementation plan ensures:

Clear separation between Admin, Staff, Vendor

Secure discount calculation

Full integration from backend to store

Prevent revenue loss due to logic errors

Scalable structure for future expansion