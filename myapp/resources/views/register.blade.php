<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 600px;
            margin-top: 50px;
            background-color: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-control {
            border-radius: 0.25rem;
        }
        .btn-primary {
            border-radius: 0.25rem;
        }
        .btn-link {
            font-size: 0.875rem;
        }
        .alert {
            border-radius: 0.25rem;
        }
        .form-group label {
            font-weight: bold;
        }
        .resend-section {
            display: none;
        }
        .link-button {
            background: none;
            border: none;
            color: #007bff; /* Bootstrap primary link color, adjust if needed */
            text-decoration: underline;
            cursor: pointer;
            padding: 0;
        }

        .link-button:hover {
            color: #0056b3; /* Darker shade for hover effect, adjust if needed */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center mb-4">Register</h2>
        <form action="{{ route('register') }}" method="POST">
            @csrf
            <!-- Registration form fields -->
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                @error('name')
                    <div class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </div>
                @enderror
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <div class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </div>
                @enderror
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                @error('password')
                    <div class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </div>
                @enderror
            </div>
            <div class="form-group">
                <label for="password_confirmation">Confirm Password:</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Register</button>
        </form>

        @if(session('message'))
            <div class="alert alert-success mt-3">
                {{ session('message') }}
            </div>
        @endif
    
        <!-- Button to show resend verification section -->
        <div class="mt-3 text-center">
            <button class="link-button" onclick="toggleResendSection()">If you did not receive any link kindly click here!</button>
        </div>
        <!-- Resend verification email section -->
        <div id="resendSection" class="resend-section mt-3">
            <form action="{{ route('verification.resend') }}" method="POST">
                @csrf
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="resend_email" name="email" placeholder="Enter your email" value="{{ old('email') }}" required>
                @error('email')
                    <div class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </div>
                @enderror
                <button type="submit" class="btn btn-link">Resend verification email</button>
            </form>
        </div>
    </div>

    <script>
        function toggleResendSection() {
            var resendSection = document.getElementById('resendSection');
            if (resendSection.style.display === 'none' || resendSection.style.display === '') {
                resendSection.style.display = 'block';
            } else {
                resendSection.style.display = 'none';
            }
        }
    </script>
</body>
</html>
