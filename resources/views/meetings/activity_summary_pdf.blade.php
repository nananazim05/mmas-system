<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Senarai Aktiviti</title>
    <style>
        /* --- CSS UNTUK PDF (DomPDF) --- */
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #000;
        }
        
        /* Header Laporan */
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
            font-weight: bold;
        }
        .header h2 {
            margin: 5px 0 0 0;
            font-size: 14px;
            font-weight: normal;
        }
        
        /* Info Filter (Metadata) */
        .info-section {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table {
            width: 100%;
            border: none;
        }
        .info-table td {
            padding: 2px;
            vertical-align: top;
        }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }

        /* Jadual Data Utama */
        table.main-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table.main-table th, table.main-table td {
            border: 1px solid #000; /* Border hitam penuh */
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }
        table.main-table th {
            background-color: #e0e0e0; /* Kelabu cair untuk header */
            text-transform: uppercase;
            font-size: 11px;
            font-weight: bold;
            text-align: center;
        }
        /* Belang-belang (Striped rows) */
        table.main-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            font-size: 10px;
            text-align: center;
            color: #555;
            padding-top: 10px;
            border-top: 1px solid #aaa;
        }
        
        .page-number:before {
            content: "Muka Surat " counter(page);
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>
            Laporan Senarai Aktiviti
            @if($request->year && $request->month)
                ({{ \Carbon\Carbon::create(null, $request->month, 1)->translatedFormat('F') }} {{ $request->year }})
            
            @elseif($request->year)
                ({{ $request->year }})
                
            @elseif($request->month)
                 ({{ \Carbon\Carbon::create(null, $request->month, 1)->translatedFormat('F') }})
            @endif
        </h1>
        <h2>Lembaga Perindustrian Kayu Malaysia (MTIB)</h2>
    </div>

    <div class="info-section">
        <table class="info-table">
            <tr>
                <td width="60%">
                    <span class="font-bold">Dijana Oleh:</span> {{ Auth::user()->name }} <br>
                    <span class="font-bold">Tarikh Laporan:</span> {{ now()->format('d/m/Y, h:i A') }}
                </td>
                <td width="40%" class="text-right">
                    @if($request->year)
                        <span class="font-bold">Tahun:</span> {{ $request->year }} <br>
                    @endif
                    
                    @if($request->month)
                        <span class="font-bold">Bulan:</span> {{ \Carbon\Carbon::create(null, $request->month, 1)->translatedFormat('F') }} <br>
                    @endif

                    @if($request->search)
                        <span class="font-bold">Carian:</span> "{{ $request->search }}"
                    @endif

                    @if(!$request->year && !$request->month && !$request->search)
                        <span class="font-bold">Paparan:</span> Semua Rekod
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <table class="main-table">
        <thead>
            <tr>
                <th width="5%">No.</th>
                <th width="35%">Tajuk Aktiviti</th>
                <th width="20%">Tarikh & Masa</th>
                <th width="25%">Tempat / Penganjur</th>
                <th width="15%">Jenis</th>
            </tr>
        </thead>
        <tbody>
            @forelse($meetings as $index => $meeting)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $meeting->title }}</strong>
                    </td>
                    <td style="text-align: center;">
                        {{ $meeting->activity_type }}
                    </td>
                    <td>
                        {{ \Carbon\Carbon::parse($meeting->date)->format('d/m/Y') }} <br>
                        <small>{{ \Carbon\Carbon::parse($meeting->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($meeting->end_time)->format('h:i A') }}</small>
                    </td>
                    <td>
                        <div><strong>V:</strong> {{ $meeting->venue }}</div>
                        <div style="margin-top:4px; color:#555;"><strong>Org:</strong> {{ $meeting->organizer }}</div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px;">
                        Tiada rekod dijumpai untuk kriteria ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak dari Sistem MMAS | Lembaga Perindustrian Kayu Malaysia <br>
        <span class="page-number"></span>
    </div>

</body>
</html>