<?php

namespace App\Guards;
use App\Helpers\Constants;
use Symfony\Component\Workflow\Event\GuardEvent;

class InvoiceGuard
{
    public function canSubmitForPending(GuardEvent $event)
    {
        $invoice = $event->getSubject();
        if ($invoice->order_no!=null|| $invoice->type== Constants::INVOICE_TYPE_COMPTANT) {
            $event->setBlocked(false);
        }else{
            $event->setBlocked(true);
        }
    }
}
