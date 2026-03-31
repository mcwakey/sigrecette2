<?php

namespace App\Enums;

enum PrintNameEnums: string
{
    case BORDEREAU_REDUCTION = 'Bordereau journal des avis de réduction ou d’annulation';
    case BORDEREAU = 'Bordereau Journal des avis des sommes à payer';
    case FICHE_DE_DISTRIBUTION_DES_AVIS = 'Fiche de distribution des avis';
    case FICHE_DE_RECOUVREMENT_DES_AVIS_DISTRIBUES = 'Fiche de recouvrement des avis distribués';
    case MULTIPLE_INVOICE = 'multiple-invoices';
}
