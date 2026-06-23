@extends('layouts.app')

@section('title', 'Connexion - Eco-Déchet')

@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=Syne:wght@500;700;800&family=Inter:wght@400;500;600;700&display=swap');

:root {
    --primary: #2f5d3a;
    --accent: #e8b84b;
    --bg: #f4f7ff;
    --card: #ffffff;
    --text: #1f2937;
    --radius: 18px;
}

body {
    background: var(--bg);
    font-family: 'Inter', system-ui, sans-serif; /* Police principale ultra-professionnelle */
}

/* Masquer les éléments de navigation hérités du layout global */
nav, .layout-navbar, .search-bar, footer {
    display: none !important;
}

/* CENTER WRAPPER */
.login-wrapper {
    position: fixed;
    inset: 0;
    z-index: 999;
    background: var(--bg);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 10px;
}

/* CARD */
.login-card {
    width: 100%;
    max-width: 990px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    background: var(--card);
    border-radius: var(--radius);
    overflow: hidden;
    box-shadow: 0 30px 80px rgba(0,0,0,0.12);
}

/* LEFT SIDE */
.login-visual {
    background: linear-gradient(135deg, rgba(47,93,58,0.92), rgba(47,93,58,0.85)),
                url('https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=900&q=80');
    background-size: cover;
    background-position: center;
    color: white;
    padding: 50px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.login-visual h2 {
    font-family: 'Syne', sans-serif;
    font-size: 42px;
    font-weight: 800;
    line-height: 1.1;
    margin-bottom: 16px;
}

.login-visual p {
    font-family: 'Inter', sans-serif;
    opacity: 0.92;
    font-size: 16.5px;
    line-height: 1.65;
    max-width: 320px;
    font-weight: 400;
}

/* RIGHT SIDE */
.login-form-area {
    padding: 55px 60px;
}

.brand {
    font-family: 'Syne', sans-serif;
    font-size: 29px;
    font-weight: 800;
    margin-bottom: 28px;
    color: var(--primary);
}

.brand span {
    color: var(--accent);
}

/* INPUT */
.form-group {
    margin-bottom: 20px;
}

label {
    font-family: 'Inter', sans-serif;
    font-weight: 600;
    font-size: 13.8px;
    margin-bottom: 7px;
    display: block;
    color: #374151;
    letter-spacing: 0.3px;
}

.input-box {
    position: relative;
}

.input-box i {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
    font-size: 18px;
}

.input-box input {
    width: 100%;
    padding: 13px 13px 13px 46px;
    border-radius: 12px;
    border: 1.8px solid #e5e7eb;
    outline: none;
    transition: 0.2s;
    font-size: 15px;
    font-family: 'Inter', sans-serif;
    font-weight: 500;
}

.input-box input:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(47,93,58,0.12);
}

/* BUTTON */
.btn-login {
    width: 100%;
    padding: 14px;
    background: var(--primary);
    color: white;
    border: none;
    border-radius: 12px;
    font-weight: 700;
    font-family: 'Syne', sans-serif;
    font-size: 15.5px;
    cursor: pointer;
    transition: 0.25s ease;
    margin-top: 12px;
    letter-spacing: 0.4px;
}

.btn-login:hover {
    background: #24472d;
    transform: translateY(-2px);
}

/* ERROR */
.error-text {
    font-family: 'Inter', sans-serif;
    font-size: 13px;
    color: #dc2626;
    margin-top: 6px;
    font-weight: 500;
}

/* FOOTER LINKS */
.bottom-text {
    text-align: center;
    margin-top: 22px;
    font-size: 14px;
    font-family: 'Inter', sans-serif;
}

.bottom-text a {
    color: var(--accent);
    font-weight: 600;
    text-decoration: none;
}

.bottom-text a:hover {
    text-decoration: underline;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .login-card {
        grid-template-columns: 1fr;
    }

    .login-visual {
        display: none;
    }

    .login-form-area {
        padding: 40px 35px;
    }
    
    .login-visual h2 {
        font-size: 36px;
    }
}
</style>

<div class="login-wrapper">
    <div class="login-card">

        <!-- LEFT -->
        <div class="login-visual">
            <h2>Eco-Flux</h2>
            <p>
                Transformons les déchets en ressources durables grâce à une gestion intelligente et écologique.
            </p>
        </div>

        <!-- RIGHT -->
        <div class="login-form-area">

            <div class="brand">
                Connexion <span>Sécurisée</span>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label>Email</label>
                    <div class="input-box">
                        <i class="bx bx-envelope"></i>
                        <input type="email" name="email"
                               value="{{ old('email') }}"
                               required
                               placeholder="exemple@mail.com">
                    </div>
                    @error('email')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Mot de passe</label>
                    <div class="input-box">
                        <i class="bx bx-lock"></i>
                        <input type="password" name="password" required placeholder="••••••••">
                    </div>
                </div>

                <div style="display:flex; justify-content:space-between; font-size:13.8px; margin:12px 0;">
                    <label>
                        <input type="checkbox" name="remember"> Se souvenir
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" style="color: var(--primary); text-decoration:none;">
                            Mot de passe oublié ?
                        </a>
                    @endif
                </div>

                <button class="btn-login">
                    Se connecter →
                </button>

                <!-- <div class="bottom-text">
                    Nouveau utilisateur ?
                    <a href="{{ route('register') }}">Créer un compte</a>
                </div> -->

            </form>

        </div>
    </div>
</div>

@endsection