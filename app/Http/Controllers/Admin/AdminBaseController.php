<?php

namespace App\Http\Controllers\Admin;

use App\CompanySetting;
use App\EmailNotificationSetting;
use App\LanguageSetting;
use App\Notification;
use App\ProjectActivity;
use App\Setting;
use App\StickyNote;
use App\Traits\FileSystemSettingTrait;
use App\UniversalSearch;
use App\UserActivity;
use App\UserChat;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\LinkedInSetting;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use SebastianBergmann\CodeCoverage\Report\Xml\Project;
use App\ThemeSetting;

class AdminBaseController extends Controller
{
    /**
     * @var array
     */
    public $data = [];

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->data[$name];
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->data[ $name ]);
    }

    /**
     * UserBaseController constructor.
     */
    public function __construct()
    {
        // Inject currently logged in user object into every view of user dashboard
        parent::__construct();

        $this->global = CompanySetting::first();
//        $this->emailSetting = EmailNotificationSetting::all();
        $this->companyName = $this->global->company_name;

        $this->adminTheme = ThemeSetting::first();
        $this->languageSettings = LanguageSetting::where('status', 'enabled')->orderBy('language_name')->get();

        App::setLocale($this->global->locale);
        Carbon::setLocale($this->global->locale);
        setlocale(LC_TIME,$this->global->locale.'_'.strtoupper($this->global->locale));

        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            $this->todoItems = $this->user->todoItems()->groupBy('status', 'position')->get();
            $this->linkedinGlobal = LinkedInSetting::first();
            $this->stickyNotes = StickyNote::where('user_id', $this->user->id)
                ->orderBy('updated_at', 'desc')
                ->get();

            view()->share('languages', $this->languageSettings);
            view()->share('global', $this->global);

            return $next($request);
        });
    }

    public function generateTodoView()
    {
        $pendingTodos = $this->user->todoItems()->status('pending')->orderBy('position', 'DESC')->limit(5)->get();
        $completedTodos = $this->user->todoItems()->status('completed')->orderBy('position', 'DESC')->limit(5)->get();

        $view = view('sections.todo_items_list', compact('pendingTodos', 'completedTodos'))->render();

        return $view;
    }
}
