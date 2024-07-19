<?php

namespace App\Helpers;

use App\Enums\InvoiceActionsEnums;
use App\Enums\InvoiceStatusEnums;
use App\Enums\PaymentStatusEnums;
use App\Models\Invoice;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
class Constants
{
    public const CURRENCY = " FCFA";
    public  const DEMANDE="DEMANDE";
    public  const  TITRE="TITRE";
    public  const  REDUCTION="Réduction";
    public  const  ANNULATION="Annulation";
    public  const INVOICE_TYPE_TITRE ="TITRE";
    public  const INVOICE_TYPE_COMPTANT ="COMPTANT";
    const CANCELED = "CANCELED";
    const REDUCED="REDUCED";
    const NOT_PERMISSION_TO_PERFORM_ACTION ="Vous n'avez pas la permission pour effectuer cette action.";
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
    const INVOICE_STATE_VALIDATION_MAP = [
        self::INVOICE_STATE_DRAFT_KEY => InvoiceStatusEnums::DRAFT,
        self::INVOICE_STATE_ACCEPTED_KEY => InvoiceStatusEnums::ACCEPTED,
        self::INVOICE_STATE_PENDING_KEY => InvoiceStatusEnums::PENDING,
        self::INVOICE_STATE_REJECT_KEY => InvoiceStatusEnums::REJECTED,
        self::INVOICE_STATE_APPROVE_KEY => InvoiceStatusEnums::APPROVED,


    ];
    const PAYMENT_STATE_VALIDATION_MAP = [
        self::PAYMENT_STATE_PENDING_KEY => InvoiceStatusEnums::PENDING,
        self::PAYMENT_STATE_CANCEL_KEY => PaymentStatusEnums::CANCELED,

    ];

    const INVOICE_DELIVERY_STATE_VALIDATION_MAP = [
        self::INVOICE_DELIVERY_NON_LIV_KEY,
        self::INVOICE_DELIVERY_LIV_KEY
    ];

    const INVOICE_TYPE_VALIDATION_MAP = [
        self::INVOICE_TYPE_COMPTANT_KEY => Constants::INVOICE_TYPE_COMPTANT,
        self::INVOICE_TYPE_TITRE_KEY => Constants::INVOICE_TYPE_TITRE,
    ];
    public static function getMonths():array{
        $months = [];
        for ($i = 1 ; $i <= 12; $i++) {
            $monthName = Carbon::createFromFormat('m',$i)->monthName;
            $monthNumber = str_pad($i, 2, '0', STR_PAD_LEFT);
            $months[$monthNumber] = $monthName;
        }
        return $months;
    }
    public static function getInvoiceActionsBasedOnRouteNameAndStatut( string $state = null): array
    {
        $actions = [InvoiceActionsEnums::VIEW];


            if(request()->routeIs('invoices.*')){
                $actions = self::getInvoiceActions();
            }

        return $actions;
    }

    private static function getInvoiceActions(): array
    {
        if(request()->has('state')){
            if( request()->input('state') == self::INVOICE_STATE_DRAFT_KEY) {
                return [InvoiceActionsEnums::VIEW,InvoiceActionsEnums::EDITSTATUT];
            }
            elseif(  request()->input('state') == self::INVOICE_STATE_ACCEPTED_KEY ){
                return [InvoiceActionsEnums::VIEW,InvoiceActionsEnums::PRINT,InvoiceActionsEnums::ADDORNO];
            }
            elseif(request()->input('state') == self::INVOICE_STATE_PENDING_KEY ){
                return [InvoiceActionsEnums::VIEW,InvoiceActionsEnums::EDITSTATUT,InvoiceActionsEnums::PRINT];
            }
            elseif(request()->input('state') == self::INVOICE_STATE_APPROVE_KEY ){
                return [InvoiceActionsEnums::VIEW,InvoiceActionsEnums::REDUCE];
            }
        }else{
        if (request()->has('delivery')){
                if( request()->input('delivery')==Constants::INVOICE_DELIVERY_NON_LIV_KEY){
                    return [InvoiceActionsEnums::VIEW,InvoiceActionsEnums::ADDDELIVERY];

                }
                elseif ( request()->input('delivery')==Constants::INVOICE_DELIVERY_LIV_KEY&& request()->input('to_paid')=="1"){
                    return [InvoiceActionsEnums::VIEW,InvoiceActionsEnums::PAYMENT];
                }
                elseif ( request()->input('delivery')==Constants::INVOICE_DELIVERY_LIV_KEY){
                    return [InvoiceActionsEnums::VIEW,InvoiceActionsEnums::REDUCE,InvoiceActionsEnums::RELAUNCH];
                }
        }
        else {
                if(request()->input('type')==Constants::INVOICE_TYPE_TITRE_KEY){
                    return [InvoiceActionsEnums::VIEW,InvoiceActionsEnums::ZEROEDITION,InvoiceActionsEnums::PRINT];
                }
                if(request()->input('type')==Constants::INVOICE_TYPE_COMPTANT_KEY){
                    return [InvoiceActionsEnums::VIEW,InvoiceActionsEnums::REDUCE,InvoiceActionsEnums::PRINT,InvoiceActionsEnums::ADDORNO,InvoiceActionsEnums::EDITSTATUT];
                }
            }
        }



        return [InvoiceActionsEnums::VIEW];
    }

    /**
     * check if user is already on a url
     *
     * @param string $url
     * @return string
     */
    public static function checkUrl(string $url): string
    {
        //dump(request()->fullUrl() ,url($url));
        if (request()->fullUrl() == url($url)) {
            return 'javascript:void(0);';
        }

        return $url;
    }

    /**
     * @param array $permissions
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
