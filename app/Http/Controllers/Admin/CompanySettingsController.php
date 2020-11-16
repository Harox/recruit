<?php

namespace App\Http\Controllers\Admin;

use App\CompanySetting;
use App\Helper\Files;
use App\Helper\Reply;
use App\Http\Requests\Company\UpdateCompany;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CompanySettingsController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = __('menu.companySettings');
        $this->pageIcon = 'icon-settings';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(!$this->user->can('manage_settings'), 403);

        $this->timezones = \DateTimeZone::listIdentifiers(\DateTimeZone::ALL);
        $setting = CompanySetting::first();

        if (!$setting) {
            abort(404);
        }

        return view('admin.settings.index', $this->data);
    }


    public function update(UpdateCompany $request, $id)
    {
        $data = $request->all();
        $data['system_update'] = $request->has('system_update') && $request->input('system_update') == 'on' ? 1 : 0;

        $setting = CompanySetting::find($id);

        if ($request->hasFile('logo')) {
            $data['logo'] = Files::upload($request->logo, 'app-logo');
        }

        $setting->update($data);


        return Reply::redirect(route('admin.settings.index'));
    }

}
