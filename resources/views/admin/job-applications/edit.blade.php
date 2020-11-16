@extends('layouts.app')

@push('head-script')
    <link rel="stylesheet" href="{{ asset('assets/plugins/datepicker/datepicker3.css') }}">
@endpush

@section('content')
    @php
        $gender = [
            'male' => __('modules.front.male'),
            'female' => __('modules.front.female'),
            'others' => __('modules.front.others')
        ];
    @endphp
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">@lang('app.edit')</h4>

                    <form class="ajax-form" method="POST" id="createForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="job_id" value="{{ $application->job->id }}">

                        <div class="row">
                            <div class="col-md-4 pl-4 pr-4 pb-4  pb-4 b-b">
                                <h5>@lang('modules.front.personalInformation')</h5>
                            </div>


                            <div class="col-md-8 pb-4 b-b">

                                <div class="form-group">
                                    <input class="form-control" type="text" value="{{ $application->full_name }}" name="full_name" placeholder="@lang('app.name')">
                                </div>

                                <div class="form-group">
                                    <input class="form-control" type="email" name="email" value="{{ $application->email }}"
                                           placeholder="@lang('app.email')">
                                </div>

                                <div class="form-group">
                                    <input class="form-control" type="tel" name="phone" value="{{ $application->phone }}"
                                           placeholder="@lang('app.phone')">
                                </div>

                                @if ($application->job->required_columns['gender'])
                                    <label class="control-label">@lang('modules.front.gender')</label>
                                    <div class="form-group">
                                        <div class="form-inline">
                                            @foreach ($gender as $key => $value)
                                                <div class="form-check form-check-inline">
                                                    <input @if ($key == $application->gender) checked @endif class="form-check-input" type="radio" name="gender" id="{{ $key }}" value="{{ $key }}">
                                                    <label class="form-check-label" for="{{ $key }}">{{ ucFirst($value) }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                                @if ($application->job->required_columns['dob'])
                                    <div class="form-group">
                                        <input class="form-control form-control-lg dob" type="text" name="dob"
                                            placeholder="@lang('modules.front.dob')" autocomplete="none">
                                    </div>
                                @endif
                                @if ($application->job->required_columns['country'])
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <select class="select2 countries" name="country" id="countryId">
                                                    <option value="0">@lang('modules.front.selectCountry')</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <select class="select2 states" name="state" id="stateId">
                                                    <option value="0">@lang('modules.front.selectState')</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input class="form-control" type="text" name="city" id="cityId" placeholder="@lang('modules.front.selectCity')" value="{{ $application->city }}">
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="form-group">
                                    <h6>@lang('modules.front.photo')</h6>
                                    <input class="select-file" accept=".png,.jpg,.jpeg" type="file" name="photo"><br>
                                    <span class="help-block">@lang('modules.front.photoFileType')</span>
                                </div>

                                @if(!is_null($application->photo))
                                <p>
                                    <a target="_blank" href="{{ asset('user-uploads/candidate-photos/'.$application->photo) }}" class="btn btn-sm btn-primary">@lang('app.view')</a>
                                </p>
                                @endif


                            </div>

                            <div class="col-md-4 pl-4 pr-4 pb-4 pt-4 b-b">
                                <h5>@lang('modules.front.resume')</h5>
                            </div>


                            <div class="col-md-8 pb-4 pt-4 b-b">

                                <div class="form-group">
                                    <input class="select-file" type="file" name="resume"><br>
                                </div>

                                <p>
                                    <a target="_blank" href="{{ asset('user-uploads/resumes/'.$application->resume) }}" class="btn btn-sm btn-primary">@lang('app.view')</a>
                                </p>


                            </div>

                            <div class="col-md-4  pl-4 pr-4 pb-4 pt-4 pt-4 b-b">
                                <h5>@lang('modules.front.coverLetter')</h5>
                            </div>


                            <div class="col-md-8 pb-4 pt-4 b-b">

                                <div class="form-group">
                                    <textarea class="form-control" name="cover_letter" rows="4">{{ $application->cover_letter }}</textarea>
                                </div>
                            </div>
                            @if(count($jobQuestion) > 0)
                                <div class="col-md-4 pl-4 pr-4 pt-4 b-b" id="questionBoxTitle">
                                    <h5>@lang('modules.front.additionalDetails')</h5>
                                </div>


                                <div class="col-md-8 pt-4 b-b" id="questionBox">
                                    @forelse($jobQuestion as $question)
                                        <div class="form-group">
                                            <input class="form-control" type="text" id="answer[{{ $question->question->id}}]" value="{{ $question->getAnswerByQuestion($application->id) }}" name="answer[{{ $question->question->id}}]" placeholder="{{ $question->question->question }} ?">
                                        </div>
                                    @empty
                                    @endforelse
                                </div>
                            @endif
                            <div class="col-md-4 pl-4 pr-4 pt-4">
                                <h5>@lang('app.status')</h5>
                            </div>

                            <div class="col-md-8 pt-4">

                                <div class="form-group">
                                    <select name="status_id" id="status_id" class="form-control">
                                        @foreach($statuses as $status)
                                            <option
                                                    @if($application->status_id == $status->id) selected @endif
                                                    value="{{ $status->id }}">{{ ucwords($status->status) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>


                        <button type="button" id="save-form" class="btn btn-success"><i
                                    class="fa fa-check"></i> @lang('app.save')</button>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('footer-script')
    <script src="{{ asset('assets/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('assets/node_modules/select2/dist/js/select2.full.min.js') }}"></script>
    <script>
        const fetchCountryState = "{{ route('jobs.fetchCountryState') }}";
        const csrfToken = "{{ csrf_token() }}";
        const selectCountry = "@lang('modules.front.selectCountry')";
        const selectState = "@lang('modules.front.selectState')";
        const selectCity = "@lang('modules.front.selectCity')";
        const pleaseWait = "@lang('modules.front.pleaseWait')";

        let country = "{{ $application->country }}";
        let state = "{{ $application->state }}";
    </script>
    <script src="{{ asset('front/assets/js/location.js') }}"></script>
    <script>
        var datepicker = $('.dob').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd',
            endDate: (new Date()).toDateString(),
        });

        @if ($application->dob)
            datepicker.datepicker('setDate', new Date('{{ $application->dob }}'))
        @endif
        
        $('.select2').select2({
            width: '100%'
        });

        $('#save-form').click(function () {

            $.easyAjax({
                url: '{{route('admin.job-applications.update', $application->id)}}',
                container: '#createForm',
                type: "POST",
                redirect: true,
                file:true,
                error: function (response) {
                    handleFails(response);
                }
            })
        });

        function handleFails(response) {

            if (typeof response.responseJSON.errors != "undefined") {
                var keys = Object.keys(response.responseJSON.errors);
                $('#createForm').find(".has-error").find(".help-block").remove();
                $('#createForm').find(".has-error").removeClass("has-error");

                for (var i = 0; i < keys.length; i++) {
                    // Escape dot that comes with error in array fields
                    var key = keys[i].replace(".", '\\.');
                    var formarray = keys[i];

                    // If the response has form array
                    if(formarray.indexOf('.') >0){
                        var array = formarray.split('.');
                        response.responseJSON.errors[keys[i]] = response.responseJSON.errors[keys[i]];
                        key = array[0]+'['+array[1]+']';
                    }

                    var ele = $('#createForm').find("[name='" + key + "']");

                    var grp = ele.closest(".form-group");
                    $(grp).find(".help-block").remove();

                    //check if wysihtml5 editor exist
                    var wys = $(grp).find(".wysihtml5-toolbar").length;

                    if(wys > 0){
                        var helpBlockContainer = $(grp);
                    }
                    else{
                        var helpBlockContainer = $(grp).find("div:first");
                    }
                    if($(ele).is(':radio')){
                        helpBlockContainer = $(grp);
                    }

                    if (helpBlockContainer.length == 0) {
                        helpBlockContainer = $(grp);
                    }

                    helpBlockContainer.append('<div class="help-block">' + response.responseJSON.errors[keys[i]] + '</div>');
                    $(grp).addClass("has-error");
                }

                if (keys.length > 0) {
                    var element = $("[name='" + keys[0] + "']");
                    if (element.length > 0) {
                        $("html, body").animate({scrollTop: element.offset().top - 150}, 200);
                    }
                }
            }
        }
    </script>
@endpush