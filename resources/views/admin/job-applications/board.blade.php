@extends('layouts.app')

@permission('add_job_applications')
@section('create-button')
    <a href="{{ route('admin.job-applications.create') }}" class="btn btn-dark btn-sm m-l-15"><i
                class="fa fa-plus-circle"></i> @lang('app.createNew')</a>
@endsection
@endpermission

@push('head-script')
    <link rel="stylesheet" href="{{ asset('assets/lobipanel/dist/css/lobipanel.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
    <link rel="stylesheet" href="{{ asset('assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/node_modules/multiselect/css/multi-select.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/iCheck/all.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/jquery-bar-rating-master/dist/themes/fontawesome-stars.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/node_modules/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}">


    <style>
        .board-column{
            /* max-width: 21%; */
        }

        .board-column .card{
            box-shadow: none;
        }
        .notify-button{
            /*width: 9em;*/
            height: 1.5em;
            font-size: 0.730rem !important;
            line-height: 0.5 !important;
        }
        .panel-scroll{
            height: calc(100vh - 330px); overflow-y: scroll
        }
        .mb-20{
            margin-bottom: 20px
        }
        .datepicker{
            z-index: 9999 !important;
        }
        /* .select2-container--default .select2-selection--single, .select2-selection .select2-selection--single {
            width: 100%;
        }
        .select2-search {
            width: 100%;
        }
        .select2.select2-container {
            width: 100% !important;
        }
        .select2-search__field {
            width: 100% !important;
            display: block;
            padding: .375rem .75rem !important;
            font-size: 1rem;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        } */
        .d-block{
            display: block;
        }
        .upcomingdata {
            height: 37.5rem;
            overflow-x: scroll;
        }
        .notify-button{
            height: 1.5em;
            font-size: 0.730rem !important;
            line-height: 0.5 !important;
        }
        .scheduleul
        {
            padding: 0 15px 0 11px;
        }
        .searchInput
        {
            width: 50%; display: inline
        }
        .searchButton
        {
            margin-bottom: 4px;margin-left: 3px;
        }

    </style>
@endpush

@section('content')

    <div class="row mb-2">
        <div class="col-sm-6">
            <a href="{{ route('admin.job-applications.table') }}" class="btn btn-sm btn-primary">@lang('app.tableView') <i class="fa fa-table"></i></a>
        </div>
        <div class="col-sm-6">
            <div class="form-group pull-right">
                <input type="text" id="search" name="search" class="form-control searchInput">
                <button type="button" id="applySearch" class="btn btn-sm searchButton btn-success "><i class="fa fa-search"></i> </button>
            </div>
        </div>

    </div>

    <div class="container-scroll">
        <div class="row">
            
            <div class="col-md-4">
                <div class="input-daterange input-group">
                    <input type="text" class="form-control" id="date-start" value="{{ $startDate }}" name="start_date">
                    <span class="input-group-addon bg-info b-0 text-white p-1">@lang('app.to')</span>
                    <input type="text" class="form-control" id="date-end" name="end_date" value="{{ $endDate }}">
                </div>
            </div>
            <div class="col-md-4">
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
            <div class="col-md-3">
                <div class="form-group">
                    <button type="button" id="apply-filters" class="btn btn-success btn-sm col-md-6"><i class="fa fa-check"></i> @lang('app.apply')</button>
                    <button type="button" id="reset-filters" class="btn btn-info btn-sm col-md-5 col-md-offset-1"><i class="fa fa-refresh"></i> @lang('app.reset')</button>
                </div>
            </div>
        </div>
        <div class="row">
            <label style="margin-left: 10px">Send mail if candidate move to </label>
            @forelse($boardColumns as $boardColumn)
                <div class="form-group" style="margin-left: 20px">
                    <label class="">
                        <div class="icheckbox_flat-green" aria-checked="false" aria-disabled="false" style="position: relative; margin-right: 5px">
                            <input type="checkbox" checked value="{{$boardColumn->id}}" name="checkBoardColumn[]" class="flat-red columnCheck"  style="position: absolute; opacity: 0;">
                            <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
                        </div>
                        {{ __('modules.jobApplicationStatus.'.camel_case($boardColumn->status)) }}
                    </label>
                </div>
            @empty
            @endforelse
        </div>
        <div class="row container-row">

        </div>
    </div>
    {{--Ajax Modal Start for--}}
    <div class="modal fade bs-modal-md in" id="scheduleDetailModal" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <span class="caption-subject font-red-sunglo bold uppercase" id="modelHeading"></span>
                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn blue">Save changes</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    {{--Ajax Modal Ends--}}
@endsection

@push('footer-script')
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
    <script src="{{ asset('assets/lobipanel/dist/js/lobipanel.min.js') }}"></script>
    <script src="{{ asset('assets/node_modules/moment/moment.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/node_modules/multiselect/js/jquery.multi-select.js') }}"></script>
    <script src="{{ asset('assets/plugins/iCheck/icheck.min.js') }}"></script>
    <script src="{{ asset('assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/node_modules/select2/dist/js/select2.full.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/node_modules/bootstrap-select/bootstrap-select.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/jquery-bar-rating-master/dist/jquery.barrating.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/node_modules/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}" type="text/javascript"></script>

    <script>
        //Flat red color scheme for iCheck
        $('input[type="checkbox"].flat-red').iCheck({
            checkboxClass: 'icheckbox_flat-blue',
        })


        $(".select2").select2();
        loadData();

        $('#apply-filters').click(function () {
            loadData();
        });

        $('#reset-filters').click(function () {
            $('#date-start').val('{{ $startDate }}');
            $('#date-end').val('{{ $endDate }}');
            $('#jobs').val('all').trigger('change');

            loadData();
        })
        $('#applySearch').click(function () {
            var search = $('#search').val();
            if(search !== undefined && search !== null && search !== ""){
                loadData();
            }
        })

        $('#date-end').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
        $('#date-start').bootstrapMaterialDatePicker({ weekStart : 0, time: false }).on('change', function(e, date)
        {
            $('#date-end').bootstrapMaterialDatePicker('setMinDate', date);
        });
        {{--@endif--}}
        // Schedule create modal view
        function createSchedule (id) {
            var url = "{{ route('admin.job-applications.create-schedule',':id') }}";
            url = url.replace(':id', id);
            $('#modelHeading').html('Schedule');
            $.ajaxModal('#scheduleDetailModal', url);
        }



        function loadData () {
            var startDate = $('#date-start').val();
            var jobs = $('#jobs').val();
            var search = $('#search').val();

            if (startDate == '') {
                startDate = null;
            }

            var endDate = $('#date-end').val();

            if (endDate == '') {
                endDate = null;
            }

            var url = '{{route('admin.job-applications.index')}}?startDate=' + startDate + '&endDate=' + endDate + '&jobs=' + jobs + '&search=' + search;

            $.easyAjax({
                url: url,
                container: '.container-row',
                type: "GET",
                success: function (response) {
                    $('.container-row').html(response.view);
                    $("body").tooltip({
                        selector: '[data-toggle="tooltip"]'
                    });
                }

            })
        }
    </script>
    <script>

        // job-applications.create-schedule
    </script>
@endpush