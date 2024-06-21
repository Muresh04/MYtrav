<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;

class ReviewController extends Controller
{
    public function submitReview(Request $request)
    {
        $review = new Review();
        $review->user_id = Auth::id();
        $review->review = $request->input('review');
        $review->rating = $request->input('rating');
        $review->save();

        return redirect()->route('profile')->with('success', 'Review submitted successfully');
    }

    public function showReviews()
    {
        $reviews = Review::with('user')->get();
        return view('auth.profile', compact('reviews'));
    }
}
