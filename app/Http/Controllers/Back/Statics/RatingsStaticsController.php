<?php

namespace App\Http\Controllers\Back\Statics;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Http\Request;

class RatingsStaticsController extends Controller
{
    public function index()
    {

        $ratings = Rating::paginate(25);

        foreach ($ratings as $rating) {
            $rating->userData = User::find($rating->uid);

        }

        // dd($ratings[0]->User);
        return view(
            'admin.statics.ratings.index',
            [
                'ratings' => $ratings,
            ]
        );
    }
}
