@extends('layouts.app')

@push('head-script')
    <link rel="stylesheet" href="{{ asset('assets/node_modules/clockpicker/dist/jquery-clockpicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/node_modules/jquery-asColorPicker-master/css/asColorPicker.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/node_modules/dropify/dist/css/dropify.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/summernote/dist/summernote.css') }}">

@endpush

@section('content')

    <div class="row">
        <div class="col-12">
            <!-- Card -->
            <form action="" class="ajax-form" id="createForm" method="POST">
                @csrf
                <div class="card">
                    <div class="card-body">
                         <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="example">
                                    <h5 class="box-title">@lang('modules.themeSettings.themePrimaryColor')</h5>
                                    <input type="text" name="primary_color" class="gradient-colorpicker form-control" autocomplete="off" value="{{ $adminTheme->primary_color }}" />
                                </div>

                            </div>

                            <div class="col-md-12 mb-4">
                                <h5 class="box-title">@lang('modules.themeSettings.adminPanelCustomCss')</h5>
                                <textarea name="admin_custom_css" class="my-code-area" rows="20" style="width: 100%">@if(is_null($adminTheme->admin_custom_css))/*Enter your custom css after this line*/@else {!! $adminTheme->admin_custom_css !!} @endif</textarea>

                            </div>

                            <div class="col-md-12 mb-4">
                                <h5 class="box-title">@lang('modules.themeSettings.frontSiteCustomCss')</h5>
                                <textarea name="front_custom_css" class="my-code-area" rows="20" style="width: 100%">@if(is_null($adminTheme->front_custom_css))/*Enter your custom css after this line*/@else {!! $adminTheme->front_custom_css !!} @endif</textarea>

                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="exampleInputPassword1">@lang('app.background') @lang('app.image')</label>
                                    <div class="card">
                                        <div class="card-body">
                                            <input type="file" id="input-file-now" name="image" accept=".png,.jpg,.jpeg" class="dropify"
                                                   data-default-file="{{ $adminTheme->background_image_url }}"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="welcome_title">@lang('app.welcomeTitle')</label>
                                            <input type="text"  name="welcome_title"  id="welcome_title"  class="form-control"
                                                   value="{{ $adminTheme->welcome_title }}"
                                            />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="welcome_sub_title">@lang('app.welcomeSubTitle')</label>
                                        <textarea type="text"  name="welcome_sub_title" id="welcome_sub_title" class="form-control">
                                            {!! $adminTheme->welcome_sub_title !!}
                                        </textarea>
                                </div>
                            </div>

                            <div class="col-md-12 mt-4">
                                <button class="btn btn-success" id="save-form" type="button"><i class="fa fa-check"></i> @lang('app.save')</button>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection

@push('footer-script')
    <script src="{{ asset('assets/node_modules/jquery-asColorPicker-master/libs/jquery-asColor.js') }}"></script>
    <script src="{{ asset('assets/node_modules/jquery-asColorPicker-master/libs/jquery-asGradient.js') }}"></script>
    <script src="{{ asset('assets/node_modules/jquery-asColorPicker-master/dist/jquery-asColorPicker.min.js') }}"></script>

    <script src="{{ asset('assets/ace/ace.js') }}"></script>
    <script src="{{ asset('assets/ace/theme-twilight.js') }}"></script>
    <script src="{{ asset('assets/ace/mode-css.js') }}"></script>
    <script src="{{ asset('assets/ace/jquery-ace.min.js') }}"></script>
    <script src="{{ asset('assets/node_modules/dropify/dist/js/dropify.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/summernote/dist/summernote.min.js') }}"></script>


    <script>
        $('#welcome_sub_title').summernote({
            height: 150,                 // set editor height
            minHeight: null,             // set minimum height of editor
            maxHeight: null,             // set maximum height of editor
            focus: false,                 // set focus to editable area after initializing summernote
            toolbar: [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough']],
                ['fontsize', ['fontsize']],
                ['para', ['ul', 'ol', 'paragraph']],
                ["view", ["fullscreen"]]
            ]
        });
        $('.dropify').dropify({
            messages: {
                default: '@lang("app.dragDrop")',
                replace: '@lang("app.dragDropReplace")',
                remove: '@lang("app.remove")',
                error: '@lang('app.largeFile')'
            }
        });

        $('.my-code-area').ace({ theme: 'twilight', lang: 'css' })

        // Colorpicker
        $(".colorpicker").asColorPicker();
        $(".complex-colorpicker").asColorPicker({
            mode: 'complex'
        });
        $(".gradient-colorpicker").asColorPicker(
            // {
            //     mode: 'gradient'
            // }
        );
        $('.gradient-colorpicker').on('asColorPicker::change', function (e) {
            document.documentElement.style.setProperty('--main-color', e.target.value);
        });

        $('#save-form').click(function () {
            $.easyAjax({
                url: '{{route('admin.theme-settings.store')}}',
                container: '#createForm',
                type: "POST",
                file: true,
                redirect: true
            })
        });

    </script>
@endpush
