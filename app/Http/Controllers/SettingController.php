<?php

namespace App\Http\Controllers;

use App\PaymentGateway;
use App\Setting;
use DotenvEditor;
use Illuminate\Contracts\Cache\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Image;

class SettingController extends Controller
{
    public function getSettings()
    {
        $settings = Setting::whereNotIn('key', ['stripeSecretKey', 'payslackPrivateKey', 'twilioSid', 'twilioAccessToken', 'twilioServiceId', 'razorpayKeySecret', 'deliveryAcceptTimeThreshold', 'restaurantAcceptTimeThreshold', 'enDevMode', 'deliveryGuyCommissionFrom'])->get(['key', 'value']);
        return response()->json($settings);
    }

    /**
     * @param Request $request
     */
    public function settings(Request $request)
    {
        $paymentGateways = PaymentGateway::all();
        $activePaymentGateways = PaymentGateway::where('is_active', '1')->get();

        /*Version Info */
        $versionFile = File::get(base_path('version.txt'));
        if ($versionFile) {
            $versionMsg = 'Core - v' . $versionFile;
        } else {
            $versionMsg = null;
        }

        return view('admin.settings', array(
            'paymentGateways' => $paymentGateways,
            'activePaymentGateways' => $activePaymentGateways,
            'versionMsg' => $versionMsg,
        ));
    }

