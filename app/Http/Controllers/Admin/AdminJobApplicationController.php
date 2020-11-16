<?php

namespace App\Http\Controllers\Admin;

use App\ApplicationStatus;
use App\Department;
use App\Designation;
use App\Helper\Files;
use App\Helper\Reply;
use App\Http\Requests\InterviewSchedule\StoreRequest;
use App\Http\Requests\StoreJobApplication;
use App\Http\Requests\UpdateJobApplication;
use App\InterviewSchedule;
use App\InterviewScheduleEmployee;
use App\Job;
use App\JobApplication;
use App\JobApplicationAnswer;
use App\JobLocation;
use App\JobQuestion;
use App\Notifications\CandidateScheduleInterview;
use App\Notifications\CandidateStatusChange;
use App\Notifications\ScheduleInterview;
use App\Onboard;
use App\OnboardFiles;
use App\Skill;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;


class AdminJobApplicationController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = __('menu.jobApplications');
        $this->pageIcon = 'icon-user';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        abort_if(!$this->user->can('view_job_applications'), 403);

        $date = Carbon::now();
        $startDate = $date->subDays(30)->format('Y-m-d');
        $endDate = Carbon::now()->format('Y-m-d');
        $this->jobs = Job::all();

        $this->boardColumns = ApplicationStatus::with(['applications' => function ($q) use ($startDate, $endDate, $request) {

            if ($request->startDate !== null && $request->startDate != 'null' && $request->startDate != '') {
                $q = $q->where(DB::raw('DATE(job_applications.`created_at`)'), '>=', $request->startDate);
            } else {
                $q = $q->where(DB::raw('DATE(job_applications.`created_at`)'), '>=', $startDate);
            }

            if ($request->endDate !== null && $request->endDate != 'null' && $request->endDate != '') {
                $q = $q->where(DB::raw('DATE(job_applications.`created_at`)'), '<=', $request->endDate);
            } else {
                $q = $q->where(DB::raw('DATE(job_applications.`created_at`)'), '<=', $endDate);
            }

            // Filter By jobs
            if ($request->jobs != 'all' && $request->jobs != '') {
                $q = $q->where('job_applications.job_id', $request->jobs);
            }
            // Filter by EndDate
            if ($request->search != null && $request->search != '') {
                $q = $q->where('full_name', 'LIKE', '%' . $request->search . '%');
            }

        }, 'applications.schedule'])->get();
        $boardStracture = [];
        foreach ($this->boardColumns as $key => $column) {
            $boardStracture[$column->id] = [];
            foreach ($column->applications as $application) {
                $boardStracture[$column->id][] = $application->id;
            }
        }
        $this->boardStracture = json_encode($boardStracture);
        $this->currentDate = Carbon::now()->timestamp;

        $this->startDate = $startDate;
        $this->endDate = $endDate;

        if ($request->ajax()) {
            $view = view('admin.job-applications.board-data', $this->data)->render();
            return Reply::dataOnly(['view' => $view]);
        }

        return view('admin.job-applications.board', $this->data);
    }

    public function create()
    {
        abort_if(!$this->user->can('add_job_applications'), 403);

        $this->jobs = Job::activeJobs();
        $this->gender = [
            'male' => __('modules.front.male'),
            'female' => __('modules.front.female'),
            'others' => __('modules.front.others')
        ];
        return view('admin.job-applications.create', $this->data);
    }

    /**
     * @param $jobID
     * @return mixed
     * @throws \Throwable
     */
    public function jobQuestion($jobID)
    {
        $this->job = Job::findOrFail($jobID);
        $this->jobQuestion = JobQuestion::with(['question'])->where('job_id', $jobID)->get();
        $this->gender = [
            'male' => __('modules.front.male'),
            'female' => __('modules.front.female'),
            'others' => __('modules.front.others')
        ];
        $view = view('admin.job-applications.job-question', $this->data)->render();
        $requiredColumnsView = view('admin.job-applications.required-columns', ['job' => $this->job, 'gender' => $this->gender])->render();

        $count = count($this->jobQuestion);

        return Reply::dataOnly(['status' => 'success', 'view' => $view, 'requiredColumnsView' => $requiredColumnsView,'count' => $count]);
    }


    public function edit($id)
    {
        abort_if(!$this->user->can('edit_job_applications'), 403);

        $this->statuses = ApplicationStatus::all();
        $this->application = JobApplication::find($id);
        $this->jobQuestion = JobQuestion::with(['question'])
            ->where('job_id', $this->application->job_id)->get();

        return view('admin.job-applications.edit', $this->data);
    }

    public function data(Request $request)
    {
        abort_if(!$this->user->can('view_job_applications'), 403);

        $jobApplications = JobApplication::select('job_applications.id', 'job_applications.full_name', 'job_applications.resume', 'jobs.title', 'job_locations.location', 'application_status.status')
            ->join('jobs', 'jobs.id', 'job_applications.job_id')
            ->leftjoin('job_locations', 'job_locations.id', 'jobs.location_id')
            ->leftjoin('application_status', 'application_status.id', 'job_applications.status_id');

        // Filter by status
        if ($request->status != 'all' && $request->status != '') {
            $jobApplications = $jobApplications->where('job_applications.status_id', $request->status);
        }

        // Filter By jobs
        if ($request->jobs != 'all' && $request->jobs != '') {
            $jobApplications = $jobApplications->where('job_applications.job_id', $request->jobs);
        }

        // Filter by location
        if ($request->location != 'all' && $request->location != '') {
            $jobApplications = $jobApplications->where('jobs.location_id', $request->location);
        }

        // Filter by StartDate
        if ($request->startDate != null && $request->startDate != '') {
            $jobApplications = $jobApplications->where(DB::raw('DATE(job_applications.`created_at`)'), '>=', "$request->startDate");
        }

        // Filter by EndDate
        if ($request->endDate != null && $request->endDate != '') {
            $jobApplications = $jobApplications->where(DB::raw('DATE(job_applications.`created_at`)'), '<=', "$request->endDate");
        }
        $jobApplications = $jobApplications->orderBy('column_priority', 'asc')->get();
        return DataTables::of($jobApplications)
            ->addColumn('action', function ($row) {
                $action = '';

                if ($this->user->can('edit_job_applications')) {
                    $action .= '<a href="' . route('admin.job-applications.edit', [$row->id]) . '" class="btn btn-primary btn-circle"
                      data-toggle="tooltip" data-original-title="' . __('app.edit') . '"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                }

                if ($this->user->can('delete_job_applications')) {
                    $action .= ' <a href="javascript:;" class="btn btn-danger btn-circle sa-params"
                      data-toggle="tooltip" data-row-id="' . $row->id . '" data-original-title="' . __('app.delete') . '"><i class="fa fa-times" aria-hidden="true"></i></a>';
                }
                return $action;
            })
            ->editColumn('full_name', function ($row) {
                return '<a href="javascript:;" class="show-detail" data-widget="control-sidebar" data-slide="true" data-row-id="' . $row->id . '">' . ucwords($row->full_name) . '</a>';
            })
            ->editColumn('title', function ($row) {
                return ucfirst($row->title);
            })
            ->editColumn('resume', function ($row) {
                return '<a href="' . $row->resume_url . '" target="_blank">' . __('app.view') . ' ' . __('modules.jobApplication.resume') . '</a>';
            })
            ->editColumn('location', function ($row) {
                return ucwords($row->location);
            })
            ->editColumn('status', function ($row) {
                return ucwords($row->status);
            })
            ->rawColumns(['action', 'resume', 'full_name'])
            ->addIndexColumn()
            ->make(true);
    }


    public function createSchedule(Request $request, $id)
    {
        abort_if(!$this->user->can('add_schedule'), 403);
        $this->candidates = JobApplication::all();
        $this->users = User::all();
        $this->scheduleDate = $request->date;
        $this->currentApplicant = JobApplication::findOrFail($id);
        return view('admin.job-applications.interview-create', $this->data)->render();
    }

    public function storeSchedule(StoreRequest $request)
    {
        abort_if(!$this->user->can('add_schedule'), 403);

        $dateTime = $request->scheduleDate . ' ' . $request->scheduleTime;
        $dateTime = Carbon::createFromFormat('Y-m-d H:i', $dateTime);

        // store Schedule
        $interviewSchedule = new InterviewSchedule();
        $interviewSchedule->job_application_id = $request->candidate;
        $interviewSchedule->schedule_date = $dateTime;
        $interviewSchedule->save();

        // Update Schedule Status
        $jobApplication = JobApplication::find($request->candidate);
        $jobApplication->status_id = 3;
        $jobApplication->save();

        if (!empty($request->employee)) {
            InterviewScheduleEmployee::where('interview_schedule_id', $interviewSchedule->id)->delete();
            foreach ($request->employee as $employee) {
                $scheduleEmployee = new InterviewScheduleEmployee();
                $scheduleEmployee->user_id = $employee;
                $scheduleEmployee->interview_schedule_id = $interviewSchedule->id;
                $scheduleEmployee->save();

                $user = User::find($employee);
                // Mail to employee for inform interview schedule
                Notification::send($user, new ScheduleInterview($jobApplication));
            }
        }

        // mail to candidate for inform interview schedule
        Notification::send($jobApplication, new CandidateScheduleInterview($jobApplication, $interviewSchedule));

        return Reply::redirect(route('admin.interview-schedule.index'), __('menu.interviewSchedule') . ' ' . __('messages.createdSuccessfully'));
    }


    public function store(StoreJobApplication $request)
    {
        abort_if(!$this->user->can('add_job_applications'), 403);

        $jobApplication = new JobApplication();
        $jobApplication->full_name = $request->full_name;
        $jobApplication->job_id = $request->job_id;
        $jobApplication->status_id = 1; //applied status id
        $jobApplication->email = $request->email;
        $jobApplication->phone = $request->phone;
        $jobApplication->cover_letter = $request->cover_letter;
        $jobApplication->column_priority = 0;

        if ($request->has('gender')) {
            $jobApplication->gender = $request->gender;
        }
        if ($request->has('dob')) {
            $jobApplication->dob = $request->dob;
        }
        if ($request->has('country')) {
            $countriesArray = json_decode(file_get_contents(public_path('country-state-city/countries.json')), true)['countries'];
            $statesArray = json_decode(file_get_contents(public_path('country-state-city/states.json')), true)['states'];

            $jobApplication->country = $this->getName($countriesArray, $request->country);
            $jobApplication->state = $this->getName($statesArray, $request->state);
            $jobApplication->city = $request->city;
        }

        if ($request->hasFile('resume')) {
            $jobApplication->resume = Files::upload($request->resume, 'resumes', null, null, false);
        }

        if ($request->hasFile('photo')) {
            $jobApplication->photo = Files::upload($request->photo, 'candidate-photos', null, null, false);
        }
        $jobApplication->save();

        // Job Application Answer save
        if (isset($request->answer) && !empty($request->answer)) {
            JobApplicationAnswer::where('job_application_id', $jobApplication->id)->delete();

            foreach ($request->answer as $key => $value) {
                $answer = new JobApplicationAnswer();
                $answer->job_application_id = $jobApplication->id;
                $answer->job_id = $request->job_id;
                $answer->question_id = $key;
                $answer->answer = $value;
                $answer->save();
            }
        }

        return Reply::redirect(route('admin.job-applications.index'), __('menu.jobApplications') . ' ' . __('messages.createdSuccessfully'));
    }

    public function update(UpdateJobApplication $request, $id)
    {
        abort_if(!$this->user->can('edit_job_applications'), 403);

        $jobApplication = JobApplication::find($id);
        $jobApplication->full_name = $request->full_name;
        $jobApplication->status_id = $request->status_id;
        $jobApplication->email = $request->email;
        $jobApplication->phone = $request->phone;
        $jobApplication->cover_letter = $request->cover_letter;

        if ($request->has('gender')) {
            $jobApplication->gender = $request->gender;
        }
        if ($request->has('dob')) {
            $jobApplication->dob = $request->dob;
        }
        if ($request->has('country')) {
            $countriesArray = json_decode(file_get_contents(public_path('country-state-city/countries.json')), true)['countries'];
            $statesArray = json_decode(file_get_contents(public_path('country-state-city/states.json')), true)['states'];

            $jobApplication->country = $this->getName($countriesArray, $request->country);
            $jobApplication->state = $this->getName($statesArray, $request->state);
            $jobApplication->city = $request->city;
        }

        if ($request->hasFile('resume')) {
            $jobApplication->resume = Files::upload($request->resume, 'resumes', null, null, false);
        }

        if ($request->hasFile('photo')) {
            $jobApplication->photo = Files::upload($request->photo, 'candidate-photos', null, null, false);
        }


        $jobApplication->save();

        // Job Application Answer save
        if (isset($request->answer) && count($request->answer) > 0) {
            JobApplicationAnswer::where('job_application_id', $jobApplication->id)->delete();
            foreach ($request->answer as $key => $value) {
                $answer = new JobApplicationAnswer();
                $answer->job_application_id = $jobApplication->id;
                $answer->job_id = $jobApplication->job_id;
                $answer->question_id = $key;
                $answer->answer = $value;
                $answer->save();
            }
        }

        return Reply::redirect(route('admin.job-applications.index'), __('menu.jobApplications') . ' ' . __('messages.updatedSuccessfully'));
    }

    public function destroy($id)
    {
        abort_if(!$this->user->can('delete_job_applications'), 403);

        $jobApplication = JobApplication::findOrFail($id);

        if ($jobApplication->photo) {
            Storage::delete('candidate-photos/'.$jobApplication->photo);
        }

        $jobApplication->forceDelete();

        return Reply::success(__('messages.recordDeleted'));
    }

    public function show($id)
    {
        $this->application = JobApplication::with(['schedule','notes','onboard', 'status', 'schedule.employee', 'schedule.comments.user'])->find($id);

        $this->skills = Skill::select('id', 'name')->get();

        $this->answers = JobApplicationAnswer::with(['question'])
            ->where('job_id', $this->application->job_id)
            ->where('job_application_id', $this->application->id)
            ->get();


        $view = view('admin.job-applications.show', $this->data)->render();
        return Reply::dataOnly(['status' => 'success', 'view' => $view]);
    }

    public function updateIndex(Request $request)
    {
        $taskIds = $request->applicationIds;
        $boardColumnIds = $request->boardColumnIds;
        $priorities = $request->prioritys;

        if (!is_null($taskIds)) {
            foreach ($taskIds as $key => $taskId) {
                if (!is_null($taskId)) {

                    $task = JobApplication::find($taskId);
                    $task->column_priority = $priorities[$key];
                    $task->status_id = $boardColumnIds[$key];

                    $task->save();
                }
            }

            // Send notification to candidate on update status
            if(!is_null($request->checkedArray) && in_array($boardColumnIds[0], $request->checkedArray)){
                $jobApplication = JobApplication::findOrFail($taskIds[0]);
                Notification::send($jobApplication, new CandidateStatusChange($jobApplication));
            }
        }
        $date               = Carbon::now();
        $startDate          = $date->subDays(30)->format('Y-m-d');
        $endDate            = Carbon::now()->format('Y-m-d');
        $jobIDCondition     = '';
        $jobSearchCondition = '';

        if ($request->startDate !== null && $request->startDate != 'null' && $request->startDate != '') {
            $createDateCondition =  " and DATE(job_applications.`created_at`) >= '".$request->startDate."'" ;
        } else {
            $createDateCondition =  " and DATE(job_applications.`created_at`) >= '".$startDate."'" ;
        }

        if ($request->endDate !== null && $request->endDate != 'null' && $request->endDate != '') {
            $endDateCondition =  " and DATE(job_applications.`created_at`) <= '".$request->endDate."'" ;
        } else {
            $endDateCondition =  " and DATE(job_applications.`created_at`) <= '".$endDate."'" ;
        }

        if ($request->jobs != 'all' && $request->jobs != '') {
            $jobIDCondition =  ' and job_applications.`job_id` = '.$request->jobs ;
        }
        if ($request->jobs != 'all' && $request->jobs != '') {
            $jobSearchCondition =  ' full_name LIKE %' . $request->search . '% ';
        }

        $columnCountByIds = ApplicationStatus::selectRaw('application_status.id, ( select count("id") from job_applications where application_status.id = job_applications.status_id '.$createDateCondition.' '.$endDateCondition.' '.$jobIDCondition.' '.$jobSearchCondition.') as status_count')
            ->get()->toArray();

        return Reply::dataOnly(['status' => 'success','columnCountByIds' => $columnCountByIds]);
    }

    public function table()
    {
        abort_if(!$this->user->can('view_job_applications'), 403);

        $this->boardColumns = ApplicationStatus::all();
        $this->locations = JobLocation::all();
        $this->jobs = Job::all();
        return view('admin.job-applications.index', $this->data);
    }

    public function ratingSave(Request $request, $id)
    {
        abort_if(!$this->user->can('edit_job_applications'), 403);

        $application = JobApplication::withTrashed()->findOrFail($id);
        $application->rating = $request->rating;
        $application->save();

        return Reply::success(__('messages.updatedSuccessfully'));
    }

    // Job Applications data Export
    public function export($status, $location, $startDate, $endDate, $jobs)
    {

        // Fetching All Job Applications
        $jobApplications = JobApplication::select(
            'job_applications.id',
            'jobs.title',
            'job_applications.full_name',
            'job_applications.email',
            'job_applications.phone',
            'job_applications.cover_letter',
            'application_status.status',
            'job_applications.created_at'
        )
            ->leftJoin('jobs', 'jobs.id', '=', 'job_applications.job_id')
            ->leftJoin('application_status', 'application_status.id', '=', 'job_applications.status_id');
        if ($status != 'all' && $status != '') {
            $jobApplications = $jobApplications->where('job_applications.status_id', $status);
        }

        // Filter  By Location
        if ($location != 'all' && $location != '') {
            $jobApplications = $jobApplications->where('jobs.location_id', $location);
        }

        // Filter  By Job
        if ($jobs != 'all' && $jobs != '') {
            $jobApplications = $jobApplications->where('job_applications.job_id', $jobs);
        }  // Filter  By Job
        if ($jobs != 'all' && $jobs != '') {
            $jobApplications = $jobApplications->where('job_applications.job_id', $jobs);
        }

        // Filter  By StartDate of job
        if ($startDate != null && $startDate != '' && $startDate != 0) {
            $jobApplications = $jobApplications->where(DB::raw('DATE(jobs.`start_date`)'), '>=', "$startDate");
        }

        // Filter  By EndDate of job
        if ($endDate != null && $endDate != '' && $endDate != 0) {
            $jobApplications = $jobApplications->where(DB::raw('DATE(jobs.`end_date`)'), '<=', "$endDate");
        }

        $jobApplications = $jobApplications->get();

        // Initialize the array which will be passed into the Excel
        // generator.
        $exportArray = [];

        // Define the Excel spreadsheet headers
        $exportArray[] = ['ID', 'Job Title', 'Name', 'Email', 'Mobile', 'Cover Letter', 'Status', 'Created at'];

        // Convert each member of the returned collection into an array,
        // and append it to the payments array.
        foreach ($jobApplications as $row) {
            $exportArray[] = $row->toArray();
        }

        // Generate and return the spreadsheet
        Excel::create('job-applications', function ($excel) use ($exportArray) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Job Applications');
            $excel->setCreator('Recruit')->setCompany($this->companyName);
            $excel->setDescription('job-applications file');

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function ($sheet) use ($exportArray) {
                $sheet->fromArray($exportArray, null, 'A1', false, false);

                $sheet->row(1, function ($row) {

                    // call row manipulation methods
                    $row->setFont(array(
                        'bold' => true
                    ));

                });

            });

        })->download('xlsx');
    }

    public function getName($arr, $id)
    {
        $result = array_filter($arr, function ($value) use ($id) {
            return $value['id'] == $id;
        });
        return current($result)['name'];
    }

    public function archiveJobApplication(Request $request, JobApplication $application)
    {
        abort_if(!$this->user->can('delete_job_applications'), 403);

        $application->delete();

        return Reply::success(__('messages.applicationArchivedSuccessfully'));
    }

    public function addSkills(Request $request, $applicationId)
    {
        abort_if(!$this->user->can('edit_job_applications'), 403);

        $application = JobApplication::withTrashed()->findOrFail($applicationId);

        $application->skills = $request->skills;
        $application->save();

        return Reply::success(__('messages.skillsSavedSuccessfully'));
    }
}
