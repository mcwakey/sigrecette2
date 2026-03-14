<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use App\Services\InvoiceCodeBalanceService;
use Illuminate\Console\Command;

class RebuildInvoiceCodeBalances extends Command
{
    protected $signature = 'sync:rebuild-invoice-code-balances {--chunk=200}';
    protected $description = 'Rebuild invoice code balances from invoices and invoice items.';

    public function handle(): int
    {
        $chunk = (int) $this->option('chunk');
        $service = app(InvoiceCodeBalanceService::class);

        Invoice::query()
            ->with('invoiceitems.taxpayer_taxable.taxable.tax_label')
            ->orderBy('id')
            ->chunkById($chunk, function ($invoices) use ($service) {
                foreach ($invoices as $invoice) {
                    $service->syncForInvoice($invoice);
                }
            });

        $this->info('Invoice code balances rebuilt.');

        return self::SUCCESS;
    }
}
