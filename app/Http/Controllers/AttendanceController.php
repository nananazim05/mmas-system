<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function scan(Request $request, $meeting_id, $code)
    {
        // 1. Cari Meeting
        $meeting = Meeting::where('id', $meeting_id)
                          ->where('qr_code_string', $code)
                          ->firstOrFail();

        // 2. Semak Validasi URL (Untuk QR Dinamik)
    if (! $request->hasValidSignature()) {
        
        // --- MULA KOD DEBUG (Hanya sementara) ---
        // Kita paksa dia tunjuk kenapa dia gagal validasi
        dd([
            'STATUS' => 'GAGAL: Signature Invalid',
            '1. Apa URL yang Laravel nampak?' => $request->url(),
            '2. Apa URL Penuh (termasuk ?signature=...)?' => $request->fullUrl(),
            '3. Adakah Laravel kesan HTTPS?' => $request->secure() ? 'YA (Betul)' : 'TIDAK (Ini Masalahnya!)',
            '4. Signature yang dihantar' => $request->query('signature'),
            '5. Header X-Forwarded-Proto' => $request->header('X-Forwarded-Proto'), // Render hantar signal https kat sini
        ]);
        // --- TAMAT KOD DEBUG ---

        // Saya komenkan baris error ini supaya anda boleh nampak data di atas
        // abort(403, 'Kod QR ini telah luput atau tidak sah. Sila imbas Kod QR terkini di skrin penganjur.');
    }

    // 3. Semak Waktu Mesyuarat (Aktif 15 minit sebelum & 15 minit selepas)
    $now = Carbon::now();
    $startTime = Carbon::parse($meeting->date . ' ' . $meeting->start_time)->subMinutes(15);
    $endTime = Carbon::parse($meeting->date . ' ' . $meeting->end_time)->addMinutes(15);

    // Jika masa belum sampai
    if ($now->lessThan($startTime)) {
        abort(403, 'Pendaftaran kehadiran belum dibuka. Sila tunggu 15 minit sebelum aktiviti bermula.');
    }

    // Jika masa dah tamat (Kecuali status disetkan 'active' manual oleh penganjur)
    if ($now->greaterThan($endTime) && $meeting->status !== 'active') {
        abort(403, 'Masa pendaftaran kehadiran telah tamat. Sila minta penganjur aktifkan semula kod QR.');
    }

    // Jika Lulus, tunjuk borang
    return view('attendance.form', compact('meeting'));
}

    // 2. Proses Simpan Kehadiran
    public function store(Request $request, Meeting $meeting)
    {
        // Validasi
        $request->validate([
            'type' => 'required|in:staff,guest',
            'guest_name' => 'required_if:type,guest',
            'guest_email' => 'required_if:type,guest|nullable|email',
        ]);

        // Data untuk disimpan
        $data = [
            'meeting_id' => $meeting->id,
            'scanned_at' => now(),
            'status' => 'present',
        ];

        if ($request->type == 'staff') {
            // Jika Staf (Pastikan Login)
            if (!Auth::check()) {
                return redirect()->route('login');
            }
            $data['user_id'] = Auth::id();
            
            // Cek Duplikasi (Tak nak scan 2 kali)
            $exists = Attendance::where('meeting_id', $meeting->id)->where('user_id', Auth::id())->exists();
        } else {
            // Jika Tetamu Luar
            $data['guest_email'] = $request->guest_email;
            
            // Cek Duplikasi Email
            $exists = Attendance::where('meeting_id', $meeting->id)->where('guest_email', $request->guest_email)->exists();
        }

        if ($exists) {
            return redirect()->back()->with('error', 'Anda telah mendaftar kehadiran sebelum ini.');
        }

        // Simpan!
        Attendance::create($data);

        return redirect()->back()->with('success', 'Kehadiran berjaya direkodkan! Terima kasih.');
    }

    // 3. Senarai Rekod Kehadiran Saya (Berdasarkan Jemputan)
    public function history()
    {
        // 1. Ambil semua jemputan untuk user ini
        $histories = \App\Models\Invitation::where('user_id', Auth::id())
                        ->with(['meeting', 'meeting.attendances' => function($q) {
                            // Ambil data kehadiran user ini untuk meeting tersebut (jika ada)
                            $q->where('user_id', Auth::id());
                        }])
                        ->get()
                        ->sortByDesc(function($invitation) {
                            return $invitation->meeting->date;
                        });

        return view('attendance.history', compact('histories'));
    }
}
