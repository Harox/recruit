<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary">
    <!-- Brand Logo -->
    <a href="{{ route('admin.dashboard') }}" class="brand-link">
        <img src="{{ $global->logo_url }}"
             alt="AdminLTE Logo"
             class="brand-image img-fluid">
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" id="sidebarnav" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->is('admin/dashboard*') ? 'active' : '' }}">
                        <i class="nav-icon icon-speedometer"></i>
                        <p>
                            @lang('menu.dashboard')
                        </p>
                    </a>
                </li>

                @permission('view_job_applications')
                <li class="nav-item">
                    <a href="{{ route('admin.job-applications.table') }}" class="nav-link {{ request()->is('admin/job-applications*') ? 'active' : '' }}">
                        <i class="nav-icon icon-user"></i>
                        <p>
                            @lang('menu.jobApplications')
                        </p>
                    </a>
                </li>
                {{--  <li class="nav-item">
                    <a href="{{ route('admin.job-onboard.index') }}" class="nav-link {{ request()->is('admin/job-onboard*') ? 'active' : '' }}">
                        <i class="nav-icon icon-user"></i>
                        <p>
                            @lang('app.job') @lang('app.onBoard')
                        </p>
                    </a>
                </li>  --}}
                @endpermission

                @permission('view_jobs')
                <li class="nav-item">
                    <a href="{{ route('admin.jobs.index') }}" class="nav-link {{ request()->is('admin/jobs*') ? 'active' : '' }}">
                        <i class="nav-icon icon-badge"></i>
                        <p>
                            @lang('menu.jobs')
                        </p>
                    </a>
                </li>
                @endpermission

                @permission('view_category')
                <li class="nav-item">
                    <a href="{{ route('admin.job-categories.index') }}" class="nav-link {{ request()->is('admin/job-categories*') ? 'active' : '' }}">
                        <i class="nav-icon icon-grid"></i>
                        <p>
                            @lang('menu.jobCategories')
                        </p>
                    </a>
                </li>
                @endpermission

                @permission('view_skills')
                <li class="nav-item">
                    <a href="{{ route('admin.skills.index') }}" class="nav-link {{ request()->is('admin/skills*') ? 'active' : '' }}">
                        <i class="nav-icon icon-game-controller"></i>
                        <p>
                            @lang('menu.skills')
                        </p>
                    </a>
                </li>
                @endpermission

                @permission('view_locations')
                <li class="nav-item">
                    <a href="{{ route('admin.locations.index') }}" class="nav-link {{ request()->is('admin/locations*') ? 'active' : '' }}">
                        <i class="nav-icon icon-location-pin"></i>
                        <p>
                            @lang('menu.locations')
                        </p>
                    </a>
                </li>
                @endpermission

                @permission('view_job_applications')
                <li class="nav-item">
                    <a href="{{ route('admin.applications-archive.index') }}" class="nav-link {{ request()->is('admin/applications-archive*') ? 'active' : '' }}">
                        <i class="nav-icon icon-drawer"></i>
                        <p>
                            @lang('menu.candidateDatabase')
                        </p>
                    </a>
                </li>
                @endpermission

                @permission('view_company')
                <li class="nav-item">
                    <a href="{{ route('admin.company.index') }}" class="nav-link {{ request()->is('admin/company*') ? 'active' : '' }}">
                        <i class="nav-icon icon-film"></i>
                        <p>
                            @lang('menu.companies')
                        </p>
                    </a>
                </li>
                @endpermission

                

                

                

                {{--  @permission('view_schedule')
                <li class="nav-item">
                    <a href="{{ route('admin.interview-schedule.index') }}" class="nav-link {{ request()->is('admin/interview-schedule*') ? 'active' : '' }}">
                        <i class="nav-icon icon-calendar"></i>
                        <p>
                            @lang('menu.interviewSchedule')
                        </p>
                    </a>
                </li>
                @endpermission  --}}

                @permission('view_team')
                <li class="nav-item">
                    <a href="{{ route('admin.team.index') }}" class="nav-link {{ request()->is('admin/team*') ? 'active' : '' }}">
                        <i class="nav-icon icon-people"></i>
                        <p>
                            @lang('menu.team')
                        </p>
                    </a>
                </li>
                @endpermission

                @if ($user->roles->count() > 0)
                    <li class="nav-item">
                        <a href="{{ route('admin.todo-items.index') }}" class="nav-link {{ request()->is('admin/todo-items*') ? 'active' : '' }}">
                            <i class="nav-icon icon-notebook"></i>
                            <p>
                                @lang('menu.todoList')
                            </p>
                        </a>
                    </li>
                @endif

                <li class="nav-item has-treeview @if(\Request()->is('admin/settings/*') || \Request()->is('admin/profile'))active menu-open @endif">
                    <a href="#" class="nav-link">
                        <i class="nav-icon icon-settings"></i>
                        <p>
                            @lang('menu.settings')
                            <i class="right fa fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.profile.index') }}" class="nav-link {{ request()->is('admin/profile*') ? 'active' : '' }}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p> @lang('menu.myProfile')</p>
                            </a>
                        </li>
                        @permission('manage_settings')
                        <li class="nav-item">
                            <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->is('admin/settings/settings') ? 'active' : '' }}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>@lang('menu.businessSettings')</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.role-permission.index') }}" class="nav-link {{ request()->is('admin/settings/role-permission') ? 'active' : '' }}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>@lang('menu.rolesPermission')</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.language-settings.index') }}" class="nav-link {{ request()->is('admin/settings/language-settings') ? 'active' : '' }}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>@lang('menu.languageConfig')</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.footer-settings.index') }}" class="nav-link {{ request()->is('admin/settings/footer-settings') ? 'active' : '' }}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>@lang('menu.footerSettings')</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.theme-settings.index') }}" class="nav-link {{ request()->is('admin/settings/theme-settings') ? 'active' : '' }}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>@lang('menu.themeSettings')</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.smtp-settings.index') }}" class="nav-link {{ request()->is('admin/settings/smtp-settings') ? 'active' : '' }}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>@lang('menu.mailSetting')</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.sms-settings.index') }}" class="nav-link {{ request()->is('admin/settings/sms-settings') ? 'active' : '' }}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>@lang('menu.smsSettings')</p>
                            </a>
                        </li>
                        {{-- <li class="nav-item">
                            <a href="{{ route('admin.linkedin-settings.index') }}" class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>@lang('menu.linkedInSettings')</p>
                            </a>
                        </li>
                        @if($global->system_update == 1)
                        <li class="nav-item">
                            <a href="{{ route('admin.update-application.index') }}" class="nav-link {{ request()->is('admin/settings/update-application') ? 'active' : '' }}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>@lang('menu.updateApplication')</p>
                            </a>
                        </li>
                        @endif --}}
                        <li class="nav-item">
                            <a href="https://www.google.com" class="nav-link" target="_blank">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>@lang('menu.help')</p>
                            </a>
                        </li>
                        @endpermission

                    </ul>
                </li>

                <li class="nav-header">DIVERSOS</li>
                <li class="nav-item">
                    <a href="{{ url('/') }}" target="_blank" class="nav-link">
                        <i class="nav-icon fa fa-external-link"></i>
                        <p>Visitar o Website</p>
                    </a>
                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>