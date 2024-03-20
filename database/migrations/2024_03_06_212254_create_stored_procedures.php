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
                INNER JOIN invoices ON taxpayer_taxables.invoice_id = invoices.invoice_no
                AND validity = "VALID"
                SET taxpayer_taxables.invoice_id = NULL,
                taxpayer_taxables.bill_status = "NOT BILLED",
                invoices.validity = "EXPIRED",
                invoices.status = "APROVED"
                WHERE invoices.to_date = today AND
                invoices.taxpayer_id <> NULL;
            END
        ');
    }

    public function down()
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS updateTaxpayerTaxables');
    }
}

