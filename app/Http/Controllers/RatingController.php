<?php

namespace App\Http\Controllers;

use App\AcceptDelivery;
use App\Order;
use App\Restaurant;
use App\User;
use DB;
use Illuminate\Http\Request;

class RatingController extends Controller
{

    /**
     * @param Request $request
     */
    public function getRatableOrder(Request $request)
    {
        //check if order exists
        $order = Order::where('id', $request->order_id)->with('restaurant', 'orderitems')->first();

        if ($order) {
            //check if order belongs to the auth user
            if ($order->user->id == $request->user_id) {
                //check if order already rated,
                $rating = DB::table('ratings')->where('order_id', $order->id)->get();

                if ($rating->isEmpty()) {
                    //empty rating, that means not rated earlier
                    $response = [
                        'success' => true,
                        'order' => $order,
                    ];
                    return response()->json($response);
                } else {
                    $response = [
                        'success' => false,
                        'message' => 'Already rated',
                    ];
                    return response()->json($response);
                }
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Order doesnt belongs to user',
                ];
                return response()->json($response);
            }
        }

        $response = [
            'success' => false,
            'message' => 'No order found',
        ];
        return response()->json($response);
    }

    /**
     * @param Request $request
     */
    public function saveNewRating(Request $request)
    {
        $user = User::where('id', $request->user_id)->first();

        if ($user) {
            //find the restaurant
            $order = Order::where('id', $request->order_id)->first();
            if ($order) {

                //rating the restaurant
                $restaurant = Restaurant::where('id', $order->restaurant_id)->first();
                $rating = new \willvincent\Rateable\Rating;
                $rating->rating = $request->restaurant_rating;
                $rating->comment = $request->comment;
                $rating->user_id = $user->id;
                $rating->order_id = $order->id;
                $restaurant->ratings()->save($rating);

                //rating the delivery guy
                $deliveryGuy = AcceptDelivery::where('order_id', $order->id)->first();
                $deliveryGuy = User::where('id', $deliveryGuy->user_id)->first();
                $rating = new \willvincent\Rateable\Rating;
                $rating->rating = $request->delivery_rating;
                $rating->comment = $request->comment;
                $rating->user_id = $user->id;
                $rating->order_id = $order->id;
                $deliveryGuy->ratings()->save($rating);

                $response = [
                    'success' => true,
                ];
                return response()->json($response);
            } else {
                $response = [
                    'success' => false,
                    'message' => 'No order found',
                ];
                return response()->json($response);
            }
        }
        $response = [
            'success' => false,
            'message' => 'No user found',
        ];
        return response()->json($response);
    }

    public function adminRatings()
    {
        return 'hello';
    }

    public function newRating()
    {
        return 'asdsadsadasd';
    }
}
