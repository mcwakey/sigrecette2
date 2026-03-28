# SIG-RECETTE: Complete Laravel Project Setup & Implementation Guide

**Version:** 1.0  
**Date:** 2026-03-28  
**Project Type:** Tax Collection & Payment Synchronization System (Laravel 10)

---

## 📋 Table of Contents

1. [Project Overview](#project-overview)
2. [System Architecture](#system-architecture)
3. [Prerequisites & Installation](#prerequisites--installation)
4. [Environment Configuration](#environment-configuration)
5. [Database Setup](#database-setup)
6. [Application Configuration](#application-configuration)
7. [Synchronization System](#synchronization-system)
8. [API Endpoints](#api-endpoints)
9. [Job Scheduling](#job-scheduling)
10. [Deployment & Production](#deployment--production)
11. [Monitoring & Troubleshooting](#monitoring--troubleshooting)

---

## 🎯 Project Overview

### What is SIG-RECETTE?

SIG-RECETTE is a Laravel-based tax collection management system that:

- **Manages tax invoices** locally with detailed line items and taxpayer information
- **Synchronizes payment data** with a remote payment collection system (Mobile Money, Bank Cards, etc.)
- **Tracks payment statuses** (OWING, PART PAID, PAID) with real-time updates
- **Provides REST API endpoints** for secure data exchange between systems
- **Implements batch synchronization** with idempotence guarantees and comprehensive logging

### Key Entities

| Entity | Purpose | Direction |
|--------|---------|-----------|
| **Invoice** | Tax bills to be paid | Local → Remote (export), Remote → Local (status updates) |
| **Payment** | Received payments | Remote → Local (primary source of truth) |
| **InvoiceItem** | Line items in invoices | Local → Remote (read-only) |
| **Taxpayer** | Entity responsible for payment | Local → Remote (identification) |
| **TaxpayerTaxable** | Link between taxpayer and taxable items | Local → Remote (context) |

---

## 🏗️ System Architecture

### High-Level Flow

```
┌─────────────────────────────────────────────────────────────────┐
│                     LOCAL SYSTEM (SIG-RECETTE)                  │
├─────────────────────────────────────────────────────────────────┤
│                                                                   │
│  ┌──────────────────┐              ┌──────────────────┐          │
│  │   Invoice Mgmt   │              │  Payment Import  │          │
│  │   - Create       │              │  - Via API       │          │
│  │   - Track Status │              │  - Batch Process │          │
│  └────────┬─────────┘              └────────┬─────────┘          │
│           │                                 │                    │
│           └─────────────┬───────────────────┘                    │
│                         │                                         │
│                    ┌────▼────────┐                               │
│                    │  Queue Jobs  │                               │
│                    │ - Export Inv │                               │
│                    │ - Import Pay │                               │
│                    └────┬────────┘                               │
│                         │                                         │
└─────────────────────────┼─────────────────────────────────────────┘
                          │
                   HTTP API (Sanctum Auth)
                          │
┌─────────────────────────┼─────────────────────────────────────────┐
│              REMOTE SYSTEM (Payment Collection)                    │
├─────────────────────────┼─────────────────────────────────────────┤
│                         │                                          │
│  ┌──────────────────────▼──────────────┐                         │
│  │  Mobile Money / Bank Card Processing│                         │
│  │  - Accept Payments                  │                         │
│  │  - Send Back Status Updates         │                         │
│  └─────────────────────────────────────┘                         │
│                                                                   │
└─────────────────────────────────────────────────────────────────┘
```

### Data Synchronization Model

**Batch-based, not real-time** — Every 10 minutes (configurable):
1. **Export Phase**: Local system exports invoices to remote
2. **Payment Phase**: Remote processes payments
3. **Import Phase**: Remote sends back payment records
4. **Update Phase**: Local recalculates invoice statuses

---

## 📦 Prerequisites & Installation

### System Requirements

| Component | Version | Notes |
|-----------|---------|-------|
| **PHP** | 8.0.2+ | Required extensions: gd, intl, zip, xml, mbstring, curl, bcmath |
| **Laravel** | 10.0+ | Latest LTS version |
| **MySQL/MariaDB** | 5.7+ | Or PostgreSQL 9.6+ |
| **Composer** | 2.0+ | Dependency manager |
| **Node.js** | 16+ | For asset compilation |
| **Apache/Nginx** | Latest | Web server |

### Step 1: Clone the Repository

```bash
# Via Git (if available)
cd /var/www
git clone https://github.com/your-org/sigrecette.git
cd sigrecette

# Via ZIP (alternative)
cd /var/www
wget https://example.com/sigrecette.zip
unzip sigrecette.zip
cd sigrecette
```

### Step 2: Install PHP Dependencies

```bash
composer install --no-interaction --prefer-dist
```

**If composer.lock exists:**
```bash
composer install  # Uses locked versions for consistency
```

**For development:**
```bash
composer install --dev
```

### Step 3: Install Node Dependencies

```bash
npm install
# or
npm ci  # Uses package-lock.json for consistency
```

### Step 4: Generate Application Key

```bash
php artisan key:generate
```

This command:
- Creates a unique `APP_KEY` in `.env`
- Used for encrypting cookies, sessions, and sensitive data

---

## 🔧 Environment Configuration

### Step 1: Copy Environment File

```bash
cp .env.example .env
```

### Step 2: Configure .env File

Edit `.env` with your specific settings:

```env
################################################################################
# APPLICATION
################################################################################
APP_NAME="SIG-RECETTE"
APP_ENV=production  # Set to 'local' for development
APP_KEY=base64:YOUR_GENERATED_KEY  # Generated by key:generate
APP_DEBUG=false     # Set to true only in development!
APP_URL=https://example.com

################################################################################
# DATABASE
################################################################################
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sigrecette_db
DB_USERNAME=sigrecette_user
DB_PASSWORD=your_secure_password

# Database connection optimization
DB_POOL=5
DB_TIMEOUT=10

################################################################################
# CACHE & SESSION
################################################################################
CACHE_DRIVER=redis      # or 'file', 'memcached'
SESSION_DRIVER=cookie   # or 'database'
QUEUE_CONNECTION=redis  # or 'database', 'sync' for development

# Redis configuration (if using Redis)
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

################################################################################
# MAIL (for notifications)
################################################################################
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME="SIG-RECETTE"

################################################################################
# SYNCHRONIZATION - CRITICAL SETTINGS
################################################################################
# Base URL of the remote payment system
SYNC_REMOTE_BASE_URL=https://bridge.example.com

# Authentication token (generated by remote system)
SYNC_REMOTE_TOKEN=your_bearer_token_here

# IP whitelist for incoming sync requests (comma-separated)
SYNC_ALLOWED_IPS=203.0.113.10,203.0.113.11

# Performance & reliability
SYNC_TIMEOUT=15              # seconds
SYNC_RETRIES=3               # number of retries on failure
SYNC_CHUNK_SIZE=200          # items per batch
SYNC_PER_PAGE=200            # items per API page

# Scheduler: Cron expressions for automated sync
SYNC_EXPORT_CRON="*/10 * * * *"  # Every 10 minutes
SYNC_IMPORT_CRON="*/10 * * * *"  # Every 10 minutes

# Logging
SYNC_LOG_CHANNEL=sync        # Uses 'storage/logs/sync.log'
SYNC_LOG_LEVEL=info          # debug, info, warning, error

################################################################################
# MONITORING & LOGGING
################################################################################
LOG_CHANNEL=stack
LOG_LEVEL=info
LOG_DEPRECATIONS_CHANNEL=null

# File logging rotation
LOG_MAX_FILES=7  # Keep 7 days of logs

################################################################################
# AUTHENTICATION (Sanctum)
################################################################################
SANCTUM_STATEFUL_DOMAINS=localhost,127.0.0.1,example.com
SANCTUM_EXPIRATION=525600  # 1 year in minutes

################################################################################
# OPTIONAL: Payment Integration
################################################################################
PAYMENT_GATEWAY_MODE=sandbox  # sandbox or production
PAYMENT_GATEWAY_KEY=your_api_key
PAYMENT_GATEWAY_SECRET=your_api_secret
```

### Critical Sync Variables Explained

| Variable | Required | Default | Purpose |
|----------|----------|---------|---------|
| `SYNC_REMOTE_BASE_URL` | ✅ Yes | - | URL of remote payment system API |
| `SYNC_REMOTE_TOKEN` | ✅ Yes | - | Bearer token for API authentication |
| `SYNC_ALLOWED_IPS` | ⚠️ Recommended | - | Security: IP whitelist for incoming requests |
| `SYNC_TIMEOUT` | ❌ No | 15s | Max time to wait for remote response |
| `SYNC_RETRIES` | ❌ No | 3 | Retry failed requests this many times |
| `SYNC_CHUNK_SIZE` | ❌ No | 200 | Items per batch during export |
| `SYNC_PER_PAGE` | ❌ No | 200 | Items per page on remote API |

---

## 🗄️ Database Setup

### Step 1: Create Database & User

**On MySQL/MariaDB:**

```bash
mysql -u root -p

CREATE DATABASE sigrecette_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'sigrecette_user'@'localhost' IDENTIFIED BY 'your_secure_password';
GRANT ALL PRIVILEGES ON sigrecette_db.* TO 'sigrecette_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

**On PostgreSQL:**

```bash
sudo -u postgres psql

CREATE DATABASE sigrecette_db ENCODING 'UTF8' LC_COLLATE 'C' LC_CTYPE 'C';
CREATE USER sigrecette_user WITH PASSWORD 'your_secure_password';
GRANT ALL PRIVILEGES ON DATABASE sigrecette_db TO sigrecette_user;
EXIT;
```

### Step 2: Run Migrations

```bash
php artisan migrate
```

This creates all necessary tables:
- `invoices` - Tax bills
- `payments` - Payment records
- `invoice_items` - Line items
- `taxpayers` - Entity information
- `taxpayer_taxables` - Taxable item links
- `sync_runs` - Synchronization audit trail
- And more...

### Step 3: Seed Initial Data (Optional)

```bash
# Seed database with default data
php artisan migrate:seed

# Or seed specific table
php artisan db:seed --class=YearSeeder
```

### Verify Database Connection

```bash
php artisan tinker
>>> DB::connection()->getDatabaseName()
=> "sigrecette_db"
>>> DB::table('users')->count()  # Should work if migrations ran successfully
```

---

## ⚙️ Application Configuration

### Step 1: Publish Configuration Files

```bash
# Publish Sanctum config
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"

# Publish Livewire assets
php artisan vendor:publish --tag=livewire:assets --force
```

### Step 2: Configure Sync Module

The sync configuration lives in `config/sync.php`. Verify it has:

```php
return [
    'remote' => [
        'base_url' => env('SYNC_REMOTE_BASE_URL'),
        'token' => env('SYNC_REMOTE_TOKEN'),
    ],
    
    'security' => [
        'allowed_ips' => explode(',', env('SYNC_ALLOWED_IPS', '')),
    ],
    
    'performance' => [
        'timeout' => env('SYNC_TIMEOUT', 15),
        'retries' => env('SYNC_RETRIES', 3),
        'chunk_size' => env('SYNC_CHUNK_SIZE', 200),
        'per_page' => env('SYNC_PER_PAGE', 200),
    ],
    
    'scheduling' => [
        'export_cron' => env('SYNC_EXPORT_CRON', '*/10 * * * *'),
        'import_cron' => env('SYNC_IMPORT_CRON', '*/10 * * * *'),
    ],
    
    'logging' => [
        'channel' => env('SYNC_LOG_CHANNEL', 'sync'),
        'level' => env('SYNC_LOG_LEVEL', 'info'),
    ],
];
```

### Step 3: Create Admin User

```bash
# Interactive command to create system admin
php artisan app:create-system-admin username youremail@example.com password

# You'll be prompted for missing values
```

### Step 4: Create Storage Symlink

```bash
php artisan storage:link
```

This allows accessing uploaded files via `/storage` URL.

### Step 5: Setup File Permissions

**On Linux/Ubuntu:**

```bash
# Set ownership
sudo chown -R www-data:www-data /var/www/sigrecette

# Set permissions
chmod -R 755 /var/www/sigrecette
chmod -R 775 storage bootstrap/cache
```

**On macOS (development):**

```bash
chmod -R 755 storage bootstrap/cache
```

---

## 🔄 Synchronization System

### Overview

The sync system is built on:
- **REST API endpoints** for data exchange
- **Laravel Sanctum** for token-based authentication
- **Queue jobs** for async batch processing
- **Audit trail** in `sync_runs` table

### Architecture

```
┌──────────────────────┐
│  Scheduler (Kernel)  │
│  Every 10 minutes    │
└──────────┬───────────┘
           │
    ┌──────┴──────┐
    │             │
┌───▼────┐  ┌────▼───┐
│ Export  │  │ Import │
│ Job     │  │ Job    │
└───┬────┘  └────┬───┘
    │            │
┌───▼────────────▼───┐
│   Local Database   │
│                    │
│ ┌──────────────┐   │
│ │ sync_runs    │   │
│ │ (audit log)  │   │
│ └──────────────┘   │
└────────────────────┘
```

### How It Works

#### Export Phase (Local → Remote)

1. **SyncExportInvoicesJob** triggers (every 10 min)
2. Fetches invoices from active year:
   - Status: APPROVED
   - Validity: VALID
3. Chunks into batches (200 items max)
4. Sends to `{SYNC_REMOTE_BASE_URL}/api/v1/sync/invoices`
5. Logs result in `sync_runs` table

**What gets exported:**
- Invoice UUID, number, amount
- Invoice status (pay_status, status)
- Taxpayer ID and contact details
- Invoice line items (if requested)

#### Import Phase (Remote → Local)

1. **SyncImportPaymentsJob** triggers (every 10 min)
2. Calls remote API: `GET /v1/sync/payments`
3. Receives payment records (idempotent by UUID)
4. **For each payment:**
   - If UUID exists → Update
   - If UUID new → Create
   - Update linked invoice's `pay_status`
5. Logs metrics in `sync_runs` table

**Payment statuses accepted:**
- `PENDING` - Awaiting confirmation
- `DONE` - Confirmed/received
- `ACCOUNTED` - Recorded in accounting
- `CANCELED` - Transaction voided

**Payment types accepted:**
- `CASH` - Cash payment
- `CHEQUE` - Check payment
- `DIGI` - Digital payment (Mobile Money, Card, etc.)

### Authentication

All API endpoints use **Laravel Sanctum** token authentication:

```http
GET /api/v1/sync/invoices HTTP/1.1
Authorization: Bearer your_token_here
Accept: application/json
```

**Token generation (for admins):**

```bash
php artisan tinker
>>> $token = App\Models\User::find(1)->createToken('api-token')->plainTextToken
=> "1|abc123xyz789..."
```

---

## 📡 API Endpoints

### Base URL

```
https://example.com/api/v1/sync
```

### 1. Export Invoices (GET)

**Endpoint:** `GET /v1/sync/invoices`

**Purpose:** Remote system fetches invoices to display for payment

**Parameters:**

| Param | Type | Required | Example |
|-------|------|----------|---------|
| `updated_since` | ISO 8601 | No | `2026-01-01T00:00:00Z` |
| `page` | Integer | No | `1` (default) |
| `per_page` | Integer (max 200) | No | `50` (default) |
| `include` | Comma-separated | No | `taxpayer,items` |

**Example Request:**

```bash
curl -X GET "https://example.com/api/v1/sync/invoices?page=1&per_page=200&include=taxpayer,items" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

**Success Response (200):**

```json
{
  "data": [
    {
      "uuid": "3f2b8c6e-8f3a-4f71-9d2d-0d9a3c5a2b11",
      "invoice_no": "INV-2024-000123",
      "amount": 150000,
      "reduce_amount": 0,
      "qty": 1,
      "from_date": "2024-01-01",
      "to_date": "2024-12-31",
      "pay_status": "OWING",
      "status": "APPROVED",
      "type": "TITRE",
      "taxpayer_id": 100045,
      "taxpayer": {
        "id": 100045,
        "tnif": "TNIF-0045",
        "name": "SOCIETE EXEMPLE",
        "mobilephone": "+22890000000",
        "email": "contact@example.tg",
        "address": "Lomé"
      },
      "items": [
        {
          "taxpayer_taxable_id": 200011,
          "qty": 1,
          "amount": 150000,
          "ii_tariff": 150000
        }
      ],
      "created_at": "2024-01-15T10:30:00Z",
      "updated_at": "2024-01-15T10:30:00Z"
    }
  ],
  "meta": {
    "pagination": {
      "current_page": 1,
      "per_page": 200,
      "last_page": 3,
      "total": 542
    },
    "active_year": {
      "id": 1,
      "name": "2024",
      "status": "ACTIVE"
    }
  }
}
```

**Error Responses:**

```json
// 401 Unauthorized - Invalid token
{
  "message": "Unauthenticated."
}

// 403 Forbidden - IP not whitelisted
{
  "message": "Access denied. Your IP is not authorized."
}

// 422 Unprocessable Entity - Invalid parameters
{
  "message": "The given data was invalid.",
  "errors": {
    "per_page": ["The per page must not be greater than 200."]
  }
}
```

---

### 2. Import Payments (POST)

**Endpoint:** `POST /v1/sync/payments`

**Purpose:** Remote system sends payment records to local

**Request Body:**

```json
{
  "payments": [
    {
      "uuid": "9a7a4f20-1d72-4b16-bad0-2b1d0e7a8f51",
      "invoice_uuid": "3f2b8c6e-8f3a-4f71-9d2d-0d9a3c5a2b11",
      "taxpayer_id": 100045,
      "amount": 50000,
      "payment_type": "DIGI",
      "invoice_type": "TITRE",
      "reference": "MM-TRX-88990011",
      "description": "Paiement Mobile Money",
      "remaining_amount": 100000,
      "status": "DONE",
      "deposit": null,
      "notes": "Paiement partiel"
    }
  ]
}
```

**Field Descriptions:**

| Field | Type | Required | Notes |
|-------|------|----------|-------|
| `uuid` | UUID | ✅ | Unique payment ID (idempotent key) |
| `invoice_uuid` | UUID | ✅ | Links to Invoice.uuid |
| `taxpayer_id` | Integer | ✅ | References Taxpayer.id |
| `amount` | Number | ✅ | Amount paid |
| `payment_type` | Enum | ✅ | CASH, CHEQUE, DIGI |
| `invoice_type` | String | ✅ | Invoice type (TITRE, etc.) |
| `reference` | String | ✅ | Payment reference (MM-TRX-xxx) |
| `description` | String | No | Payment description |
| `remaining_amount` | Number | No | Amount still outstanding |
| `status` | Enum | ✅ | PENDING, DONE, ACCOUNTED, CANCELED |
| `deposit` | String | No | Deposit reference |
| `notes` | String | No | Additional notes |

**Example Request:**

```bash
curl -X POST "https://example.com/api/v1/sync/payments" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "payments": [
      {
        "uuid": "9a7a4f20-1d72-4b16-bad0-2b1d0e7a8f51",
        "invoice_uuid": "3f2b8c6e-8f3a-4f71-9d2d-0d9a3c5a2b11",
        "taxpayer_id": 100045,
        "amount": 50000,
        "payment_type": "DIGI",
        "invoice_type": "TITRE",
        "reference": "MM-TRX-88990011",
        "status": "DONE"
      }
    ]
  }'
```

**Success Response (200):**

```json
{
  "created": 1,
  "updated": 0,
  "skipped": 0,
  "errors": []
}
```

**Error Response (422 - Validation):**

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "payments.0.uuid": ["The uuid field is required."],
    "payments.0.amount": ["The amount must be numeric."]
  }
}
```

---

### 3. Export Payments (GET)

**Endpoint:** `GET /v1/sync/payments`

**Purpose:** Remote system queries payment status (optional, for reconciliation)

**Parameters:**

| Param | Type | Example |
|-------|------|---------|
| `updated_since` | ISO 8601 | `2026-01-01T00:00:00Z` |
| `status` | Enum | `DONE`, `PENDING` |
| `page` | Integer | `1` |
| `per_page` | Integer (max 200) | `50` |

**Example Request:**

```bash
curl -X GET "https://example.com/api/v1/sync/payments?status=DONE&page=1&per_page=100" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

**Success Response (200):**

```json
{
  "data": [
    {
      "uuid": "9a7a4f20-1d72-4b16-bad0-2b1d0e7a8f51",
      "invoice_uuid": "3f2b8c6e-8f3a-4f71-9d2d-0d9a3c5a2b11",
      "amount": 50000,
      "payment_type": "DIGI",
      "status": "DONE",
      "reference": "MM-TRX-88990011",
      "created_at": "2026-01-15T14:30:00Z"
    }
  ],
  "meta": {
    "pagination": {
      "current_page": 1,
      "per_page": 100,
      "last_page": 5,
      "total": 432
    }
  }
}
```

---

## 🕐 Job Scheduling

### How Scheduling Works

The scheduler runs the queue worker continuously, executing jobs at configured times.

### Step 1: Configure in `app/Console/Kernel.php`

```php
protected function schedule(Schedule $schedule)
{
    // Export invoices every 10 minutes
    $schedule->job(new SyncExportInvoicesJob)
        ->cron(config('sync.scheduling.export_cron'))
        ->name('sync:export-invoices')
        ->onOneServer()
        ->withoutOverlapping();

    // Import payments every 10 minutes  
    $schedule->job(new SyncImportPaymentsJob)
        ->cron(config('sync.scheduling.import_cron'))
        ->name('sync:import-payments')
        ->onOneServer()
        ->withoutOverlapping();
}
```

### Cron Expression Examples

| Expression | Meaning |
|-----------|---------|
| `*/5 * * * *` | Every 5 minutes |
| `*/10 * * * *` | Every 10 minutes (default) |
| `0 * * * *` | Every hour |
| `0 0 * * *` | Daily at midnight |
| `0 2 * * *` | Daily at 2 AM |
| `0 0 * * 0` | Weekly on Sunday |

### Step 2: Start the Queue Worker

**Development:**

```bash
php artisan queue:listen --tries=1
```

This listens for jobs and processes them immediately.

**Production (with supervisor):**

Create `/etc/supervisor/conf.d/laravel-worker.conf`:

```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/sigrecette/artisan queue:work --queue=default --tries=3 --timeout=900
autostart=true
autorestart=true
numprocs=4
redirect_stderr=true
stdout_logfile=/var/log/supervisor/laravel-worker.log
```

Then:

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-worker:*
```

### Step 3: Verify Scheduling

```bash
# Check scheduled jobs
php artisan schedule:list

# Run the scheduler for testing (runs scheduled jobs for 1 minute)
php artisan schedule:run

# See what would run
php artisan schedule:test
```

### Monitoring Jobs

```bash
# View job history
php artisan queue:failed

# View pending jobs
php artisan queue:monitor

# Retry failed jobs
php artisan queue:retry all
```

---

## 🚀 Deployment & Production

### Pre-Deployment Checklist

- [ ] Database migrated and seeded
- [ ] Environment variables configured (especially sync tokens)
- [ ] Storage directory is writable by web server
- [ ] Composer dependencies installed with `--no-dev`
- [ ] Cache cleared: `php artisan cache:clear`
- [ ] Config cached: `php artisan config:cache`
- [ ] Routes cached: `php artisan route:cache`
- [ ] Logs rotation configured
- [ ] Backup strategy in place
- [ ] SSL certificate installed
- [ ] Firewall rules configured

### Step 1: Optimize Application

```bash
# Cache configuration
php artisan config:cache

# Cache routes for faster routing
php artisan route:cache

# Cache views
php artisan view:cache

# Install composer without dev dependencies
composer install --no-dev --optimize-autoloader

# Clear any stale caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Step 2: Apache Configuration

Create `/etc/apache2/sites-available/sigrecette.conf`:

```apache
<VirtualHost *:80>
    ServerName example.com
    ServerAlias www.example.com
    ServerAdmin admin@example.com
    
    DocumentRoot /var/www/sigrecette/public
    
    <Directory /var/www/sigrecette/public>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
        
        <IfModule mod_rewrite.c>
            RewriteEngine On
            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteCond %{REQUEST_FILENAME} !-d
            RewriteRule ^ index.php [QSA,L]
        </IfModule>
    </Directory>
    
    <FilesMatch \.php$>
        SetHandler "proxy:unix:/run/php/php8.0-fpm.sock|fcgi://localhost"
    </FilesMatch>
    
    ErrorLog ${APACHE_LOG_DIR}/sigrecette-error.log
    CustomLog ${APACHE_LOG_DIR}/sigrecette-access.log combined
    
    # Expire headers
    <IfModule mod_expires.c>
        ExpiresActive On
        ExpiresByType text/css "access plus 1 year"
        ExpiresByType application/javascript "access plus 1 year"
        ExpiresByType image/* "access plus 1 month"
    </IfModule>
    
    # Gzip compression
    <IfModule mod_deflate.c>
        AddOutputFilterByType DEFLATE text/html text/plain text/css text/javascript application/javascript application/json
    </IfModule>
</VirtualHost>

# HTTPS Redirect (after setting up SSL)
<VirtualHost *:443>
    ServerName example.com
    ServerAlias www.example.com
    
    SSLEngine on
    SSLCertificateFile /etc/ssl/certs/your-cert.crt
    SSLCertificateKeyFile /etc/ssl/private/your-key.key
    SSLCertificateChainFile /etc/ssl/certs/your-chain.crt
    
    # ... same config as above ...
</VirtualHost>

# Redirect HTTP to HTTPS
<VirtualHost *:80>
    ServerName example.com
    ServerAlias www.example.com
    Redirect permanent / https://example.com/
</VirtualHost>
```

Enable the site:

```bash
sudo a2ensite sigrecette.conf
sudo a2enmod rewrite
sudo a2enmod headers
sudo a2enmod expires
sudo a2enmod deflate
sudo a2enmod proxy_fcgi
sudo systemctl restart apache2
```

### Step 3: Configure Log Rotation

Create `/etc/logrotate.d/sigrecette`:

```
/var/www/sigrecette/storage/logs/*.log {
    daily
    missingok
    rotate 7
    compress
    delaycompress
    notifempty
    create 0640 www-data www-data
    sharedscripts
    postrotate
        systemctl reload php8.0-fpm > /dev/null 2>&1 || true
    endscript
}
```

### Step 4: Backup Strategy

```bash
# Create daily backup script at /usr/local/bin/backup-sigrecette.sh
#!/bin/bash

BACKUP_DIR="/var/backups/sigrecette"
DATE=$(date +\%Y\%m\%d)
DB_NAME="sigrecette_db"
DB_USER="sigrecette_user"

mkdir -p $BACKUP_DIR

# Database backup
mysqldump -u$DB_USER -p$DB_PASSWORD $DB_NAME | gzip > $BACKUP_DIR/db-$DATE.sql.gz

# Application files
tar -czf $BACKUP_DIR/app-$DATE.tar.gz /var/www/sigrecette --exclude=node_modules --exclude=vendor

# Keep only last 7 days
find $BACKUP_DIR -name "*.gz" -mtime +7 -delete

echo "Backup completed: $DATE"
```

Add to crontab:

```bash
0 2 * * * /usr/local/bin/backup-sigrecette.sh
```

---

## 🔍 Monitoring & Troubleshooting

### View Sync Logs

```bash
# Follow real-time logs
tail -f storage/logs/sync.log

# Last 50 lines
tail -50 storage/logs/sync.log

# Search for errors
grep ERROR storage/logs/sync.log

# View full application logs
tail -f storage/logs/laravel.log
```

### Check Sync Run History

```bash
php artisan tinker

# View recent syncs
>>> App\Models\SyncRun::latest()->take(10)->get()

# View failed syncs
>>> App\Models\SyncRun::where('status', 'failed')->get()

# View sync metrics
>>> App\Models\SyncRun::where('status', 'success')
    ->where('created_at', '>', now()->subDay())
    ->sum('processed')
```

### Monitor Queue Jobs

```bash
# View pending jobs
php artisan queue:monitor

# View failed jobs
php artisan queue:failed

# Retry failed job
php artisan queue:retry <id>

# Retry all failed jobs
php artisan queue:retry all
```

### Common Issues & Solutions

#### Issue: "Access denied. Your IP is not authorized."

**Cause:** Requesting IP not in `SYNC_ALLOWED_IPS` whitelist

**Solution:**
```bash
# Check current IP
curl ifconfig.me

# Update .env
SYNC_ALLOWED_IPS=203.0.113.10,203.0.113.11,YOUR_IP

# Restart scheduler
php artisan schedule:clear-cache
```

#### Issue: Scheduled jobs not running

**Solution:**
```bash
# Verify scheduler is running
ps aux | grep schedule

# Force-start scheduler
php artisan schedule:run

# Check supervisor status
sudo supervisorctl status

# Restart supervisor
sudo supervisorctl restart all
```

#### Issue: Database connection timeout

**Solution:**
```bash
# Check DB connection
php artisan tinker
>>> DB::connection()->getPdo()

# Increase timeout in .env
DB_TIMEOUT=30

# Verify MySQL is running
sudo systemctl status mysql
```

#### Issue: Payment import fails silently

**Solution:**
```bash
# Check sync logs
tail -100 storage/logs/sync.log

# View error details
php artisan tinker
>>> App\Models\SyncRun::latest()->first()->error_sample

# Re-test import manually
>>> App\Jobs\SyncImportPaymentsJob::dispatch()
```

### Performance Optimization

```bash
# Enable Redis caching
# Update .env
CACHE_DRIVER=redis

# Cache warm-up
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Database indexing check
php artisan tinker
>>> DB::select("SHOW INDEXES FROM invoices")
```

### Security Checklist

- [ ] `APP_DEBUG=false` in production
- [ ] Strong `APP_KEY` (auto-generated)
- [ ] Database password is complex
- [ ] `SYNC_ALLOWED_IPS` is restrictive
- [ ] HTTPS/SSL enabled
- [ ] API tokens rotated regularly
- [ ] Logs don't contain sensitive data
- [ ] Backups are encrypted and remote
- [ ] Firewall restricts access appropriately

---

## 🛠️ Development Guide

### Local Development Setup

```bash
# 1. Clone and install
git clone ...
cd sigrecette
composer install
npm install

# 2. Setup environment
cp .env.example .env
php artisan key:generate

# 3. Database
php artisan migrate:seed

# 4. Run development server
# Terminal 1: Web server
php artisan serve

# Terminal 2: Queue worker
php artisan queue:listen

# Terminal 3: Vite dev server  
npm run dev

# All in one:
composer run dev
```

### Testing

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test tests/Feature/SyncExportTest.php

# With coverage
php artisan test --coverage

# Watch mode
php artisan test --watch
```

### Code Quality

```bash
# Format code
./vendor/bin/pint

# Static analysis
./vendor/bin/phpstan analyse

# Fix issues with Rector
./vendor/bin/rector
```

---

## 📚 Additional Resources

- [Laravel Documentation](https://laravel.com/docs/10.x)
- [Sanctum Authentication](https://laravel.com/docs/10.x/sanctum)
- [Queue Jobs](https://laravel.com/docs/10.x/queues)
- [Task Scheduling](https://laravel.com/docs/10.x/scheduling)

---

## 🔐 Security Notes

1. **Never commit .env file** - It contains secrets
2. **API tokens** - Rotate them regularly, never hardcode them
3. **IP whitelist** - Always restrict incoming sync requests
4. **HTTPS** - Always use SSL/TLS in production
5. **Database passwords** - Use strong, unique passwords
6. **Backups** - Encrypt and store off-site
7. **Logs** - Monitor for suspicious activity
8. **Dependencies** - Keep Composer and Node packages updated

```bash
# Check for security vulnerabilities
composer audit
npm audit

# Update packages
composer update
npm update
```

---

## ✅ Validation Checklist

Before going live:

- [ ] All tests passing: `php artisan test`
- [ ] Code formatted: `./vendor/bin/pint --test`
- [ ] No static analysis issues: `./vendor/bin/phpstan`
- [ ] Database migrations clean: `php artisan migrate:status`
- [ ] Environment variables set correctly
- [ ] Sync endpoints tested with real remote system
- [ ] Error handling works gracefully
- [ ] Logs are being written correctly
- [ ] Performance is acceptable under load
- [ ] Backups are working
- [ ] SSL certificate is valid
- [ ] Database backups are automated

---

## 📞 Support & Troubleshooting

For detailed issues:

1. **Check logs first:** `storage/logs/sync.log` and `storage/logs/laravel.log`
2. **Database status:** Verify DB connection and permissions
3. **Queue status:** Ensure worker is running
4. **Network connectivity:** Test sync URLs with curl
5. **Authentication:** Verify tokens are valid and not expired

---

**Last Updated:** 2026-03-28  
**Version:** 1.0  
**Maintained By:** Development Team
