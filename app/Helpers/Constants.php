<?php

namespace App\Helpers;

use App\Enums\ExportTypeEnums;
use App\Enums\InvoiceActionsEnums;
use App\Enums\InvoiceStatusEnums;
use App\Enums\PaymentStatusEnums;
use App\Models\Invoice;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class Constants
{
    public const CURRENCY = " FCFA";
    public const DEMANDE = "DEMANDE";
    public const  TITRE = "TITRE";
    public const  REDUCTION = "Réduction";
    public const  ANNULATION = "Annulation";
    public const INVOICE_TYPE_TITRE = "TITRE";
    public const INVOICE_TYPE_COMPTANT = "COMPTANT";

    const PROVIDER_QOSIC = 'qosic';
    const PROVIDER_FEDAPAY = 'fedapay';
    const PROVIDER_PAYGATE = 'paygate';
    const MOBILE_PROVIDERS = [
        self::PROVIDER_QOSIC,
        self::PROVIDER_FEDAPAY,
        self::PROVIDER_PAYGATE,
    ];

    const NETWORK_TMONEY = 'TMONEY';
    const NETWORK_FLOOZ = 'FLOOZ';
    const MOBILE_NETWORKS = [
        self::NETWORK_TMONEY,
        self::NETWORK_FLOOZ,
    ];


    const CANCELED = "CANCELED";
    const REDUCED = "REDUCED";
    const NOT_PERMISSION_TO_PERFORM_ACTION = "Vous n'avez pas la permission pour effectuer cette action.";
    const DEFAULT_ROLE_CAN_NOT_DELETE = "Ce role par défaut ne peut etre supprimé.";
    const INVOICE_STATE_DRAFT_KEY = 'br';
    const INVOICE_STATE_APPROVE_KEY = 'pr';
    const INVOICE_STATE_ACCEPTED_KEY = 'ac';
    const INVOICE_STATE_PENDING_KEY = 'at';
    const INVOICE_STATE_REJECT_KEY = 'rj';
    const INVOICE_DELIVERY_NON_LIV_KEY = 'nonliv';
    const INVOICE_DELIVERY_LIV_KEY = 'liv';
    const INVOICE_TYPE_COMPTANT_KEY = 'comptant';
    const INVOICE_TYPE_TITRE_KEY = 'titre';
    const PAYMENT_STATE_CANCEL_KEY = "del";
    const PAYMENT_STATE_PENDING_KEY = self:: INVOICE_STATE_PENDING_KEY;
    const EXPORT_TAXPAYER_KEY = "contribuables";
    const EXPORT_INVOICE_KEY = "avis";
    const EXPORT_PAYMENT_KEY = "recouvrement";
    const EXPORT_TAXPAYERTAXABLE_KEY = "taxation";
    const EXPORT_TAXABLE_KEY = "taxe";
    const EXPORT_VALIDATION_MAP = [
        self::EXPORT_TAXPAYER_KEY => ExportTypeEnums::TAXPAYER->value,
        self::EXPORT_INVOICE_KEY => ExportTypeEnums::INVOICE->value,
        self::EXPORT_PAYMENT_KEY => ExportTypeEnums::PAYMENT->value,
        self::EXPORT_TAXPAYERTAXABLE_KEY => ExportTypeEnums::TAXPAYER_TAXABLE->value,
        self::EXPORT_TAXABLE_KEY => ExportTypeEnums::TAXABLE->value,
    ];
    const INVOICE_STATE_VALIDATION_MAP = [
        self::INVOICE_STATE_DRAFT_KEY => InvoiceStatusEnums::DRAFT->value,
        self::INVOICE_STATE_ACCEPTED_KEY => InvoiceStatusEnums::ACCEPTED->value,
        self::INVOICE_STATE_PENDING_KEY => InvoiceStatusEnums::PENDING->value,
        self::INVOICE_STATE_REJECT_KEY => InvoiceStatusEnums::REJECTED->value,
        self::INVOICE_STATE_APPROVE_KEY => InvoiceStatusEnums::APPROVED->value,
    ];
    const INVOICE_STATE_PRINTABLE_MAP = [
        self::INVOICE_STATE_ACCEPTED_KEY => InvoiceStatusEnums::ACCEPTED->value,
        self::INVOICE_STATE_PENDING_KEY => InvoiceStatusEnums::PENDING->value,
    ];
    const PAYMENT_STATE_VALIDATION_MAP = [
        self::PAYMENT_STATE_PENDING_KEY => InvoiceStatusEnums::PENDING->value,
        self::PAYMENT_STATE_CANCEL_KEY => PaymentStatusEnums::CANCELED->value,
    ];
    const INVOICE_DELIVERY_STATE_VALIDATION_MAP = [
        self::INVOICE_DELIVERY_NON_LIV_KEY,
        self::INVOICE_DELIVERY_LIV_KEY
    ];
    const INVOICE_TYPE_VALIDATION_MAP = [
        self::INVOICE_TYPE_COMPTANT_KEY => Constants::INVOICE_TYPE_COMPTANT,
        self::INVOICE_TYPE_TITRE_KEY => Constants::INVOICE_TYPE_TITRE,
    ];
    const REFERENCE_DEPOSIT_NULL = "ref_deposit_is_null";
    public static function getMonths(): array
    {
        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthName = Carbon::createFromFormat('m', $i)->monthName;
            $monthNumber = str_pad($i, 2, '0', STR_PAD_LEFT);
            $months[$monthNumber] = $monthName;
        }
        return $months;
    }
    public static function getInvoiceActionsBasedOnRouteNameAndStatut(string $state = null): array
    {
        $actions = [InvoiceActionsEnums::VIEW->value];
        $previousUrl = url()->previous();
        $previousRoute = Route::getRoutes()->match(Request::create($previousUrl));
        if ($previousRoute->getName() === "taxpayers.show") {
            $actions = [InvoiceActionsEnums::VIEW->value,InvoiceActionsEnums::PRINT->value,
                InvoiceActionsEnums::PAYMENT->value,
                InvoiceActionsEnums::RELAUNCH->value
            ];
        } elseif (request()->routeIs('invoices.*')) {
            $actions = self::getInvoiceActions();
        }
       // dd($actions,$previousRoute->getName() === "taxpayers.show");
        return $actions;
    }
    private static function getInvoiceActions(): array
    {
        if (request()->has('state')) {
            if (request()->input('state') == self::INVOICE_STATE_DRAFT_KEY) {
                return [InvoiceActionsEnums::VIEW->value, InvoiceActionsEnums::EDITSTATUT->value];
            } elseif (request()->input('state') == self::INVOICE_STATE_ACCEPTED_KEY) {
                return [InvoiceActionsEnums::VIEW->value, InvoiceActionsEnums::PRINT->value, InvoiceActionsEnums::ADDORNO->value];
            } elseif (request()->input('state') == self::INVOICE_STATE_PENDING_KEY) {
                return [InvoiceActionsEnums::VIEW->value, InvoiceActionsEnums::EDITSTATUT->value, InvoiceActionsEnums::PRINT->value];
            } elseif (request()->input('state') == self::INVOICE_STATE_APPROVE_KEY) {
                return [InvoiceActionsEnums::VIEW->value, InvoiceActionsEnums::REDUCE->value];
            }
        } elseif (request()->has('delivery')) {
            if (request()->input('delivery') == Constants::INVOICE_DELIVERY_NON_LIV_KEY) {
                return [InvoiceActionsEnums::VIEW->value, InvoiceActionsEnums::ADDDELIVERY->value];
            } elseif (request()->input('delivery') == Constants::INVOICE_DELIVERY_LIV_KEY && request()->input('to_paid') == "1") {
                return [InvoiceActionsEnums::VIEW->value, InvoiceActionsEnums::PAYMENT->value];
            } elseif (request()->input('delivery') == Constants::INVOICE_DELIVERY_LIV_KEY) {
                return [InvoiceActionsEnums::VIEW->value, InvoiceActionsEnums::REDUCE->value, InvoiceActionsEnums::RELAUNCH->value];
            }
        } else {
            if (request()->input('type') == Constants::INVOICE_TYPE_TITRE_KEY) {
                return [InvoiceActionsEnums::VIEW->value, InvoiceActionsEnums::ZEROEDITION->value, InvoiceActionsEnums::PRINT->value];
            }
            if (request()->input('type') == Constants::INVOICE_TYPE_COMPTANT_KEY) {
                return [InvoiceActionsEnums::VIEW->value, InvoiceActionsEnums::REDUCE->value,
                    InvoiceActionsEnums::PRINT->value, InvoiceActionsEnums::ADDORNO->value, InvoiceActionsEnums::EDITSTATUT->value];
            }
        }
        return [InvoiceActionsEnums::VIEW->value];
    }
    /**
     * check if user is already on a url
     */
    public static function checkUrl(string $url): string
    {
        if (request()->fullUrl() == url($url)) {
            return 'javascript:void(0);';
        }
        return $url;
    }
    /**
     * @return Builder[]|Collection
     */
    public static function getUserWithPermission(array $permissions): Collection|array
    {
        return User::where(function ($query) use ($permissions) {
            $query->whereHas('permissions', function ($q) use ($permissions) {
                $q->whereIn('name', $permissions);
            })->orWhereHas('roles.permissions', function ($q) use ($permissions) {
                $q->whereIn('name', $permissions);
            });
        })->get();
    }
}
