@foreach($boardColumns as $key=>$column)
    <div class="board-column p-0" data-column-id="{{ $column->id }}">
        <div class="card" style="margin-bottom:0 !important;">
            <div class="card-body">
                <h4 class="card-title pt-1 pb-1">{{ __('modules.jobApplicationStatus.'.camel_case($column->status)) }} <span
                            class="badge badge-pill badge-primary text-white ml-auto"
                            id="columnCount_{{$column->id}}"> {{ count($column->applications) }}</span></h4>
                <div class="card-text">
                    <div class="panel-body ">
                        <div class="row">
                            <div class="custom-column panel-scroll">
                                @foreach($column->applications as $application)
                                    <div class="panel panel-default lobipanel show-detail "
                                         data-widget="control-sidebar" data-slide="true"
                                         data-row-id="{{ $application->id }}"
                                         data-application-id="{{ $application->id }}" data-sortable="true">
                                        <div class="panel-body ">
                                            <h5>
                                                <img src="{{$application->photo_url}}" alt="user" class="img-circle"
                                                     width="25">
                                                {{ ucwords($application->full_name) }}</h5>
                                            <div class="stars stars-example-fontawesome">
                                                <select id="example-fontawesome_{{$application->id}}"
                                                        data-value="{{ $application->rating }}"
                                                        data-id="{{ $application->id }}"
                                                        class="example-fontawesome bar-rating" name="rating"
                                                        autocomplete="off">
                                                    <option value=""></option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                </select>
                                            </div>
                                            <h6 class="text-muted">{{ ucwords($application->job->title) }}</h6>
                                            <div class="pt-2 pb-2 mt-3">
                                                            <span class="text-dark font-14">
                                                                @if(!is_null($application->schedule)  && $column->id == 3)
                                                                    {{ $application->schedule->schedule_date->format('d M, Y') }}
                                                                @else
                                                                    {{ $application->created_at->format('d M, Y') }}
                                                                @endif
                                                            </span>
                                                @permission('add_schedule')
                                                <span id="buttonBox{{ $column->id }}{{$application->id}}"
                                                      data-timestamp="@if(!is_null($application->schedule)){{$application->schedule->schedule_date->timestamp}}@endif">

                                                                    @if(!is_null($application->schedule) && $column->id == 3 && $currentDate < $application->schedule->schedule_date->timestamp)
                                                        <button onclick="sendReminder({{$application->id}}, 'reminder')"
                                                                type="button"
                                                                class="btn btn-sm btn-info notify-button">@lang('app.reminder')</button>@endif
                                                    @if($column->id == 4)
                                                        <button onclick="sendReminder({{$application->id}}, 'notify')"
                                                                type="button"
                                                                class="btn btn-sm btn-info notify-button">@lang('app.notify')</button>
                                                    @endif
                                                                </span>
                                                @endpermission
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="panel panel-default lobipanel" data-sortable="true"></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endforeach

