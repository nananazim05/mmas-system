<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Senarai Aktiviti</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
        }

        /* HEADER: Table untuk Logo & Tajuk */
        .header-table {
            width: 100%;
            margin-bottom: 10px;
            border-bottom: 2px solid #B6192E; /* Garis Merah */
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

        /* Nama & No Pekerja */
        .user-info {
            width: 100%;
            margin-bottom: 20px;
            font-size: 12px;
        }
        .user-info td {
            padding: 3px 0;
            font-weight: bold;
            color: #333;
        }

        /* JADUAL DATA */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #999; 
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }
        table.data-table th {
            background-color: #B6192E; /* Header*/
            color: white;
            text-transform: uppercase;
            font-size: 10px;
            font-weight: bold;
        }
        table.data-table tr:nth-child(even) {
            background-color: #f2f2f2; /* Zebra striping */
        }

        /* Helper Styles */
        .text-center { text-align: center; }
        .badge {
            font-size: 9px;
            color: #666;
            font-style: italic;
            display: block;
            margin-top: 2px;
        }
        .time-text {
            font-size: 9px;
            color: #444;
            display: block;
            margin-top: 2px;
        }
        
        /* STATUS COLORS */
        .status-hadir { color: #008000; font-weight: bold; text-transform: uppercase; font-size: 10px; }
        .status-tidak { color: #B6192E; font-weight: bold; text-transform: uppercase; font-size: 10px; }
        .status-belum { color: #777; font-style: italic; font-size: 10px; }
    </style>
</head>
<body>

    {{-- 1. HEADER LOGO & TAJUK --}}
    <table class="header-table">
        <tr>
            <td class="header-logo">
                <img src="{{ public_path('images/logo-mtib.png') }}" style="width: 90px; height: auto;">
            </td>
            <td class="header-title">
                <h2>SENARAI PROGRAM</h2>
                <p>Sistem MMAS - Lembaga Perindustrian Kayu Malaysia (MTIB)</p>
            </td>
        </tr>
    </table>

    {{-- 2. INFO PENGGUNA (Nama & No Pekerja) --}}
    <table class="user-info">
        <tr>
            <td width="15%">Nama:</td>
            <td width="45%">{{ Auth::user()->name }}</td>
            
            <td width="15%" style="text-align: right; padding-right: 10px;">No. Pekerja:</td>
            <td width="25%">{{ Auth::user()->staff_number ?? '-' }}</td>
        </tr>
    </table>

    {{-- 3. JADUAL SENARAI AKTIVITI --}}
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%" class="text-center">BIL</th>
                <th width="30%">PROGRAM</th>    
                <th width="20%">TARIKH & MASA</th>     
                <th width="25%">TEMPAT</th>            
                <th width="20%">PENGANJUR</th>
                
                {{-- Status hanya untuk bukan Admin --}}
                @if(Auth::user()->role !== 'admin')
                    <th width="15%">STATUS</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($meetings as $index => $meeting)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    
                    {{-- PROGRAM --}}
                    <td>
                        <strong style="font-size: 11px;">{{ $meeting->title }}</strong>
                        <span class="badge">({{ $meeting->activity_type }})</span>
                    </td>

                    {{-- TARIKH & MASA --}}
                    <td>
                        <div style="font-weight: bold;">{{ \Carbon\Carbon::parse($meeting->date)->format('d/m/Y') }}</div>
                        <span class="time-text">
                            {{ \Carbon\Carbon::parse($meeting->start_time)->format('h:i A') }} - 
                            {{ \Carbon\Carbon::parse($meeting->end_time)->format('h:i A') }}
                        </span>
                    </td>

                    {{-- TEMPAT --}}
                    <td>
                        {{ $meeting->venue }}
                    </td>

                    {{-- PENGANJUR --}}
                    <td>
                        {{ $meeting->organizer }}
                    </td>

                    {{-- STATUS (Untuk User Biasa) --}}
                    @if(Auth::user()->role !== 'admin')
                        <td>
                            @php
                                // Logic Status Kehadiran
                                $attendance = $meeting->attendances->where('user_id', Auth::id())->first();
                                $meetingEnd = \Carbon\Carbon::parse($meeting->date . ' ' . $meeting->end_time);
                                $isPast = now()->greaterThan($meetingEnd);
                            @endphp

                            @if($attendance)
                                <div class="status-hadir">HADIR</div>
                                <div style="font-size: 9px; color: #555;">{{ \Carbon\Carbon::parse($attendance->created_at)->format('h:i A') }}</div>
                            @elseif($isPast)
                                <div class="status-tidak">TIDAK HADIR</div>
                            @else
                                <div class="status-belum">Belum Berlangsung</div>
                            @endif
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="{{ Auth::user()->role !== 'admin' ? '6' : '5' }}" class="text-center" style="padding: 20px; color: #777;">
                        Tiada rekod aktiviti ditemui.
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