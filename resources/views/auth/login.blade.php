<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            background-image: url('{{ asset('images/bg3.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        .header {
            background-color: #f8f9fa;
            padding: 3px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header img {
            height: 50px;
        }
        .header a {
            text-decoration: none;
            color: #000;
            margin: 0 10px;
            font-weight: bold;
        }
        .main-content {
            display: flex;
            justify-content: center;
            align-items: center;
            height: calc(100vh - 60px); /* Adjust the height considering header and footer */
        }
        .image-container {
            flex: 0.3;
            display: flex;
            justify-content: center;
            align-items: center;
            background: url('{{ asset('images/login.jpg') }}') no-repeat center center;
            background-size: cover;
            height: 90%;
            margin-right: 80px;
        }
        .form-container {
            flex: 0.3;
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: rgba(255, 255, 255, 0.7);
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
        }
        .form-container img {
            height: 50px;
            margin-bottom: 10px;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 4px;
            text-align: center;
            position: fixed;
            width: 100%;
            bottom: 0;
        }
    </style>
</head>
<body>
<header class="header">
    <a href="{{ route('welcome') }}">
        <img src="{{ asset('images/logo.jpg') }}" alt="MYtrav Logo">
    </a>
    <div>
        <a href="{{ route('register') }}">Sign up/Sign in</a>
        <a href="{{ route('ktm.live_schedule') }}">Live KTM</a>
        <a href="{{ route('about') }}">About</a>
    </div>
</header>
<div class="main-content">
    <div class="image-container"></div>
    <div class="form-container">
        <img src="{{ asset('images/logo.jpg') }}" alt="MYtrav Logo">
        <h2>Login</h2>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Sign in</button>
        </form>
        <p>Don't have an account? <a href="{{ route('register') }}">Sign up</a></p>
    </div>
</div>
<footer class="footer">
    &copy; 2024 The Project by Muresh. All Rights Reserved.
</footer>
</body>
</html>

