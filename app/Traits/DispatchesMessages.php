<?php

namespace App\Traits;

trait DispatchesMessages
{
    /**
     * Dispatch an event with a message.
     *
     * @param string $resourceName The name of the resource.
     * @param string $eventType The type of event (e.g., 'create', 'update', 'delete', 'restore'). Default is 'create'.
     * @param string $type The type of message ('success', 'error'). Default is 'success'.
     * @param string|null $custom_message An optional custom message to override the default.
     * @return void
     */
    protected function dispatchMessage(string $resourceName, string $eventType = "create", string $type = "success", string $custom_message = null)
    {
        $message = match ($eventType) {
            'update' => __(':resource mis à jour', ['resource' => $resourceName]),
            'delete' => __(':resource supprimé', ['resource' => $resourceName]),
            'restore' => __(':resource restoré', ['resource' => $resourceName]),
            default => __(':resource créé', ['resource' => $resourceName]),
        };
        if ($type === "error" && $custom_message != null) {
            $this->dispatch($type, $custom_message);
        } else {
            $this->dispatch($type, $message);
        }
    }
}
