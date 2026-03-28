# 🧠 TASK: Implement Mobile Payment System (Verification-Based) in SIG-RECETTE

## 🎯 Objective

Extend the existing SIG-RECETTE Laravel application to support **mobile money payments** using a **verification-based architecture (no central server)**.

⚠️ IMPORTANT:
- The system already supports **cash payments**
- We are ONLY adding **mobile payment as an additional method**
- Existing functionality MUST NOT be broken

---

## 🧩 EXISTING SYSTEM CONTEXT

- Laravel application
- Invoice system already implemented
- Cash payments already working
- Accounting integration already exists
- Some communes operate in **low or unstable internet environments**

---

## 🧠 REQUIRED ARCHITECTURE (CRITICAL)

We are NOT using webhooks as the primary mechanism.

We are using:

👉 **Verification-based payment flow**

### Core principle:

> NEVER trust immediate API response  
> ALWAYS verify transaction status using transaction reference

---

## 🔄 PAYMENT LIFECYCLE

PENDING → VERIFYING → SUCCESS / FAILED / EXPIRED

---

## 🗄️ DATABASE REQUIREMENTS

payments table:

- id
- invoice_id
- amount
- phone_number
- provider (qosic, fedapay, paygate)
- reference (UNIQUE)
- external_id (nullable)
- status (pending, verifying, success, failed, expired)
- verification_attempts
- last_checked_at
- created_at
- updated_at

---

## 🔌 PROVIDERS

Implement:

- QOSIC
- FedaPay
- PayGate

Interface:

interface PaymentProviderInterface {
    public function initiate(array $data);
    public function verify(string $reference);
}

---

## ⚙️ FLOW

1. Create payment (pending)
2. Call provider
3. DO NOT trust response
4. Dispatch verification job

---

## 🔁 VERIFICATION JOB

Retries: 5  
Backoff: 10,30,60,120,300 seconds  

Logic:
- Check payment status via provider
- Update accordingly

---

## 📩 SMS MODULE

Toggle via modules table

---

## 🔐 RULES

- Idempotency
- Unique reference (UUID)
- Logging required

---

## 📱 UI

- Add Digital Payment option
- Provider selection
- Status feedback

---

## 🚀 GOAL

Build a reliable, fault-tolerant mobile payment system without breaking existing functionality.
