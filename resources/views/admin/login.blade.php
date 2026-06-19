<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jin Pooja Seva - Admin Login</title>
    <!-- Lucide Icons (Kept for rendering the vector icon paths cleanly) -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        /* Global & Reset Styles */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            min-height: 100vh;
            width: 100%;
            display: flex;
            flex-col-direction: column;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            padding: 1.5rem;
            position: relative;
            background-color: #FFF9F2;
            background-image: url('{{ asset("images/five.jpeg") }}');
            background-size: cover;
            background-position: center bottom;
            background-repeat: no-repeat;
            overflow-x: hidden;
            -webkit-user-select: none;
            user-select: none;
        }

        /* ======================================================= */
        /* ARTWORK MULTI-LAYER STACK */
        /* ======================================================= */
        .artwork-layer {
            position: absolute;
            pointer-events: none;
            z-index: 0;
            mix-blend-mode: multiply;
        }

       /* Layer 1: Hanging Decorative Side Bells */
.layer-bells {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    width: 100%;
    height: 150px; /* Maintained exactly at 150px */
    background-image: url('{{ asset("images/three.jpeg") }}');
    background-repeat: no-repeat;
    background-position: top center;
    opacity: 1;
    z-index: 0;

    /* Desktop/Tablet default view */
    background-size: 100% 100%;
}

