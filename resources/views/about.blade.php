@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <!-- MYtrav branding and links -->
            <nav class="navbar navbar-expand-lg navbar-light bg-transparent">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#">MYtrav</a>
                    <div class="collapse navbar-collapse">
                        <ul class="navbar-nav ml-auto">
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">Sign in</a>
                                </li>
                            @endif
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">Sign up</a>
                                </li>
                            @endif
                            <li class="nav-item">
                                <a class="nav-link" href="#">Live</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('about') }}">About</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Profile</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
<div class="container mt-4">
    <div class="row">
        <!-- Contact Information -->
        <div class="col-md-6">
            <h2>Contact us for more Details</h2>
            <p><strong>Email:</strong> MYtrav@gmail.com</p>
            <p><strong>Contact No:</strong> +6011-xxxx-xxxx</p>
            <p><strong>Head Office:</strong></p>
            <p>No.1, Jalan xxxx-xxxxx, xxxxxxx, xxxxx, xxxxx.</p>
            <p>XXXXXX</p>
            <p>xxxxxxxxxxxxxxx</p>
        </div>

        <!-- Location Map -->
        <div class="col-md-6">
            <h2>Our Location</h2>
            <!-- Embed Google Maps -->
            <div class="map-responsive">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1994.1234567891011!2d102.1234567891011!3d3.1234567891011!2m3!1f0!2f0!3f0!3m2!1i1024!1i768!4f13.1!3m3!1m2!1s0x0000000000000000%3A0x0000000000000000!2sNameOfPlace!5e0!3m2!1sen!2smy!4v1234567891011" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
    </div>

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
@endsection
