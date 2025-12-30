<!DOCTYPE html>
<html>
<head>
    <title>Cetak QR - {{ $meeting->title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 50px;
        }
        .container {
            border: 1px solid #ccc;
            padding: 40px;
            max-width: 600px;
            margin: 0 auto;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        .details {
            margin-bottom: 30px;
            color: #555;
            font-size: 14px;
        }
        .qr-box {
            margin: 20px 0;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #888;
        }
        /* Auto print bila page loading */
        @media print {
            .no-print { display: none; }
            .container { border: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="container">
        <div class="title">{{ $meeting->title }}</div>
        
        <div class="details">
            <div><strong>Tarikh:</strong> {{ \Carbon\Carbon::parse($meeting->date)->format('d M Y') }}</div>
            <div><strong>Masa:</strong> 
                {{ \Carbon\Carbon::parse($meeting->start_time)->format('H:i') }} - 
                {{ \Carbon\Carbon::parse($meeting->end_time)->format('H:i') }}
            </div>
            <div><strong>Tempat:</strong> {{ $meeting->venue }}</div>
        </div>

        <hr>

        <div class="qr-box">
            @if($qrCode)
                <p>Sila imbas kod QR di bawah untuk kehadiran:</p>
                <br>
                <div style="display: flex; justify-content: center;">
                    {!! $qrCode !!}
                </div>

                @if($isReactivated)
                    <p style="color: green; font-weight: bold; margin-top: 15px;">
                        (Kod QR Diaktifkan Semula Sementara)
                    </p>
                @endif
            @else
                <div style="border: 2px dashed red; padding: 30px; margin-top: 20px;">
                    <h1 style="color: red; margin: 0;">AKTIVITI TAMAT</h1>
                    <p>Kod QR tidak lagi sah.</p>
                </div>
            @endif
        </div>

        <div class="footer">
            Sistem MMAS - MTIB
        </div>
    </div>

</body>
</html>