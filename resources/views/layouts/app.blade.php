<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Emi Veterinaria')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.7.2/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">

    <style>
        :root {
            --emi-primary: #8b78b9;
            --emi-primary-dark: #5d4a82;
            --emi-sidebar-dark: #181623;
            --emi-sidebar-mid: #242033;
            --emi-sidebar-light: #322c45;
            --emi-bg: #f2f0f7;
            --emi-dark: #252332;
            --emi-border: #dad6e3;
            --emi-muted: #6f6a80;
            --emi-surface: #fbfafc;
        }

        body {
            font-family: 'Nunito', sans-serif;
            background: var(--emi-bg);
            min-height: 100vh;
            color: var(--emi-dark);
        }

        .emi-card {
            border: 1px solid var(--emi-border);
            border-radius: 16px;
            box-shadow: 0 10px 22px rgba(37, 35, 50, 0.08);
            overflow: hidden;
            background: var(--emi-surface);
        }

        .app-shell {
            display: flex;
            min-height: 100vh;
        }

        .app-content {
            flex: 1;
            min-width: 0;
            padding: 1.25rem;
        }

        .sidebar-layout {
            position: sticky;
            top: 0;
            width: 270px;
            height: 100vh;
            z-index: 1200;
            flex-shrink: 0;
            background: linear-gradient(180deg, var(--emi-sidebar-dark), var(--emi-sidebar-mid), var(--emi-sidebar-light));
            color: #fff;
            display: flex;
            flex-direction: column;
            border-right: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.35);
            transition: 0.35s ease;
        }

        .sidebar-brand {
            padding: 1rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
        }

        .sidebar-toggle {
            width: 38px;
            height: 38px;
            border: 0;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--emi-primary), var(--emi-primary-dark));
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: 0.25s;
        }

        .sidebar-toggle:hover {
            transform: scale(1.06);
        }

        .sidebar-brand-link {
            color: #fff;
            text-decoration: none;
            font-weight: 800;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .sidebar-menu {
            list-style: none;
            margin: 0;
            padding: 1rem 0.8rem;
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            padding: 0.7rem 0.8rem;
            border-radius: 10px;
            color: #fff;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.95rem;
            transition: 0.2s;
        }

        .sidebar-menu a:hover {
            background: rgba(255, 255, 255, 0.14);
            transform: translateX(5px);
        }

        .sidebar-menu a.active {
            background: linear-gradient(135deg, var(--emi-primary), var(--emi-primary-dark));
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.18);
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.15);
        }

        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            margin-bottom: 0.85rem;
        }

        .sidebar-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.16);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
        }

        .sidebar-logout {
            width: 100%;
            border: 0;
            border-radius: 10px;
            padding: 0.6rem 0.75rem;
            background: rgba(239, 68, 68, 0.18);
            color: #fff;
            text-align: left;
            font-weight: 700;
        }

        html.sidebar-collapsed .sidebar-layout {
            width: 88px;
        }

        html.sidebar-collapsed .sidebar-brand-link span,
        html.sidebar-collapsed .sidebar-menu a span,
        html.sidebar-collapsed .sidebar-user-name,
        html.sidebar-collapsed .logout-text {
            display: none;
        }

        .page-hero {
            background: linear-gradient(135deg, #2a253b, var(--emi-primary-dark));
            border-radius: 16px;
            color: #fff;
            padding: 1.25rem 1.4rem;
            box-shadow: 0 10px 28px rgba(37, 35, 50, 0.28);
        }

        .kpi-card {
            border-radius: 16px;
            padding: 1rem 1.1rem;
            border: none;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
        }

        .kpi-blue {
            background: linear-gradient(135deg, #f4f2f9, #e4deef);
        }

        .kpi-green {
            background: linear-gradient(135deg, #f6f5f8, #ebe8f1);
        }

        .kpi-warm {
            background: linear-gradient(135deg, #f3f0f8, #e8e1f3);
        }

        .kpi-soft {
            background: linear-gradient(135deg, #f8f7fb, #ece9f4);
        }

        .table-modern {
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        .table-modern thead th {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 700;
            color: #6b7280;
            border: none;
        }

        .table-modern tbody tr {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .table-modern tbody td {
            border: none !important;
            vertical-align: middle;
        }

        .module-panel {
            background: var(--emi-surface);
            border: 1px solid var(--emi-border);
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(37, 35, 50, 0.08);
            padding: 1.2rem;
        }

        .emi-badge {
            background: rgba(255, 255, 255, 0.18);
            color: #fff;
            border-radius: 999px;
            padding: 6px 12px;
            font-weight: 700;
            font-size: 0.8rem;
        }

        .text-muted {
            color: var(--emi-muted) !important;
        }

        .btn-success {
            --bs-btn-bg: var(--emi-primary);
            --bs-btn-border-color: var(--emi-primary);
            --bs-btn-hover-bg: var(--emi-primary-dark);
            --bs-btn-hover-border-color: var(--emi-primary-dark);
            --bs-btn-active-bg: var(--emi-primary-dark);
            --bs-btn-active-border-color: var(--emi-primary-dark);
        }

        .btn-outline-success,
        .btn-outline-primary,
        .btn-outline-warning {
            --bs-btn-color: var(--emi-primary-dark);
            --bs-btn-border-color: #b8aed0;
            --bs-btn-hover-color: #fff;
            --bs-btn-hover-bg: var(--emi-primary);
            --bs-btn-hover-border-color: var(--emi-primary);
            --bs-btn-active-bg: var(--emi-primary-dark);
            --bs-btn-active-border-color: var(--emi-primary-dark);
            --bs-btn-disabled-color: #8e869f;
            --bs-btn-disabled-border-color: #d4cede;
        }

        .text-success,
        .text-primary,
        .text-warning {
            color: var(--emi-primary-dark) !important;
        }

        .form-control,
        .form-select {
            border-color: #cbc4d9;
            background-color: #fff;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #b6aad1;
            box-shadow: 0 0 0 0.2rem rgba(139, 120, 185, 0.2);
        }

        .select2-container .select2-selection--single {
            height: 38px;
            border: 1px solid #cbc4d9;
            border-radius: 0.375rem;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 36px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }

        @media (max-width: 991px) {
            .sidebar-layout {
                width: 88px;
            }

            .sidebar-brand-link span,
            .sidebar-menu a span,
            .sidebar-user-name,
            .logout-text {
                display: none;
            }
        }
    </style>
</head>
<body>
    @auth
        <div class="app-shell">
            <aside class="sidebar-layout" id="sidebarLayout">
                <div class="sidebar-brand">
                    <button class="sidebar-toggle" id="sidebarToggle" type="button">
                        <i class="fas fa-bars"></i>
                    </button>
                    <a href="{{ route('vistas-inicio') }}" class="sidebar-brand-link">
                        <i class="fas fa-paw"></i>
                        <span>Emi Vet</span>
                    </a>
                </div>

                <ul class="sidebar-menu">
                    <li>
                        <a href="{{ route('vistas-inicio') }}" class="{{ request()->routeIs('vistas-inicio') ? 'active' : '' }}">
                            <i class="fas fa-house"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    @if(auth()->user()->isAdmin())
                        <li>
                            <a href="{{ route('inventario-listar') }}" class="{{ request()->routeIs('inventario-*') ? 'active' : '' }}">
                                <i class="fas fa-boxes-stacked"></i>
                                <span>Inventario</span>
                            </a>
                        </li>
                    @endif
                    <li>
                        <a href="{{ route('sales-listar') }}" class="{{ request()->routeIs('sales-*') ? 'active' : '' }}">
                            <i class="fas fa-cash-register"></i>
                            <span>Punto de Venta</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('consultations-listar') }}" class="{{ request()->routeIs('consultations-*') ? 'active' : '' }}">
                            <i class="fas fa-stethoscope"></i>
                            <span>Consultas</span>
                        </a>
                    </li>
                    @if(auth()->user()->isAdmin())
                        <li>
                            <a href="{{ route('employees-listar') }}" class="{{ request()->routeIs('employees-*') ? 'active' : '' }}">
                                <i class="fas fa-users"></i>
                                <span>Empleados</span>
                            </a>
                        </li>
                    @endif
                </ul>

                <div class="sidebar-footer">
                    <div class="sidebar-user">
                        <div class="sidebar-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                        <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="sidebar-logout">
                            <i class="fas fa-right-from-bracket"></i>
                            <span class="logout-text">Cerrar sesión</span>
                        </button>
                    </form>
                </div>
            </aside>

            <main class="app-content">
                @yield('content')
            </main>
        </div>
    @else
        @yield('content')
    @endauth

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggle = document.getElementById('sidebarToggle');

            if (localStorage.getItem('sidebar') === 'closed') {
                document.documentElement.classList.add('sidebar-collapsed');
            }

            if (toggle) {
                toggle.addEventListener('click', function () {
                    document.documentElement.classList.toggle('sidebar-collapsed');

                    if (document.documentElement.classList.contains('sidebar-collapsed')) {
                        localStorage.setItem('sidebar', 'closed');
                    } else {
                        localStorage.setItem('sidebar', 'open');
                    }
                });
            }
        });
    </script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Listo',
                text: @json(session('success')),
                timer: 1800,
                showConfirmButton: false,
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error de validación',
                html: @json(implode('<br>', $errors->all())),
            });
        </script>
    @endif

    @stack('scripts')
</body>
</html>
