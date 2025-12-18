<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body>
    <h3>Pesan Baru dari Website</h3>

    <p><strong>Judul:</strong> {{ $judul }}</p>

    <p><strong>Pesan:</strong></p>
    <p>{{ $pesan }}</p>

    <hr>

    <small>
        Dikirim oleh: {{ $nama }} ({{ $email }})<br>
        No. Telp: {{ $no_telp }}
    </small>
</body>
</html>
