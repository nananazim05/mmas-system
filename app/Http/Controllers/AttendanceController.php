<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    // 1. Paparan Borang Imbasan (Scan)
    public function scan(Request $request, $meeting_id, $code)
    {
        // Cari Meeting berdasarkan ID dan QR string
        $meeting = Meeting::where('id', $meeting_id)
                          ->where('qr_code_string', $code)
                          ->firstOrFail();

        // Semak Validasi URL (Untuk keselamatan QR Dinamik)
        if (! $request->hasValidSignature()) {
            abort(403, 'Kod QR ini telah luput atau tidak sah. Sila imbas Kod QR terkini di skrin penganjur.');
        }

        // Semak Waktu Mesyuarat (Aktif 15 minit sebelum & 15 minit selepas)
        $now = Carbon::now();
        $startTime = Carbon::parse($meeting->date . ' ' . $meeting->start_time)->subMinutes(15);
        $endTime = Carbon::parse($meeting->date . ' ' . $meeting->end_time)->addMinutes(15);

        // Jika masa belum sampai
        if ($now->lessThan($startTime)) {
            abort(403, 'Pendaftaran kehadiran belum dibuka.');
        }

        // Jika masa dah tamat (Kecuali status disetkan 'active' manual oleh penganjur)
        if ($now->greaterThan($endTime) && $meeting->status !== 'active') {
            abort(403, 'Masa pendaftaran kehadiran telah tamat. Sila minta penganjur aktifkan semula kod QR.');
        }

        // Jika Lulus, tunjuk borang kehadiran
        return view('attendance.form', compact('meeting'));
    }

    // 2. Proses Simpan Kehadiran (Logic Utama)
    public function store(Request $request)
    {
        $request->validate([
            'meeting_id' => 'required',
        ]);

        $attendance = new Attendance();
        $attendance->meeting_id = $request->meeting_id; 
        $attendance->scanned_at = now(); 
        $attendance->status = 'Hadir';

        // emak berdasarkan Tab yang dipilih (staff atau guest)
        if ($request->attendance_type === 'staff') {
            
            // --- SENARIO 1: STAF MTIB ---
            // Cari user berdasarkan column 'staff_number'
            $registeredUser = User::where('staff_number', $request->staff_id)->first();

            if (!$registeredUser) {
                return redirect()->back()
                        ->withInput()
                        ->with('error', 'Maaf, No. Pekerja tidak dijumpai. Sila semak semula.');
            }

            $attendance->user_id = $registeredUser->id; 
            $attendance->participant_name = $registeredUser->name; 
            $attendance->participant_email = $registeredUser->email;
            $attendance->participant_type = 'Staf MTIB';
            $attendance->department = $registeredUser->department ?? $registeredUser->division ?? null; 
            $attendance->company_name = 'MTIB';

        } else {
            // --- SENARIO 2: PESERTA LUAR ---
            $attendance->user_id = null;
            $attendance->participant_name = $request->name;
            $attendance->participant_email = $request->email;
            $attendance->participant_type = 'Peserta Luar';
            $attendance->company_name = $request->company_name ?? null; 
        }

        $attendance->save();

        return redirect()->back()->with('success', 'Kehadiran berjaya direkodkan! Terima kasih.');
    }

    // 3. Senarai Rekod Kehadiran Saya (Berdasarkan Jemputan)
    public function history()
    {
        // Ambil semua jemputan untuk user yang sedang login
        $histories = \App\Models\Invitation::where('staff_number', Auth::id())
                        ->with(['meeting', 'meeting.attendances' => function($q) {
                            // Ambil data kehadiran user ini untuk meeting tersebut
                            $q->where('user_id', Auth::id());
                        }])
                        ->get()
                        ->sortByDesc(function($invitation) {
                            // Susun ikut tarikh terkini
                            return $invitation->meeting->date ?? ''; 
                        });

        return view('attendance.history', compact('histories'));
    }
}