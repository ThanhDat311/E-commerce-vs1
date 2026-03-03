# System Workflows

This document outlines the logical flows for key operations within the E-commerce system.

## 1. Checkout Workflow
1. **Cart Selection**: User adds items to the cart.
2. **Deal Application**: `PriceCalculatorService` applies the best available deals.
3. **Checkout Initiation**: User proceeds to checkout, selects an address and payment method.
4. **Validation**: System verifies stock levels and deal validity.
5. **Payment**: User is redirected to the payment gateway (e.g., VNPay).
6. **Order Creation**: Upon successful payment, an `Order` and `OrderItems` are created, locking in the price and discounts.
7. **Fulfillment**: Vendor/Staff are notified to process the order.

## 2. Deal Approval Workflow
1. **Creation**: Vendor creates a deal via the Vendor Dashboard (status defaults to `pending`).
2. **Notification**: Admin is notified of a new deal request.
3. **Review**: Admin reviews high-level deal parameters and eligibility.
4. **Action**: Admin either Approves (status becomes `active`) or Rejects the deal.

## 3. Risk Assessment Workflow
1. **Activity Trigger**: A user performs a sensitive action (e.g., placing a large order).
2. **Feature Extraction**: Current activity is saved to `AiFeatureStore`.
3. **Rule Evaluation**: `RiskRule` set is checked against the user's `UserBehaviorProfile`.
4. **Flagging**: If a threshold is met, the activity is flagged for manual review in the `AiDashboard`.

## 4. Support Ticket Workflow
1. **Inquiry**: Customer submits a ticket via "My Tickets".
2. **Assignment**: Ticket becomes visible in the Admin/Staff support queue.
3. **Response**: Staff replies, and the ticket status updates.
4. **Resolution**: Once resolved, the ticket is marked as "Closed" by either the customer or staff.
