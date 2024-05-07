<?php

namespace App\Helpers;

use Carbon\Carbon;

class Constants
{
    public  const DEMANDE="DEMANDE";
    public  const  TITRE="TITRE";
    public  const  REDUCTION="Réduction";
    public  const  ANNULATION="Annulation";
    public  const INVOICE_TYPE_TITRE ="TITRE";
    public  const INVOICE_TYPE_COMPTANT ="COMPTANT";
    const CANCELED = "CANCELED";
    const REDUCED="REDUCED";
    const NOT_PERMISSION_TO_PERFORM_ACTION ="Vous n'avez pas la permission pour effectuer cette action.";

    public static function getMonths():array{
        $months = [];
        for ($i = 1 ; $i <= 12; $i++) {
            $monthName = Carbon::createFromFormat('m',$i)->monthName;
            $monthNumber = str_pad($i, 2, '0', STR_PAD_LEFT);
            $months[$monthNumber] = $monthName;
        }
        return $months;
    }
}