    /**
     * @param Request $request
     * @param Factory $cache
     */
    public function saveSettings(Request $request, Factory $cache)
    {
        // dd($request->all());
        $allSettings = $request->except(['logo', 'favicon', 'splashLogo', 'seoOgImage', 'seoTwitterImage', 'firstScreenHeroImage', 'showPromoSlider', 'showMap', 'enablePushNotification', 'enablePushNotificationOrders', 'showGdpr', 'enableGoogleAnalytics', 'taxApplicable', 'enSOV', 'enableGoogleAPI', 'enableDeliveryPin', 'timezone', 'enDevMode', 'hidePriceWhenZero', 'multiLanguageSelection', 'enableDeliveryGuyEarning']);
        // dd($allSettings);
        foreach ($allSettings as $key => $value) {
            $setting = Setting::where('key', $key)->first();
            if ($setting != null) {
                $setting->value = $value;
                $setting->save();
            }
        }

        if ($request->hasFile('favicon')) {
            $setting = Setting::where('key', 'favicon-16x16')->first();
            $image = $request->file('favicon');
            $filename = 'favicon-16x16.' . strtolower($image->getClientOriginalExtension());
            Image::make($image)->resize(16, 16)->save(base_path('/assets/img/favicons/' . $filename));
            $setting->value = $filename . '?v=' . time() . str_random(5);
            $setting->save();

            $setting = Setting::where('key', 'favicon-32x32')->first();
            $image = $request->file('favicon');
            $filename = 'favicon-32x32.' . strtolower($image->getClientOriginalExtension());
            Image::make($image)->resize(32, 32)->save(base_path('/assets/img/favicons/' . $filename));
            $setting->value = $filename . '?v=' . time() . str_random(5);
            $setting->save();

            $setting = Setting::where('key', 'favicon-96x96')->first();
            $image = $request->file('favicon');
            $filename = 'favicon-96x96.' . strtolower($image->getClientOriginalExtension());
            Image::make($image)->resize(96, 96)->save(base_path('/assets/img/favicons/' . $filename));
            $setting->value = $filename . '?v=' . time() . str_random(5);
            $setting->save();

            $setting = Setting::where('key', 'favicon-128x128')->first();
            $image = $request->file('favicon');
            $filename = 'favicon-128x128.' . strtolower($image->getClientOriginalExtension());
            Image::make($image)->resize(128, 128)->save(base_path('/assets/img/favicons/' . $filename));
            $setting->value = $filename . '?v=' . time() . str_random(5);
            $setting->save();

            /* For PWA Manifest*/
            $image = $request->file('favicon');
            $filename = 'favicon-36x36.' . strtolower($image->getClientOriginalExtension());
            Image::make($image)->resize(36, 36)->save(base_path('/assets/img/favicons/' . $filename));

            $image = $request->file('favicon');
            $filename = 'favicon-48x48.' . strtolower($image->getClientOriginalExtension());
            Image::make($image)->resize(48, 48)->save(base_path('/assets/img/favicons/' . $filename));

            $image = $request->file('favicon');
            $filename = 'favicon-144x144.' . strtolower($image->getClientOriginalExtension());
            Image::make($image)->resize(144, 144)->save(base_path('/assets/img/favicons/' . $filename));

            $image = $request->file('favicon');
            $filename = 'favicon-192x192.' . strtolower($image->getClientOriginalExtension());
            Image::make($image)->resize(192, 192)->save(base_path('/assets/img/favicons/' . $filename));

            $image = $request->file('favicon');
            $filename = 'favicon-512x512.' . strtolower($image->getClientOriginalExtension());
            Image::make($image)->resize(512, 512)->save(base_path('/assets/img/favicons/' . $filename));

        }

        if ($request->hasFile('logo')) {
            $setting = Setting::where('key', 'storeLogo')->first();
            $image = $request->file('logo');
            $filename = 'logo.' . strtolower($image->getClientOriginalExtension());
            $smallFile = 'logo-sm.' . strtolower($image->getClientOriginalExtension());
            Image::make($image)->resize(320, 89)->save(base_path('/assets/img/logos/' . $filename));
            Image::make($image)->resize(120, 33)->save(base_path('/assets/img/logos/' . $smallFile));
            $setting->value = $filename . '?v=' . time() . str_random(5);
            $setting->save();
        }

        if ($request->hasFile('splashLogo')) {
            $setting = Setting::where('key', 'splashLogo')->first();
            $image = $request->file('splashLogo');
            $filename = 'splash.jpg';
            Image::make($image)->resize(480, 853)->encode('jpg', 65)->save(base_path('/assets/img/splash/' . $filename));
            $setting->value = $filename . '?v=' . time() . str_random(5);
            $setting->save();
        }

        if ($request->hasFile('seoOgImage')) {
            $setting = Setting::where('key', 'seoOgImage')->first();
            $image = $request->file('seoOgImage');
            $filename = 'ogimage.png';
            Image::make($image)->resize(1200, 630)->encode('png', 65)->save(base_path('/assets/img/social/' . $filename));
            $setting->value = $filename . '?v=' . time() . str_random(5);
            $setting->save();
        }

        if ($request->hasFile('seoTwitterImage')) {
            $setting = Setting::where('key', 'seoTwitterImage')->first();
            $image = $request->file('seoTwitterImage');
            $filename = 'twitterimage.png';
            Image::make($image)->resize(600, 335)->encode('png', 65)->save(base_path('/assets/img/social/' . $filename));
            $setting->value = $filename . '?v=' . time() . str_random(5);
            $setting->save();
        }

        if ($request->hasFile('firstScreenHeroImage')) {
            $setting = Setting::where('key', 'firstScreenHeroImage')->first();
            $image = $request->file('firstScreenHeroImage');
            $random = str_random(10);
            $filename = time() . $random . '.' . strtolower($image->getClientOriginalExtension());
            $filenameSm = time() . $random . '-sm.' . strtolower($image->getClientOriginalExtension());
            Image::make($image)->resize(592, 640)->save(base_path('/assets/img/various/' . $filename));
            Image::make($image)->resize(75, 81)->save(base_path('/assets/img/various/' . $filenameSm));
            $setting->value = 'assets/img/various/' . $filename . '?v=' . time() . str_random(5);
            $setting->save();
            $setting = Setting::where('key', 'firstScreenHeroImageSm')->first();
            $setting->value = 'assets/img/various/' . $filenameSm . '?v=' . time() . str_random(5);
            $setting->save();
        }

        $setting = Setting::where('key', 'showMap')->first();
        if ($request->showMap == 'true') {
            $setting->value = 'true';
            $setting->save();
        } else {
            $setting->value = 'false';
            $setting->save();
        }

        $setting = Setting::where('key', 'showPromoSlider')->first();
        if ($request->showPromoSlider == 'true') {
            $setting->value = 'true';
            $setting->save();
        } else {
            $setting->value = 'false';
            $setting->save();
        }

        $setting = Setting::where('key', 'enablePushNotification')->first();
        if ($request->enablePushNotification == 'true') {
            $setting->value = 'true';
            $setting->save();
        } else {
            $setting->value = 'false';
            $setting->save();
        }

        $setting = Setting::where('key', 'enablePushNotificationOrders')->first();
        if ($request->enablePushNotificationOrders == 'true') {
            $setting->value = 'true';
            $setting->save();
        } else {
            $setting->value = 'false';
            $setting->save();
        }

        $setting = Setting::where('key', 'showGdpr')->first();
        if ($request->showGdpr == 'true') {
            $setting->value = 'true';
            $setting->save();
        } else {
            $setting->value = 'false';
            $setting->save();
        }

        $setting = Setting::where('key', 'enableGoogleAnalytics')->first();
        if ($request->enableGoogleAnalytics == 'true') {
            $setting->value = 'true';
            $setting->save();
        } else {
            $setting->value = 'false';
            $setting->save();
        }

        $setting = Setting::where('key', 'taxApplicable')->first();
        if ($request->taxApplicable == 'true') {
            $setting->value = 'true';
            $setting->save();
        } else {
            $setting->value = 'false';
            $setting->save();
        }

        $setting = Setting::where('key', 'enableFacebookLogin')->first();
        if ($request->enableFacebookLogin == 'true') {
            $setting->value = 'true';
            $setting->save();
        } else {
            $setting->value = 'false';
            $setting->save();
        }

        $setting = Setting::where('key', 'enableGoogleLogin')->first();
        if ($request->enableGoogleLogin == 'true') {
            $setting->value = 'true';
            $setting->save();
        } else {
            $setting->value = 'false';
            $setting->save();
        }

        $setting = Setting::where('key', 'enSOV')->first();
        if ($request->enSOV == 'true') {
            $setting->value = 'true';
            $setting->save();
        } else {
            $setting->value = 'false';
            $setting->save();
        }

        $setting = Setting::where('key', 'enSPU')->first();
        if ($request->enSPU == 'true') {
            $setting->value = 'true';
            $setting->save();
        } else {
            $setting->value = 'false';
            $setting->save();
        }

        $setting = Setting::where('key', 'enableGoogleAPI')->first();
        if ($request->enableGoogleAPI == 'true') {
            $setting->value = 'true';
            $setting->save();
        } else {
            $setting->value = 'false';
            $setting->save();
        }

        $setting = Setting::where('key', 'enableDeliveryPin')->first();
        if ($request->enableDeliveryPin == 'true') {
            $setting->value = 'true';
            $setting->save();
        } else {
            $setting->value = 'false';
            $setting->save();
        }

        if ($request->enDevMode == 'true') {
            $env = DotenvEditor::load();
            $env->setKey('APP_ENV', 'local');
            $env->setKey('APP_DEBUG', 'true');
            $env->save();
            $setting = Setting::where('key', 'enDevMode')->first();
            $setting->value = 'true';
            $setting->save();
        } else {
            $env = DotenvEditor::load();
            $env->setKey('APP_ENV', 'production');
            $env->setKey('APP_DEBUG', 'false');
            $env->save();
            $setting = Setting::where('key', 'enDevMode')->first();
            $setting->value = 'false';
            $setting->save();
        }

        $setting = Setting::where('key', 'hidePriceWhenZero')->first();
        if ($request->hidePriceWhenZero == 'true') {
            $setting->value = 'true';
            $setting->save();
        } else {
            $setting->value = 'false';
            $setting->save();
        }

        $setting = Setting::where('key', 'multiLanguageSelection')->first();
        if ($request->multiLanguageSelection == 'true') {
            $setting->value = 'true';
            $setting->save();
        } else {
            $setting->value = 'false';
            $setting->save();
        }

        $setting = Setting::where('key', 'enableDeliveryGuyEarning')->first();
        if ($request->enableDeliveryGuyEarning == 'true') {
            $setting->value = 'true';
            $setting->save();
        } else {
            $setting->value = 'false';
            $setting->save();
        }

        $env = DotenvEditor::load();
        $env->setKey('APP_TIMEZONE', $request->timezone);
        $env->setKey('GOOGLE_MAPS_GEOCODING_API_KEY', $request->googleApiKeyIP);
        $env->save();

        $cache->forget('settings'); //reset cache
        return redirect()->back()->with(['success' => 'Operation Successful']);
    }
}
