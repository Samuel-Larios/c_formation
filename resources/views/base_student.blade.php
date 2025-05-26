<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Student Dashboard</title>

    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">


    <link rel="shortcut icon" href="{{ asset('assets/img/kaiadmin/Logo_Songhai.jpg') }}" type="image/jpeg">
    <link rel="icon" href="{{ asset('assets/img/kaiadmin/Logo_Songhai.jpg') }}?v=2" type="image/jpeg">

    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #f8f9fc;
            --accent-color: #2e59d9;
            --dark-color: #2c3e50;
            --light-color: #f8f9fa;
            --success-color: #1cc88a;
            --danger-color: #e74a3b;
        }

        body {
            font-family: 'Inter', sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: var(--secondary-color);
            color: #212529;
        }

        /* Navigation - Adapted for dark/light mode */
        .navbar {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            padding: 0.75rem 1rem;
            transition: all 0.3s;
        }

        [data-bs-theme="light"] .navbar {
            background: white;
        }

        [data-bs-theme="dark"] .navbar {
            background: #1a1a2e;
            border-bottom: 1px solid #2d3748;
        }

        .navbar-brand {
            font-weight: 700;
            letter-spacing: -0.5px;
            color: inherit;
            color: rgb(0, 180, 63);
        }

        .nav-link {
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            transition: all 0.2s;
            color: inherit;
            color: rgb(0, 180, 63);
        }

        [data-bs-theme="light"] .nav-link:hover,
        [data-bs-theme="light"] .nav-link.active {
            background-color: rgba(78, 115, 223, 0.1);
            color: var(--primary-color);
        }

        [data-bs-theme="dark"] .nav-link:hover,
        [data-bs-theme="dark"] .nav-link.active {
            background-color: rgba(78, 115, 223, 0.2);
            color: rgb(0, 4, 241);
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 2rem 0;
        }

        [data-bs-theme="dark"] .main-content {
            background-color: #121212;
            color: #e0e0e0;
        }

        /* Cards */
        .card {
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            margin-bottom: 1.5rem;
        }

        [data-bs-theme="light"] .card {
            background-color: white;
        }

        [data-bs-theme="dark"] .card {
            background-color: #1e1e1e;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(0, 0, 0, 0.3);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1.5rem rgba(58, 59, 69, 0.2);
        }

        .card-header {
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            font-weight: 600;
            padding: 1rem 1.5rem;
        }

        [data-bs-theme="dark"] .card-header {
            background-color: #252525;
            border-bottom-color: #333;
        }

        /* Buttons */
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 0.5rem 1.25rem;
        }

        .btn-primary:hover {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }

        /* Footer */
        .footer {
            background-color: var(--dark-color);
            color: white;
            padding: 1.5rem 0;
            margin-top: auto;
        }

        [data-bs-theme="dark"] .footer {
            background-color: #0f0f1a;
            border-top: 1px solid #2d3748;
        }

        /* Dark mode toggle */
        .theme-toggle {
            cursor: pointer;
            border: none;
            background: transparent;
            color: inherit;
            color: red;
        }

        /* Text colors for dark mode */
        [data-bs-theme="dark"] {
            color: #e0e0e0;
        }

        [data-bs-theme="dark"] .text-dark {
            color: #f8f9fa !important;
        }

        /* Responsive adjustments */
        @media (max-width: 992px) {
            .navbar-collapse {
                padding-top: 1rem;
            }

            .nav-link {
                margin-bottom: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('student.dashboard') }}">
                <i class="bi bi-speedometer2 me-2"></i>
                <span>Dashboard</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('student.job_creations.index') }}">
                            <i class="bi bi-briefcase me-1"></i>
                            Job creations
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('student.entities.index') }}">
                            <i class="bi bi-building me-1"></i>
                            Business
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('student.salaries.index') }}">
                            <i class="bi bi-people me-1"></i>
                            Permanent contract
                        </a>
                    </li>
                </ul>

                <div class="d-flex align-items-center">
                    <button class="theme-toggle me-3">
                        <i class="bi bi-moon-stars"></i>
                    </button>

                    <form action="{{ route('student.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-box-arrow-right me-1"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container py-4">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container text-center">
            <p class="mb-0">
                &copy; <span id="current-year">{{ date('Y') }}</span> Student Dashboard.
                All rights reserved.
            </p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Dark mode toggle
        const themeToggle = document.querySelector('.theme-toggle');
        const html = document.documentElement;

        function updateThemeIcon(theme) {
            const icon = themeToggle.querySelector('i');
            icon.className = theme === 'dark' ? 'bi bi-sun' : 'bi bi-moon-stars';
        }

        themeToggle.addEventListener('click', () => {
            const currentTheme = html.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

            html.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeIcon(newTheme);
        });

        // Apply saved theme or preferred color scheme
        function applyTheme() {
            const savedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

            if (savedTheme) {
                html.setAttribute('data-bs-theme', savedTheme);
                updateThemeIcon(savedTheme);
            } else {
                const theme = prefersDark ? 'dark' : 'light';
                html.setAttribute('data-bs-theme', theme);
                updateThemeIcon(theme);
            }
        }

        document.addEventListener('DOMContentLoaded', applyTheme);
    </script>
</body>
</html>
