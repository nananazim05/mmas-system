<!DOCTYPE html>
<html>
<head>
    <title>Laporan Kehadiran Individu</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #B6192E; padding-bottom: 10px; }
        .header h1 { margin: 0; color: #B6192E; text-transform: uppercase; font-size: 18px; }
        .header p { margin: 2px 0; color: #555; }
        
        .profile-box { width: 100%; margin-bottom: 20px; background-color: #f9f9f9; padding: 10px; }
        .profile-box td { padding: 5px; vertical-align: top; }
        .label { font-weight: bold; width: 120px; color: #333; }

        table.list { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.list th, table.list td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        table.list th { background-color: #B6192E; color: white; }
        
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #aaa; }
        
        .status-hadir { color: green; font-weight: bold; }
        .status-xhadir { color: red; font-weight: bold; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Rekod Kehadiran Kakitangan</h1>
        <p>Sistem MMAS - Lembaga Perindustrian Kayu Malaysia</p>
    </div>

    <table class="profile-box">
        <tr>
            <td class="label">Nama:</td>
            <td><strong>{{ $user->name }}</strong></td>
            <td class="label">No. Pekerja:</td>
            <td>{{ $user->staff_number }}</td>
        </tr>
        <tr>
            <td class="label">Jawatan:</td>
            <td>{{ $user->section }}</td>
            <td class="label">Gred:</td>
            <td>{{ $user->grade }}</td>
        </tr>
        <tr>
            <td class="label">Bahagian:</td>
            <td colspan="3">{{ $user->division }}</td>
        </tr>
    </table>

    <h3>Sejarah Penyertaan Aktiviti</h3>

    <table class="list">
        <thead>
            <tr>
                <th style="width: 30px;">Bil</th>
                <th>Tajuk Aktiviti</th>
                <th>Tarikh & Tempat</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($histories as $index => $invite)
                @php
                    $attendance = $invite->meeting->attendances->first();
                    $isPast = \Carbon\Carbon::parse($invite->meeting->date)->endOfDay()->isPast();
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        {{ $invite->meeting->title }} <br>
                        <small>({{ $invite->meeting->activity_type }})</small>
                    </td>
                    <td>
                        {{ \Carbon\Carbon::parse($invite->meeting->date)->format('d/m/Y') }} <br>
                        {{ $invite->meeting->venue }}
                    </td>
                    <td>
                        @if($attendance)
                            <span class="status-hadir">HADIR</span><br>
                            <small>{{ \Carbon\Carbon::parse($attendance->scanned_at)->format('H:i A') }}</small>
                        @elseif($isPast)
                            <span class="status-xhadir">TIDAK HADIR</span>
                        @else
                            <span style="color: grey;">Belum Berlangsung</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; padding: 20px;">
                        Tiada rekod penyertaan dijumpai.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dijana oleh Sistem MMAS pada: {{ now()->format('d M Y, H:i A') }}
    </div>

</body>
</html>