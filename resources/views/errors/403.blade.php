<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak</title>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            color: #1e293b;
        }
        .container {
            text-align: center;
            padding: 40px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            max-width: 400px;
        }
        h1 {
            font-size: 72px;
            margin: 0;
            color: #92400e;
        }
        p {
            font-size: 18px;
            color: #64748b;
            margin-bottom: 30px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #92400e;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: opacity 0.2s;
        }
        .btn:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>403</h1>
        <p>{{ $exception->getMessage() ?: 'Maaf, Anda tidak memiliki akses ke halaman ini.' }}</p>
        <a href="{{ route('home') }}" class="btn">Kembali ke Beranda</a>
    </div>
</body>
</html>
