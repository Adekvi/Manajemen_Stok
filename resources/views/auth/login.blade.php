<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login!</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome (untuk icon exclamation-triangle) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('asset/css/login.css') }}">

    <style>
        .modal-content {
            animation: fadeInScale 0.3s ease;
        }

        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-header">
            <h1>Welcome back</h1>
            <p>Please enter your credentials to login</p>
        </div>

        <form action="{{ route('auth.login') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" id="username"
                    class="form-input @error('username') is-invalid @enderror"
                    value="{{ old('username', Cookie::get('remember_username')) }}" required>
                @error('username')
                    <span class="invalid-feedback text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group position-relative">
                <label for="password" class="form-label">Password</label>

                <div class="position-relative">
                    <input type="password" name="password" id="password"
                        class="form-input pe-5 @error('password') is-invalid @enderror"
                        placeholder="Enter your password" required />

                    <!-- Eye Button -->
                    <span id="togglePassword" class="position-absolute top-50 end-0 translate-middle-y me-3"
                        style="cursor: pointer;">
                        <i class="fas fa-eye text-secondary"></i>
                    </span>
                </div>

                @error('password')
                    <span class="invalid-feedback text-danger d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="remember-forgot">
                <div class="remember-me">
                    <input type="checkbox" name="remember" id="remember" value="1"
                        {{ Cookie::has('remember_username') ? 'checked' : '' }}>
                    <label for="remember" class="checkbox-label">Remember me</label>
                </div>
                <a href="#" class="forgot-password">Forgot password?</a>
            </div>

            <button type="submit" class="login-button">Sign in</button>

            <div class="social-login">
                <div class="social-label">
                    <span>Or continue with</span>
                </div>
                <div class="social-buttons">
                    <button type="button" class="social-icon google">
                        <!-- Google Icon -->
                        <svg viewBox="0 0 24 24">
                            <path fill="#EA4335"
                                d="M12 10.2v3.9h5.4c-.2 1.3-1.5 3.8-5.4 3.8-3.2 0-5.8-2.7-5.8-6s2.6-6 5.8-6c1.8 0 3 .8 3.7 1.5l2.5-2.4C16.7 3.6 14.6 2.7 12 2.7 6.9 2.7 2.8 6.9 2.8 12s4.1 9.3 9.2 9.3c5.3 0 8.8-3.7 8.8-8.9 0-.6-.1-1.1-.2-1.6H12z" />
                        </svg>
                    </button>

                    <button type="button" class="social-icon facebook">
                        <!-- Facebook Icon -->
                        <svg viewBox="0 0 24 24">
                            <path fill="#1877F2"
                                d="M22 12a10 10 0 1 0-11.6 9.9v-7h-2.7V12h2.7V9.8c0-2.6 1.6-4 3.9-4 1.1 0 2.3.2 2.3.2v2.5h-1.3c-1.3 0-1.7.8-1.7 1.6V12h2.9l-.5 2.9h-2.4v7A10 10 0 0 0 22 12z" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="signup-link">
                Don't have an account? <a href="#">Sign up</a>
            </div>
        </form>

    </div>

    <!-- Modal Akun Dinonaktifkan -->
    <div class="modal fade" id="accountDisabledModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

                <!-- Header -->
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title d-flex align-items-center gap-2">
                        <i class="fas fa-exclamation-circle"></i>
                        Akses Ditolak
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <!-- Body -->
                <div class="modal-body text-center py-4 px-3">
                    <div class="mb-3">
                        <i class="fas fa-user-lock text-danger" style="font-size: 48px;"></i>
                    </div>

                    <!-- 🔥 HANYA SATU SUMBER PESAN -->
                    <p id="modalDisabledMessage" class="fw-semibold text-danger mb-2"></p>

                    <small class="text-muted">
                        Silakan hubungi administrator untuk bantuan lebih lanjut.
                    </small>
                </div>

                <!-- Footer -->
                <div class="modal-footer border-0 justify-content-center pb-4">
                    <button type="button" class="btn btn-danger px-4 rounded-pill" data-bs-dismiss="modal">
                        Mengerti
                    </button>
                </div>

            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Toggle password
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const togglePassword = document.getElementById('togglePassword');
            const icon = togglePassword.querySelector('i');

            togglePassword.addEventListener('click', function() {
                const isPassword = passwordInput.getAttribute('type') === 'password';

                // Toggle type
                passwordInput.setAttribute('type', isPassword ? 'text' : 'password');

                // Toggle icon
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            @if (session('account_disabled') && session('disabled_message'))
                const modalElement = document.getElementById('accountDisabledModal');

                if (modalElement) {
                    const modal = new bootstrap.Modal(modalElement);

                    // 🔥 set message dari controller (single source)
                    document.getElementById('modalDisabledMessage').textContent =
                        @json(session('disabled_message'));

                    // Delay sedikit biar smooth
                    setTimeout(() => {
                        modal.show();
                    }, 300);
                }
            @endif
        });
    </script>

</body>

</html>
