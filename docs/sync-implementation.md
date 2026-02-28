# Implémentation Sync (v1)

Ce document décrit l’implémentation des endpoints de synchronisation et des jobs planifiés.

## Endpoints v1 (API locale)

Base: `/api/v1/sync`

### 1) Export des factures (local -> en ligne)
- **Route**: `GET /v1/sync/invoices`
- **Auth**: Sanctum
- **Paramètres**
  - `updated_since` (ISO 8601, optionnel)
  - `page` (optionnel, défaut 1)
  - `per_page` (optionnel, défaut 50, max 200)
  - `include` (optionnel, ex: `taxpayer,items`)
- **Filtrage**
  - Année active (via `Year::getActiveYear()`)
  - `status = APPROVED`, `validity = VALID`
- **Réponse**
  - `data`: tableau des factures
  - `meta`: pagination + année active

### 2) Export des paiements (local -> en ligne)
- **Route**: `GET /v1/sync/payments`
- **Auth**: Sanctum
- **Paramètres**
  - `updated_since` (ISO 8601, optionnel)
  - `status` (optionnel)
  - `page` (optionnel)
  - `per_page` (optionnel)
- **Filtrage**
  - Année active (via `Year::getActiveYear()`)
- **Réponse**
  - `data`: tableau des paiements + `invoice_uuid`
  - `meta`: pagination + année active

### 3) Import des paiements (en ligne -> local)
- **Route**: `POST /v1/sync/payments`
- **Auth**: Sanctum
- **Body**
```json
{
  "payments": [
    {
      "uuid": "...",
      "invoice_uuid": "...",
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
- **Idempotence**
  - Création/maj via `Payment.uuid`
- **Recalcul**
  - `Invoice.pay_status` recalculé après import

## Jobs & Scheduler

### Jobs
- `SyncExportInvoicesJob`
  - Export par batch (chunking)
  - Retry/timeout configurables
  - Journalisation des lots et erreurs

- `SyncImportPaymentsJob`
  - Pagination côté remote
  - Import local via `PaymentImportService`
  - Journalisation des lots et erreurs

### Scheduler
- Déclaré dans `app/Console/Kernel.php`
- Cron configurables via `.env`:
  - `SYNC_EXPORT_CRON` (defaut `*/10 * * * *`)
  - `SYNC_IMPORT_CRON` (defaut `*/10 * * * *`)

## Observabilité

- **Logs**: channel `sync` (fichier `storage/logs/sync.log`)
- **Table**: `sync_runs`
  - Statuts: `started`, `success`, `failed`
  - Métriques: `processed`, `succeeded`, `failed`
  - `error_sample` stocke un extrait d’erreurs (par `uuid`)
  - `meta` peut contenir `last_synced_at`
- **Sécurité**: whitelist IP via `SYNC_ALLOWED_IPS`

## Configuration (.env)

```
SYNC_REMOTE_BASE_URL=
SYNC_REMOTE_TOKEN=
SYNC_ALLOWED_IPS=
SYNC_TIMEOUT=15
SYNC_RETRIES=3
SYNC_CHUNK_SIZE=200
SYNC_PER_PAGE=200
SYNC_EXPORT_CRON="*/10 * * * *"
SYNC_IMPORT_CRON="*/10 * * * *"
SYNC_LOG_CHANNEL=sync
SYNC_LOG_LEVEL=info
```

## Fichiers impactés

- Routes: `routes/api.php`
- Controllers:
  - `app/Http/Controllers/Api/SyncV1InvoicesController.php`
  - `app/Http/Controllers/Api/SyncV1PaymentsController.php`
- Resources:
  - `app/Http/Resources/SyncInvoiceResource.php`
  - `app/Http/Resources/SyncInvoiceItemResource.php`
  - `app/Http/Resources/SyncTaxpayerResource.php`
  - `app/Http/Resources/SyncPaymentResource.php`
- Jobs:
  - `app/Jobs/SyncExportInvoicesJob.php`
  - `app/Jobs/SyncImportPaymentsJob.php`
- Service:
  - `app/Services/Sync/PaymentImportService.php`
- Modèle + migration:
  - `app/Models/SyncRun.php`
  - `database/migrations/2026_02_28_000000_create_sync_runs_table.php`
- Config:
  - `config/sync.php`
  - `config/logging.php`
  - `.env.example`

## Notes

- Les anciens endpoints `/v1/synchronisation/*` restent inchangés.
- L’export invoices utilise `include=taxpayer,items` pour charger les relations.
- La source de vérité des paiements reste l’app d’encaissement.
