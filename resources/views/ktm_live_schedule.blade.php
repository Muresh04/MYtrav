<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MYtrav KTM Live Schedule</title>
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
        .iframe-container {
            width: 100%;
            height: calc(100vh - 50px);
            border: none;
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
        @auth
            <a href="{{ route('profile') }}">Profile</a>
        @else
            <a href="{{ route('register') }}">Sign up/Sign in</a>
        @endauth
        <a href="{{ route('about') }}">About</a>
    </div>
</header>
<iframe src="https://moovitapp.com/index/en/public_transit-line-1-Kuala_Lumpur-1082-850995-641390-0" class="iframe-container"></iframe>
<footer class="footer">
    &copy; 2024 The Project by Muresh. All Rights Reserved.
</footer>
</body>
</html>
