<!DOCTYPE html>
<html>
<head>
    <title>Jemputan Mesyuarat MTIB</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333;">
    <div style="border-top: 4px solid #B6192E; padding: 20px; background-color: #f9f9f9;">
        <h2 style="color: #B6192E;">Jemputan ke {{ $meeting->title }}</h2>
        
        <p>Tuan/Puan,</p>
        <p>Anda dijemput hadir ke aktiviti berikut:</p>
        
        <div style="background: white; padding: 15px; border: 1px solid #ddd; border-radius: 5px;">
            <ul style="list-style: none; padding: 0;">
                <li style="margin-bottom: 10px;">📅 <strong>Tarikh:</strong> {{ \Carbon\Carbon::parse($meeting->date)->format('d M Y') }}</li>
                <li style="margin-bottom: 10px;">⏰ <strong>Masa:</strong> {{ \Carbon\Carbon::parse($meeting->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($meeting->end_time)->format('H:i') }}</li>
                <li style="margin-bottom: 10px;">📍 <strong>Tempat:</strong> {{ $meeting->venue }}</li>
                <li>👤 <strong>Penganjur:</strong> {{ $meeting->organizer->name ?? 'MTIB' }}</li>
            </ul>
        </div>

        <p>Sila simpan e-mel ini. Pada hari kejadian, sila imbas <strong>Kod QR</strong> yang akan dipaparkan oleh urusetia untuk pengesahan kehadiran.</p>
        
        <br>
        <p style="font-size: 12px; color: #777;">Terima kasih,<br>Sistem MMAS MTIB</p>
    </div>
</body>
</html>