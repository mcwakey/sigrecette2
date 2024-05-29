<?php

namespace App\Listeners;

use App\Enums\InvoiceStatusEnums;
use Symfony\Component\Workflow\Event\GuardEvent;
use Symfony\Component\Workflow\Event\Event;
use Illuminate\Contracts\Events\Dispatcher;

class InvoiceWorkflowSubscriber
{
    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            'workflow.invoice.enter',
            [self::class, 'onEnter']
        );

        $events->listen(
            'workflow.invoice.leave',
            [self::class, 'onLeave']
        );

        $events->listen(
            'workflow.invoice.transition',
            [self::class, 'onTransition']
        );

        $events->listen(
            'workflow.invoice.guard',
            [self::class, 'onGuard']
        );
    }

    public function onEnter(Event $event)
    {
        $invoice = $event->getSubject();
        $place = $event->getTransition()->getTos();
       //dd($place);
       switch ($place){
           case  InvoiceStatusEnums::PENDING:
               dump("pending");
           break;
           default :
               dump($place);
       }
    }

    public function onLeave(Event $event)
    {
        $invoice = $event->getSubject();
        $place = $event->getTransition()->getFroms();

        // Logique lorsque vous quittez un état
        //dump("Leaving state: " . implode(', ', $place));
    }

    public function onTransition(Event $event)
    {
        $invoice = $event->getSubject();
        $transition = $event->getTransition()->getName();

        // Logique pendant une transition
        logger()->info("Transitioning via: " . $transition);
    }

    public function onGuard(Event $event)
    {
        $invoice = $event->getSubject();
        $transition = $event->getTransition()->getName();

        // Logique pour vérifier les guards
        //dump("Guard for transition: " . $transition);
    }
}
