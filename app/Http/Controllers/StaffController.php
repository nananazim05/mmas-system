<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf; 
use App\Models\Invitation; 
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class StaffController extends Controller
{
    // 1. Senarai Staf (Dengan Fungsi Search)
    public function index(Request $request)
    {
        // Mula Query
        $query = User::query();

        // Logik Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('staff_number', 'LIKE', "%{$search}%")
                  ->orWhere('ic_number', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        // Susun & Dapatkan Data
        $users = $query->orderBy('name', 'asc')->get();

        return view('staff.index', compact('users'));
    }

    // 2. Borang Tambah Staf
    public function create()
    {
        return view('staff.create');
    }

    // 3. Simpan Staf Baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'ic_number' => ['required', 'string', 'max:12', 'unique:'.User::class],
            'staff_number' => ['required', 'string', 'max:20', 'unique:'.User::class],
            'section' => ['required', 'string'],
            'division' => ['required', 'string'],
            'grade' => ['required', 'string'],
            'role' => ['required', 'in:admin,staff'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'ic_number' => $request->ic_number,
            'staff_number' => $request->staff_number,
            'section' => $request->section,
            'division' => $request->division,
            'grade' => $request->grade,
            'role' => $request->role,
            'password' => Hash::make('password'), // Password default: 'password'
        ]);

        return redirect()->route('staff.index')->with('success', 'Staf berjaya didaftarkan.');
    }

    // 4. Borang Edit Staf
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('staff.edit', compact('user'));
    }

    // 5. Kemaskini Staf
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,'.$user->id], // Abaikan diri sendiri
            'ic_number' => ['required', 'string', 'unique:users,ic_number,'.$user->id],
            'staff_number' => ['required', 'string', 'unique:users,staff_number,'.$user->id],
            'section' => ['required', 'string'],
            'division' => ['required', 'string'],
            'grade' => ['required', 'string'],
            'role' => ['required', 'in:admin,staff'],
        ]);

        $user->update($request->except('password')); // Jangan update password di sini

        return redirect()->route('staff.index')->with('success', 'Maklumat staf berjaya dikemaskini.');
    }

    // 6. Padam Staf
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('staff.index')->with('success', 'Akaun staf telah dipadam.');
    }

    // 7. Lihat Rekod Kehadiran Staf (Admin View)
    public function show($id)
    {
        $user = User::findOrFail($id);

        // Ambil semua jemputan staf ini untuk semak kehadiran
        $histories = \App\Models\Invitation::where('user_id', $user->id)
                        ->with(['meeting', 'meeting.attendances' => function($q) use ($user) {
                            $q->where('user_id', $user->id);
                        }])
                        ->get()
                        ->sortByDesc(function($invitation) {
                            return $invitation->meeting->date;
                        });

        return view('staff.show', compact('user', 'histories'));
    }

    // 8. Jana Laporan Individu (PDF)
    public function report($id)
    {
        $user = User::findOrFail($id);

        // Ambil sejarah kehadiran staf ini
        $histories = Invitation::where('user_id', $user->id)
                        ->with(['meeting', 'meeting.attendances' => function($q) use ($user) {
                            $q->where('user_id', $user->id);
                        }])
                        ->get()
                        ->sortByDesc(function($invitation) {
                            return $invitation->meeting->date;
                        });

        // Jana PDF
        $pdf = Pdf::loadView('staff.report', compact('user', 'histories'));

        // Download: Laporan-NamaStaf.pdf
        return $pdf->download('Laporan-' . str_replace(' ', '-', $user->name) . '.pdf');
    }
}