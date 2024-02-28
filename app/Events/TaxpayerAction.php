<?php

namespace App\Events;

use App\Models\UserLogs;
use Illuminate\Http\Request;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TaxpayerAction
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data;
    public $request;

    /**
     * Create a new event instance.
     */
    public function __construct(Request $request, array $data)
    {
        $this->request = $request;
        $this->data = $data;
        $this->createUserLogsActivity();
    }


    private function createUserLogsActivity(){

        $data = [
            'user_id' => auth()->id(),
            'taxpayer_id' => $this->data['taxpayerId'],
            'ip_address' => $this->request->getClientIp(),
            'request' => json_encode([
                'path' => $this->request->url(),
                'path_info' => $this->request->getPathInfo(),
                'method' => $this->request->method(),
            ]),
            'response' => json_encode([
                'status' => $this->data['status'],
                'status_text' => $this->data['statusText'],
            ]),
        ];    

        UserLogs::create($data);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
