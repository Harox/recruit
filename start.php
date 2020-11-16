<?php

/*
|--------------------------------------------------------------------------
| Register Namespaces And Routes
|--------------------------------------------------------------------------
|
| When a module starting, this file will executed automatically. This helps
| to register some namespaces like translator or view. Also this file
| will load the routes file for each module. You may also modify
| this file as you want.
|
*/

use App\MessageSetting;
use Illuminate\Support\Str;

if (!function_exists('user')) {

    /**
     * Return current logged in user
     */
    function user()
    {
        if (session()->has('user')) {
            return session('user');
        }

        $user = auth()->user();

        if ($user) {
            session(['user' => $user]);
            return session('user');
        }

        return null;


    }

}

if (!function_exists('asset_url')) {

    // @codingStandardsIgnoreLine
    function asset_url($path)
    {
        $path = 'user-uploads/' . $path;
        $storageUrl = $path;

        if (!Str::startsWith($storageUrl, 'http')) {
            return url($storageUrl);
        }

        return $storageUrl;

    }

}
