<?php

namespace App\Http\Controllers;

use App\CompanySetting;
use App\SmsSetting;
use Carbon\Carbon;
use Froiden\Envato\Traits\AppBoot;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests,AppBoot;

    public function __construct() {
        $this->showInstall();
        $this->checkMigrateStatus();
        $this->global = CompanySetting::first();
        $this->smsSettings = SmsSetting::first();

        config(['app.name' => $this->global->company_name]);
        config(['app.url' => url('/')]);
        view()->share('smsSettings', $this->smsSettings);

        App::setLocale($this->global->locale);
        Carbon::setLocale($this->global->locale);
        setlocale(LC_TIME,$this->global->locale.'_'.strtoupper($this->global->locale));

        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            if ($this->user) {
                config(['froiden_envato.allow_users_id' => true]);
            }
            return $next($request);
        });
    }
    public function checkMigrateStatus()
    {
        $status = Artisan::call('migrate:check');

        if ($status && !request()->ajax()) {
            Artisan::call('migrate', array('--force' => true)); //migrate database
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');
            Artisan::call('cache:clear');
        }
    }
    
    public function getCallingCodes()
    {
        $codes = [];
        foreach(config('calling_codes.codes') as $code) {
            $codes = array_add($codes, $code['code'], array('name' => $code['name'], 'dial_code' => $code['dial_code']));
        };
        return $codes;
    }
}
