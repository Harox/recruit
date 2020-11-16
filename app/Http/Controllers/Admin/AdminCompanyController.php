<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Files;
use App\Helper\Reply;
use App\Company;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\Company\StoreCompany;
use App\Job;

class AdminCompanyController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = __('menu.companies');
        $this->pageIcon = 'icon-film';
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        abort_if(!$this->user->can('view_company'), 403);

        $this->totalCompanies = Company::count();
        $this->activeCompanies = Company::where('status', 'active')->count();
        $this->inactiveCompanies = Company::where('status', 'inactive')->count();
        return view('admin.company.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(!$this->user->can('add_company'), 403);
        return view('admin.company.create', $this->data);
    }

    /**
     * @param StoreCompany $request
     * @return array
     * @throws \Exception
     */
    public function store(StoreCompany $request)
    {
        abort_if(!$this->user->can('add_company'), 403);
        $data = $request->all();

        if ($request->hasFile('logo')) {
            $data['logo'] = Files::upload($request->logo, 'company-logo');
        }else{
            unset($data['logo']);
        }

        Company::create($data);

        return Reply::redirect(route('admin.company.index'), __('menu.companies') . ' ' . __('messages.createdSuccessfully'));
    }

    public function edit($id)
    {
        abort_if(!$this->user->can('edit_company'), 403);

        $this->company = Company::find($id);
        return view('admin.company.edit', $this->data);
    }

    /**
     * @param StoreCompany $request
     * @param $id
     * @return array
     * @throws \Exception
     */
    public function update(StoreCompany $request, $id)
    {
        abort_if(!$this->user->can('edit_company'), 403);

        $data =  $request->all();

        $setting = Company::findOrFail($id);

        if ($request->hasFile('logo')) {
            $data['logo'] = Files::upload($request->logo, 'company-logo');
        }else{
            unset($data['logo']);
        }

        $setting->update($data);

        //update company's jobs status
        if($setting->status != $request->input('status')) {
            Job::where('company_id', $id)->update(['status' => $request->status]);
        }

        return Reply::redirect(route('admin.company.index'), __('menu.companies') . ' ' . __('messages.updatedSuccessfully'));
    }

    /**
     * @param $id
     * @return array
     */
    public function destroy($id)
    {
        abort_if(!$this->user->can('delete_company'), 403);

        Company::destroy($id);
        return Reply::success(__('messages.recordDeleted'));
    }

    public function data()
    {
        abort_if(!$this->user->can('view_company'), 403);

        $categories = Company::all();

        return DataTables::of($categories)
            ->addColumn('action', function ($row) {
                $action = '';

                if ($this->user->can('edit_company')) {
                    $action .= '<a href="' . route('admin.company.edit', [$row->id]) . '" class="btn btn-primary btn-circle"
                      data-toggle="tooltip" data-original-title="' . __('app.edit') . '"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                }

                if ($this->user->can('delete_company')) {
                    $action .= ' <a href="javascript:;" class="btn btn-danger btn-circle sa-params"
                      data-toggle="tooltip" data-row-id="' . $row->id . '" data-original-title="' . __('app.delete') . '"><i class="fa fa-times" aria-hidden="true"></i></a>';
                }
                return $action;
            })
            ->editColumn('status', function ($row) {
                if($row->status == 'active'){
                    return '<label class="badge bg-success">'.__('app.active').'</label>';
                }
                if($row->status == 'inactive'){
                    return '<label class="badge bg-danger">'.__('app.inactive').'</label>';
                }
             })
            ->editColumn('logo', function ($row) {
                return '<img src="' . $row->logo_url . '" class="img-responsive" height="25" />';
            })
            ->editColumn('company_name', function ($row) {
                return ucfirst($row->company_name);
            })
            ->addIndexColumn()
            ->rawColumns(['logo', 'action', 'status'])
            ->make(true);
    }
}
