<?php

namespace App\Http\Controllers\Admin;

use App\FooterSetting;
use App\Helper\Reply;
use App\Http\Requests\Admin\FooterSetting\StoreRequest;
use App\Http\Requests\Admin\FooterSetting\UpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class FooterSettingController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = __('menu.footerSettings');
        $this->pageIcon = 'icon-settings';
    }

    /**
     * Display edit form of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.footer-setting.index', $this->data);
    }

    /**
     * Display edit form of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->footer = FooterSetting::all();

        return view('admin.footer-setting.create', $this->data);
    }

    /**
     * @param StoreRequest $request
     * @return array
     */
    public function store(StoreRequest $request)
    {
        $footer = new FooterSetting();

        $footer->name        = $request->name;
        $footer->slug        = Str::slug($request->name);
        $footer->description = $request->description;
        $footer->status      = $request->status;
        $footer->save();

        return Reply::redirect(route('admin.footer-settings.index'), 'messages.feature.addedSuccess');
    }


    public function data()
    {
        $footer = FooterSetting::all();

        return DataTables::of($footer)
            ->addColumn('action', function ($row) {
                $action = '';
                $action .= ' <a href="'.route('admin.footer-settings.edit', $row->id ).'" class="btn btn-info btn-circle"
                  data-toggle="tooltip" data-row-id="' . $row->id . '" data-original-title="' . __('app.edit') . '"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                $action .= ' <a href="javascript:;" class="btn btn-danger btn-circle sa-params"
                  data-toggle="tooltip" data-row-id="' . $row->id . '" data-original-title="' . __('app.delete') . '"><i class="fa fa-times" aria-hidden="true"></i></a>';

                return $action;
            })
            ->editColumn('name', function ($row) {
                return ucfirst($row->name);
            })
            ->editColumn('description', function ($row) {
                return ucwords($row->description);
            })
            ->editColumn('status', function ($row) {
                if ($row->status == 'active') {
                    return '<label class="badge bg-success">' . __('app.active') . '</label>';
                }
                if ($row->status == 'inactive') {
                    return '<label class="badge bg-danger">' . __('app.inactive') . '</label>';
                }
            })
            ->rawColumns(['status','description', 'action'])
            ->addIndexColumn()
            ->make(true);
    }

    /**
     * Display edit form of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->footer = FooterSetting::findOrFail($id);
        return view('admin.footer-setting.edit', $this->data);
    }

    /**
     * @param UpdateRequest $request
     * @param $id
     * @return array
     */
    public function update(UpdateRequest $request, $id)
    {
        $footer = FooterSetting::findOrFail($id);

        $footer->name        = $request->name;
        $footer->slug        = Str::slug($request->name);
        $footer->description = $request->description;
        $footer->status      = $request->status;
        $footer->save();

        return Reply::redirect(route('admin.footer-settings.index'), 'messages.updatedSuccessfully');
    }

    /**
     * @param Request $request
     * @param $id
     * @return array
     */
    public function destroy(Request $request, $id)
    {
        FooterSetting::destroy($id);
        return Reply::redirect(route('admin.footer-settings.index'), 'messages.feature.deletedSuccess');
    }
}
