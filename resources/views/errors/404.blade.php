<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - Page Not Found | {{ config('app.name') }}</title>
    
    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .error-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f9fafb;
            padding: 24px;
        }
        .error-card {
            max-width: 500px;
            width: 100%;
            text-align: center;
            background: white;
            padding: 48px;
            border-radius: 24px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
        }
        .error-icon {
            font-size: 64px;
            color: #2b1770;
            margin-bottom: 24px;
        }
        .error-code {
            font-size: 120px;
            font-weight: 900;
            color: #2b1770;
            line-height: 1;
            margin-bottom: 8px;
            opacity: 0.1;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 0;
            pointer-events: none;
        }
        .error-content {
            position: relative;
            z-index: 1;
        }
        .error-title {
            font-size: 24px;
            font-weight: 800;
            color: #111827;
            margin-bottom: 12px;
        }
        .error-message {
            color: #6b7280;
            margin-bottom: 32px;
            line-height: 1.6;
        }
        .btn-home {
            display: inline-block;
            background: #2b1770;
            color: white;
            padding: 14px 32px;
            border-radius: 12px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(43, 23, 112, 0.2);
            background: #1e1050;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-card">
            <div class="error-content">
                <div class="error-code">404</div>
                <div class="error-icon">
                    <i class="fa-solid fa-notes-medical"></i>
                </div>
                <h1 class="error-title">Page Not Found</h1>
                <p class="error-message">
                    The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.
                </p>
                <a href="{{ url('/') }}" class="btn-home">
                    Back to Home
                </a>
            </div>
        </div>
    </div>
</body>
</html>
