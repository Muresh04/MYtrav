<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Review;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $reviews = Review::latest()->get(); // Fetch all reviews
        return view('auth.profile', compact('user', 'reviews'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $user->update($request->only('email'));
        return back()->with('success', 'Profile updated successfully.');
    }

    public function updateUsername(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . Auth::id(),
        ]);

        $user = Auth::user();
        $user->username = $request->input('username');
        $user->save();

        return back()->with('success', 'Username updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->input('password'));
        $user->save();

        return back()->with('success', 'Password updated successfully.');
    }

    public function submitReview(Request $request)
    {
        $user = Auth::user();
        Review::create([
            'username' => $user->username,
            'review' => $request->input('review'),
            'rating' => $request->input('rating')
        ]);

        return back()->with('success', 'Review submitted successfully.');
    }
}
