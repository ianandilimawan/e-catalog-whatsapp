<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #1a1a1a;
            --secondary: #4a5568;
            --accent: #3b82f6;
            --accent-light: #60a5fa;
            --border: #e5e7eb;
            --bg: #ffffff;
            --bg-light: #f9fafb;
            --gradient-1: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-2: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --gradient-3: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--primary);
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* Animated Background Gradient */
        body::before {
            content: '';
            position: fixed;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at 30% 50%, rgba(102, 126, 234, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 70% 80%, rgba(245, 87, 108, 0.08) 0%, transparent 50%);
            animation: rotate 30s linear infinite;
            z-index: -1;
        }

        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        /* Header */
        .header {
            border-bottom: 1px solid var(--border);
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 24px;
            font-weight: 800;
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            gap: 32px;
            align-items: center;
        }

        .nav-link {
            color: var(--secondary);
            text-decoration: none;
            font-size: 15px;
            font-weight: 500;
            transition: color 0.2s;
            position: relative;
        }

        .nav-link:hover {
            color: var(--primary);
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--gradient-1);
            transition: width 0.3s;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .btn-login {
            padding: 10px 24px;
            background: var(--primary);
            color: white;
            border-radius: 8px;
            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        /* Hero Section */
        .hero {
            max-width: 1200px;
            margin: 0 auto;
            padding: 120px 24px 80px;
            text-align: center;
            position: relative;
        }

        .hero-badge {
            display: inline-block;
            padding: 8px 20px;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
            border: 1px solid rgba(102, 126, 234, 0.2);
            border-radius: 50px;
            font-size: 14px;
            font-weight: 600;
            color: #667eea;
            margin-bottom: 24px;
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hero h1 {
            font-size: 64px;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 24px;
            color: var(--primary);
            letter-spacing: -2px;
            animation: fadeInUp 0.6s ease-out 0.1s both;
        }

        .hero h1 .gradient-text {
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero p {
            font-size: 20px;
            color: var(--secondary);
            max-width: 700px;
            margin: 0 auto 40px;
            line-height: 1.7;
            animation: fadeInUp 0.6s ease-out 0.2s both;
        }

        .hero-buttons {
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
            animation: fadeInUp 0.6s ease-out 0.3s both;
        }

        .btn-primary {
            padding: 16px 36px;
            background: var(--gradient-1);
            color: white;
            border-radius: 10px;
            text-decoration: none;
            font-size: 16px;
            font-weight: 700;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 8px 24px rgba(102, 126, 234, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 32px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            padding: 16px 36px;
            background: white;
            color: var(--primary);
            border: 2px solid var(--border);
            border-radius: 10px;
            text-decoration: none;
            font-size: 16px;
            font-weight: 700;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-secondary:hover {
            border-color: var(--primary);
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        /* Features Section */
        .features {
            background: var(--bg-light);
            padding: 100px 24px;
            position: relative;
        }

        .features::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--border), transparent);
        }

        .features-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-title {
            text-align: center;
            margin-bottom: 70px;
        }

        .section-title h2 {
            font-size: 42px;
            font-weight: 800;
            margin-bottom: 16px;
            color: var(--primary);
            letter-spacing: -1px;
        }

        .section-title p {
            font-size: 18px;
            color: var(--secondary);
            max-width: 600px;
            margin: 0 auto;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 28px;
        }

        .feature-card {
            background: white;
            padding: 36px;
            border-radius: 16px;
            border: 1px solid var(--border);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--gradient-1);
            transform: scaleX(0);
            transition: transform 0.4s;
        }

        .feature-card:hover::before {
            transform: scaleX(1);
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
            border-color: transparent;
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            background: var(--gradient-1);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-bottom: 20px;
            box-shadow: 0 8px 16px rgba(102, 126, 234, 0.2);
        }

        .feature-card:nth-child(2) .feature-icon {
            background: var(--gradient-2);
            box-shadow: 0 8px 16px rgba(245, 87, 108, 0.2);
        }

        .feature-card:nth-child(3) .feature-icon {
            background: var(--gradient-3);
            box-shadow: 0 8px 16px rgba(79, 172, 254, 0.2);
        }

        .feature-card:nth-child(4) .feature-icon {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            box-shadow: 0 8px 16px rgba(250, 112, 154, 0.2);
        }

        .feature-card:nth-child(5) .feature-icon {
            background: linear-gradient(135deg, #30cfd0 0%, #330867 100%);
            box-shadow: 0 8px 16px rgba(48, 207, 208, 0.2);
        }

        .feature-card:nth-child(6) .feature-icon {
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            box-shadow: 0 8px 16px rgba(168, 237, 234, 0.2);
        }

        .feature-card h3 {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 12px;
            color: var(--primary);
        }

        .feature-card p {
            font-size: 15px;
            color: var(--secondary);
            line-height: 1.7;
        }

        /* Stats Section */
        .stats {
            padding: 100px 24px;
            background: white;
            position: relative;
            overflow: hidden;
        }

        .stats::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 800px;
            height: 800px;
            background: radial-gradient(circle, rgba(102, 126, 234, 0.05) 0%, transparent 70%);
            pointer-events: none;
        }

        .stats-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 48px;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .stat-item {
            padding: 24px;
            border-radius: 12px;
            transition: transform 0.3s;
        }

        .stat-item:hover {
            transform: scale(1.05);
        }

        .stat-item h3 {
            font-size: 56px;
            font-weight: 800;
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 8px;
            letter-spacing: -2px;
        }

        .stat-item:nth-child(2) h3 {
            background: var(--gradient-2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stat-item:nth-child(3) h3 {
            background: var(--gradient-3);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stat-item:nth-child(4) h3 {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stat-item p {
            font-size: 16px;
            color: var(--secondary);
            font-weight: 600;
        }

        /* Footer */
        .footer {
            background: var(--bg-light);
            padding: 60px 24px;
            border-top: 1px solid var(--border);
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
        }

        .footer-links {
            display: flex;
            gap: 32px;
            justify-content: center;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }

        .footer-link {
            color: var(--secondary);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
            position: relative;
        }

        .footer-link:hover {
            color: var(--primary);
        }

        .footer-link::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--gradient-1);
            transition: width 0.3s;
        }

        .footer-link:hover::after {
            width: 100%;
        }

        .footer-text {
            font-size: 14px;
            color: var(--secondary);
        }

        /* Mobile Menu */
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: var(--primary);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .mobile-menu-btn {
                display: block;
            }

            .hero {
                padding: 80px 24px 60px;
            }

            .hero h1 {
                font-size: 42px;
                letter-spacing: -1px;
            }

            .hero p {
                font-size: 18px;
            }

            .hero-buttons {
                flex-direction: column;
            }

            .btn-primary,
            .btn-secondary {
                width: 100%;
                justify-content: center;
            }

            .features {
                padding: 60px 24px;
            }

            .section-title h2 {
                font-size: 32px;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .stats {
                padding: 60px 24px;
            }

            .stats-container {
                grid-template-columns: 1fr;
                gap: 32px;
            }

            .stat-item h3 {
                font-size: 48px;
            }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header class="header">
        <div class="nav-container">
            <a href="/" class="logo">Gerai</a>
            <nav class="nav-links">
                <a href="#features" class="nav-link">Features</a>
                <a href="https://gerai.id" target="_blank" class="nav-link">Gerai.id</a>
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn-login">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn-login">Login</a>
                    @endauth
                @endif
            </nav>
            <button class="mobile-menu-btn">☰</button>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-badge">✨ Powered by Gerai.id</div>
        <h1>Build Admin Panels<br><span class="gradient-text">in Minutes</span></h1>
        <p>Powerful template generator from Gerai.id. Create modern and professional admin panels for your projects
            without coding from scratch.</p>
        <div class="hero-buttons">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn-primary">
                        Start Generating →
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-primary">
                        Start Generating →
                    </a>
                @endauth
            @endif
            <a href="https://gerai.id" target="_blank" class="btn-secondary">
                View Our Portfolio
            </a>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="features-container">
            <div class="section-title">
                <h2>Why Choose Our Template?</h2>
                <p>Save up to 70% development time with ready-to-use features</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">👥</div>
                    <h3>User Management</h3>
                    <p>Complete user management system with roles & permissions. Ready to use, no need to build from
                        scratch.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">📝</div>
                    <h3>Content Management</h3>
                    <p>Automatic CRUD generator. Create, read, update, delete pages in just seconds.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">📊</div>
                    <h3>Analytics Dashboard</h3>
                    <p>Ready-to-use analytics dashboard with interactive charts. Monitor real-time performance.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🔌</div>
                    <h3>API Ready</h3>
                    <p>RESTful API already set up. Connect easily with mobile apps or other services.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🔐</div>
                    <h3>Security First</h3>
                    <p>Authentication, authorization, and security best practices built-in. Your project is safe.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">⚡</div>
                    <h3>Production Ready</h3>
                    <p>Clean, structured code ready to deploy. Production-ready without refactoring.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats">
        <div class="stats-container">
            <div class="stat-item">
                <h3>70%</h3>
                <p>Faster Development</p>
            </div>
            <div class="stat-item">
                <h3>100+</h3>
                <p>Ready Features</p>
            </div>
            <div class="stat-item">
                <h3>24/7</h3>
                <p>Support Ready</p>
            </div>
            <div class="stat-item">
                <h3>0</h3>
                <p>Coding from Scratch</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-links">
                <a href="https://gerai.id" target="_blank" class="footer-link">Gerai.id</a>
                <a href="https://gerai.id/service" target="_blank" class="footer-link">Services</a>
                <a href="https://gerai.id/portfolio" target="_blank" class="footer-link">Portfolio</a>
                <a href="https://gerai.id/about" target="_blank" class="footer-link">About Us</a>
                <a href="https://gerai.id/contact-us" target="_blank" class="footer-link">Contact</a>
            </div>
            <p class="footer-text">&copy; {{ date('Y') }} Gerai.id - All Rights Reserved</p>
        </div>
    </footer>
</body>

</html>
