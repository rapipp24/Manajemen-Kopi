<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">
    <title>500 – Terjadi Kendala | Kopi Elang Emas</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            background-color: #F7F2EC;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: #3B1F10;
            padding: 1.5rem;
        }

        .card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(107, 46, 22, 0.12);
            padding: 3rem 2.5rem 2.5rem;
            max-width: 420px;
            width: 100%;
            text-align: center;
        }

        .logo-wrap {
            margin-bottom: 1.75rem;
        }

        .logo {
            height: 64px;
            width: 64px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #EDE0D4;
        }

        .code {
            font-size: 5rem;
            font-weight: 800;
            line-height: 1;
            color: #A3470D;
            letter-spacing: -2px;
            margin-bottom: 0.5rem;
        }

        .divider {
            width: 48px;
            height: 4px;
            background: linear-gradient(90deg, #6B2E16, #A3470D);
            border-radius: 2px;
            margin: 0 auto 1.5rem;
        }

        h1 {
            font-size: 1.25rem;
            font-weight: 700;
            color: #3B1F10;
            margin: 0 0 0.75rem;
        }

        p {
            font-size: 0.9rem;
            color: #7A5C48;
            line-height: 1.7;
            margin: 0 0 2rem;
        }

        .btn {
            display: inline-block;
            padding: 0.7rem 2rem;
            background: linear-gradient(135deg, #6B2E16, #A3470D);
            color: #ffffff;
            text-decoration: none;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 600;
            transition: opacity 0.2s ease;
        }

        .btn:hover {
            opacity: 0.88;
        }

        .brand-footer {
            margin-top: 2rem;
            font-size: 0.75rem;
            color: #C4A882;
            letter-spacing: 0.5px;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo-wrap">
            <img src="/images/LOGO-KOPI-ELANG-EMAS.jpg"
                 alt="Logo Kopi Elang Emas"
                 class="logo"
                 onerror="this.style.display='none'">
        </div>

        <div class="code">500</div>
        <div class="divider"></div>

        <h1>Terjadi Kendala</h1>
        <p>Sistem sedang mengalami kendala. Silakan coba beberapa saat lagi atau hubungi admin.</p>

        <a href="{{ url('/') }}" class="btn">Kembali ke Beranda</a>

        <p class="brand-footer">Kopi Elang Emas</p>
    </div>
</body>
</html>
