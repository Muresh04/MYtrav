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
        .container {
            padding: 20px;
            display: flex;
            justify-content: space-between;
        }
        .image-container {
            width: 30%;
            position: fixed;
            top: 80px;
            left: 20px;
        }
        .image-container img {
            width: 100%;
            height: auto;
        }
        .content-container {
            width: 55%;
            margin-left: 25%;
        }
        .profile-container {
            width: 20%;
        }
        .profile-box{
            padding: 20px;
            border-radius: 20px;
            box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.7);
            margin-bottom: 20px;
            width: 150%;
            margin-left: 70%;
            background-color: rgba(255, 255, 255, 0.9);
        }
        .review-box, .submit-review-box, .faq-box {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.7 );
            margin-bottom: 20px;
        }
        .review-box, .submit-review-box, .faq-box {
            margin-bottom: 30px;
        }
        .faq-item {
            margin-bottom: 15px;
        }
        .faq-item h5 {
            margin: 0;
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
    <div class="image-container">
        <img src="{{ asset('images/rev2.jpg') }}" alt="Profile Image">
    </div>
    <div class="content-container">
        <div class="review-box">
            <h2>Reviews</h2>
            @forelse ($reviews as $review)
                <div class="review">
                    <p><strong>{{ $review->username }}</strong>: {{ $review->review }} ({{ $review->rating }} stars)</p>
                </div>
            @empty
                <p>No reviews yet.</p>
            @endforelse
        </div>
        <div class="submit-review-box">
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
        <div class="faq-box">
            <h2>General Questions</h2>
            <div class="faq-item">
                <h5>How do I update my profile?</h5>
                <p>You can update your profile by filling out the form on the right and clicking the update button.</p>
            </div>
            <div class="faq-item">
                <h5>How do I submit a review?</h5>
                <p>To submit a review, fill out the review form at the bottom of the page and click the submit button.</p>
            </div>
            <div class="faq-item">
                <h5>Can I change my password?</h5>
                <p>Yes, you can change your password by filling out the password fields in the profile update section.</p>
            </div>
        </div>
    </div>
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
    </div>
</div>
</body>
</html>
