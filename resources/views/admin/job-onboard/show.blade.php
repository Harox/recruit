@extends('layouts.app')
@push('head-script')
<style>

</style>
@endpush
@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4"> @lang('app.job') @lang('app.onBoard')
                        @if($onboard->hired_status == 'accepted')
                             <label class="badge bg-success">@lang('app.accepted')</label>
                        @elseif($onboard->hired_status == 'offered')
                             <label class="badge bg-warning">@lang('app.offered')</label>
                        @elseif($onboard->hired_status == 'canceled')
                             <label class="badge bg-danger">@lang('app.canceled')</label>
                        @else
                             <label class="badge bg-danger">@lang('app.rejected')</label>
                        @endif
                    </h4>
                    <form id="createSchedule" class="ajax-form" method="post">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="PUT">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6  col-xs-12">
                                    <div class="form-group">
                                        <label class="d-block">@lang('modules.interviewSchedule.candidate') @lang('app.name')</label>
                                        <p>{{ $application->full_name }}</p>
                                        <input type="hidden" name="candidate_id" value="{{ $application->id }}">
                                    </div>
                                </div>
                                <div class="col-md-6  col-xs-12">
                                    <div class="form-group">
                                        <label class="d-block">@lang('modules.interviewSchedule.candidate') @lang('app.phone') </label>
                                        <p>{{ $application->phone }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <div class="form-group">
                                        <label class="d-block">@lang('modules.interviewSchedule.candidate') @lang('app.email')</label>
                                        <p>{{ $application->email }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6  col-xs-12">
                                    <div class="form-group">
                                        <label class="d-block">@lang('app.job') @lang('app.location') </label>
                                        <p>{{ ucwords($application->job->location->location) }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6  col-xs-12">
                                    <div class="form-group">
                                        <label class="d-block">@lang('app.department') </label>
                                        <p>{{ ucwords($onboard->department->name) }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6  col-xs-12">
                                    <div class="form-group">
                                        <label class="d-block">@lang('app.designation') </label>
                                        <p>{{ ucwords($onboard->designation->name) }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6  col-xs-12">
                                    <div class="form-group">
                                        <label class="d-block">@lang('app.salaryOfferedPerMonth') </label>
                                        <p>{{  $onboard->salary_offered }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6  col-xs-12">
                                    <div class="form-group">
                                        <label class="d-block">@lang('app.acceptLastDate') </label>
                                        <p>{{  $onboard->accept_last_date->format('d M Y') }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6  col-xs-12">
                                    <div class="form-group">
                                        <label class="d-block">@lang('app.reportTo') </label>
                                        <p>{{  ucwords($onboard->reportto->name) }}</p>
                                    </div>
                                </div>
                                @if(sizeof($onboard->files) > 0)
                                    <div class="col-md-12">
                                    <h4 class="theme-color mt-20">@lang('app.files')</h4>

                                    <div class="row">
                                        @forelse($onboard->files as $file)
                                            <input type="hidden" name="oldImages[{{$file->id}}]">
                                            <div class="col-md-2 m-b-10" id="fileBox{{$file->id}}">
                                                <a target="_blank" href="{{ asset_url('onboard-files/'.$file->hash_name) }}">
                                                    <div class="card">
                                                        <div class="file-bg">
                                                            <div class="overlay-file-box">
                                                                <div class="user-content">
                                                                    @if(array_key_exists($file->ext, $imageExt))
                                                                        <img class="card-img-top img-responsive" src="{{ asset_url('onboard-files/'.$file->hash_name) }}" >
                                                                    @else
                                                                        <i class="fa {{ $fileExt[$file->ext]}}" style="font-size: -webkit-xxx-large; padding-top: 65px;"></i>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-block">
                                                            <h6 class="card-title">{{ $file->name }}</h6>
                                                            <a href="javascript:;" data-toggle="tooltip"
                                                               data-original-title="Delete"
                                                               data-file-id="{{ $file->id }}"
                                                               class="btn btn-danger btn-circle sa-params" data-pk="thumbnail"><i
                                                                        class="fa fa-times"></i></a>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        @empty

                                        @endforelse
                                    </div>
                                </div>
                                @endif
                                <div class="row">
                                    @if($onboard->hired_status == 'accepted' && !is_null($onboard->sign))
                                        <div class="form-group">
                                            <label class="d-block">@lang('app.signature') </label>
                                            <img class="img-responsive" src="{{ asset_url('offer/sign/'.$onboard->sign) }}" alt="Candidate Signiture">
                                        </div>
                                    @elseif($onboard->hired_status == 'rejected' && !is_null($onboard->reason))
                                        <div class="form-group">
                                            <label class="d-block">@lang('app.reason') </label>
                                            <p>{{  ucwords($onboard->reason) }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <br>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
