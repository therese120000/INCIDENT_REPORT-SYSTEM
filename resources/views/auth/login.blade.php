<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login | Incident Report System</title>

    <script src="{{ asset('libs/jquery.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>

    <div class="login-page">

        <div class="login-card">

            <div class="login-header">
                <div class="login-icon">
                    ⚠
                </div>

                <h1>Incident Report System</h1>
                <p>Sign in to continue</p>
            </div>

            <form id="loginForm" method="POST" action="/auth/login">

                @csrf

                <div class="form-group">
                    <label for="contact_number">Phone Number</label>

                    <input
                        type="text"
                        id="contact_number"
                        name="contact_number"
                        placeholder="Enter your phone number"
                        autocomplete="tel"
                        value="{{ old('contact_number') }}"
                        required
                        autofocus
                    >

                    @error('contact_number')
                        <small class="error-message">
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                <div class="form-group">

                    <label for="password">
                        Password
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Enter your password"
                        autocomplete="current-password"
                        required
                    >

                    <label class="show-password-label">
                        <input
                            type="checkbox"
                            id="showPassword"
                        >
                        <span>Show password</span>
                    </label>

                    @error('password')
                        <small class="error-message">
                            {{ $message }}
                        </small>
                    @enderror

                </div>

                <!-- <div class="login-options">

                    <label class="remember-me">
                        <input type="checkbox" id="remember" name="remember">
                        <span>Remember me</span>
                    </label>

                    <a href="#" class="forgot-password">
                        Forgot Password?
                    </a>

                </div> -->

                <button type="submit" class="login-btn">
                    Login
                </button>

                <div class="register-link">
                    <span>Don't have an account?</span>
                    <a href="{{ url('/auth/register') }}">Create an Account</a>
                </div>

            </form>

            <div class="login-footer">
                <p></p>
                <span>Reporting Portal</span>
            </div>

        </div>

    </div>

</body>

</html>
<script src="{{ asset('js/login.js')}}"></script>