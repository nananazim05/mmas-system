<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Rekod Kehadiran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
        }

        /* HEADER STYLE */
        .header-table {
            width: 100%;
            margin-bottom: 20px;
            border-bottom: 2px solid #B6192E;
            padding-bottom: 10px;
        }
        .header-logo {
            width: 15%;
            vertical-align: middle;
            text-align: left;
        }
        .header-title {
            width: 85%;
            text-align: center;
            vertical-align: middle;
            padding-right: 15%;
        }
        .header-title h2 {
            margin: 0;
            color: #B6192E;
            text-transform: uppercase;
            font-size: 18px;
        }
        .header-title p {
            margin: 5px 0 0;
            font-size: 10px;
            color: #555;
            text-transform: uppercase;
        }

        /* MAKLUMAT STAF */
        .profile-box {
            width: 100%;
            margin-bottom: 20px;
            background-color: #f9f9f9;
            padding: 10px;
            border-radius: 5px;
            font-size: 12px;
        }
        .profile-box td {
            padding: 5px;
            vertical-align: top;
        }
        .label {
            font-weight: bold;
            width: 15%;
            color: #333;
        }
        .value {
            width: 35%;
        }

        /* JADUAL SENARAI */
        table.list {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table.list th, table.list td {
            border: 1px solid #999;
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }
        table.list th {
            background-color: #B6192E;
            color: white;
            text-transform: uppercase;
            font-size: 10px;
            font-weight: bold;
        }
        
        /* STATUS */
        .status-hadir { color: #008000; font-weight: bold; text-transform: uppercase; font-size: 10px; }
        .status-xhadir { color: #B6192E; font-weight: bold; text-transform: uppercase; font-size: 10px; }
        .badge { font-size: 9px; color: #666; font-style: italic; display: block; margin-top: 2px; }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 9px;
            color: #aaa;
            border-top: 1px solid #eee;
            padding-top: 5px;
        }

        .text-center { text-align: center; }
    </style>
</head>
<body>

    {{-- 1. HEADER LOGO & TAJUK --}}
    <table class="header-table">
        <tr>
            <td class="header-logo">
                <img src="{{ public_path('images/logo-mtib.png') }}" style="width: 80px; height: auto;">
            </td>
            <td class="header-title">
                <h2>REKOD KEHADIRAN</h2>
                <p>Sistem MMAS - Lembaga Perindustrian Kayu Malaysia (MTIB)</p>
            </td>
        </tr>
    </table>

    {{-- 2. MAKLUMAT STAF --}}
    <table class="profile-box">
        <tr>
            <td class="label">Nama:</td>
            <td class="value"><strong>{{ $user->name }}</strong></td>
            
            <td class="label">No. Pekerja:</td>
            <td class="value">{{ $user->staff_number ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Seksyen:</td>
            <td class="value">{{ $user->section ?? '-' }}</td>
            
            <td class="label">Gred:</td>
            <td class="value">{{ $user->grade ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Bahagian:</td>
            <td colspan="3">{{ $user->division ?? '-' }}</td>
        </tr>
    </table>

    {{-- TAJUK SEKSYEN --}}
    <div style="margin-bottom: 5px; font-weight: bold; font-size: 12px; margin-top: 20px;">
        Sejarah Penyertaan Program
    </div>

    {{-- 3. JADUAL REKOD KEHADIRAN --}}
    <table class="list">
        <thead>
            <tr>
                <th width="5%" class="text-center">Bil</th>
                <th width="40%">Program</th>
                <th width="35%">Tarikh & Tempat</th>
                <th width="20%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($histories as $index => $invite)
                @php
                    // Ambil attendance rekod pertama
                    $attendance = $invite->meeting->attendances->first();
                    
                    // Logic check expired
                    $meetingDate = \Carbon\Carbon::parse($invite->meeting->date . ' ' . $invite->meeting->end_time);
                    $isPast = now()->greaterThan($meetingDate);
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    
                    {{-- PROGRAM --}}
                    <td>
                        <strong>{{ $invite->meeting->title }}</strong>
                        <span class="badge">({{ $invite->meeting->activity_type }})</span>
                    </td>

                    {{-- TARIKH & TEMPAT --}}
                    <td>
                        <strong>{{ \Carbon\Carbon::parse($invite->meeting->date)->format('d/m/Y') }}</strong>
                        <br>
                        {{ $invite->meeting->venue }}
                    </td>

                    {{-- STATUS --}}
                    <td>
                        @if($attendance)
                            <div class="status-hadir">HADIR</div>
                            <div style="font-size: 9px; color: #555;">
                                {{ \Carbon\Carbon::parse($attendance->scanned_at)->format('h:i A') }}
                            </div>
                        @elseif($isPast)
                            <div class="status-xhadir">TIDAK HADIR</div>
                        @else
                            <div style="color: grey; font-style: italic; font-size: 10px;">Belum Berlangsung</div>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center" style="padding: 20px; color: #777;">
                        Tiada rekod penyertaan dijumpai.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ now()->format('d M Y, h:i A') }} oleh Sistem MMAS
    </div>

</body>
</html>