@extends('layouts.app')

@push('head-script')
    <link rel="stylesheet" href="//cdn.datatables.net/fixedheader/3.1.5/css/fixedHeader.bootstrap.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">    <link rel="stylesheet" href="{{ asset('assets/node_modules/html5-editor/bootstrap-wysihtml5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/node_modules/multiselect/css/multi-select.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/iCheck/all.css') }}">

    <style>
        .mb-20{
            margin-bottom: 20px
        }
        .datepicker{
            z-index: 9999 !important;
        }
        .select2-container--default .select2-selection--single, .select2-selection .select2-selection--single {
            width: 250px;
        }
    </style>
@endpush

@permission('add_job_applications')
@section('create-button')
    <a href="{{ route('admin.job-applications.create') }}" class="btn btn-dark btn-sm m-l-15"><i class="fa fa-plus-circle"></i> @lang('app.createNew')</a>
@endsection
@endpermission

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row clearfix">
                        <div class="col-md-12 mb-20">
                            <a href="javascript:;" id="toggle-filter" class="btn btn-outline btn-success btn-sm toggle-filter"><i
                                        class="fa fa-sliders"></i> @lang('app.filterResults')</a>
                            <a href="{{ route('admin.job-applications.index') }}" class="btn btn-outline btn-primary btn-sm">
                                <i class="fa fa-columns"></i> @lang('modules.jobApplication.boardView')
                            </a>
                            <a class="pull-right" onclick="exportJobApplication()" ><button class="btn btn-sm btn-primary" type="button">
                                    <i class="fa fa-upload"></i>  @lang('menu.export')
                                </button></a>
                        </div>
                    </div>
                    <div class="row b-b b-t" style="display: none; background: #fbfbfb;" id="ticket-filters">
                        <div class="col-md-12">
                            <h4>@lang('app.filterBy') <a href="javascript:;" class="pull-right toggle-filter"><i class="fa fa-times-circle-o"></i></a></h4>
                        </div>
                        <div class="col-md-12">
                            <form action="" id="filter-form" class="row" >
                                <div class="col-md-5">
                                    <div class="example">
                                        <div class="input-daterange input-group" id="date-range">
                                            <input type="text" class="form-control" id="start-date" placeholder="Data de Inicio" value="" />
                                            <span class="input-group-addon bg-info b-0 text-white p-1">@lang('app.to')</span>
                                            <input type="text" class="form-control" id="end-date" placeholder="Data de Fim" value="" />
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <select class="select2" name="status" id="status" data-style="form-control">
                                            <option value="all">@lang('modules.jobApplication.allStatus')</option>
                                            @forelse($boardColumns as $status)
                                                <option value="{{$status->id}}">{{ucfirst($status->status)}}</option>
                                            @empty
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <select class="select2" name="jobs" id="jobs" data-style="form-control">
                                            <option value="all">@lang('modules.jobApplication.allJobs')</option>
                                            @forelse($jobs as $job)
                                                <option title="{{ucfirst($job->title)}}" value="{{$job->id}}">{{ucfirst($job->title)}}</option>
                                            @empty
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <select class="select2" name="location" id="location" data-style="form-control">
                                            <option value="all">@lang('modules.jobApplication.allLocation')</option>
                                            @forelse($locations as $location)
                                                <option value="{{$location->id}}">{{ucfirst($location->location)}}</option>
                                            @empty
                                            @endforelse

                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group float-right">
                                        <label class="control-label col-xs-12">&nbsp;</label>
                                        <button type="button" id="apply-filters" class="btn btn-sm btn-success col-md-6"><i class="fa fa-check"></i> @lang('app.apply')</button>
                                        <button type="button" id="reset-filters" class="btn btn-sm btn-dark col-md-5 col-md-offset-1"><i class="fa fa-refresh"></i> @lang('app.reset')</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                     <div class="table-responsive m-t-40">
                        <table id="myTable" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('modules.jobApplication.applicantName')</th>
                                <th>@lang('modules.jobApplication.resume')</th>
                                <th>@lang('menu.jobs')</th>
                                <th>@lang('menu.locations')</th>
                                <th>@lang('app.status')</th>
                                <th>@lang('app.action')</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('footer-script')
    <script src="{{ asset('assets/node_modules/select2/dist/js/select2.full.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/node_modules/bootstrap-select/bootstrap-select.min.js') }}" type="text/javascript"></script>
    <script src="//cdn.datatables.net/fixedheader/3.1.5/js/dataTables.fixedHeader.min.js"></script>
    <script src="//cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
    <script src="//cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>
    <script src="{{ asset('assets/node_modules/moment/moment.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/node_modules/multiselect/js/jquery.multi-select.js') }}"></script>
    <script src="{{ asset('assets/plugins/iCheck/icheck.min.js') }}"></script>
    <script src="{{ asset('assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>

    <script>

        $('#start-date').datepicker({
            format: 'yyyy-mm-dd'
        })
        $('#end-date').datepicker({
            format: 'yyyy-mm-dd'
        })

        var table;
        tableLoad('load');
        // For select 2
        $(".select2").select2();
        $('#reset-filters').click(function () {
            $('#filter-form')[0].reset();
            $('#filter-form').find('select').select2('render');
            tableLoad('load');
        })
        $('#apply-filters').click(function () {
            tableLoad('filter');
        });
        function tableLoad(type) {

            var status = $('#status').val();
            var jobs = $('#jobs').val();
            var location = $('#location').val();
            var   startDate = $('#start-date').val();
            var   endDate = $('#end-date').val();

            table = $('#myTable').dataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                destroy: true,
                stateSave: true,
                ajax: '{!! route('admin.job-applications.data') !!}?status='+status+'&location='+location+'&startDate='+startDate+'&endDate='+endDate+'&jobs='+jobs,
                language: languageOptions(),
                "fnDrawCallback": function (oSettings) {
                    $("body").tooltip({
                        selector: '[data-toggle="tooltip"]'
                    });
                },
                columns: [
                    {data: 'id', name:'id'},
                    {data: 'full_name', name: 'full_name', width: '17%'},
                    {data: 'resume', name: 'resume'},
                    {data: 'title', name: 'jobs.title', width: '17%'},
                    {data: 'location', name: 'job_locations.location'},
                    {data: 'status', name: 'application_status.status'},
                    {data: 'action', name: 'action', width: '15%', searchable : false}
                ]
            });
            new $.fn.dataTable.FixedHeader(table);
        }

        $('body').on('click', '.sa-params', function(){
            var id = $(this).data('row-id');
            swal({
                title: "@lang('errors.areYouSure')",
                text: "@lang('errors.deleteWarning')",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "@lang('app.delete')",
                cancelButtonText: "@lang('app.cancel')",
                closeOnConfirm: true,
                closeOnCancel: true
            }, function(isConfirm){
                if (isConfirm) {

                    var url = "{{ route('admin.job-applications.destroy',':id') }}";
                    url = url.replace(':id', id);

                    var token = "{{ csrf_token() }}";

                    $.easyAjax({
                        type: 'POST',
                        url: url,
                        data: {'_token': token, '_method': 'DELETE'},
                        success: function (response) {
                            if (response.status == "success") {
                                $.unblockUI();
//                                    swal("Deleted!", response.message, "success");
                                table._fnDraw();
                            }
                        }
                    });
                }
            });
        });

        table.on('click', '.show-detail', function () {
            $(".right-sidebar").slideDown(50).addClass("shw-rside");

            var id = $(this).data('row-id');
            var url = "{{ route('admin.job-applications.show',':id') }}";
            url = url.replace(':id', id);

            $.easyAjax({
                type: 'GET',
                url: url,
                success: function (response) {
                    if (response.status == "success") {
                        $('#right-sidebar-content').html(response.view);
                    }
                }
            });
        });

        $('.toggle-filter').click(function () {
            $('#ticket-filters').toggle('slide');
        });

        function exportJobApplication(){
            var startDate;
            var endDate;
            var status = $('#status').val();
            var jobs = $('#jobs').val();
            var location = $('#location').val();

             startDate = $('#start-date').val();
             endDate = $('#end-date').val();

            if(startDate == '' || startDate == null){
                startDate = 0;
            }

            if(endDate == '' || endDate == null){
                endDate = 0;
            }

            var url = '{{ route('admin.job-applications.export', [':status',':location', ':startDate', ':endDate', ':jobs']) }}';
            url = url.replace(':status', status);
            url = url.replace(':location', location);
            url = url.replace(':startDate', startDate);
            url = url.replace(':endDate', endDate);
            url = url.replace(':jobs', jobs);

            window.location.href = url;
        }

    </script>
@endpush