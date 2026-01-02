<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
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

        // Semak Validasi URL (Untuk keselamatan QR Dinamik/Signed Route)
        if (! $request->hasValidSignature()) {
            abort(403, 'Kod QR ini telah luput atau tidak sah. Sila imbas Kod QR terkini di skrin penganjur.');
        }

        // Tetapkan Waktu Mula & Tamat
        $now = Carbon::now();
        $startTime = Carbon::parse($meeting->date . ' ' . $meeting->start_time)->subMinutes(60);
        $endTime = Carbon::parse($meeting->date . ' ' . $meeting->end_time)->addMinutes(15);

        // 4. Semak jika terlalu awal
        if ($now->lessThan($startTime)) {
            abort(403, 'Pendaftaran kehadiran belum dibuka.');
        }

        // Semak jika masa dah tamat
        // Mula-mula, check dulu ada tak "Lesen Sementara" (Cache) 
        $isExtended = Cache::has('meeting_extended_' . $meeting->id);

        // Jika (Masa Dah Tamat) DAN (Tiada Lesen Cache) -> Block.
        // Kalau masa dah tamat tapi ADA lesen cache -> Dia akan lepas.
        if ($now->greaterThan($endTime) && !$isExtended) {
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

        // --- 1. SETUP AWAL ---
        $attendance = new Attendance();
        $attendance->meeting_id = $request->meeting_id;
        $attendance->scanned_at = now();
        $attendance->status = 'present'; 

        // --- 2. LOGIC MENGIKUT JENIS PENGGUNA ---
        
        if ($request->attendance_type === 'staff') {
            
            // === SENARIO A: STAF MTIB ===
            
            // 1. Cari Staf
            $registeredUser = User::where('staff_number', $request->staff_id)->first();

            // Error jika staff tak jumpa
            if (!$registeredUser) {
                return redirect()->back()->withInput()->with('error', 'Maaf, No. Pekerja tidak dijumpai.');
            }

            // 2. Semak Duplicate (Guna User ID)
            $alreadyRegistered = Attendance::where('meeting_id', $request->meeting_id)
                                           ->where('user_id', $registeredUser->id)
                                           ->exists();

            if ($alreadyRegistered) {
                return redirect()->back()->with('error', 'Anda telah mendaftar kehadiran sebelum ini.');
            }

            // 3. Simpan Data Staf
            $attendance->user_id = $registeredUser->id;
            $attendance->participant_name = $registeredUser->name; 
            $attendance->guest_email = $registeredUser->email; 
            $attendance->participant_type = 'Staf MTIB';
            $attendance->department = $registeredUser->division ?? $registeredUser->section ?? null; 
            $attendance->company_name = 'MTIB';

        } else {
            
            // === SENARIO B: PESERTA LUAR (GUEST) ===
            
            // 1. [BARU] Semak Duplicate (Guna Email)
            $alreadyRegistered = Attendance::where('meeting_id', $request->meeting_id)
                                           ->where('guest_email', $request->email)
                                           ->exists();

            if ($alreadyRegistered) {
                return redirect()->back()->with('error', 'E-mel ini telah didaftarkan untuk kehadiran sebelum ini.');
            }

            // 2. Simpan Data Guest
            $attendance->user_id = null;
            
            // Pastikan Nama disimpan 
            $attendance->participant_name = $request->name; 
            
            // Pastikan Email disimpan
            $attendance->guest_email = $request->email;
            
            // Pastikan Company disimpan
            $attendance->company_name = $request->company_name ?? '-'; 
            
            $attendance->participant_type = 'Peserta Luar';
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