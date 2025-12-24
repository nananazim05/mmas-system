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

        // A. TENTUKAN TARIKH KALENDAR
        $tarikhKalendar = $request->has('month') && $request->has('year')
            ? Carbon::create($request->year, $request->month, 1)
            : now();

        // B. LOGIK MENGIKUT PERANAN
        if ($user->role === 'admin') {
            // --- ADMIN (Lihat Semua) ---
            
            // Statistik: Semua aktiviti tahun ini
            $jumlahAktivitiTahunIni = Meeting::whereYear('date', now()->year)->count();
            
            // Senarai: Semua aktiviti akan datang
            $aktivitiAkanDatang = Meeting::whereDate('date', '>=', now())
                                        ->orderBy('date', 'asc')
                                        ->limit(5)
                                        ->get();
            
            // Jumlah akan datang (Semua)
            $jumlahAkanDatang = Meeting::whereDate('date', '>=', now())->count();

            // Data Kalendar (Semua)
            $tarikhMeeting = Meeting::whereMonth('date', $tarikhKalendar->month)
                                    ->whereYear('date', $tarikhKalendar->year)
                                    ->pluck('date')
                                    ->toArray();

        } else {
            // --- STAF (Lihat Jemputan Sendiri Sahaja) ---

            // Statistik: Hanya yang dia DIJEMPUT tahun ini
            $jumlahAktivitiTahunIni = Invitation::where('user_id', $user->id)
                                                ->whereHas('meeting', function($q) {
                                                    $q->whereYear('date', now()->year);
                                                })->count();

            // Senarai: Hanya meeting yang dia DIJEMPUT dan belum lepas
           
            $aktivitiAkanDatang = Meeting::whereDate('date', '>=', now())
                                        ->whereHas('invitations', function($q) use ($user) {
                                            $q->where('user_id', $user->id);
                                        })
                                        ->orderBy('date', 'asc')
                                        ->limit(5)
                                        ->get();
            
            // Jumlah akan datang (Jemputan sahaja)
            $jumlahAkanDatang = $aktivitiAkanDatang->count();

            // Data Kalendar (Hanya tanda tarikh yang dia dijemput)
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
