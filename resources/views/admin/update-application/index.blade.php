@extends('layouts.app')
@push('head-script')
    <style>
        .btn{
            text-decoration: none !important;
        }
    </style>
@endpush
@section('content')

    <div class="row">

        <div class="col-12">
            @include('vendor.froiden-envato.update.update_blade')

            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="table-responsive">

                                @include('vendor.froiden-envato.update.version_info')
                            </div>
                        </div>
                    </div>


                    <hr>
                    <!--row-->
                @include('vendor.froiden-envato.update.changelog')
                    <!--/row-->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('footer-script')
    @include('vendor.froiden-envato.update.update_script')
@endpush