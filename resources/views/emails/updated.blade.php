<!DOCTYPE html>
<html>
<body style="font-family: Arial, sans-serif;">
    <h2 style="color: #d97706;">⚠️ Perubahan Maklumat Aktiviti</h2>
    <p>Tuan/Puan,</p>
    <p>Harap maklum bahawa terdapat <strong>kemaskini terbaru</strong> bagi aktiviti berikut:</p>
    
    <ul>
        <li><strong>Tajuk:</strong> {{ $meeting->title }}</li>
        <li><strong>Tarikh Baru:</strong> {{ $meeting->date }}</li>
        <li><strong>Masa Baru:</strong> {{ $meeting->start_time }} - {{ $meeting->end_time }}</li>
        <li><strong>Tempat Baru:</strong> {{ $meeting->venue }}</li>
    </ul>

    <p>Sila ambil maklum perubahan ini.</p>
</body>
</html>