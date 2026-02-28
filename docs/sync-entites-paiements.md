# Sync paiements en ligne: entités concernées (base projet)

Contexte: liaison entre l’app locale et une app en ligne qui encaisse via Mobile Money et carte bancaire. L’app en ligne doit synchroniser périodiquement la liste des factures de l’année courante, récupérer/mettre à jour les paiements, et répercuter les statuts côté local.

## Analyse du SIG-Recette existant
- Le cœur métier côté local est centré sur `Invoice` et `Payment`, liés à `Taxpayer`.
- Les statuts de paiement et de facture sont déjà modélisés via enums (`InvoicePayStatusEnums`, `PaymentStatusEnums`, `PaymentTypeEnums`).
- Les factures de l’année courante peuvent être filtrées via la notion d’année active (`Year::getActiveYear()`).
- Les relations nécessaires à l’encaissement sont présentes (facture -> redevable, facture -> paiements, facture -> lignes).

## Analyse du flux actuel des recettes
- Le flux actuel est local: création/gestion des `Invoice`, enregistrement des `Payment`, puis mise à jour du `pay_status` des factures.
- Les paiements sont associés aux factures via `Payment.invoice_id` et peuvent être filtrés par année active.

## Identification des modules impactés
- Modèles Eloquent: `Invoice`, `Payment`, `InvoiceItem`, `Taxpayer`, `TaxpayerTaxable`, `Taxable`, `TaxLabel`, `Year`.
- Enums: `InvoicePayStatusEnums`, `PaymentStatusEnums`, `PaymentTypeEnums`.
- Jobs/Queues: import/export périodiques.
- API/Controllers: endpoints de sync (export factures, import paiements).

## Identification des points d’extension
- Exposer des endpoints API pour: export factures, import paiements.
- Ajouter une logique d’idempotence par `uuid` lors de l’import.
- Ajouter des jobs planifiés (scheduler) pour la synchronisation périodique.
- Journaliser les échanges (logs de sync).

## Inventaire des contraintes techniques
- Synchronisation non temps réel, par lots.
- Source de vérité des paiements: app en ligne.
- Support des types de paiement `CASH`, `CHEQUE`, `DIGI`.
- Nécessité d’un filtrage par année courante (`Year::getActiveYear()`).

## Périmètre fonctionnel demandé
- Synchronisation périodique des `invoices` de l’année courante.
- Synchronisation des `payments` (création et mise à jour d’état).
- Mise à jour des statuts de paiement sur les factures associées.
- Remontée asynchrone (non temps réel) des paiements de l’app en ligne vers l’app locale.

## Entités principales à synchroniser

Pourquoi ces modèles doivent être synchronisés avec l’app d’encaissement en ligne
- Ils permettent d’identifier de façon fiable la facture à payer, le redevable concerné et les montants exacts à encaisser.
- Ils assurent la cohérence des statuts (facture payée/partiellement payée, paiement confirmé/annulé) entre les deux systèmes.
- Ils donnent à l’app en ligne le contexte minimal nécessaire pour afficher, contrôler et justifier un encaissement.

**Invoice** (`app/Models/Invoice.php`, table `invoices`)
- Rôle: facture à payer et état d’avancement du paiement.
- Direction: Local -> En ligne (liste), puis En ligne -> Local (mise à jour des statuts liés aux paiements).
- Clés: `id`, `uuid`, `invoice_no`, `taxpayer_id`.
- Champs de sync: `amount`, `reduce_amount`, `qty`, `from_date`, `to_date`, `pay_status`, `status`, `type`, `delivery`, `delivery_date`, `edition_state`, `notes`.
- Filtre année courante: baser la sélection sur la période (`from_date`/`to_date`) et l’année active (voir `Year::getActiveYear()` dans `app/Models/Year.php`).
- Relations: `taxpayer_id`, `payments`, `invoiceitems`, `taxpayer_taxables`.
- Pourquoi sync: l’app en ligne doit connaître les factures éligibles au paiement et leur statut courant pour éviter les doublons et calculer le reste à payer.

