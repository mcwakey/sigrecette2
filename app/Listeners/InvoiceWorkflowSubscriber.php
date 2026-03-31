<?php

namespace App\Listeners;

use App\Enums\InvoiceStatusEnums;
use App\Helpers\Constants;
use App\Models\User;
use App\Notifications\InvoiceAccepted;
use App\Notifications\InvoiceApproved;
use App\Notifications\InvoiceCreated;
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
        switch ($place) {
            case InvoiceStatusEnums::ACCEPTED->value:
                $permissions = ['peut émettre un avis sur titre', 'peut accepter un avis sur titre', 'peut ajouter le numéro d\'ordre de recette d\'un avis'];
                $users = Constants::getUserWithPermission($permissions);
                ;
                if ($users && count($users) > 0) {
                    Notification::send($users, new InvoiceAccepted($invoice, Auth::user(), 'agent_delegation'));
                }
                break;
            case InvoiceStatusEnums::REJECTED_BY_OR->value:
                $permissions = ['peut émettre un avis sur titre'];
                $users = Constants::getUserWithPermission($permissions);
                if ($users && count($users) > 0) {
                    Notification::send($users, new  InvoiceRejected($invoice, Auth::user(), 'agent_delegation'));
                }
                $invoice->releaseTaxpayerTaxable();
                break;
            case InvoiceStatusEnums::REJECTED->value:
                $invoice->releaseTaxpayerTaxable();
                break;
            case InvoiceStatusEnums::PENDING->value:
                $permissions = ['peut prendre en charge un avis sur titre', 'peut rejeter un avis sur titre (agent par délégation du receveur)'];
                $users = Constants::getUserWithPermission($permissions);
                if ($users && count($users) > 0) {
                    Notification::send($users, new InvoiceAccepted($invoice, Auth::user(), "agent_recette"));
                }
                break;
            case InvoiceStatusEnums::APPROVED->value:
            case InvoiceStatusEnums::APPROVED_CANCELLATION->value:
                $permissions = ['peut prendre en charge un avis sur titre', 'peut rejeter un avis sur titre (agent par délégation du receveur)', 'peut comptabiliser un paiement'];
                $users = Constants::getUserWithPermission($permissions);
                if ($users && count($users) > 0) {
                    Notification::send($users, new InvoiceApproved($invoice, Auth::user(), "regisseur"));
                }
                break;
            case InvoiceStatusEnums::CANCELED->value:
            case InvoiceStatusEnums::REDUCED->value:
                break;
            default:
        }
    }
    public function onLeave(Event $event)
    {
        $event->getSubject();
        $event->getTransition()->getFroms();
    }
    public function onTransition(Event $event)
    {
        $event->getSubject();
        $event->getTransition()->getName();
    }
    public function onGuard(Event $event)
    {
        $event->getSubject();
        $event->getTransition()->getName();
    }
}
