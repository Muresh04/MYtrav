<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
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
        .profile-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .profile-box {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
<header class="header">
    <a href="{{ route('welcome') }}">
        <img src="{{ asset('images/logo.jpg') }}" alt="MYtrav Logo">
    </a>
    <div>
        <a href="{{ route('ktm.live_schedule') }}">Live Schedule</a>
        <a href="{{ route('about') }}">About</a>
        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
            @csrf
            <button type="submit" style="background:none;border:none;color:#000;text-decoration:underline;cursor:pointer;">Logout</button>
        </form>
    </div>
</header>
<div class="profile-container">
    <div class="profile-box">
        <h2>Welcome, {{ Auth::user()->username }}</h2>
        <p>Email: {{ Auth::user()->email }}</p>
        <!-- Add more profile details as needed -->
    </div>
</div>
</body>
</html>

