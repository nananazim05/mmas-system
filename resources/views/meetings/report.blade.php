<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Program</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
        }

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

        /* MAKLUMAT PROGRAM */
        .info-table {
            width: 100%;
            margin-bottom: 20px;
            font-size: 12px;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 4px 0;
            vertical-align: top;
        }
        .label {
            font-weight: bold;
            width: 15%;
        }
        .colon {
            width: 2%;
            text-align: center;
        }
        .value {
            width: 83%;
        }

        /* JADUAL KEHADIRAN */
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
            background-color: #f2f2f2;
            color: #333;
            text-transform: uppercase;
            font-size: 10px;
            font-weight: bold;
        }
        
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .text-small { font-size: 9px; color: #666; display: block; margin-top: 2px; }
        .status-hadir { color: #008000; font-weight: bold; text-align: center; text-transform: uppercase; }
        .badge { background-color: #eee; padding: 2px 5px; border-radius: 3px; font-size: 9px; }
        
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
    </style>
</head>
<body>

    {{-- 1. HEADER LOGO & TAJUK --}}
    <table class="header-table">
        <tr>
            <td class="header-logo">
                <img src="{{ public_path('images/logo-mtib.png') }}" style="width: 100px; height: auto;">
            </td>
            <td class="header-title">
                <h2>LAPORAN PROGRAM</h2> 
                <p>Sistem MMAS - Lembaga Perindustrian Kayu Malaysia (MTIB)</p>
            </td>
        </tr>
    </table>

    {{-- Setting Locale untuk Tarikh Bahasa Melayu --}}
    @php
        \Carbon\Carbon::setLocale('ms');
    @endphp

    {{-- 2. BUTIRAN PROGRAM --}}
    <table class="info-table">
        <tr>
            <td class="label">Program</td> {{-- Ubah Label --}}
            <td class="colon">:</td>
            <td class="value"><strong>{{ $meeting->title }}</strong></td>
        </tr>
        <tr>
            <td class="label">Tarikh</td>
            <td class="colon">:</td>
            <td class="value">
                {{ \Carbon\Carbon::parse($meeting->date)->translatedFormat('d F Y') }}
            </td>
        </tr>
        <tr>
            <td class="label">Masa</td>
            <td class="colon">:</td>
            <td class="value">
                {{-- Format: 08:45 a.m. - 10:45 a.m. --}}
                {{ \Carbon\Carbon::parse($meeting->start_time)->format('h:i') }} 
                {{ \Carbon\Carbon::parse($meeting->start_time)->format('A') == 'AM' ? 'a.m.' : 'p.m.' }} 
                - 
                {{ \Carbon\Carbon::parse($meeting->end_time)->format('h:i') }}
                {{ \Carbon\Carbon::parse($meeting->end_time)->format('A') == 'AM' ? 'a.m.' : 'p.m.' }}
            </td>
        </tr>
        <tr>
            <td class="label">Tempat</td>
            <td class="colon">:</td>
            <td class="value">{{ $meeting->venue }}</td>
        </tr>
        <tr>
            <td class="label">Penganjur</td>
            <td class="colon">:</td>
            <td class="value">{{ $meeting->organizer }}</td>
        </tr>
    </table>

    <hr style="border: 0; border-top: 1px solid #ccc; margin: 15px 0;">

    <div style="margin-bottom: 10px; font-weight: bold; font-size: 12px;">
        Senarai Kehadiran ({{ $attendances->count() }} Orang)
    </div>

    {{-- 3. JADUAL SENARAI PESERTA --}}
    <table class="list">
        <thead>
            <tr>
                <th width="5%" class="text-center">Bil</th>
                <th width="35%">Nama Peserta</th>          
                <th width="35%">Bahagian / Syarikat</th> 
                <th width="15%" class="text-center">Waktu Imbas</th>
                <th width="10%" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attendances as $index => $attendance)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    
                    {{-- NAMA PESERTA --}}
                    <td>
                        {{-- Keutamaan kepada participant_name (dari join) atau user->name --}}
                        <div class="text-bold">{{ $attendance->participant_name ?? $attendance->user->name ?? 'Nama Tidak Dijumpai' }}</div>
                        
                        @if($attendance->user)
                            <span class="text-small">No. Pekerja: {{ $attendance->user->staff_number ?? '-' }}</span>
                            <span class="text-small">{{ $attendance->user->email }}</span>
                        @else
                            <span class="text-small">{{ $attendance->guest_email }}</span>
                            <div style="margin-top:2px;"><span class="badge">Peserta Luar</span></div>
                        @endif
                    </td>

                    {{-- BAHAGIAN / SYARIKAT + SEKSYEN --}}
                    <td>
                        @if($attendance->user)
                            {{-- Nama Division / Bahagian --}}
                            @if(!empty($attendance->user->section))
                            <div class="text-small">Seksyen: {{ $attendance->user->section }}</div>
                            
                            @endif
                            
                            {{-- Nama  --}}
                            <div>{{ $attendance->user->division ?? $attendance->user->department ?? '-' }}</div>
                        @else
                            {{-- Syarikat untuk peserta luar --}}
                            {{ $attendance->company_name ?? '-' }}
                        @endif
                    </td>

                    {{-- WAKTU IMBAS --}}
                    <td class="text-center">
                        {{ \Carbon\Carbon::parse($attendance->scanned_at)->format('h:i') }}
                        {{ \Carbon\Carbon::parse($attendance->scanned_at)->format('A') == 'AM' ? 'a.m.' : 'p.m.' }}
                    </td>

                    {{-- STATUS --}}
                    <td class="status-hadir">
                        @if(in_array(strtolower($attendance->status), ['present', 'hadir']))
                            Hadir
                        @else
                            {{ ucfirst($attendance->status) }}
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px; color: #777;">
                        Tiada kehadiran direkodkan setakat ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ now()->translatedFormat('d M Y, h:i') }} {{ now()->format('A') == 'AM' ? 'a.m.' : 'p.m.' }} oleh {{ Auth::user()->name }}
    </div>

</body>
</html>