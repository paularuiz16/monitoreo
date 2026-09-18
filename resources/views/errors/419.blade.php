<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>419 - Sesión Expirada | AvícolaPro Control</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo.jpg') }}?v=12">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0f172a;
            color: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            margin: 0;
        }
        .error-card {
            background: #1e293b;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1.25rem;
            padding: 2.5rem;
            max-width: 480px;
            width: 100%;
            text-align: center;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        .error-code-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #f87171;
            padding: 0.35rem 1rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            margin-bottom: 1.25rem;
        }
        .btn-reload {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            font-weight: 600;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-decoration: none;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }
        .btn-reload:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
            color: white;
        }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="mb-3">
            <img src="{{ asset('images/logo.png') }}" alt="AvícolaPro Control" style="max-height: 60px; width: auto; object-fit: contain;">
        </div>

        <div class="error-code-badge">
            <span class="material-symbols-outlined fs-6">timer_off</span>
            <span>ERROR 419 &bull; SESIÓN EXPIRADA</span>
        </div>

        <h3 class="fw-bold mb-2 text-white">Tu sesión o formulario ha caducado</h3>

        <p class="text-white-50 small mb-4" style="line-height: 1.6;">
            Por motivos de seguridad y protección de datos, los formularios caducan si la página permanece abierta o inactiva durante mucho tiempo. Haz clic en el botón de abajo para renovar tu acceso inmediatamente.
        </p>

        <div class="d-flex flex-column gap-2">
            <a href="{{ route('login') }}" class="btn-reload w-100">
                <span class="material-symbols-outlined fs-5">refresh</span>
                <span>Renovar e Iniciar Sesión</span>
            </a>
            <button type="button" class="btn btn-outline-secondary text-white-50 border-secondary py-2 rounded-3 small" onclick="window.location.reload();">
                Recargar página actual
            </button>
        </div>

        <div class="mt-4 pt-3 border-top border-secondary border-opacity-25 text-white-50" style="font-size: 11px;">
            AvícolaPro Control &bull; Monitoreo SCADA
        </div>
    </div>
</body>
</html>
