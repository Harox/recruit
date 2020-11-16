@extends('layouts.app')

@push('head-script')
<link rel="stylesheet" href="{{ asset('assets/node_modules/html5-editor/bootstrap-wysihtml5.css') }}">
@endpush


@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <form id="editSettings" class="ajax-form">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label>@lang('app.name')</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $footer->name }}">
                    </div>
                    <div class="form-group">
                        <label>@lang('app.description')</label>
                        <textarea name="description" class="form-control" id="description" cols="30"
                            rows="4">{{ $footer->description }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="address">@lang('app.status')</label>
                        <select name="status" id="status" class="form-control">
                            <option
                                    @if($footer->status == 'active') selected @endif
                            value="active">@lang('app.active')</option>
                            <option
                                    @if($footer->status == 'inactive') selected @endif
                            value="inactive">@lang('app.inactive')</option>
                        </select>
                    </div>


                    <button type="button" id="save-form" class="btn btn-success waves-effect waves-light m-r-10">
                        @lang('app.save')
                    </button>
                    <button type="reset" class="btn btn-inverse waves-effect waves-light">@lang('app.reset')</button>
                </form>
                
            </div>
        </div>
    </div>
</div>
@endsection
@push('footer-script')
<script src="{{ asset('assets/node_modules/html5-editor/wysihtml5-0.3.0.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/node_modules/html5-editor/bootstrap-wysihtml5.js') }}"
        type="text/javascript"></script>
<script>
    $('#description').wysihtml5({
        "font-styles": true, //Font styling, e.g. h1, h2, etc. Default true
        "emphasis": true, //Italics, bold, etc. Default true
        "lists": true, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
        "html": true, //Button which allows you to edit the generated HTML. Default false
        "link": true, //Button to insert a link. Default true
        "image": true, //Button to insert an image. Default true,
        "color": true, //Button to change color of font
        stylesheets: ["{{ asset('assets/node_modules/html5-editor/wysiwyg-color.css') }}"], // (path_to_project/lib/css/wysiwyg-color.css)
    });
    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route("admin.footer-settings.update", $footer->id)}}',
            container: '#editSettings',
            type: "POST",
            redirect: true,
            file: true
        })
    });
</script>

@endpush