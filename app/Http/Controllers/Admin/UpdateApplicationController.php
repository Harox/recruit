<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\File;
use ZanySoft\Zip\Zip;

class UpdateApplicationController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = __('menu.updateApplication');
        $this->pageIcon = __('ti-settings');
    }

    public function index()
    {
        \config(['froiden_envato.allow_users_id' => true]);
        return view('admin.update-application.index', $this->data);
    }
}
