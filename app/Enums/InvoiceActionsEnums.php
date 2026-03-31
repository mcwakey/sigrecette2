<?php

namespace App\Enums;

enum InvoiceActionsEnums: string
{
    case VIEW = 'VIEW';
    case PRINT = 'PRINT';
    case REDUCE = 'REDUCE';
    case PAYMENT = 'ADD_PAYMENT';
    case RELAUNCH = 'RELAUNCH';
    case ZEROEDITION = 'zero_edit';
    case ADDORNO = 'add_or_no';
    case ADDDELIVERY = 'add_delivery';
    case EDITSTATUT = 'edit_statut';
}
