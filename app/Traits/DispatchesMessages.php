<?php

namespace App\Traits;

trait DispatchesMessages
{


    /**
     * Dispatch a success event with a message.
     *
     * @param string $resourceName
     * @param string $eventType
     * @param string $type
     * @return void
     */
    protected function dispatchMessage(string $resourceName, string $eventType = "create", string $type = "success")
    {
        switch ($eventType) {
            case 'update':
                $message = __(':resource mis à jour', ['resource' => $resourceName]);
                break;
            case 'delete':
                $message = __(':resource supprimé', ['resource' => $resourceName]);
                break;
            default:
                $message = __(':resource créé', ['resource' => $resourceName]);
                break;
        }

        $this->dispatch($type, $message);
    }

}
