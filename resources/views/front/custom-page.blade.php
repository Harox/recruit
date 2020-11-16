@extends('layouts.front')

@section('header-text')
    <h1 class="hidden-sm-down"> {{ $pageTitle }}</h1>

@endsection

@push('style')
<style>
    .header {
        padding: 43px 100px !important;
    }
    .min-height-section{
        min-height: 400px;
    }
</style>
@endpush

@section('content')
    <!--
    |‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒
    | Working at TheThemeio
    |‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒
    !-->
    <section class="section bg-gray py-40 min-height-section">
        <div class="container">
            <div class="row gap-y align-items-center">
                <div class="col-12">
                    <h3>@if(!is_null($customPage->name)) {{ $customPage->name }}  @endif</h3>
                    <p>@if(!is_null($customPage->description)) {!! $customPage->description !!}  @endif</p>
                </div>
            </div>
        </div>
    </section>

@endsection