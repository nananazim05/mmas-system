<!DOCTYPE html>
<html>
<body style="font-family: Arial, sans-serif;">
    <h2 style="color: #d97706;">⚠️ Perubahan Maklumat Aktiviti</h2>
    <p>
        Assalamualaikum WBT & Salam Sejahtera, <br>
        YBhg. Datuk / Dato' / YBrs. Ts. / Dr. / Tc. / Tuan / Puan,
        </p>
    <p>Harap maklum bahawa terdapat <strong>perubahan maklumat</strong> bagi aktiviti berikut:</p>
    
    <ul>
        <li><strong>Tajuk:</strong> {{ $meeting->title }}</li>
        <li><strong>Tarikh Baru:</strong> {{ $meeting->date }}</li>
        <li><strong>Masa Baru:</strong> {{ $meeting->start_time }} - {{ $meeting->end_time }}</li>
        <li><strong>Tempat Baru:</strong> {{ $meeting->venue }}</li>
    </ul>

    <p>Sila ambil maklum.</p>
</body>
</html>