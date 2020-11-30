<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Favicon icon -->
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('favicon/apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('favicon/apple-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('favicon/apple-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('favicon/apple-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('favicon/apple-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('favicon/apple-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('favicon/apple-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('favicon/apple-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-icon-180x180.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicon/android-icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon/favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('favicon/manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('favicon/ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#ffffff">

    <title>@lang('app.adminPanel') | {{ $pageTitle }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">

    <!-- Simple line icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.css">

    <!-- Themify icons -->
    <link rel="stylesheet" href="{{ asset('assets/icons/themify-icons/themify-icons.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->

    <link href="{{ asset('froiden-helper/helper.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/node_modules/toast-master/css/jquery.toast.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/node_modules/sweetalert/sweetalert.css') }}" rel="stylesheet">
    <link rel='stylesheet prefetch' href='https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.0/dist/css/bootstrap-select.min.css'>

    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.css') }}">
    <link href="{{ asset('assets/node_modules/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('assets/node_modules/bootstrap-select/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/node_modules/Magnific-Popup-master/dist/magnific-popup.css') }}">

    @stack('head-script')

    <link rel='stylesheet prefetch'
          href='//cdnjs.cloudflare.com/ajax/libs/flag-icon-css/0.8.2/css/flag-icon.min.css'>

    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <style>
        :root {
            --main-color: {{ $adminTheme->primary_color }};
        }
        .well, pre {
            background: #fff;
            border-radius: 0;
        }

        .btn-group-xs > .btn, .btn-xs {
            padding  : .25rem .4rem;
            font-size  : .875rem;
            line-height  : .5;
            border-radius : .2rem;
        }

        .btn-circle {
            width: 30px;
            height: 30px;
            padding: 6px 0;
            border-radius: 15px;
            text-align: center;
            font-size: 12px;
            line-height: 1.428571429;
        }
        .well {
            min-height: 20px;
            padding: 19px;
            margin-bottom: 20px;
            background-color: #f5f5f5;
            border: 1px solid #e3e3e3;
            border-radius: 4px;
            -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
            box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
            font-size: 12px;
        }
        .text-truncate-notify{
            white-space: pre-wrap !important;
        }

        {!! $adminTheme->admin_custom_css !!}
    </style>

    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
            </li>
        </ul>
        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                    <a class="nav-link  waves-effect waves-light" 
                    @if(!$user->is_superadmin)
                        href="{{ route('admin.profile.index') }}"
                    @else
                        href="{{ route('superadmin.profile.index') }}"
                    @endif
                    >
                        <img src="{{ $user->profile_image_url  }}" class="img-circle img-size-32" alt="User Image">
                        {{ ucwords($user->name) }}
                    </a>
    
                </li>

            <!-- Notifications Dropdown Menu -->
            <li class="nav-item dropdown" id="top-notification-dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="fa fa-bell-o"></i>
                    @if(count($user->unreadNotifications) > 0)
                        <span class="badge badge-warning navbar-badge ">{{ count($user->unreadNotifications) }}</span>
                    @endif
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    @foreach ($user->unreadNotifications as $notification)
                        @include('notifications.'.snake_case(class_basename($notification->type)))
                    @endforeach
                    @if(count($user->unreadNotifications) > 0)
                        <a id="mark-notification-read" href="javascript:void(0);" class="dropdown-item dropdown-footer">@lang('app.markNotificationRead') <i class="fa fa-check"></i></a>
                    @else
                        <a  href="javascript:void(0);" class="dropdown-item dropdown-footer">@lang('messages.notificationNotFound') </a>
                    @endif
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link  waves-effect waves-light" href="{{ route('logout') }}" title="Logout" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();"
                ><i class="fa fa-power-off"></i>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </a>

            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    @include('sections.left-sidebar')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        @include('sections.breadcrumb')

        <!-- Main content -->
        <section class="content">

            @yield('content')

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
        {{--Footer sticky notes--}}
        <div id="footer-sticky-notes" class="row hidden-xs hidden-sm rounded">
            <div class="col-md-12" id="sticky-note-header">
                <div class="row">
                    <div class="col-md-10" style="line-height: 30px">
                        @lang('app.menu.stickyNotes') <a href="javascript:;" onclick="showCreateNoteModal()" class="btn btn-outline-success btn-xs ml-5"><i class="fa fa-plus"></i> @lang("modules.sticky.addNote")</a>
                    </div>
                    <div class="col-md-2">
                        <a href="javascript:;" class="btn btn-default btn-circle ml-2" id="open-sticky-bar"><i class="fa fa-chevron-up"></i></a>
                        <a style="display: none;" class="btn btn-default btn-circle ml-2" href="javascript:;" id="close-sticky-bar"><i class="fa fa-chevron-down"></i></a>
                    </div>
                </div>


            </div>

            <div id="sticky-note-list" style="display: none; width: 100%">

                @foreach($stickyNotes as $note)
                    <div class="col-md-12 sticky-note" id="stickyBox_{{$note->id}}">
                        <div class="well
                 @if($note->colour == 'red')  bg-danger @endif
                        @if($note->colour == 'green') bg-success @endif
                        @if($note->colour == 'yellow')  bg-warning @endif
                        @if($note->colour == 'blue') bg-info @endif
                        @if($note->colour == 'purple') bg-purple @endif b-none">
                            <p>{!! nl2br($note->note_text)  !!}</p>
                            <hr>
                            <div class="row font-12">
                                <div class="col-md-9">
                                    @lang("modules.sticky.lastUpdated"): {{ $note->updated_at->diffForHumans() }}
                                </div>
                                <div class="col-md-3">
                                    <a href="javascript:;"  onclick="showEditNoteModal({{$note->id}})"><i class="ti-pencil-alt text-white"></i></a>
                                    <a href="javascript:;" class="m-l-5" onclick="deleteSticky({{$note->id}})" ><i class="ti-close text-white"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
        {{--sticky note end--}}
        {{--sticky note modal--}}
        <div id="responsive-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    Loading ...
                </div>
            </div>
        </div>
        {{--sticky note modal ends--}}

    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="application-modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <span class="caption-subject font-red-sunglo bold uppercase" id="modelHeading"></span>
                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> @lang('app.cancel')</button>
                    <button type="button" class="btn btn-success"><i class="fa fa-check"></i> @lang('app.save')</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    {{--Ajax Modal Ends--}}


    <footer class="main-footer">
        &copy; {{ \Carbon\Carbon::today()->year }} @lang('app.by') {{ $companyName }}
    </footer>

    @include('sections.right-sidebar')
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('assets/node_modules/popper/popper.min.js') }}"></script>
<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.js') }}"></script>

<!-- SlimScroll -->
<script src="{{ asset('assets/plugins/slimScroll/jquery.slimscroll.min.js') }}"></script>
<!-- FastClick -->
<script src="{{ asset('assets/plugins/fastclick/fastclick.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('assets/dist/js/adminlte.min.js') }}"></script>

<script src='https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.0/dist/js/bootstrap-select.min.js'></script>
<script src="{{ asset('assets/node_modules/sweetalert/sweetalert.min.js') }}"></script>

<script src="{{ asset('froiden-helper/helper.js') }}"></script>
<script src="{{ asset('assets/node_modules/toast-master/js/jquery.toast.js') }}"></script>
<script src="{{ asset('js/cbpFWTabs.js') }}"></script>
<script src="{{ asset('assets/plugins/icheck/icheck.min.js') }}"></script>
<script src="{{ asset('assets/plugins/icheck/icheck.init.js') }}"></script>
<script src="{{ asset('assets/node_modules/Magnific-Popup-master/dist/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ asset('assets/node_modules/Magnific-Popup-master/dist/jquery.magnific-popup-init.js') }}"></script>

<script>
    $('body').on('click', '.right-side-toggle', function () {
        $("body").removeClass("control-sidebar-slide-open");
    })

    $(function () {
        $('.selectpicker').selectpicker({
            style: 'btn-info',
            size: 4
        });
    });

    function languageOptions() {
        return {
            processing:     "@lang('modules.datatables.processing')",
            search:         "@lang('modules.datatables.search')",
            lengthMenu:    "@lang('modules.datatables.lengthMenu')",
            info:           "@lang('modules.datatables.info')",
            infoEmpty:      "@lang('modules.datatables.infoEmpty')",
            infoFiltered:   "@lang('modules.datatables.infoFiltered')",
            infoPostFix:    "@lang('modules.datatables.infoPostFix')",
            loadingRecords: "@lang('modules.datatables.loadingRecords')",
            zeroRecords:    "@lang('modules.datatables.zeroRecords')",
            emptyTable:     "@lang('modules.datatables.emptyTable')",
            paginate: {
                first:      "@lang('modules.datatables.paginate.first')",
                previous:   "@lang('modules.datatables.paginate.previous')",
                next:       "@lang('modules.datatables.paginate.next')",
                last:       "@lang('modules.datatables.paginate.last')",
            },
            aria: {
                sortAscending:  "@lang('modules.datatables.aria.sortAscending')",
                sortDescending: "@lang('modules.datatables.aria.sortDescending')",
            },
        }
    }

    $('.language-switcher').change(function () {
        var lang = $(this).val();
        $.easyAjax({
            url: '{{ route("admin.language-settings.change-language") }}',
            data: {'lang': lang},
            success: function (data) {
                if (data.status == 'success') {
                    window.location.reload();
                }
            }
        });
    });

    $('#mark-notification-read').click(function () {
        var token = '{{ csrf_token() }}';
        $.easyAjax({
            type: 'POST',
            url: '{{ route("mark-notification-read") }}',
            data: {'_token': token},
            success: function (data) {
                if (data.status == 'success') {
                    $('.top-notifications').remove();
                    $('#top-notification-dropdown .notify').remove();
                    window.location.reload();
                }
            }
        });

    });

    function addOrEditStickyNote(id)
    {
        var url = '';
        var method = 'POST';
        if(id === undefined || id == "" || id == null) {
            url =  '{{ route('admin.sticky-note.store') }}'
        } else{

            url = "{{ route('admin.sticky-note.update',':id') }}";
            url = url.replace(':id', id);
            var stickyID = $('#stickyID').val();
            method = 'PUT'
        }

        var noteText = $('#notetext').val();
        var stickyColor = $('#stickyColor').val();
        $.easyAjax({
            url: url,
            container: '#responsive-modal',
            type: method,
            data:{'notetext':noteText,'stickyColor':stickyColor,'_token':'{{ csrf_token() }}'},
            success: function (response) {
                $("#responsive-modal").modal('hide');
                getNoteData();
            }
        })
    }

    // FOR SHOWING FEEDBACK DETAIL IN MODEL
    function showCreateNoteModal(){
        var url = '{{ route('admin.sticky-note.create') }}';
        $.ajaxModal('#responsive-modal', url);

        return false;
    }

    // FOR SHOWING FEEDBACK DETAIL IN MODEL
    function showEditNoteModal(id){
        var url = '{{ route('admin.sticky-note.edit',':id') }}';
        url  = url.replace(':id',id);
        $.ajaxModal('#responsive-modal', url);
        return false;
    }
    function selectColor(id){
        $('.icolors li.active ').removeClass('active');
        $('#'+id).addClass('active');
        $('#stickyColor').val(id);

    }

    function deleteSticky(id){

        swal({
            title: "Tens a certeza?",
            text: "Não poderá recuperar a Nota apagada!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Sim, eliminar!",
            cancelButtonText: "Não, cancele, por favor.!",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function(isConfirm){
            if (isConfirm) {

                var url = "{{ route('admin.sticky-note.destroy',':id') }}";
                url = url.replace(':id', id);

                var token = "{{ csrf_token() }}";

                $.easyAjax({
                    type: 'POST',
                    url: url,
                    data: {'_token': token, '_method': 'DELETE'},
                    success: function (response) {
                        $('#stickyBox_'+id).hide('slow');
                        $("#responsive-modal").modal('hide');
                        getNoteData();
                    }
                });
            }
        });
    }


    //getting all chat data according to user
    function getNoteData(){

        var url = "{{ route('admin.sticky-note.index') }}";

        $.easyAjax({
            type: 'GET',
            url: url,
            messagePosition: '',
            data:  {},
            container: ".noteBox",
            error: function (response) {

                //set notes in box
                $('#sticky-note-list').html(response.responseText);
            }
        });
    }

    //    sticky notes script
    var stickyNoteOpen = $('#open-sticky-bar');
    var stickyNoteClose = $('#close-sticky-bar');
    var stickyNotes = $('#footer-sticky-notes');
    var viewportHeight = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);
    var stickyNoteHeaderHeight = stickyNotes.height();

    $('#sticky-note-list').css('max-height', viewportHeight-150);

    stickyNoteOpen.click(function () {
        $('#sticky-note-list').toggle(function () {
            $(this).animate({
                height: (viewportHeight-150)
            })
        });
        stickyNoteClose.toggle();
        stickyNoteOpen.toggle();
    })

    stickyNoteClose.click(function () {
        $('#sticky-note-list').toggle(function () {
            $(this).animate({
                height: 0
            })
        });
        stickyNoteOpen.toggle();
        stickyNoteClose.toggle();
    })

</script>

@stack('footer-script')

</body>
</html>
