# Variables d'environnement – Sync (SIG Recette local)

Ce fichier liste toutes les variables nécessaires au fonctionnement de la synchronisation locale.

## Variables requises

- `SYNC_REMOTE_BASE_URL`
  - URL base du backend distant (bridge), ex: `https://bridge.example.com`
- `SYNC_REMOTE_TOKEN`
  - Token Sanctum du local à utiliser par le bridge (Bearer)

## Variables recommandées

- `SYNC_ALLOWED_IPS`
  - Whitelist des IP autorisées à appeler l’API de sync locale
  - Format: liste séparée par virgules
  - Exemple: `203.0.113.10,203.0.113.11`

## Variables optionnelles

- `SYNC_TIMEOUT` (défaut: 15)
- `SYNC_RETRIES` (défaut: 3)
- `SYNC_CHUNK_SIZE` (défaut: 200)
- `SYNC_PER_PAGE` (défaut: 200)
- `SYNC_EXPORT_CRON` (défaut: `*/10 * * * *`)
- `SYNC_IMPORT_CRON` (défaut: `*/10 * * * *`)
- `SYNC_LOG_CHANNEL` (défaut: `sync`)
- `SYNC_LOG_LEVEL` (défaut: `info`)

## Exemple complet

```
SYNC_REMOTE_BASE_URL=https://bridge.example.com
SYNC_REMOTE_TOKEN=...
SYNC_ALLOWED_IPS=203.0.113.10
SYNC_TIMEOUT=15
SYNC_RETRIES=3
SYNC_CHUNK_SIZE=200
SYNC_PER_PAGE=200
SYNC_EXPORT_CRON="*/10 * * * *"
SYNC_IMPORT_CRON="*/10 * * * *"
SYNC_LOG_CHANNEL=sync
SYNC_LOG_LEVEL=info
```
