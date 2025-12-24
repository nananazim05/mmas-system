<!DOCTYPE html>
<html>
<head>
    <title>Laporan Kehadiran</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { margin: 0; color: #B6192E; text-transform: uppercase; }
        .header p { margin: 2px 0; color: #555; }
        
        .info-box { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
        .info-box td { padding: 5px; }
        .label { font-weight: bold; width: 100px; }

        table.list { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.list th, table.list td { border: 1px solid #ddd; padding: 8px; text-align: left; vertical-align: top; }
        table.list th { background-color: #f2f2f2; color: #333; }
        
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #aaa; }
        
        /* Helper classes */
        .text-bold { font-weight: bold; }
        .text-small { font-size: 10px; color: #666; }
        .badge { background-color: #eee; padding: 2px 5px; border-radius: 3px; font-size: 10px; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Laporan Kehadiran Aktiviti</h1>
        <p>Sistem MMAS - Lembaga Perindustrian Kayu Malaysia (MTIB)</p>
    </div>

    <table class="info-box">
        <tr>
            <td class="label">Tajuk:</td>
            <td>{{ $meeting->title }}</td>
        </tr>
        <tr>
            <td class="label">Tarikh:</td>
            <td>{{ \Carbon\Carbon::parse($meeting->date)->format('d F Y') }}</td>
        </tr>
        <tr>
            <td class="label">Masa:</td>
            <td>
                {{ \Carbon\Carbon::parse($meeting->start_time)->format('H:i') }} - 
                {{ \Carbon\Carbon::parse($meeting->end_time)->format('H:i') }}
            </td>
        </tr>
        <tr>
            <td class="label">Tempat:</td>
            <td>{{ $meeting->venue }}</td>
        </tr>
        <tr>
            <td class="label">Penganjur:</td>
            <td>{{ $meeting->organizer->name ?? 'MTIB' }}</td>
        </tr>
    </table>

    <hr>

    <h3>Senarai Kehadiran ({{ $attendances->count() }} Orang)</h3>

    <table class="list">
        <thead>
            <tr>
                <th style="width: 30px;">Bil</th>
                <th>Nama Peserta & Identiti</th>
                <th>Syarikat / Bahagian</th>
                <th>Waktu Imbas</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attendances as $index => $attendance)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    
                    <td>
                        <div class="text-bold">{{ $attendance->participant_name ?? 'Nama Tidak Dijumpai' }}</div>
                        
                        @if($attendance->user)
                            <div class="text-small">No. Pekerja: {{ $attendance->user->staff_number ?? '-' }}</div>
                            <div class="text-small">{{ $attendance->user->email }}</div>
                        @else
                            <div class="text-small">{{ $attendance->guest_email }}</div>
                            <span class="badge">Peserta Luar</span>
                        @endif
                    </td>

                    <td>
                        @if($attendance->user)
                            MTIB
                            <br>
                            <span class="text-small">
                                {{ $attendance->department ?? $attendance->user->division ?? $attendance->user->section ?? '-' }}
                            </span>
                        @else
                            {{ $attendance->company_name ?? '-' }}
                        @endif
                    </td>

                    <td>
                        {{ \Carbon\Carbon::parse($attendance->scanned_at)->format('H:i:s A') }}
                    </td>

                    <td style="color: green; font-weight: bold; text-align: center;">
                        @if($attendance->status == 'present' || $attendance->status == 'Hadir')
                            Hadir
                        @else
                            {{ ucfirst($attendance->status) }}
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px;">
                        Tiada kehadiran direkodkan setakat ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ now()->format('d M Y, H:i A') }} oleh {{ Auth::user()->name }}
    </div>

</body>
</html>