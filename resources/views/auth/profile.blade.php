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
        .container {
            padding: 20px;
        }
        .profile-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .profile-box {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            width: 100%;
            max-width: 800px;
        }
        .review-form {
            margin-top: 20px;
        }
    </style>
</head>
<body>
<header class="header">
    <a href="{{ route('welcome') }}">
        <img src="{{ asset('images/logo.jpg') }}" alt="MYtrav Logo">
    </a>
    <div>
        <a href="{{ route('ktm.live_schedule') }}">Live KTM</a>
        <a href="{{ route('about') }}">About</a>
        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
            @csrf
            <button type="submit" style="background:none;border:none;color:#000;text-decoration:underline;cursor:pointer;">Logout</button>
        </form>
    </div>
</header>
<div class="container">
    <div class="profile-container">
        <div class="profile-box">
            <h2>Welcome, {{ $user->username }}</h2>
            <p>Email: {{ $user->email }}</p>
            <form method="POST" action="{{ route('updateProfile') }}">
                @csrf
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}">
                </div>
                <button type="submit" class="btn btn-primary">Update Email</button>
            </form>
            <form method="POST" action="{{ route('updateUsername') }}">
                @csrf
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" class="form-control" id="username" name="username" value="{{ $user->username }}">
                </div>
                <button type="submit" class="btn btn-primary">Update Username</button>
            </form>
            <form method="POST" action="{{ route('updatePassword') }}">
                @csrf
                <div class="form-group">
                    <label for="password">New Password:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Retype New Password:</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                </div>
                <button type="submit" class="btn btn-primary">Update Password</button>
            </form>
        </div>
        <div class="profile-box">
            <h2>Submit a Review</h2>
            <form method="POST" action="{{ route('submitReview') }}" class="review-form">
                @csrf
                <div class="form-group">
                    <label for="rating">Rating:</label>
                    <select id="rating" name="rating" class="form-control" required>
                        <option value="5">5</option>
                        <option value="4">4</option>
                        <option value="3">3</option>
                        <option value="2">2</option>
                        <option value="1">1</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="review">Review:</label>
                    <textarea id="review" name="review" class="form-control" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit Review</button>
            </form>
        </div>
        <div class="profile-box">
            <h2>Reviews</h2>
            @forelse ($reviews as $review)
                <div class="review">
                    <p><strong>{{ $review->username }}</strong>: {{ $review->review }} ({{ $review->rating }} stars)</p>
                </div>
            @empty
                <p>No reviews yet.</p>
            @endforelse
        </div>
    </div>
</div>
</body>
</html>
