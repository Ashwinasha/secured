<!-- resources/views/welcome.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Your Application</title>
    <!-- Bootstrap CSS via CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <style>
        /* Optional: Custom styles for your welcome page */
        body {
            background-color: #f8f9fa;
            padding: 3rem;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            
        }
    </style>
</head>
<body>
    <div class="container text-center">
        <h1>Welcome to Your Application</h1>
        <p class="lead">implementation of secured registration and login system </p>
        <div class="mt-4">
            <a href="{{ route('login') }}" class="btn btn-primary mr-2">Login</a>
            <a href="{{ route('register') }}" class="btn btn-success">Register</a>
        </div>
    </div>

    <!-- Bootstrap JS via CDN (optional, for certain Bootstrap features) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8sh+EW0PA/O3c5GmWvEyfldFkUt6BvFExl3Og4" crossorigin="anonymous"></script>
</body>
</html>
