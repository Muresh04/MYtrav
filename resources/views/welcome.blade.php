<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MYtrav</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
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
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            padding: 20px;
        }
        .main-content img {
            width: 100%;
            max-width: 300px;
            height: auto;
        }
        .search-box {
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: rgba(0, 0, 0, 0.6);
            padding: 20px;
            border-radius: 20px;
            color: #fff;
            position: absolute;
            top: 73%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        .search-box .row {
            display: flex;
            width: 100%;
            justify-content: space-between;
        }
        .search-box .row input {
            margin: 10px 0;
            padding: 10px;
            border: none;
            border-radius: 5px;
            width: 48%;
        }
        .search-box button {
            margin: 10px 0;
            padding: 10px;
            border: none;
            border-radius: 5px;
            width: 100%;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 4px;
            text-align: center;
            position: fixed;
            width: 100%;
            bottom: 0;
        }
        .image-container {
            display: flex;
            justify-content: space-around;
        }
        .image-container img {
            width: 22%;
            height: auto;
        }
        .image-container img:nth-child(2) {
            width: 28%;
            height:10%;
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
        <a href="{{ route('ktm.live_schedule') }}">Live KTM</a>
        <a href="{{ route('about') }}">About</a>
    </div>
</header>
<div class="image-container">
    <img src="{{ asset('images/plane.jpg') }}" alt="Airplane">
    <img src="{{ asset('images/bus.jpg') }}" alt="Bus">
    <img src="{{ asset('images/train.jpg') }}" alt="Train">
</div>
<form action="{{ route('search') }}" method="GET" class="search-box">
    <div class="row">
        <input type="text" id="departure_date" name="departure_date" placeholder="Departure" onfocus="(this.type='date')" required>
        <input type="text" id="return_date" name="return_date" placeholder="Return" onfocus="(this.type='date')" required>
    </div>
    <div class="row">
        <input type="text" name="origin" placeholder="Origin" required>
        <input type="text" name="destination" placeholder="Destination" required>
    </div>
    <button type="submit">Search</button>
</form>
<footer class="footer">
    &copy; 2024 The Project by Muresh. All Rights Reserved.
</footer>
</body>
</html>
