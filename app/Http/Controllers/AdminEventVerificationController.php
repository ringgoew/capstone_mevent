<?php

namespace App\Http\Controllers;

use App\Models\AdminEventModeration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AdminEventVerificationController extends Controller
{
    /**
     * Memastikan Admin sudah login.
     */
    private function isAdmin(): bool
    {
        return session('admin_login') === true;
    }

    /**
     * Daftar event pending.
     */
    public function index()
    {
        if (!$this->isAdmin()) {
            return redirect()->route('admin.login');
        }

        $events = AdminEventModeration::getPendingEvents();

        return view(
            'admin.event-verification.index',
            compact('events')
        );
    }

    /**
     * Detail event dan tiket untuk diperiksa.
     */
    public function show(int $id)
    {
        if (!$this->isAdmin()) {
            return redirect()->route('admin.login');
        }

        $event = AdminEventModeration::findEvent($id);

        abort_if(!$event, 404, 'Event tidak ditemukan.');

        $tickets = AdminEventModeration::getTicketTypes($id);

        return view(
            'admin.event-verification.show',
            compact('event', 'tickets')
        );
    }

    /**
     * Publish event yang memenuhi persyaratan.
     */
    public function publish(int $id)
    {
        if (!$this->isAdmin()) {
            return redirect()->route('admin.login');
        }

        $event = AdminEventModeration::findEvent($id);

        abort_if(!$event, 404, 'Event tidak ditemukan.');

        if ($event->status !== 'pending') {
            return back()->with(
                'error',
                'Event ini sudah diproses dan tidak lagi berstatus pending.'
            );
        }

        $updated = AdminEventModeration::updatePendingStatus(
            $id,
            'published'
        );

        if (!$updated) {
            return back()->with(
                'error',
                'Status event berubah atau event sudah diproses.'
            );
        }

        $emailSent = $this->notifyEO(
            $event->eo_email,
            'Event Berhasil Dipublikasikan',
            "Halo {$event->eo_name},\n\n"
                . "Event '{$event->title}' telah disetujui oleh Admin "
                . "dan sekarang berstatus PUBLISHED.\n\n"
                . "Terima kasih."
        );

        $message = $emailSent
            ? 'Event berhasil dipublikasikan. Email konfirmasi berhasil dikirim.'
            : 'Event berhasil dipublikasikan, tetapi email belum terkirim. Periksa konfigurasi email.';

        return redirect()
            ->route('admin.event-verification.index')
            ->with('success', $message);
    }

    /**
     * Reject event dengan alasan yang wajib diisi.
     */
    public function reject(Request $request, int $id)
    {
        if (!$this->isAdmin()) {
            return redirect()->route('admin.login');
        }

        $data = $request->validate([
            'rejection_reason' => [
                'required',
                'string',
                'max:2000',
            ],
        ]);

        $event = AdminEventModeration::findEvent($id);

        abort_if(!$event, 404, 'Event tidak ditemukan.');

        if ($event->status !== 'pending') {
            return back()->with(
                'error',
                'Event ini sudah diproses dan tidak lagi berstatus pending.'
            );
        }

        $updated = AdminEventModeration::updatePendingStatus(
            $id,
            'rejected',
            $data['rejection_reason']
        );

        if (!$updated) {
            return back()->with(
                'error',
                'Status event berubah atau event sudah diproses.'
            );
        }

        $emailSent = $this->notifyEO(
            $event->eo_email,
            'Pengajuan Event Belum Disetujui',
            "Halo {$event->eo_name},\n\n"
                . "Event '{$event->title}' belum disetujui oleh Admin.\n\n"
                . "Alasan penolakan:\n"
                . $data['rejection_reason']
                . "\n\nSilakan periksa kembali pengajuan event Anda."
        );

        $message = $emailSent
            ? 'Event ditolak. Alasan penolakan berhasil dikirim melalui email.'
            : 'Event ditolak dan alasan tersimpan, tetapi email belum terkirim. Periksa konfigurasi email.';

        return redirect()
            ->route('admin.event-verification.index')
            ->with('success', $message);
    }

    /**
     * Mengirim notifikasi email kepada EO.
     */
    private function notifyEO(
        string $email,
        string $subject,
        string $body
    ): bool {
        try {
            Mail::raw($body, function ($message) use ($email, $subject) {
                $message->to($email)
                    ->subject($subject);
            });

            return true;
        } catch (\Throwable $e) {
            Log::error('Notifikasi verifikasi event gagal dikirim.', [
                'recipient' => $email,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }
}
