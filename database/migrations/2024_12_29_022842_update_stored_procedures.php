<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (config('database.default') !== 'sqlite') {
            DB::unprepared('
                DROP PROCEDURE IF EXISTS updateTaxpayerTaxables;
                CREATE PROCEDURE updateTaxpayerTaxables()
                BEGIN
                    DECLARE today DATE;
                    SET today = CURDATE();

                    UPDATE taxpayer_taxables
                    INNER JOIN invoices ON taxpayer_taxables.invoice_id = invoices.invoice_no
                    AND invoices.validity = "VALID"
                    SET taxpayer_taxables.invoice_id = NULL,
                        taxpayer_taxables.bill_status = "NOT BILLED",
                        invoices.validity = "EXPIRED",
                        invoices.status = "APPROVED"
                    WHERE invoices.to_date <= today
                    AND invoices.taxpayer_id IS NOT NULL;
                END
            ');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (config('database.default') !== 'sqlite') {
            DB::unprepared('DROP PROCEDURE IF EXISTS updateTaxpayerTaxables');
        }
    }
};
