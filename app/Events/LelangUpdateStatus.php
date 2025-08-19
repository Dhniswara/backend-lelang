<?php

namespace App\Events;

use App\Models\LelangBarang;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class LelangUpdateStatus implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $lelangId;
    public $status;

    // terima model, bukan id
    public function __construct(LelangBarang $barang)
    {
        $this->lelangId = $barang->id;
        $this->status   = $barang->status;
    }

    public function broadcastOn()
    {
        return new Channel('lelang'); // public channel, pakai PrivateChannel kalau perlu auth
    }

    // nama event yang client bind
    public function broadcastAs()
    {
        return 'LelangUpdateStatusEvent'; 
    }

    // payload eksplisit ke client
    public function broadcastWith()
    {
        return [
            'lelangId' => $this->lelangId,
            'status'   => $this->status,
        ];
    }
}