/* Mobile Screens optimization */
@media (max-width: 640px) {
    .layer-bells {
        /* Pushes the background canvas wide enough on narrow screens 
           so the bells lock exactly onto the left and right viewport edges */
        background-size: 130% 100%; 
    }
}

        /* Layer 2: Golden Temple Complex Silhouette */
        .layer-temple {
            top: 5%;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 44rem;
            height: 260px;
            background-image: url('{{ asset("images/two.png") }}');
            background-size: contain;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0.85;
        }

        /* Layer 3: Central Seated Lord Mahavira Idol */
        .layer-idol {
            top: 13%;
            left: 50%;
            transform: translateX(-50%);
            width: 17rem;
            height: 8rem;
            background-image: url('{{ asset("images/four.png") }}');
            background-size: contain;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 1.0;
        }

        @media (min-width: 768px) {
            .layer-idol {
                width: 20rem;
                height: 20rem;
            }
        }

        /* ======================================================= */
        /* FOREGROUND GLOBAL BRAND HEADER */
        /* ======================================================= */
        .app-header {
            width: 100%;
            max-width: 440px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 0.75rem;
            margin-bottom: 0.5rem;
            z-index: 10;
            position: relative;
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }

        .brand-box {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .brand-logo {
            height: 3.5rem;
            width: auto;
            object-fit: contain;
            mix-blend-mode: multiply;
        }

        .brand-title {
            font-family: Georgia, Cambria, "Times New Roman", Times, serif;
            font-size: 1.875rem;
            font-weight: bold;
            color: #E65100;
            letter-spacing: 0.025em;
        }

        .header-controls {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .bell-container {
            position: relative;
            cursor: pointer;
            padding: 0.5rem;
            background-color: rgba(255, 255, 255, 0.4);
            border-radius: 9999px;
            backdrop-filter: blur(8px);
            border: 1px solid rgba(251, 191, 36, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .bell-icon {
            width: 1.25rem;
            height: 1.25rem;
            color: #E65100;
        }

        .bell-badge {
            position: absolute;
            top: -0.125rem;
            right: -0.125rem;
            background-color: #E65100;
            color: white;
            font-size: 10px;
            width: 1.125rem;
            height: 1.125rem;
            border-radius: 9999px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .avatar-frame {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 9999px;
            border: 1px solid #FCD34D;
            overflow: hidden;
            background-color: #fff;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .avatar-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transform: scale(1.25);
        }

        /* ======================================================= */
        /* ADMINISTRATIVE LOGIN BOX CONTAINER */
        /* ======================================================= */
        .login-card {
            width: 100%;
            max-width: 440px;
            background-color: rgba(255, 253, 251, 0.95);
            backdrop-filter: blur(24px);
            padding: 2rem 1.5rem;
            border-radius: 2.5rem;
            border: 1px solid rgba(251, 191, 36, 0.5);
            box-shadow: 0 20px 50px rgba(230, 81, 0, 0.05);
            margin-top: auto;
            margin-bottom: auto;
            z-index: 10;
            position: relative;
        }

        .divider-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .divider-line {
            width: 3.5rem;
            height: 1px;
            background: linear-gradient(to right, transparent, rgba(245, 158, 11, 0.6));
        }

        .divider-line.rev {
            background: linear-gradient(to left, transparent, rgba(245, 158, 11, 0.6));
        }

        .badge-wrapper {
            padding: 0.625rem;
            background-color: #FFFBEB;
            border: 1px solid rgba(253, 230, 138, 0.8);
            border-radius: 0.75rem;
            color: #D97706;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-title {
            font-family: Georgia, Cambria, "Times New Roman", Times, serif;
            font-size: 1.875rem;
            font-weight: bold;
            text-align: center;
            color: #262626;
            margin-bottom: 0.25rem;
            letter-spacing: -0.025em;
        }

        .card-subtitle {
            font-size: 0.875rem;
            text-align: center;
            color: #737373;
            margin-bottom: 2rem;
            font-weight: 500;
            letter-spacing: 0.025em;
        }

        /* Forms & Inputs */
        .form-group {
            position: relative;
            margin-bottom: 1.125rem;
        }

        .input-icon {
            position: absolute;
            top: 50%;
            left: 1rem;
            transform: translateY(-50%);
            pointer-events: none;
            color: #F97316;
            display: flex;
            align-items: center;
        }

        .form-input {
            width: 100%;
            padding: 0.875rem 1rem 0.875rem 3rem;
            background-color: rgba(250, 250, 250, 0.4);
            border: 1px solid #e5e5e5;
            border-radius: 1rem;
            color: #262626;
            font-size: 1rem;
            outline: none;
            transition: all 0.2s ease;
        }

        .form-input::placeholder {
            color: #a3a3a3;
        }

        .form-input:focus {
            border-color: #F59E0B;
            box-shadow: 0 0 0 2px rgba(245, 158, 11, 0.2);
        }

        .password-toggle {
            position: absolute;
            top: 50%;
            right: 1rem;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #a3a3a3;
            cursor: pointer;
            display: flex;
            align-items: center;
        }

        .password-toggle:hover {
            color: #525252;
        }

        /* Action bar settings */
        .action-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.875rem;
            padding: 0.125rem 0.25rem;
            margin-bottom: 1.125rem;
        }

        .remember-label {
            display: flex;
            align-items: center;
            color: #525252;
            cursor: pointer;
            font-weight: 500;
        }

        .checkbox-input {
            width: 1.125rem;
            height: 1.125rem;
            border-radius: 0.25rem;
            border: 1px solid #d4d4d4;
            margin-right: 0.5rem;
            cursor: pointer;
            accent-color: #EA580C;
        }

        .forgot-link {
            font-weight: bold;
            color: #EA580C;
            text-decoration: none;
            transition: color 0.15s ease;
        }

        .forgot-link:hover {
            color: #C2410C;
        }

        /* Vivid Gradient Action Trigger Button */
        .submit-btn {
            width: 100%;
            background: linear-gradient(to right, #F97316, #EA580C, #DC2626);
            color: white;
            padding: 1rem;
            border: none;
            border-radius: 1rem;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            letter-spacing: 0.025em;
            box-shadow: 0 4px 6px -1px rgba(234, 88, 12, 0.1);
            transition: all 0.2s ease;
        }

        .submit-btn:hover {
            opacity: 0.95;
            box-shadow: 0 10px 15px -3px rgba(234, 88, 12, 0.2);
        }

        .submit-btn:active {
            transform: scale(0.99);
        }

        /* Card Footer elements */
        .card-footer {
            margin-top: 1.75rem;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .footer-divider {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-bottom: 1.125rem;
        }

        .footer-line {
            width: 3.5rem;
            height: 1px;
            background-color: #f5f5f5;
        }

        .footer-dot {
            padding: 0.25rem;
            background-color: #F59E0B;
            border-radius: 9999px;
        }

        .status-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.375rem;
            color: #525252;
            font-size: 0.75rem;
            font-weight: bold;
            letter-spacing: 0.025em;
            background-color: rgba(254, 243, 199, 0.5);
            padding: 0.5rem 1.25rem;
            border-radius: 9999px;
            border: 1px solid rgba(251, 191, 36, 0.4);
        }

        .status-icon {
            width: 1rem;
            height: 1rem;
            color: #D97706;
        }

        /* Laravel Alerts Custom Overrides */
        .alert-box {
            padding: 0.875rem;
            border-radius: 0.75rem;
            margin-bottom: 1rem;
            font-size: 0.875rem;
        }

        .alert-success {
            background-color: #ECFDF5;
            border: 1px solid #A7F3D0;
            color: #065F46;
        }

        .alert-error {
            background-color: #FEF2F2;
            border: 1px solid #FEE2E2;
            color: #991B1B;
        }

        .alert-list {
            padding-left: 1.25rem;
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }

        .layout-spacer {
            height: 0.5rem;
        }
    </style>
</head>

<body>

    <!-- ======================================================= -->
    <!-- BACKGROUND ARTWORK LAYER -->
    <!-- ======================================================= -->
    <div class="artwork-layer layer-bells"></div>
    <div class="artwork-layer layer-temple"></div>
    <div class="artwork-layer layer-idol"></div>

    <!-- ======================================================= -->
    <!-- TOP FOREGROUND APP HEADER -->
    <!-- ======================================================= -->
    <header class="app-header">
        <div class="brand-box">
            <img src="{{ asset('images/one.png') }}" alt="Tilak Logo" class="brand-logo">
            <h1 class="brand-title">Jin Pooja Seva</h1>
        </div>

        <div class="header-controls">
            <!-- <div class="bell-container">
                <i data-lucide="bell" class="bell-icon"></i>
                <span class="bell-badge">5</span>
            </div> -->
            <div class="avatar-frame">
                <img src="{{ asset('images/four.png') }}" alt="Profile" class="avatar-img">
            </div>
        </div>
    </header>

    <!-- ======================================================= -->
    <!-- RE-COLORED ADMINISTRATIVE LOGIN CARD CONTAINER -->
    <!-- ======================================================= -->
    <main class="login-card">

        <div class="divider-badge">
            <div class="divider-line"></div>
            <div class="badge-wrapper">
                <i data-lucide="shield-check" style="width: 1.25rem; height: 1.25rem;"></i>
            </div>
            <div class="divider-line rev"></div>
        </div>

        <h2 class="card-title">Admin Login</h2>
        <p class="card-subtitle">For Sunday Worship Attendance Management</p>

        <!-- Session Success Messages -->
        @if(session('success'))
        <div class="alert-box alert-success" style="display: flex; align-items: center; gap: 0.5rem;">
            <i data-lucide="check-circle" style="width: 1.125rem; height: 1.125rem; color: #059669; flex-shrink: 0;"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        <!-- Session Error Validation Arrays -->
        @if($errors->any())
        <div class="alert-box alert-error">
            <ul class="alert-list">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Identity Credentials Verification Form -->
        <form action="{{ route('admin.login') }}" method="POST">
            @csrf

            <!-- User / Key Handle Field -->
            <div class="form-group">
                <span class="input-icon">
                    <i data-lucide="user" style="width: 1.25rem; height: 1.25rem;"></i>
                </span>
                <input type="text" name="email" value="{{ old('email') }}" required placeholder="Mobile Number / Email" class="form-input">
            </div>

            <!-- Secured Password string Input -->
            <div class="form-group">
                <span class="input-icon">
                    <i data-lucide="lock" style="width: 1.25rem; height: 1.25rem;"></i>
                </span>
                <input type="password" id="password" name="password" required placeholder="Password" class="form-input">
                <button type="button" onclick="togglePasswordVisibility()" class="password-toggle">
                    <i id="eye-icon" data-lucide="eye" style="width: 1.25rem; height: 1.25rem;"></i>
                </button>
            </div>

            <!-- Configuration Elements Control -->
            <div class="action-bar">
                <label class="remember-label">
                    <input type="checkbox" name="remember" class="checkbox-input">
                    Remember me
                </label>
                <a href="#" class="forgot-link">Forgot Password?</a>
            </div>

            <!-- Dynamic Execution Button Trigger -->
            <button type="submit" class="submit-btn">
                <i data-lucide="flame" style="width: 1.25rem; height: 1.25rem; opacity: 0.9;"></i>
                Login
            </button>
        </form>

        <!-- Access Rights Notice Footer Area -->
        <div class="card-footer">
            <div class="footer-divider">
                <div class="footer-line"></div>
                <div class="footer-dot"></div>
                <div class="footer-line"></div>
            </div>
            <div class="status-badge">
                <i data-lucide="shield-alert" class="status-icon"></i>
                For authorized volunteers only
            </div>
        </div>
    </main>

    <div class="layout-spacer"></div>

    <!-- Active Icon and Form Control Scripts -->
    <script>
        lucide.createIcons();

        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.setAttribute('data-lucide', 'eye-off');
            } else {
                passwordInput.type = 'password';
                eyeIcon.setAttribute('data-lucide', 'eye');
            }
            lucide.createIcons();
        }
    </script>
</body>

</html>