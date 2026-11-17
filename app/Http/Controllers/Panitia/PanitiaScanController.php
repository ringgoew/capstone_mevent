<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PanitiaScanController extends Controller
{
    // Menampilkan halaman utama scanner (kamera / input kode tiket)
    public function index()
    {
        return view('eo.panitia.scan');
    }

    // Memproses hasil scan QR code atau input kode tiket
    public function processScan(Request $request)
    {
        $request->validate([
            'ticket_code' => 'required|string',
        ]);

        $code = trim($request->ticket_code);

        // 1. Cari data tiket di tabel e_tickets berdasarkan ticket_code atau qr_code_hash
        $ticket = DB::table('e_tickets')
            ->join('orders', 'e_tickets.order_id', '=', 'orders.id')
            ->join('events', 'orders.event_id', '=', 'events.id')
            ->join('order_items', 'e_tickets.order_item_id', '=', 'order_items.id')
            ->join('ticket_types', 'order_items.ticket_type_id', '=', 'ticket_types.id')
            ->select(
                'e_tickets.id',
                'e_tickets.ticket_code',
                'e_tickets.status',
                'e_tickets.attendee_name',
                'e_tickets.attendee_email',
                'events.title as event_title',
                'ticket_types.name as ticket_type_name'
            )
            ->where('e_tickets.ticket_code', $code)
            ->orWhere('e_tickets.qr_code_hash', $code)
            ->first();

        // 2. Jika tiket tidak ditemukan
        if (!$ticket) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tiket tidak ditemukan di dalam sistem!'
            ], 404);
        }

        // 3. Cek apakah tiket sudah pernah digunakan (status 'used')
        if ($ticket->status === 'used') {
            return response()->json([
                'status' => 'warning',
                'message' => "Tiket sudah pernah digunakan (Sudah Check-in) atas nama: {$ticket->attendee_name} ({$ticket->ticket_type_name})"
            ], 400);
        }

        // 4. Jika status valid, ubah status menjadi 'used' dan catat waktu check-in
        DB::table('e_tickets')
            ->where('id', $ticket->id)
            ->update([
                'status' => 'used',
                'checked_in_at' => now(),
                'updated_at' => now(),
            ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Check-in Berhasil!',
            'data' => [
                'attendee_name' => $ticket->attendee_name,
                'event_title' => $ticket->event_title,
                'ticket_type' => $ticket->ticket_type_name,
                'ticket_code' => $ticket->ticket_code,
                'checked_in_at' => now()->format('d M Y, H:i:s')
            ]
        ]);
    }
}
