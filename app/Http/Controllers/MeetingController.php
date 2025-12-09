<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\User;
use App\Models\Invitation;
use App\Mail\MeetingInvitation;
use App\Mail\MeetingUpdated;
use App\Mail\MeetingCancelled;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

class MeetingController extends Controller
{
    // 1. Senarai Aktiviti 
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Meeting::query();

        // Tapis Ikut Role
        if ($user->role !== 'admin') {
            // Staf: Hanya tengok yang dia ANJUR atau DIJEMPUT
            $query->where(function($q) use ($user) {
                $q->where('organizer_id', $user->id)
                  ->orWhereHas('invitations', function($subQ) use ($user) {
                      $subQ->where('user_id', $user->id);
                  });
            });
        }

        // Filter Bulan
        if ($request->filled('month')) {
            $query->whereMonth('date', $request->month);
        }

        // Filter Tahun
        if ($request->filled('year')) {
            $query->whereYear('date', $request->year);
        }

        // Dapatkan Data
        $meetings = $query->orderBy('date', 'desc')->get();

        return view('meetings.index', compact('meetings'));
    }

    // 2. Anjuran Saya 
    public function myActivities(Request $request)
    {
        // Mula Query: Hanya ambil aktiviti penganjur ini
        $query = Meeting::where('organizer_id', Auth::id());

        // 1. LOGIK CARIAN (Search)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")       // Cari Tajuk
                  ->orWhere('venue', 'LIKE', "%{$search}%")     // Cari Tempat
                  ->orWhere('activity_type', 'LIKE', "%{$search}%"); // Cari Jenis
            });
        }

        // 2. LOGIK BULAN (Filter)
        if ($request->filled('month')) {
            $query->whereMonth('date', $request->month);
        }

        // 3. LOGIK TAHUN (Filter)
        if ($request->filled('year')) {
            $query->whereYear('date', $request->year);
        }

        // Dapatkan data (susun tarikh terkini atas)
        $meetings = $query->orderBy('date', 'desc')->get();

        return view('meetings.my_activities', compact('meetings'));
    }

    // 3. Borang Cipta
    public function create()
    {
        $users = User::orderBy('name', 'asc')->get();
        return view('meetings.create', compact('users'));
    }

    // 4. Simpan Data (Store)
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'venue' => 'required|string',
            'activity_type' => 'required|string',
            'organizer_id' => 'required|exists:users,id',
            'invited_staff' => 'required_without:guest_emails',
            'guest_emails' => 'required_without:invited_staff',
        ], [
            'invited_staff.required_without' => 'Sila pilih sekurang-kurangnya seorang Staf atau masukkan E-mel Peserta Luar.',
            'guest_emails.required_without' => 'Sila pilih sekurang-kurangnya seorang Staf atau masukkan E-mel Peserta Luar.',
            'end_time.after' => 'Masa tamat mestilah selepas masa mula.',
        ]);

        $meeting = Meeting::create([
            'organizer_id' => $request->organizer_id,
            'title' => $request->title,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'venue' => $request->venue,
            'activity_type' => $request->activity_type,
            'qr_code_string' => Str::random(32),
            'status' => 'upcoming',
        ]);

        // Jemput STAF
        if ($request->has('invited_staff')) {
            foreach ($request->invited_staff as $userId) {
                Invitation::create([
                    'meeting_id' => $meeting->id,
                    'user_id' => $userId,
                    'status' => 'invited'
                ]);
                $user = User::find($userId);
                if ($user && $user->email) {
                    Mail::to($user->email)->send(new MeetingInvitation($meeting));
                }
            }
        }

        // Jemput LUAR
        if ($request->filled('guest_emails')) {
            $emails = explode(',', $request->guest_emails);
            foreach ($emails as $email) {
                $email = trim($email);
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    Invitation::create([
                        'meeting_id' => $meeting->id,
                        'guest_email' => $email,
                        'guest_name' => 'Peserta Luar',
                        'status' => 'invited'
                    ]);
                    Mail::to($email)->send(new MeetingInvitation($meeting));
                }
            }
        }

        return redirect()->route('activities.my')->with('success', 'Aktiviti berjaya dicipta & E-mel dihantar!');
    }

    // 5. Papar Butiran (Show)
    public function show(Meeting $meeting)
    {
        return view('meetings.show', compact('meeting'));
    }

    // 6. Borang Edit
    public function edit(Meeting $meeting)
    {
        if ($meeting->organizer_id != Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }
        $users = User::orderBy('name', 'asc')->get();
        return view('meetings.edit', compact('meeting', 'users'));
    }

    // 7. Update Data
    public function update(Request $request, Meeting $meeting)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'venue' => 'required|string',
            'activity_type' => 'required|string',
        ]);

        $meeting->update($request->all());

        // Hantar E-mel Update
        foreach ($meeting->invitations as $invite) {
            if ($invite->user_id) {
                $user = User::find($invite->user_id);
                if ($user && $user->email) Mail::to($user->email)->send(new MeetingUpdated($meeting));
            } elseif ($invite->guest_email) {
                Mail::to($invite->guest_email)->send(new MeetingUpdated($meeting));
            }
        }

        return redirect()->route('activities.my')->with('success', 'Aktiviti dikemaskini.');
    }

    // 8. Padam Data (Destroy)
    public function destroy(Meeting $meeting)
    {
        $title = $meeting->title;
        $date = $meeting->date;
        $invitations = $meeting->invitations;

        foreach ($invitations as $invite) {
            if ($invite->user_id) {
                $user = User::find($invite->user_id);
                if ($user && $user->email) Mail::to($user->email)->send(new MeetingCancelled($title, $date));
            } elseif ($invite->guest_email) {
                Mail::to($invite->guest_email)->send(new MeetingCancelled($title, $date));
            }
        }

        $meeting->delete();
        return redirect()->route('activities.my')->with('success', 'Aktiviti dibatalkan.');
    }

    // 9. Laporan PDF (Report)
    public function report(Meeting $meeting)
    {
        if (Auth::user()->role !== 'admin' && $meeting->organizer_id != Auth::id()) {
            abort(403, 'Anda tiada kebenaran untuk akses laporan ini.');
        }

        $attendances = $meeting->attendances()->with('user')->get();
        $pdf = Pdf::loadView('meetings.report', compact('meeting', 'attendances'));
        return $pdf->download('Laporan-' . Str::slug($meeting->title) . '.pdf');
    }

    // 10. API untuk Jana QR Dinamik (Dipanggil oleh Javascript setiap 10 minit)
    public function getQr(Meeting $meeting)
    {
        // Jana URL yang hanya sah selama 10 minit (Signed Route)
        $dynamicUrl = \Illuminate\Support\Facades\URL::temporarySignedRoute(
            'attendance.scan', 
            now()->addMinutes(10), // Expire dalam 10 minit
            [
                'meeting' => $meeting->id, 
                'code' => $meeting->qr_code_string
            ]
        );

        // Hasilkan imej QR Code
        $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(300)->generate($dynamicUrl);

        return response()->json([
            'qr_code' => (string) $qrCode,
            'url' => $dynamicUrl // Kita hantar URL juga untuk link 'Simulasi'
        ]);
    }
    
    // 11. Fungsi Aktifkan Semula (Re-activate)
    public function reactivate(Meeting $meeting)
    {
        // Kita lanjutkan masa tamat sebanyak 30 minit dari sekarang
        $meeting->update([
            'end_time' => now()->addMinutes(30)->format('H:i:s'),
            'status' => 'active' // Paksa status jadi aktif
        ]);

        return redirect()->back()->with('success', 'Kod QR diaktifkan semula selama 30 minit.');
    }

    // 12. Lihat Laporan di Browser (Stream)
    public function viewReport(Meeting $meeting)
    {
        // 1. Semak Kebenaran (Admin ATAU Penganjur sahaja)
        if (Auth::user()->role !== 'admin' && $meeting->organizer_id != Auth::id()) {
            abort(403, 'Anda tiada kebenaran untuk melihat laporan ini.');
        }

        // 2. Ambil data kehadiran
        $attendances = $meeting->attendances()->with('user')->get();

        // 3. Jana PDF
        $pdf = Pdf::loadView('meetings.report', compact('meeting', 'attendances'));

        // 4. Paparkan di browser (Stream)
        return $pdf->stream('Laporan-' . Str::slug($meeting->title) . '.pdf');
    }
}
