<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RateReview;
use App\Models\User;

class RateReviewController extends Controller
{
    public function averageRateReview(Request $request)
    {
        $productId = request()->input('product_id');

        // $userCounts = User::get()->count();
        $userCounts = RateReview::where('product_id',$productId)->pluck('user_id')->count();
        $userReviewCounts = RateReview::where('product_id',$productId)->pluck('rating');

        $reviewCounts = 0;

        foreach($userReviewCounts as $reviews)
        {
            $reviewCounts += $reviews;

        }

        $Useraverage = $reviewCounts/$userCounts;

        // Return a JSON response
        return response()->json([
            'message' => 'Review get data',
            'Useraverage' => $Useraverage
        ], 201);
    }

    public function getRateReview(Request $request)
    {
        $productId = request()->input('product_id');
        $userId = request()->input('user_id');

        $data_exist = RateReview::where('product_id', $productId)->where('user_id', $userId)->first();

        // Return a JSON response
        return response()->json([
            'message' => 'Review get data',
            'data' => $data_exist
        ], 201);
    }


    public function rateReview(Request $request)
    {
        $userId = request()->input('user_id');
        $productId = request()->input('product_id');
        $rating = request()->input('rating');
        $review = request()->input('review');

        $data_exist = RateReview::where('product_id', $productId)->where('user_id', $userId)->first();
        if($data_exist)
        {
            RateReview::where('product_id', $productId)->where('user_id', $userId)->update([
                'rating' => $rating,
                'review' => $review,
            ]);

            // Return a JSON response
            return response()->json([
                'message' => 'Review updated successfully',
                'rateReview' => $data_exist
            ], 201);
        }
        else{
            $data = [
                'user_id' => $userId,
                'product_id' => $productId,
                'rating' => $rating,
                'review' => $review,
            ];
            $rateReview = RateReview::create($data);

            // Return a JSON response
            return response()->json([
                'message' => 'Review created successfully',
                'rateReview' => $rateReview
            ], 201);
        }
    }
}
