<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateStoredProcedures extends Migration
{
    public function up()
    {
        DB::unprepared('
            CREATE PROCEDURE updateTaxpayerTaxables()
            BEGIN
                DECLARE today DATE;
                SET today = CURDATE();

                UPDATE taxpayer_taxables
                INNER JOIN invoices ON taxpayer_taxables.invoice_id = invoices.id
                SET taxpayer_taxables.invoice_id = NULL
                WHERE invoices.to_date = today;
            END
        ');
    }

    public function down()
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS updateTaxpayerTaxables');
    }
}

