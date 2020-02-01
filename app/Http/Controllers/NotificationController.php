<?php

namespace App\Http\Controllers;

use App\PushToken;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Image;
use Ixudra\Curl\Facades\Curl;

class NotificationController extends Controller
{
    /**
     * @param Request $request
     */
    public function saveToken(Request $request)
    {
        $pushToken = PushToken::where('user_id', $request->user_id)->first();

        if ($pushToken) {
            //update the existing token
            $pushToken->token = $request->push_token;
            $pushToken->save();
        } else {
            //create new token for user
            $pushToken = new PushToken();
            $pushToken->token = $request->push_token;
            $pushToken->user_id = $request->user_id;
            $pushToken->save();
        }
        $success = $request->push_token;
        return response()->json($success);
    }
    /**
     * @param Request $request
     */
    public function saveRestaurantOwnerNotificationToken(Request $request)
    {
        $pushToken = PushToken::where('user_id', $request->user_id)->first();

        if ($pushToken) {
            //update the existing token
            $pushToken->token = $request->push_token;
            $pushToken->save();
        } else {
            //create new token for user
            $pushToken = new PushToken();
            $pushToken->token = $request->push_token;
            $pushToken->user_id = $request->user_id;
            $pushToken->save();
        }
        $success = $request->push_token;
        return response()->json($success);
    }

    public function notifications()
    {
        $pushTokens = PushToken::all();
        $count = count($pushTokens);
        return view('admin.notifications', array(
            'count' => $count,
        ));
    }

    /**
     * @param Request $request
     */
    public function sendNotifiaction(Request $request)
    {
        $secretKey = 'key=' . config('settings.firebaseSecret');

        $data = $request->except(['_token']);

        $data = json_encode($data);

        $data = substr($data, 0, -1);

        $pushTokens = PushToken::where('is_active', '1')->get(['token'])->pluck('token')->toArray();

        $i = 0;
        $len = count($pushTokens);
        $last = $len - 1;
        $tokens = ', "registration_ids": [';

        foreach ($pushTokens as $key => $value) {
            if ($i == $last) {
                $tokens .= '"' . $value . '"]}';
            } else {
                $tokens .= '"' . $value . '",';
            }
            $i++;
        }

        $fullData = $data . $tokens;
        $response = Curl::to('https://fcm.googleapis.com/fcm/send')
            ->withHeader('Content-Type: application/json')
            ->withHeader("Authorization: $secretKey")
            ->withData($fullData)
            ->post();

        $response = json_decode($response);
        if ($response) {
            return redirect()->back()->with(['success' => 'Success: ' . $response->success . ' & Failed: ' . $response->failure]);
        }
    }

    /**
     * @param Request $request
     */
    public function uploadNotificationImage(Request $request)
    {
        if ($request->hasFile('file')) {
            $image = $request->file('file');
            $filename = time() . '-' . str_random(10) . '.' . $image->getClientOriginalExtension();
            Image::make($request->file)->resize(1600, 1100)->save(base_path('/assets/img/various/' . $filename));
            return response()->json(['success' => $filename]);
        }
    }

}
