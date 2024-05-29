<?php

namespace App\Listeners;

use App\Enums\InvoiceStatusEnums;
use App\Notifications\InvoiceAccepted;
use App\Notifications\InvoiceApproved;
use App\Notifications\InvoiceRejected;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role;
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
        $place = $event->getTransition()->getTos()[0];
        //dump($place);
       switch ($place){
           case InvoiceStatusEnums::ACCEPTED:
               $role = Role::where('name', 'agent_delegation_du_receveur')->first();
               $role_a = Role::where('name', 'agent_assiette')->first();
               $role_r = Role::where('name', 'agent_recette')->first();
               if ($role && $role_a && $role_r) {
                   $users = $role->users()->get();
                   $users_agent_assiette= $role_a->users()->get();
                   $users_agent_recette=  $role_r->users()->get();
                   Notification::send($users, new InvoiceAccepted($invoice, Auth::user(), "agent_delegation_du_receveur"));
                   Notification::send($users_agent_assiette, new InvoiceAccepted($invoice, Auth::user(), "agent_assiette"));
                   Notification::send( $users_agent_recette, new InvoiceAccepted($invoice, Auth::user(), "agent_recette"));

               }
               break;
           case InvoiceStatusEnums::REJECTED_BY_OR:
               $role = Role::where('name', 'agent_assiette')->first();
               if ($role) {
                   $users = $role->users()->get();
                   Notification::send($users, new InvoiceRejected($invoice, Auth::user(), "agent_assiette"));
               }
               foreach ( $invoice->taxpayer_taxables as $taxpayerTaxable){
                   $taxpayerTaxable->billable ='0';
                   $taxpayerTaxable->bill_status ="NOT BILLED";
                   $taxpayerTaxable->invoice_id = null;
                   $taxpayerTaxable->save();
               }
               break;
           case  InvoiceStatusEnums::PENDING:
               $role = Role::where('name', 'agent_recette')->first();
               if ($role) {
                   $users = $role->users()->get();
                   Notification::send($users, new InvoiceAccepted($invoice, Auth::user(), "agent_recette"));
               }
               $role_assiette = Role::where('name', 'agent_assiette')->first();
               if($role_assiette){
                   $users =  $role_assiette->users()->get();
                   Notification::send($users, new InvoiceAccepted($invoice, Auth::user(), "agent_assiette"));
               }
           break;
           case   InvoiceStatusEnums::APPROVED:
           case     InvoiceStatusEnums::APPROVED_CANCELLATION:
                 $role = Role::where('name', 'regisseur')->first();
                   if ($role) {
                       $users = $role->users()->get();
                       Notification::send($users, new InvoiceApproved($invoice, Auth::user(), "regisseur"));
                   }
               break;
           case InvoiceStatusEnums::CANCELED:
               //dump(InvoiceStatusEnums::CANCELED);
               break;
           case InvoiceStatusEnums::REDUCED:
               //dump(InvoiceStatusEnums::REDUCED);
               break;
           default :
              // dump($place);
       }
    }

    public function onLeave(Event $event)
    {
        $invoice = $event->getSubject();
        $place = $event->getTransition()->getFroms();


        //dump("Leaving state: " . implode(', ', $place));
    }

    public function onTransition(Event $event)
    {
        $invoice = $event->getSubject();
        $transition = $event->getTransition()->getName();

       // logger()->info("Transitioning via: " . $transition);
    }

    public function onGuard(Event $event)
    {
        $invoice = $event->getSubject();
        $transition = $event->getTransition()->getName();


        //dump("Guard for transition: " . $transition);
    }
}
