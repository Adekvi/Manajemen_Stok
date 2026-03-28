<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login!</title>
    <link rel="stylesheet" href="{{ asset('asset/css/login.css') }}">
</head>

<body>
    <div class="login-container">
        <div class="login-header">
            <h1>Welcome back</h1>
            <p>Please enter your credentials to login</p>
        </div>

        <form action="{{ route('auth.login') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" id="username"
                    class="form-input @error('username') is-invalid @enderror"
                    value="{{ old('username', Cookie::get('username')) }}" placeholder="Username" required />
                @error('username')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password"
                    class="form-input @error('password') is-invalid @enderror" placeholder="Enter your password"
                    value="{{ old('password', Cookie::get('password')) }}" required />
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="remember-forgot">
                <div class="remember-me">
                    <input type="checkbox" id="remember" class="checkbox-input" name="remember"
                        {{ Cookie::has('username') ? 'checked' : '' }} />
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
</body>

</html>
