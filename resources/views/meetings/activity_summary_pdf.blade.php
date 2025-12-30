<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Aktiviti</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
        }
        
        /* HEADER STYLE (Ikut contoh gambar) */
        .header-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .header-title {
            font-size: 16px;
            font-weight: bold;
            color: #B6192E; /* Merah MTIB */
            text-transform: uppercase;
            margin: 0;
        }
        .header-subtitle {
            font-size: 10px;
            color: #555;
            margin-top: 5px;
        }
        .red-line {
            border-bottom: 2px solid #B6192E;
            margin-top: 10px;
            margin-bottom: 20px;
            width: 100%;
        }

        /* INFO BOX */
        .info-table {
            width: 100%;
            margin-bottom: 15px;
            font-size: 11px;
        }
        .info-label {
            font-weight: bold;
            width: 120px;
        }

        /* JADUAL DATA UTAMA */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
        }
        table.data-table th {
            background-color: #B6192E; 
            color: white; /* Tulisan Putih */
            padding: 8px;
            text-align: left;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
            border: 1px solid #B6192E;
        }
        table.data-table td {
            padding: 8px;
            border: 1px solid #ddd; 
            vertical-align: top;
        }
        /* Belang-belang */
        table.data-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        /* STATUS COLORS (Untuk Staf) */
        .status-hadir {
            color: #008000; /* Hijau */
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-tidak {
            color: #B6192E; /* Merah */
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-belum {
            color: #777; /* Kelabu */
            font-style: italic;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            font-size: 9px;
            text-align: center;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 5px;
        }
    </style>
</head>
<body>

    <div class="header-container">
        <div class="header-title">
            Laporan Senarai Aktiviti
            @if($request->year)
                ({{ $request->year }})
            @endif
        </div>
        <div class="header-subtitle">
            Sistem MMAS - Lembaga Perindustrian Kayu Malaysia (MTIB)
        </div>
        <div class="red-line"></div>
    </div>

    <table class="info-table">
        <tr>
            <td class="info-label">Nama:</td>
            <td>{{ Auth::user()->name }}</td>
            <td class="info-label" style="text-align: right;">Tarikh Laporan:</td>
            <td style="text-align: right; width: 120px;">{{ now()->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td class="info-label">E-mel:</td>
            <td>{{ Auth::user()->email }}</td>
            <td class="info-label" style="text-align: right;">Masa:</td>
            <td style="text-align: right;">{{ now()->format('h:i A') }}</td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th width="5%" style="text-align: center;">Bil</th>
                <th width="35%">Tajuk Aktiviti</th>
                <th width="25%">Tarikh & Tempat</th>
                <th width="20%">Penganjur</th>
                
                @if(Auth::user()->role !== 'admin')
                    <th width="15%">Status</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($meetings as $index => $meeting)
                <tr>
                    <td style="text-align: center;">{{ $loop->iteration }}</td>
                    
                    <td>
                        <div style="font-weight: bold; font-size: 12px;">{{ $meeting->title }}</div>
                        <div style="font-size: 10px; color: #666; margin-top: 2px;">({{ $meeting->activity_type }})</div>
                    </td>

                    <td>
                        <div style="font-weight: bold;">{{ \Carbon\Carbon::parse($meeting->date)->format('d/m/Y') }}</div>
                        <div style="font-size: 10px; margin-top: 2px;">{{ $meeting->venue }}</div>
                        <div style="font-size: 9px; color: #666;">
                            {{ \Carbon\Carbon::parse($meeting->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($meeting->end_time)->format('h:i A') }}
                        </div>
                    </td>

                    <td>
                        {{ $meeting->organizer }}
                    </td>

                    @if(Auth::user()->role !== 'admin')
                        <td>
                            @php
                                // Check Attendance
                                $attendance = $meeting->attendances->where('user_id', Auth::id())->first();
                                
                                // Check Invitation
                                $invitation = $meeting->invitations->where('user_id', Auth::id())->first();
                                
                                // Check Masa
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
                    <td colspan="{{ Auth::user()->role !== 'admin' ? '5' : '4' }}" style="text-align: center; padding: 20px; color: #777;">
                        Tiada rekod aktiviti dijumpai.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>