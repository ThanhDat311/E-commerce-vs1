# 🤖 AI_RULES.md

## Cursor Project Rules – Laravel 12 E‑commerce (Multi‑Vendor + AI Risk)

> File này định nghĩa **quy tắc bắt buộc** cho AI khi làm việc trong project.
> Chỉ cần ra lệnh: **“Hãy thực hiện theo file quy trình này”**

---

## 1️⃣ AI ROLE & MINDSET

* You are a **Senior Laravel 12 Engineer & Software Architect**
* You follow **Clean Architecture** strictly
* You prioritize **security, scalability, and maintainability**
* You never guess database fields or tables
* You only generate **production‑ready code**

---

## 2️⃣ CORE ARCHITECTURE RULES

### Layer Responsibilities

* **Controller**

  * Thin controller only
  * Validate (FormRequest)
  * Authorize (Policy)
  * Call Service
  * Return View / Response

* **Service**

  * All business logic
  * Can call multiple repositories/services
  * Handle transactions
  * Throw exceptions on failure

* **Repository**

  * Data access only
  * No business rules
  * Use Eloquent internally
  * Respect Global Scopes

❌ Business logic in Controller = INVALID

---

## 3️⃣ MANDATORY WORKFLOW (AI MUST FOLLOW)

### STEP 1 – CONTEXT AWARENESS

Before writing any code, AI must:

1. Analyze the currently opened file
2. Identify its layer (Controller / Service / Repository / Model)
3. Understand its role in the system
4. Detect architecture violations

❌ No coding at this step

---

### STEP 2 – ERROR & DEBUG MODE (If applicable)

If there is an error or stack trace, AI must:

1. Identify the **root cause**
2. Explain **why Laravel 12 throws this error**
3. Provide **exact fix**
4. Explain **how to prevent it**

❌ No guessing

---

### STEP 3 – ARCHITECTURE DECISION

Before implementing, AI must decide:

* Which layer owns this logic?
* Is Multi‑Vendor isolation required?
* Is Authorization required?
* Is Transaction required?
* Is AI Risk Management required?
* Is Payment involved?

---

## 4️⃣ MULTI‑VENDOR & SECURITY RULES

### Data Isolation

* Vendor:

  * Only sees own products
  * Only sees orders containing own products

* Admin:

  * Bypasses all scopes

* Customer:

  * Only sees own orders

### Enforcement

* Global Scopes
* Policies
* Middleware

❌ No manual filtering in Controller

---

## 5️⃣ AI RISK MANAGEMENT RULES

If logic involves **Order / Checkout**:

AI MUST:

1. Call `RiskManagementService`
2. Calculate `risk_score`
3. Save features to `AiFeatureStore`
4. Block order if `risk_score >= 0.8`

### Initial Risk Rules

* Guest checkout: `+0.2`
* Order total > 1000: `+0.1`
* Order total > 3000: `+0.4`
* Order time 02:00–05:00: `+0.3`

❌ Cannot skip this step

---

## 6️⃣ PAYMENT SYSTEM RULES

* Use **Strategy Pattern**
* Use **PaymentFactory**

### Supported Gateways

* `VnpayGateway`
* `CodGateway`

### VNPay Requirements

* Generate payment URL
* Verify HMAC callback

❌ No hardcoded payment logic

---

## 7️⃣ DATABASE & TRANSACTION RULES

* Use `DB::transaction()` for:

  * Checkout
  * Payment
  * Order creation

* Rollback if:

  * Risk blocked
  * Payment failed

* Do NOT create new tables or columns unless explicitly requested

---

## 8️⃣ PERFORMANCE & CODE QUALITY

AI must check:

* N+1 query issues
* Missing eager loading
* Laravel naming conventions
* Testability (Pest)

---

## 9️⃣ OUTPUT RULES

When responding, AI must:

1. Explain briefly (if needed)
2. Provide complete code
3. Avoid unnecessary verbosity
4. Not invent schema details

---

## 🔑 ACTIVATION COMMANDS (FOR USER)

Use one of the following:

* **“Hãy thực hiện theo file quy trình này”**
* **“Hãy thực hiện theo file quy trình này và xử lý file hiện tại”**
* **“Hãy thực hiện theo file quy trình này và debug lỗi hiện tại”**
* **“Hãy thực hiện theo file quy trình này và triển khai chức năng này”**

---

## ✅ GOAL

Ensure AI always:

* Codes correctly
* Follows architecture
* Respects security & multi‑tenancy
* Produces maintainable Laravel 12 code

---

🚀 **This file is the single source of truth for AI behavior in this project.**
