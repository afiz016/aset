<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPK Investasi Aset Digital - @yield('title')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    </style>

    <style>
        /* ====================================================
           STYLING PREMIUM SIDEBAR
           ==================================================== */
        .sidebar-wrapper {
            background-color: #0b1121; 
            border-right: 1px solid rgba(255, 255, 255, 0.05);
            width: 280px; 
            height: 100vh;
            font-family: 'Plus Jakarta Sans', sans-serif;
            z-index: 1000;
        }

        @keyframes slideInLeftStagger {
            0% { opacity: 0; transform: translateX(-30px); }
            100% { opacity: 1; transform: translateX(0); }
        }
        
        .sidebar-animate-1 { animation: slideInLeftStagger 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards; animation-delay: 0.1s; opacity: 0; }
        .sidebar-animate-2 { animation: slideInLeftStagger 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards; animation-delay: 0.2s; opacity: 0; }
        .sidebar-animate-3 { animation: slideInLeftStagger 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards; animation-delay: 0.3s; opacity: 0; }
        .sidebar-animate-4 { animation: slideInLeftStagger 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards; animation-delay: 0.4s; opacity: 0; }

        .sidebar-link {
            transition: all 0.3s ease;
            border-radius: 12px;
            padding: 12px 20px;
            color: #94a3b8; 
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            text-decoration: none;
            font-weight: 500;
        }
        
        .sidebar-icon {
            margin-right: 15px;
            font-size: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 30px;
        }

        .sidebar-icon i { transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); }

        .sidebar-link:hover {
            background-color: rgba(255, 255, 255, 0.03);
            color: #ffffff;
            transform: translateX(5px);
        }
        
        .sidebar-link:hover .sidebar-icon i {
            transform: scale(1.3) translateY(-2px);
            color: #00d4ff !important;
            text-shadow: 0 0 15px rgba(0, 212, 255, 0.6);
        }

        .sidebar-link.active {
            background: linear-gradient(90deg, rgba(0, 212, 255, 0.15) 0%, transparent 100%);
            color: #00d4ff; border-left: 4px solid #00d4ff; font-weight: 700;
        }
        .sidebar-link.active .sidebar-icon i {
            color: #00d4ff !important;
            animation: pulseIcon 2s infinite ease-in-out;
            text-shadow: 0 0 10px rgba(0, 212, 255, 0.4);
        }

        .sidebar-link.active-warning {
            background: linear-gradient(90deg, rgba(255, 193, 7, 0.15) 0%, transparent 100%);
            color: #ffc107; border-left: 4px solid #ffc107; font-weight: 700;
        }
        .sidebar-link.active-warning .sidebar-icon i {
            color: #ffc107 !important;
            animation: pulseIcon 2s infinite ease-in-out;
            text-shadow: 0 0 10px rgba(255, 193, 7, 0.4);
        }
        .sidebar-link.hover-warning:hover .sidebar-icon i { color: #ffc107 !important; text-shadow: 0 0 15px rgba(255, 193, 7, 0.6); }

        @keyframes pulseIcon { 0% { transform: scale(1); } 50% { transform: scale(1.15); } 100% { transform: scale(1); } }

        .sidebar-profile {
            background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 16px; padding: 15px; transition: all 0.3s ease;
        }
        .sidebar-profile:hover { background: rgba(255, 255, 255, 0.04); border-color: rgba(0, 212, 255, 0.2); }

        .btn-logout { border-radius: 10px; transition: all 0.2s ease; }
        .btn-logout:hover { transform: scale(1.02); box-shadow: 0 5px 15px rgba(220, 53, 69, 0.2); }
    </style>
</head>

<body class="bg-light d-flex flex-column flex-md-row min-vh-100 overflow-hidden">

    @auth
    <div class="sidebar-wrapper d-flex flex-column flex-shrink-0 p-3 text-white shadow-lg">
        
        <a href="/dashboard" class="d-flex align-items-center mb-4 mt-2 text-info text-decoration-none fw-bolder fs-4 px-2 sidebar-animate-1">
            <i class="bi bi-hexagon-fill me-2" style="font-size: 1.5rem; text-shadow: 0 0 10px rgba(0, 212, 255, 0.5);"></i>
            SPK Aset
        </a>
        
        <hr class="border-secondary mt-0 sidebar-animate-1 opacity-25">
        
        <ul class="nav flex-column mb-auto mt-2 sidebar-animate-2 w-100">
            <li class="nav-item">
                <a href="/dashboard" class="sidebar-link {{ request()->is('dashboard') ? 'active' : '' }}">
                    <div class="sidebar-icon"><i class="bi bi-grid-1x2-fill"></i></div> Dashboard
                </a>
            </li>
            <li>
                <a href="/kriteria" class="sidebar-link {{ request()->is('kriteria*') ? 'active' : '' }}">
                    <div class="sidebar-icon"><i class="bi bi-ui-checks-grid"></i></div> Data Kriteria
                </a>
            </li>
            <li>
                <a href="{{ route('aset-digital.index') }}" class="sidebar-link {{ request()->is('aset-digital*') ? 'active' : '' }}">
                    <div class="sidebar-icon"><i class="bi bi-box-seam-fill"></i></div> Aset Digital
                </a>
            </li>
            <li>
                <a href="{{ route('topsis.hasil') }}" class="sidebar-link hover-warning {{ request()->is('topsis/hasil') ? 'active-warning' : '' }}">
                    <div class="sidebar-icon"><i class="bi bi-bar-chart-line-fill"></i></div> Hasil TOPSIS
                </a>
            </li>
        </ul>
        
        <div class="mt-auto sidebar-animate-3">
            <div class="sidebar-profile mb-3">
                <div class="d-flex align-items-center">
                    <div class="bg-info bg-gradient rounded-circle d-flex justify-content-center align-items-center text-dark fw-bold fs-5 shadow-sm" style="width: 45px; height: 45px;">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="ms-3 overflow-hidden">
                        <h6 class="mb-0 text-white fw-bold text-truncate">{{ Auth::user()->name }}</h6>
                        <small class="text-info opacity-75">Administrator</small>
                    </div>
                </div>
            </div>
            
            <form action="/logout" method="POST" class="sidebar-animate-4">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-logout w-100 fw-bold d-flex justify-content-center align-items-center py-2">
                    <i class="bi bi-box-arrow-left me-2"></i> Logout
                </button>
            </form>
        </div>

    </div>
    @endauth

    <main class="d-flex flex-column flex-grow-1 w-100" style="height: 100vh; overflow-y: auto; background-color: #0f172a;">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>