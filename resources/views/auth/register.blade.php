<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Create Account | Incident Report System</title>
    <script src="{{ asset('libs/jquery.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>

    <div class="login-page">

        <div class="login-card">

            <!-- Header -->
            <div class="login-header">

                <div class="login-icon">
                    👤
                </div>

                <h1>Create Account</h1>

                <p>Register as a local resident</p>

            </div>

            @if ($errors->any())
                <div style="background:#ffe5e5; color:#b00020; padding:15px; margin-bottom:15px; border-radius:8px;">
                    <strong>Registration failed:</strong>

                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div style="background:#e5ffe5; color:#087f23; padding:15px; margin-bottom:15px; border-radius:8px;">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Registration Form -->
            <form method="POST" action="{{ url('/auth/register') }}">

                @csrf

                <div class="form-group">
                    <label for="name">Full Name</label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        placeholder="Enter your full name"
                        value="{{ old('name') }}"
                        required
                    >
                </div>


                <div class="form-group">
                    <label for="contact_number">Contact Number</label>

                    <input
                        type="text"
                        id="contact_number"
                        name="contact_number"
                        placeholder="Enter your contact number"
                        value="{{ old('contact_number') }}"
                        required
                    >
                </div>


                <div class="form-group">
                    <label for="email">Email</label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="Optional"
                        value="{{ old('email') }}"
                    >
                </div>


                <div class="form-group">
                    <label for="address">Address / Purok</label>

                    <input
                        type="text"
                        id="address"
                        name="address"
                        placeholder="Enter your address or Purok"
                        value="{{ old('address') }}"
                        required
                    >
                </div>


                <div class="form-group">

                    <label for="password">
                        Password
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Create a password"
                        autocomplete="new-password"
                        required
                    >

                    <label class="show-password-label">

                        <input
                            type="checkbox"
                            id="showPassword"
                        >

                        <span>Show password</span>

                    </label>

                </div>


                <div class="form-group">

                    <label for="password_confirmation">
                        Confirm Password
                    </label>

                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        placeholder="Confirm your password"
                        autocomplete="new-password"
                        required
                    >

                </div>


                <!--
                    Role is NOT selected by the user.
                    Registering through this form creates a LOCAL account.
                -->
                <input type="hidden" name="role" value="LOCAL">


                <button type="submit" class="login-btn">
                    Create Account
                </button>

            </form>


            <!-- Login Link -->
            <div class="login-footer">

                <p>
                    Already have an account?
                    <a href="{{ url('/auth/login') }}"
                       class="forgot-password">
                        Login
                    </a>
                </p>

            </div>

        </div>

    </div>

</body>

</html>

<script src="{{ asset('js/login.js')}}"></script>
