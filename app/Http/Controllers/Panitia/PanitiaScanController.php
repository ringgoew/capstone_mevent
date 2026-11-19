<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $ticket = DB::table('e_tickets')
            ->join('orders', 'e_tickets.order_id', '=', 'orders.id')
            ->join('events', 'orders.event_id', '=', 'events.id')
            ->leftJoin('order_items', 'e_tickets.order_item_id', '=', 'order_items.id')
            ->leftJoin('ticket_types', 'order_items.ticket_type_id', '=', 'ticket_types.id')
            ->select(
                'e_tickets.id',
                'e_tickets.ticket_code',
                'e_tickets.status',
                'e_tickets.checked_in_at',
                'e_tickets.attendee_name',
                'e_tickets.attendee_email',
                'events.user_id as eo_user_id',
                'events.title as event_title',
                DB::raw("COALESCE(ticket_types.name, '-') as ticket_type_name")
            )
            ->where(function ($query) use ($code) {
                $query->where('e_tickets.ticket_code', $code)
                    ->orWhere('e_tickets.qr_code_hash', $code);
            })
            ->first();

        if (!$ticket) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Tiket tidak ditemukan di dalam sistem!'
            ], 404);
        }

        // Validasi agar panitia hanya bisa scan tiket dari event milik EO-nya sendiri
        $panitiaId = session('panitia_user_id') ?? session('user_id') ?? (Auth::check() ? Auth::user()->id : null);
        if ($panitiaId) {
            $ownerEoId = DB::table('users')->where('id', $panitiaId)->value('eo_id');
            if ($ownerEoId && (int) $ticket->eo_user_id !== (int) $ownerEoId) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Akses ditolak! Tiket ini bukan milik event penyelenggara Anda.'
                ], 403);
            }
        }

        // Cek apakah tiket sudah pernah digunakan
        if ($ticket->status === 'used') {
            // Jika baru saja di-scan <= 3 detik lalu (double-trigger kamera), tetap tampilkan sukses
            if ($ticket->checked_in_at && abs(now()->diffInSeconds(\Carbon\Carbon::parse($ticket->checked_in_at))) <= 3) {
                return response()->json([
                    'status'  => 'success',
                    'message' => 'Check-in Berhasil!',
                    'data'    => [
                        'attendee_name' => $ticket->attendee_name,
                        'event_title'   => $ticket->event_title,
                        'ticket_type'   => $ticket->ticket_type_name,
                        'ticket_code'   => $ticket->ticket_code,
                        'checked_in_at' => \Carbon\Carbon::parse($ticket->checked_in_at)->format('d M Y, H:i:s')
                    ]
                ]);
            }

            $waktuScan = $ticket->checked_in_at
                ? \Carbon\Carbon::parse($ticket->checked_in_at)->format('H:i:s')
                : '-';

            return response()->json([
                'status'  => 'warning',
                'message' => "Tiket sudah pernah digunakan (Check-in pukul {$waktuScan}) atas nama: {$ticket->attendee_name} ({$ticket->ticket_type_name})"
            ], 400);
        }

        $checkedInAt = now();

        DB::table('e_tickets')
            ->where('id', $ticket->id)
            ->update([
                'status'        => 'used',
                'checked_in_at' => $checkedInAt,
                'updated_at'    => $checkedInAt,
            ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Check-in Berhasil!',
            'data'    => [
                'attendee_name' => $ticket->attendee_name,
                'event_title'   => $ticket->event_title,
                'ticket_type'   => $ticket->ticket_type_name,
                'ticket_code'   => $ticket->ticket_code,
                'checked_in_at' => $checkedInAt->format('d M Y, H:i:s')
            ]
        ]);
    }
}
