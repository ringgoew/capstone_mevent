<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class TicketType
{
    // Mengambil jenis tiket berdasarkan event
    public function getByEvent($eventId)
    {
        return DB::table('ticket_types')
            ->where('event_id', $eventId)
            ->orderBy('id')
            ->get();
    }

    // Menambahkan jenis tiket
    public function createTicket($data)
    {
        $data['created_at'] = now();
        $data['updated_at'] = now();

        return DB::table('ticket_types')->insertGetId($data);
    }
}