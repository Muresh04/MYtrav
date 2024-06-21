<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
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
            display: flex;
            justify-content: space-between;
            padding: 20px;
        }
        .contact-info, .map {
            width: 48%;
        }
        .contact-info h2, .map h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        .contact-info .info, .social-media {
            margin-bottom: 20px;
        }
        .social-media a {
            display: block;
            margin-bottom: 10px;
            color: #000;
            text-decoration: none;
        }
        .map iframe {
            justify-content: space-between;
            width: 75%;
            height: 600px;
            border: 0 ;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 4px;
            text-align: center;
            position: fixed;
            width: 100%;
            bottom: 0;
        }
        .bottom-left-image {
            position: fixed;
            bottom: 5%;
            left: 4%;
            width: 40%;
            height: 15%;
        }
        .right-image {
            position: absolute;
            right: 20px;
            top: 120px;
            width: 20%;
            height: 90%
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
    </div>
</header>
<div class="container">
    <div class="contact-info">
        <h2>Contact us for more Details</h2>
        <div class="info">
            <h4>Email</h4>
            <p>MYtrav@gmail.com</p>
        </div>
        <div class="info">
            <h4>Contact No</h4>
            <p>+6011-121 7794</p>
            <p>+6014-453 3130</p>
        </div>
        <div class="info">
            <h4>Head Office</h4>
            <p>No.1(GF), Jalan Prima SGI, 68100, Taman Prima Sri Gombak, Gombak,Majlis Perbandaran Selayang,Selangor</p>
        </div>
        <div class="info">
            <div class="row mt-4">
                <!-- Social Media Links -->
                <div class="col-md-12">
                    <h2>Find us on Social Media</h2>
                    <a href="https://twitter.com/mytrav"><i class="fab fa-twitter"></i> Twitter</a><br>
                    <a href="https://facebook.com/mytrav"><i class="fab fa-facebook-f"></i> Facebook</a><br>
                    <a href="https://youtube.com/mytrav"><i class="fab fa-youtube"></i> YouTube</a><br>
                    <a href="https://instagram.com/mytrav"><i class="fab fa-instagram"></i> Instagram</a>
                </div>
            </div>
        </div>
    </div>
    <div class="map">
        <h2>Our Location</h2>
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15955.49110505009!2d101.69320794999999!3d3.1408532999999985!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31cc37d06e8f882d%3A0xf54fda54c5f0f40b!2sKuala%20Lumpur!5e0!3m2!1sen!2smy!4v1627356128319!5m2!1sen!2smy" allowfullscreen="" loading="lazy"></iframe>
    </div>
</div>
<img src="{{ asset('images/wehearyou.jpg') }}" alt="We Hear You" class="bottom-left-image">
<img src="{{ asset('images/cs2.jpg') }}" alt="Office Image" class="right-image">
<footer class="footer">
    &copy; 2024 The Project by Muresh. All Rights Reserved.
</footer>
</body>
</html>
