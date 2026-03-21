<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Rent ERP') }} | Smart Property Operations</title>
    <meta name="description" content="Run rent operations, vehicle services, utility billing, and compliance from a single ERP workspace.">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=sora:400,500,600,700,800|space-grotesk:400,500,700" rel="stylesheet" />

    <style>
        :root {
            --bg: #0b1220;
            --surface: #121d33;
            --surface-soft: #182641;
            --text: #f3f8ff;
            --muted: #acc2df;
            --line: rgba(202, 226, 255, 0.2);
            --brand: #27c8ff;
            --brand-2: #5df7cc;
            --warm: #ff9a4a;
            --shadow: 0 18px 50px rgba(5, 12, 24, 0.45);
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            min-height: 100%;
            font-family: "Space Grotesk", sans-serif;
            background: radial-gradient(1200px 800px at 15% -20%, #174f8d 0%, transparent 52%),
                        radial-gradient(900px 760px at 110% 0%, #127f66 0%, transparent 52%),
                        linear-gradient(160deg, #070d19 0%, #0c1728 55%, #091321 100%);
            color: var(--text);
        }

        .noise {
            position: fixed;
            inset: 0;
            pointer-events: none;
            opacity: 0.18;
            background-image: radial-gradient(rgba(255, 255, 255, 0.4) 0.45px, transparent 0.45px);
            background-size: 3px 3px;
        }

        .container {
            width: min(1120px, calc(100% - 40px));
            margin: 0 auto;
            position: relative;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 24px 0;
            position: relative;
            z-index: 2;
        }

        .logo {
            font-family: "Sora", sans-serif;
            font-weight: 800;
            letter-spacing: 0.04em;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo-dot {
            width: 12px;
            height: 12px;
            border-radius: 999px;
            background: linear-gradient(120deg, var(--brand), var(--brand-2));
            box-shadow: 0 0 20px rgba(39, 200, 255, 0.8);
        }

        .nav-links {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .btn {
            text-decoration: none;
            border-radius: 999px;
            padding: 11px 18px;
            border: 1px solid transparent;
            font-weight: 600;
            transition: transform 0.25s ease, border-color 0.25s ease, background-color 0.25s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--text);
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn-ghost {
            border-color: var(--line);
            background: rgba(31, 52, 87, 0.35);
        }

        .btn-brand {
            background: linear-gradient(120deg, var(--brand), var(--brand-2));
            color: #032431;
            box-shadow: 0 12px 30px rgba(39, 200, 255, 0.35);
        }

        .hero {
            padding: 42px 0 20px;
            display: grid;
            grid-template-columns: 1.15fr 0.85fr;
            gap: 26px;
            align-items: stretch;
        }

        .hero-copy {
            background: linear-gradient(160deg, rgba(20, 34, 58, 0.9), rgba(16, 27, 46, 0.85));
            border: 1px solid var(--line);
            border-radius: 28px;
            padding: 34px;
            backdrop-filter: blur(8px);
            box-shadow: var(--shadow);
            animation: reveal 0.9s ease both;
        }

        .hero-kicker {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: 1px solid rgba(147, 209, 255, 0.35);
            border-radius: 999px;
            padding: 8px 14px;
            color: var(--muted);
            font-size: 0.86rem;
            margin-bottom: 20px;
        }

        .hero h1 {
            margin: 0;
            font-family: "Sora", sans-serif;
            font-size: clamp(2rem, 4vw, 3.7rem);
            line-height: 1.07;
            letter-spacing: -0.03em;
            max-width: 18ch;
        }

        .hero h1 .gradient {
            background: linear-gradient(100deg, #95ecff, #5df7cc 54%, #ffca7b);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .hero p {
            color: var(--muted);
            line-height: 1.65;
            margin: 18px 0 24px;
            max-width: 56ch;
        }

        .cta-row {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 18px;
        }

        .mini-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 10px;
            margin-top: 12px;
        }

        .mini {
            border: 1px solid var(--line);
            border-radius: 16px;
            padding: 12px;
            background: rgba(30, 49, 81, 0.46);
        }

        .mini strong {
            display: block;
            font-size: 1.15rem;
            font-family: "Sora", sans-serif;
            margin-bottom: 3px;
        }

        .mini span {
            font-size: 0.84rem;
            color: var(--muted);
        }

        .hero-panel {
            border: 1px solid var(--line);
            border-radius: 28px;
            padding: 22px;
            background: linear-gradient(165deg, rgba(21, 35, 59, 0.8), rgba(12, 20, 36, 0.9));
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow);
            animation: reveal 0.9s ease 0.08s both;
        }

        .hero-panel::before,
        .hero-panel::after {
            content: "";
            position: absolute;
            border-radius: 999px;
            filter: blur(8px);
            opacity: 0.7;
        }

        .hero-panel::before {
            width: 220px;
            height: 220px;
            background: rgba(39, 200, 255, 0.25);
            top: -80px;
            right: -90px;
            animation: float 7s ease-in-out infinite;
        }

        .hero-panel::after {
            width: 180px;
            height: 180px;
            background: rgba(255, 154, 74, 0.2);
            left: -50px;
            bottom: -70px;
            animation: float 8.2s ease-in-out infinite reverse;
        }

        .panel-title {
            font-family: "Sora", sans-serif;
            font-size: 1rem;
            margin: 2px 0 16px;
            color: #d6ebff;
        }

        .timeline {
            display: grid;
            gap: 10px;
            position: relative;
            z-index: 1;
        }

        .timeline-item {
            border: 1px solid rgba(202, 226, 255, 0.25);
            background: rgba(18, 31, 53, 0.65);
            border-radius: 14px;
            padding: 11px 12px;
            display: grid;
            grid-template-columns: auto 1fr auto;
            gap: 10px;
            align-items: center;
            font-size: 0.9rem;
        }

        .badge {
            background: rgba(93, 247, 204, 0.18);
            border: 1px solid rgba(93, 247, 204, 0.5);
            color: #afffdf;
            padding: 4px 8px;
            border-radius: 999px;
            font-size: 0.76rem;
            font-weight: 600;
        }

        .features {
            margin: 26px 0 10px;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
        }

        .feature {
            padding: 20px;
            border: 1px solid var(--line);
            border-radius: 20px;
            background: linear-gradient(160deg, rgba(19, 31, 53, 0.8), rgba(10, 17, 30, 0.88));
            box-shadow: var(--shadow);
            transform: translateY(16px);
            opacity: 0;
            animation: riseIn 0.8s ease forwards;
        }

        .feature:nth-child(2) {
            animation-delay: 0.1s;
        }

        .feature:nth-child(3) {
            animation-delay: 0.2s;
        }

        .feature .tag {
            color: var(--warm);
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 700;
        }

        .feature h3 {
            font-family: "Sora", sans-serif;
            margin: 10px 0 8px;
            font-size: 1.15rem;
        }

        .feature p {
            margin: 0;
            color: var(--muted);
            line-height: 1.6;
            font-size: 0.95rem;
        }

        footer {
            padding: 34px 0 24px;
            color: var(--muted);
            font-size: 0.88rem;
            display: flex;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
        }

        @keyframes reveal {
            from {
                opacity: 0;
                transform: translateY(26px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes riseIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes float {
            0%,
            100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-15px);
            }
        }

        @media (max-width: 980px) {
            .hero {
                grid-template-columns: 1fr;
            }

            .features {
                grid-template-columns: 1fr;
            }

            .mini-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .container {
                width: min(1120px, calc(100% - 24px));
            }

            .topbar {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .hero-copy,
            .hero-panel {
                padding: 20px;
                border-radius: 20px;
            }

            .timeline-item {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="noise" aria-hidden="true"></div>

    @php
        $hasLogin = Route::has('login');
        $hasRegister = Route::has('register');
    @endphp

    <div class="container">
        <header class="topbar">
            <div class="logo">
                <span class="logo-dot" aria-hidden="true"></span>
                <span>RENT ERP</span>
            </div>

            <nav class="nav-links">
                <a href="{{ url('/admin') }}" class="btn btn-brand">Open Admin</a>
                @if ($hasLogin)
                    <a href="{{ route('login') }}" class="btn btn-ghost">Login</a>
                @endif
                @if ($hasRegister)
                    <a href="{{ route('register') }}" class="btn btn-ghost">Register</a>
                @endif
            </nav>
        </header>

        <section class="hero">
            <div class="hero-copy">
                <div class="hero-kicker">Live branch operations dashboard</div>
                <h1>
                    Own your rent workflow from
                    <span class="gradient">agreement to payment</span>
                </h1>
                <p>
                    Built for multi-branch teams handling office leases, vehicle fleets, utility billing,
                    and compliance notifications in one place. Designed for speed, auditability, and less manual follow-up.
                </p>

                <div class="cta-row">
                    <a href="{{ url('/admin') }}" class="btn btn-brand">Launch ERP Panel</a>
                    <a href="#modules" class="btn btn-ghost">Explore Modules</a>
                </div>

                <div class="mini-grid">
                    <div class="mini">
                        <strong>10+</strong>
                        <span>Core ERP modules</span>
                    </div>
                    <div class="mini">
                        <strong>Real-time</strong>
                        <span>Task and due alerts</span>
                    </div>
                    <div class="mini">
                        <strong>Role-safe</strong>
                        <span>Ops, Legal, and Finance scopes</span>
                    </div>
                </div>
            </div>

            <aside class="hero-panel">
                <h2 class="panel-title">Today&apos;s Operational Pulse</h2>
                <div class="timeline">
                    <div class="timeline-item">
                        <span class="badge">Office Rent</span>
                        <span>3 agreements waiting legal approval</span>
                        <span>Now</span>
                    </div>
                    <div class="timeline-item">
                        <span class="badge">Fleet</span>
                        <span>2 vehicle service requests assigned</span>
                        <span>+12m</span>
                    </div>
                    <div class="timeline-item">
                        <span class="badge">Utilities</span>
                        <span>5 bills due this week across branches</span>
                        <span>+1h</span>
                    </div>
                    <div class="timeline-item">
                        <span class="badge">Compliance</span>
                        <span>Licenses and inspections nearing expiry</span>
                        <span>Daily</span>
                    </div>
                </div>
            </aside>
        </section>

        <section class="features" id="modules">
            <article class="feature">
                <div class="tag">01 Office Management</div>
                <h3>Branch + Agreement Lifecycle</h3>
                <p>Track branch onboarding, lease decisions, renewals, and payment status in a workflow built for legal and operations teams.</p>
            </article>
            <article class="feature">
                <div class="tag">02 Fleet Control</div>
                <h3>Vehicle Service Orchestration</h3>
                <p>Handle requests, assignment, completion, and maintenance history while keeping inspection and licensing deadlines visible.</p>
            </article>
            <article class="feature">
                <div class="tag">03 Finance Utilities</div>
                <h3>Billing + Notification Engine</h3>
                <p>Generate utility and rent reminders, push scheduled alerts, and centralize payment records for cleaner monthly closeouts.</p>
            </article>
        </section>

        <footer>
            <span>{{ config('app.name', 'Rent ERP') }} on Laravel v{{ app()->version() }}</span>
            <span>Local environment: <strong>http://localhost:8080</strong></span>
        </footer>
    </div>
</body>
</html>