**Payment** (`app/Models/Payment.php`, table `payments`)
- Rôle: règlement d’une facture (Mobile Money / carte / autre).
- Direction: En ligne -> Local (création), En ligne -> Local (mise à jour), Local -> En ligne (statut si ajusté localement). La remontée vers le local n’est pas en temps réel.
- Clés: `id`, `uuid`, `invoice_id`, `taxpayer_id`, `reference`, `code`.
- Champs de sync: `amount`, `payment_type`, `invoice_type`, `reference`, `description`, `remaining_amount`, `status`, `deposit`, `notes`, `user_id`, `r_user_id`.
- Statuts: `PENDING`, `DONE`, `ACCOUNTED`, `CANCELED` (voir `app/Enums/PaymentStatusEnums.php`).
- Type de paiement: `CASH`, `CHEQUE`, `DIGI` (voir `app/Enums/PaymentTypeEnums.php`). `DIGI` couvre le paiement en ligne.
- Pourquoi sync: c’est la source de vérité des encaissements. Sans synchro, le local ne reflète pas les paiements reçus en ligne.

**InvoiceItem** (`app/Models/InvoiceItem.php`, table `invoice_items`)
- Rôle: lignes d’une facture (montants, quantités, taxe).
- Direction: Local -> En ligne (lecture seule si l’app en ligne affiche le détail des lignes).
- Clés: `id`, `invoice_id`, `taxpayer_taxable_id`.
- Champs de sync: `qty`, `amount`, `ii_tariff`, `ii_seize`.
- Pourquoi sync: permet d’afficher ou vérifier le détail d’une facture lors du paiement (transparence et contrôle).

**Taxpayer** (`app/Models/Taxpayer.php`, table `taxpayers`)
- Rôle: redevable lié à la facture et au paiement.
- Direction: Local -> En ligne (lecture seule pour identification/affichage).
- Clés: `id`, `tnif`, `nif`, `name`, `mobilephone`.
- Champs de sync: identité et contacts (`name`, `mobilephone`, `telephone`, `email`), adresse simplifiée (`address`), et liens de référence (`town_id`, `erea_id`, `zone_id`, `activity_id`, `category_id`).
- Pourquoi sync: l’app en ligne doit associer chaque paiement à un redevable identifiable et contacter/afficher les bonnes informations.

**TaxpayerTaxable** (`app/Models/TaxpayerTaxable.php`, table `taxpayer_taxables`)
- Rôle: lien entre un redevable et une taxe/activité imposable, utilisé dans les lignes de facture.
- Direction: Local -> En ligne (lecture seule si besoin de contexte métier sur la facture).
- Clés: `id`, `taxpayer_id`, `taxable_id`, `invoice_id`.
- Champs de sync: `name`, `location`, `length`, `width`, `seize`, `billable`, `bill_status`, `auth_reference`.
- Pourquoi sync: utile pour contextualiser le contenu d’une facture et faciliter les contrôles métier côté encaissement.

## Entités de référence utiles (selon l’affichage côté en ligne)

**Taxable** (`app/Models/Taxable.php`, table `taxables`)
- Rôle: catalogue des taxes/activités facturables.
- Direction: Local -> En ligne (référentiel, lecture seule).
- Champs clés: `name`, `tariff`, `tariff_type`, `unit`, `unit_type`, `modality`, `periodicity`, `penalty`, `penalty_type`, `tax_label_id`, `status`.

**TaxLabel** (`app/Models/TaxLabel.php`, table `tax_labels`)
- Rôle: classification fiscale liée à `taxables` et aux paiements (`code`).
- Direction: Local -> En ligne (référentiel, lecture seule).
- Champs clés: `name`, `category`, `code`.

**Year** (`app/Models/Year.php`, table `years`)
- Rôle: année active pour filtrer les factures/paiements de l’année courante.
- Direction: Local -> En ligne (référentiel, lecture seule).
- Champs clés: `name`, `status`, `current_month`, `auto_switch`.

## Sens de synchronisation (résumé opérationnel)

| Flux | Source -> Destination | Contenu | Règles |
| --- | --- | --- | --- |
| Local -> En ligne | App locale -> App en ligne | `Invoice`, `InvoiceItem`, `Taxpayer` et références utiles | Lecture seule côté en ligne. Sert à présenter les factures de l’année courante. |
| En ligne -> Local | App en ligne -> App locale | `Payment` (tous types: `CASH`, `CHEQUE`, `DIGI`) + mise à jour statuts | Source de vérité des paiements. Import périodique, non temps réel. |

## Points d’intégration (liste claire)
- Export `Invoice` (année courante) vers l’app en ligne.
- Export `InvoiceItem` associés pour le détail des factures (si affichage requis).
- Export `Taxpayer` (référentiel minimum pour identification et contact).
- Import `Payment` depuis l’app en ligne (création / mise à jour).
- Mise à jour locale du `pay_status` des `Invoice` après import de paiements.

