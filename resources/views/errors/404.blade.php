<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">
    <title>404 – Halaman Tidak Ditemukan | Kopi Elang Emas</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            background-color: #F7F2EC;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: #3B1F10;
            padding: 2.5rem 1.5rem;
        }

        .wrap {
            width: 100%;
            max-width: 720px;
            text-align: center;
        }

        /* Logo */
        .logo-ring {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 3px solid #D9C0A8;
            overflow: hidden;
            margin-bottom: 2.25rem;
        }

        .logo-ring img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .logo-fallback {
            font-size: 2rem;
            color: #A3470D;
        }

        /* Error code */
        .code {
            font-size: clamp(80px, 14vw, 120px);
            font-weight: 800;
            line-height: 1;
            letter-spacing: -4px;
            background: linear-gradient(135deg, #6B2E16 0%, #A3470D 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1rem;
            user-select: none;
        }

        /* Divider */
        .divider {
            width: 56px;
            height: 3px;
            background: linear-gradient(90deg, #6B2E16, #A3470D);
            border-radius: 2px;
            margin: 0 auto 1.75rem;
        }

        /* Heading */
        h1 {
            font-size: clamp(1.2rem, 3vw, 1.6rem);
            font-weight: 700;
            color: #3B1F10;
            margin-bottom: 0.875rem;
            letter-spacing: -0.3px;
        }

        /* Message */
        p.msg {
            font-size: clamp(0.9rem, 2vw, 1.05rem);
            color: #7A5C48;
            line-height: 1.75;
            max-width: 480px;
            margin: 0 auto 2.5rem;
        }

        /* Button */
        .btn {
            display: inline-block;
            padding: 0.8rem 2.25rem;
            background: linear-gradient(135deg, #6B2E16, #A3470D);
            color: #ffffff;
            text-decoration: none;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 600;
            letter-spacing: 0.1px;
            transition: opacity 0.2s ease, transform 0.15s ease;
        }

        .btn:hover {
            opacity: 0.88;
            transform: translateY(-1px);
        }

        .btn:active {
            transform: translateY(0);
        }

        /* Brand footer */
        .brand {
            margin-top: 3rem;
            font-size: 0.75rem;
            color: #BFA080;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        /* Mobile */
        @media (max-width: 480px) {
            .btn {
                display: block;
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="logo-ring">
            <img src="/images/LOGO-KOPI-ELANG-EMAS.jpg"
                 alt="Kopi Elang Emas"
                 onerror="this.parentElement.innerHTML='<span class=\'logo-fallback\'>☕</span>'">
        </div>

        <div class="code">404</div>
        <div class="divider"></div>

        <h1>Halaman Tidak Ditemukan</h1>
        <p class="msg">Halaman yang Anda cari tidak tersedia atau mungkin sudah dipindahkan.</p>

        <a href="{{ url('/') }}" class="btn">Kembali ke Beranda</a>

        <p class="brand">Kopi Elang Emas</p>
    </div>
</body>
</html>
