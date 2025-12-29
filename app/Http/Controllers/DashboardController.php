<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Ambil tarikh & masa sekarang
        $now = Carbon::now();
        $currentDate = $now->format('Y-m-d');
        $currentTime = $now->format('H:i:s');

        // TENTUKAN TARIKH KALENDAR
        $tarikhKalendar = $request->has('month') && $request->has('year')
            ? Carbon::create($request->year, $request->month, 1)
            : now();

        if ($user->role === 'admin') {
            // ================= ADMIN =================
            
            // 1. Statistik: Semua aktiviti tahun ini
            $jumlahAktivitiTahunIni = Meeting::whereYear('date', now()->year)->count();
            
            // 2. Senarai: Tunjuk 5 terawal sahaja (LIMIT 5)
            $aktivitiAkanDatang = Meeting::where(function($query) use ($currentDate, $currentTime) {
                                        $query->where('date', '>', $currentDate)
                                        ->orWhere(function($subQuery) use ($currentDate, $currentTime) {
                                            $subQuery->where('date', '=', $currentDate)
                                                     ->where('end_time', '>', $currentTime);
                                        });
                                    })
                                    ->orderBy('date', 'asc')
                                    ->orderBy('start_time', 'asc')
                                    ->limit(5) // <--- Limit untuk paparan senarai
                                    ->get();
            
            // 3. Jumlah Kad: Kira SEMUA aktiviti akan datang (TIADA LIMIT)
            $jumlahAkanDatang = Meeting::where(function($query) use ($currentDate, $currentTime) {
                                        $query->where('date', '>', $currentDate)
                                        ->orWhere(function($subQuery) use ($currentDate, $currentTime) {
                                            $subQuery->where('date', '=', $currentDate)
                                                     ->where('end_time', '>', $currentTime);
                                        });
                                    })->count(); 

            // 4. Data Kalendar
            $tarikhMeeting = Meeting::whereMonth('date', $tarikhKalendar->month)
                                    ->whereYear('date', $tarikhKalendar->year)
                                    ->pluck('date')
                                    ->toArray();

        } else {
            // ================= STAF =================

            // 1. Statistik: Hanya yang dia DIJEMPUT tahun ini
            $jumlahAktivitiTahunIni = Invitation::where('user_id', $user->id)
                                                ->whereHas('meeting', function($q) {
                                                    $q->whereYear('date', now()->year);
                                                })->count();

            // 2. Senarai: Tunjuk 5 terawal sahaja (LIMIT 5)
            $aktivitiAkanDatang = Meeting::whereHas('invitations', function($q) use ($user) {
                                        $q->where('user_id', $user->id);
                                    })
                                    ->where(function($query) use ($currentDate, $currentTime) {
                                        $query->where('date', '>', $currentDate)
                                        ->orWhere(function($subQuery) use ($currentDate, $currentTime) {
                                            $subQuery->where('date', '=', $currentDate)
                                                     ->where('end_time', '>', $currentTime);
                                        });
                                    })
                                    ->orderBy('date', 'asc')
                                    ->orderBy('start_time', 'asc')
                                    ->limit(5) // Limit paparan senarai
                                    ->get();
            
            // 3. Jumlah Kad: Kira SEMUA jemputan akan datang (TIADA LIMIT)
            $jumlahAkanDatang = Meeting::whereHas('invitations', function($q) use ($user) {
                                        $q->where('user_id', $user->id);
                                    })
                                    ->where(function($query) use ($currentDate, $currentTime) {
                                        $query->where('date', '>', $currentDate)
                                        ->orWhere(function($subQuery) use ($currentDate, $currentTime) {
                                            $subQuery->where('date', '=', $currentDate)
                                                     ->where('end_time', '>', $currentTime);
                                        });
                                    })
                                    ->count(); 

            // 4. Data Kalendar
            $tarikhMeeting = Meeting::whereMonth('date', $tarikhKalendar->month)
                                    ->whereYear('date', $tarikhKalendar->year)
                                    ->whereHas('invitations', function($q) use ($user) {
                                        $q->where('user_id', $user->id);
                                    })
                                    ->pluck('date')
                                    ->toArray();
        }

        return view('dashboard', compact(
            'jumlahAktivitiTahunIni',
            'aktivitiAkanDatang',
            'jumlahAkanDatang',
            'tarikhMeeting',
            'tarikhKalendar'
        ));
    }
}