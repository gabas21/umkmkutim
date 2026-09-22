<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventParticipantController extends Controller
{
    public function index(Event $event)
    {
        $participants = $event->participants()->with('umkm')->latest()->get();

        return view('admin.events.participants', compact('event', 'participants'));
    }

    public function store(Request $request, Event $event)
    {
        if (! Auth::guard('pelaku_usaha')->check()) {
            return redirect()->route('register')->with('warning', 'Silakan login sebagai pelaku usaha untuk mendaftar event.');
        }

        if ($event->status !== 'open') {
            return back()->with('error', 'Event ini belum dibuka untuk pendaftaran.');
        }

        $pelaku = Auth::guard('pelaku_usaha')->user();
        $umkm = $pelaku->umkmTerverifikasi()->first();

        if (! $umkm) {
            return back()->with('warning', 'Hanya pelaku usaha dengan UMKM terverifikasi yang bisa mendaftar event.');
        }

        if ($event->quota !== null && $event->participants()->count() >= $event->quota) {
            return back()->with('warning', 'Kuota event sudah penuh.');
        }

        if ($event->participants()->where('umkm_id', $umkm->id)->exists()) {
            return back()->with('warning', 'Anda sudah terdaftar pada event ini.');
        }

        EventParticipant::create([
            'event_id' => $event->id,
            'umkm_id' => $umkm->id,
            'user_id' => null,
            'status' => 'registered',
        ]);

        return back()->with('success', 'Pendaftaran event berhasil dikirim. Tim akan segera memproses konfirmasi Anda.');
    }

    public function updateStatus(Request $request, Event $event, EventParticipant $participant)
    {
        if ($participant->event_id !== $event->id) {
            abort(404);
        }

        $validated = $request->validate([
            'status' => ['required', 'in:registered,attended,cancelled'],
        ]);

        $participant->update(['status' => $validated['status']]);

        return back()->with('success', 'Status peserta berhasil diperbarui.');
    }
}