<script>
    $('.example-fontawesome').barrating({
        theme: 'fontawesome-stars',
        showSelectedRating: false,
        readonly: true,

    });

    $(function () {
        $('.bar-rating').each(function () {
            const val = $(this).data('value');

            $(this).barrating('set', val ? val : '');
        });
    });

    {{--@if($application->rating !== null)--}}
    $('.example-fontawesome').barrating('set', '');

    // Send Reminder and notification to Candidate
    function sendReminder(id, type) {
        var msg;

        if (type == 'notify') {
            msg = "@lang('errors.sendHiredNotification')";
        } else {
            msg = "@lang('errors.sendInterviewReminder')";
        }
        swal({
            title: "@lang('errors.areYouSure')",
            text: msg,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "@lang('app.yes')",
            cancelButtonText: "@lang('app.cancel')",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {

                var url = "{{ route('admin.interview-schedule.notify',[':id',':type']) }}";
                url = url.replace(':id', id);
                url = url.replace(':type', type);

                // update values for all tasks
                $.easyAjax({
                    url: url,
                    type: 'GET',
                    success: function (response) {
                    }
                });
            }
        });
    }

    $(function () {
        // Getting Data of all colomn and applications
        boardStracture = JSON.parse('{!! $boardStracture !!}');

        var oldParentId, oldElementIds = [], i = 1;
        $('.lobipanel').on('dragged.lobiPanel', function (e, a) {
            var $parent = $(this).parent(),
                $children = $parent.children();
            var pr = $(this).closest('.board-column'),
                c = pr.find('.custom-column');

//            if (i++ % 2) {

                oldParentId = pr.data('column-id');
                $children.each(function (ind, el) {
                    oldElementIds.push($(el).data('application-id'));
                });
//                return true;
//
//            }

            var currentParentId = pr.data('column-id');
            var currentElementIds = [];
            $children.each(function (ind, el) {
                currentElementIds.push($(el).data('application-id'));
            });

            var oldOriginalIds = boardStracture[oldParentId];

            var range = oldOriginalIds.length;
            var missingElementId;
            for (var j = 0; j < range; j++) {
                if (oldOriginalIds[j] !== oldElementIds[j]) {
                    missingElementId = oldOriginalIds[j];
                    break;
                }
            }

            boardStracture[oldParentId] = oldElementIds.slice(0, -1);
            boardStracture[currentParentId] = currentElementIds.slice(0, -1);
            var boardColumnIds = [];
            var applicationIds = [];
            var prioritys = [];

            $children.each(function (ind, el) {
                boardColumnIds.push($(el).closest('.board-column').data('column-id'));
                applicationIds.push($(el).data('application-id'));
                prioritys.push($(el).index());
            });

            if (oldParentId == 3 && currentParentId == 4) {
                $('#buttonBox' + oldParentId + missingElementId).show();
                var button = '<button onclick="sendReminder(' + missingElementId + ', \'notify\')" type="button" class="btn btn-sm btn-info notify-button">@lang('app.notify')</button>';
                $('#buttonBox' + oldParentId + missingElementId).html(button);
                $('#buttonBox' + oldParentId + missingElementId).attr('id', 'buttonBox' + currentParentId + missingElementId);

            } else if (oldParentId == 4 && currentParentId == 3) {
                var timeStamp = $('#buttonBox' + oldParentId + missingElementId).data('timestamp');
                var currentDate = {{$currentDate}};
                if (currentDate < timeStamp) {
                    $('#buttonBox' + oldParentId + missingElementId).show();
                    var button = '<button onclick="sendReminder(' + missingElementId + ', \'reminder\')" type="button" class="btn btn-sm btn-info notify-button">@lang('app.reminder')</button>';
                    $('#buttonBox' + oldParentId + missingElementId).html(button);
                    $('#buttonBox' + oldParentId + missingElementId).attr('id', 'buttonBox' + currentParentId + missingElementId);
                }
            } else {
                $('#buttonBox' + oldParentId + missingElementId).attr('id', 'buttonBox' + currentParentId + missingElementId);
                $('#buttonBox' + currentParentId + missingElementId).hide();
            }

            var oldVal = parseInt($('#columnCount_' + oldParentId).html());
            $('#columnCount_' + oldParentId).html((oldVal - 1));

            var newVal = parseInt($('#columnCount_' + currentParentId).html());
            $('#columnCount_' + currentParentId).html((newVal + 1));

            var chkArray = [];

            /* look for all checkboes that have a class 'chk' attached to it and check if it was checked */
            $(".columnCheck:checked").each(function() {
                chkArray.push($(this).val());
            });
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
            // update values for all tasks
            $.easyAjax({
                url: '{{ route("admin.job-applications.updateIndex") }}',
                type: 'POST',
                data: {
                    boardColumnIds: boardColumnIds,
                    applicationIds: applicationIds,
                    prioritys: prioritys,
                    startDate: startDate,
                    jobs: jobs,
                    search: search,
                    endDate: endDate,
                    checkedArray: chkArray,
                    '_token': '{{ csrf_token() }}'
                },
                success: function (response) {
                    boardColumnIds = [];
                    applicationIds = [];
                    prioritys = [];
                    $.each( response.columnCountByIds, function( key, value ) {
                        $('#columnCount_' + value.id).html((value.status_count));
                    });
                }
            });
            oldParentId = null;
            oldElementIds = [];
            currentParentId = null;
            currentElementIds = [];


        }).lobiPanel({
            sortable: true,
            reload: false,
            editTitle: false,
            close: false,
            minimize: false,
            unpin: false,
            expand: false

        });

    });

    $('body').on('click', '.show-detail', function (event) {
        if ($(event.target).hasClass('notify-button')) {
            return false;
        }
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
    })
</script>