## Notes techniques (Laravel) pour faciliter le travail des devs

- Identifiants stables: utiliser `uuid` comme clé d’idempotence pour `Invoice` et `Payment` afin d’éviter les doublons à l’import.
- Filtrage incrémental: exposer un paramètre `updated_since` (ISO 8601) sur les endpoints pour récupérer uniquement les enregistrements modifiés.
- Pagination: supporter une pagination simple (`page`, `per_page`) pour `Invoice` et `Payment`.
- Champs minimaux: pour l’app en ligne, inclure au minimum `Invoice.uuid`, `invoice_no`, `amount`, `pay_status`, `status`, `taxpayer_id` + infos de contact redevable.
- Mapping statuts: verrouiller les valeurs attendues (`InvoicePayStatusEnums`, `PaymentStatusEnums`, `PaymentTypeEnums`) pour éviter les écarts de libellés.
- Import des paiements: côté local, `Payment.uuid` unique et contrôlé; si `uuid` existe -> `update`, sinon -> `create`.
- Recalcul après import: recalculer `Invoice.pay_status` après chaque lot de paiements importés.
- Auth API: utiliser un `token` (Sanctum ou Passport) pour sécuriser les endpoints de sync.
- Exécution: privilégier des `Jobs` et `Queues` pour la sync (batch) afin d’éviter les timeouts.
- Observabilité: journaliser les imports/exports (nombre d’items, erreurs par `uuid`, timestamp).

## Exemples de payloads JSON

Exemple `Invoice` (Local -> En ligne)
```json
{
  "uuid": "3f2b8c6e-8f3a-4f71-9d2d-0d9a3c5a2b11",
  "invoice_no": "INV-2024-000123",
  "taxpayer_id": 100045,
  "amount": 150000,
  "reduce_amount": 0,
  "qty": 1,
  "from_date": "2024-01-01",
  "to_date": "2024-12-31",
  "pay_status": "OWING",
  "status": "APPROVED",
  "type": "TITRE",
  "delivery": "DELIVERED",
  "delivery_date": "2024-02-15",
  "edition_state": true,
  "notes": {
    "previous_invoice_id": null,
    "remaining_amount": null,
    "free_text": "Facture annuelle"
  },
  "taxpayer": {
    "id": 100045,
    "tnif": "TNIF-0045",
    "nif": "NIF-2024-0045",
    "name": "SOCIETE EXEMPLE",
    "mobilephone": "+22890000000",
    "telephone": "+22822000000",
    "email": "contact@example.tg",
    "address": "Lomé"
  },
  "items": [
    {
      "taxpayer_taxable_id": 200011,
      "qty": 1,
      "amount": 150000,
      "ii_tariff": 150000,
      "ii_seize": null
    }
  ]
}
```

Exemple `Payment` (En ligne -> Local)
```json
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
```

## Liens et dépendances clés à respecter
- `Invoice.taxpayer_id` -> `Taxpayer.id`.
- `Payment.invoice_id` -> `Invoice.id`.
- `Payment.taxpayer_id` -> `Taxpayer.id`.
- `InvoiceItem.invoice_id` -> `Invoice.id`.
- `InvoiceItem.taxpayer_taxable_id` -> `TaxpayerTaxable.id`.
- `TaxpayerTaxable.taxable_id` -> `Taxable.id`.
- `Taxable.tax_label_id` -> `TaxLabel.id`.

## Notes de synchronisation (pratiques)
- L’app en ligne doit pouvoir récupérer les factures « actives » de l’année courante et remonter des paiements avec `payment_type = DIGI`.
- La mise à jour locale doit recalculer `Invoice.pay_status` (OWING / PART PAID / PAID) et enregistrer les paiements liés.
- Les statuts de paiement sont une source de vérité côté encaissement: prévoir un champ de trace tel que `reference` ou `code` pour concilier.
- Règle métier confirmée: le local ne modifie jamais un paiement reçu de l’app en ligne. La source de vérité des paiements est l’app d’encaissement.
- L’app en ligne peut recevoir tous les types de paiements (`CASH`, `CHEQUE`, `DIGI`). Le local doit donc accepter et stocker ces types à l’import.

## Impacts et risques connus
- Aucun impact bloquant identifié à ce stade, sous réserve de validation des flux exacts et des règles métier finales.

## Validation
- En attente de validation par le chef de projet.
