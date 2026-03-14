# Guide de sync (Backend distant -> Backend local)

Ce guide décrit comment le backend distant (encaissement en ligne) synchronise avec le backend local.

## Authentification

- Les endpoints locaux sont protégés par Sanctum.
- Le backend distant doit utiliser un token valide (Bearer) pour appeler le local.
- Le local peut restreindre les IP autorisées via `SYNC_ALLOWED_IPS` (whitelist).

Header requis:
```
Authorization: Bearer <TOKEN>
```

## Base URL

- Base locale: `{LOCAL_BASE_URL}/api`

Exemple:
```
https://local.example.com/api
```

## Endpoints côté local

### 1) Lire les factures à encaisser (local -> distant)

**GET** `/v1/sync/invoices`

**Paramètres**
- `updated_since` (ISO 8601) — optionnel
- `page` — optionnel (défaut 1)
- `per_page` — optionnel (défaut 50, max 200)
- `include` — optionnel (ex: `taxpayer,items`)

**Exemple**
```
GET /api/v1/sync/invoices?updated_since=2026-01-01T00:00:00Z&page=1&per_page=200&include=taxpayer,items
```

**Réponse**
- `data`: factures exportables
- `meta`: pagination + année active

---

### 2) Envoyer les paiements au local (distant -> local)

**POST** `/v1/sync/payments`

**Body**
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

**Réponse**
```json
{
  "created": 1,
  "updated": 0,
  "skipped": 0,
  "errors": []
}
```

**Idempotence**
- Si `uuid` existe: update
- Sinon: create

---

### 3) Lire les paiements (local -> distant)

**GET** `/v1/sync/payments`

**Paramètres**
- `updated_since` (ISO 8601) — optionnel
- `status` (optionnel)
- `page` — optionnel
- `per_page` — optionnel

**Exemple**
```
GET /api/v1/sync/payments?updated_since=2026-01-01T00:00:00Z&page=1&per_page=200
```

## Règles métier clés

- Source de vérité des paiements: backend distant.
- Le local ne modifie jamais un paiement venant du distant.
- Les statuts valides: `PENDING`, `DONE`, `ACCOUNTED`, `CANCELED`.
- Types valides: `CASH`, `CHEQUE`, `DIGI`.
- Le local recalcule `Invoice.pay_status` après import.

## Stratégie recommandée (distant)

1. Appeler `GET /v1/sync/invoices` avec `updated_since` pour obtenir les factures à afficher.
2. Encaisser côté distant.
3. Envoyer les paiements via `POST /v1/sync/payments`.
4. (Optionnel) Rejeter ou mettre à jour l’état d’un paiement via un nouveau POST du même `uuid`.

## Codes d’erreur fréquents

- `401/403` → token invalide ou absent.
- `422` → validation payload (champs manquants / mauvais format).
- `500` → erreur interne (voir `sync.log`).

## Journalisation & suivi

- Les syncs locales sont journalisées dans `storage/logs/sync.log`.
- Les métriques sont enregistrées dans la table `sync_runs`.
