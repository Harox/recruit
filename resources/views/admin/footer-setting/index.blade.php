@extends('layouts.app') @push('head-script')
<link rel="stylesheet" href="//cdn.datatables.net/fixedheader/3.1.5/css/fixedHeader.bootstrap.min.css">
<link rel="stylesheet" href="//cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
<style>
    .mb-20 {
        margin-bottom: 20px
    }
</style>


@endpush
@permission('manage_settings')
@section('create-button')
    <a href="{{ route('admin.footer-settings.create') }}" class="btn btn-dark btn-sm m-l-15"><i class="fa fa-plus-circle"></i> @lang('app.createNew')</a>
@endsection
@endpermission
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row clearfix">
                        <div class="col-md-12 mb-20">
                        </div>
                    </div>

                    <div class="table-responsive m-t-40">
                        <table id="myTable" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('app.title')</th>
                                <th>@lang('app.description')</th>
                                <th>@lang('app.status')</th>
                                <th class="text-nowrap">@lang('app.action')</th>
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
<script src="//cdn.datatables.net/fixedheader/3.1.5/js/dataTables.fixedHeader.min.js"></script>
<script src="//cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="//cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>

<script>

    var table = $('#myTable').dataTable({
        responsive: true,
        // processing: true,
        serverSide: true,
        ajax:  '{{ route('admin.footer-settings.data') }}',
        language: languageOptions(),
        "fnDrawCallback": function( oSettings ) {
            $("body").tooltip({
                selector: '[data-toggle="tooltip"]'
            });
        },
        columns: [
            { data: 'DT_Row_Index', name: 'DT_Row_Index' , orderable: false, searchable: false},
            { data: 'name', name: 'name' },
            { data: 'description', name: 'description' },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action', width: '20%' }
        ]
    });

    $('body').on('click', '.sa-params', function(){
        var id = $(this).data('feature-id');
        swal({
            title: "Você tem certeza?",
            text: "Você não poderá recuperar a configuração de rodapé eliminada!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Sim, apagar!",
            cancelButtonText: "Não, cancele, por favor!",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function(isConfirm){
            if (isConfirm) {

                var url = "{{ route('admin.footer-settings.destroy',':id') }}";
                url = url.replace(':id', id);

                var token = "{{ csrf_token() }}";

                $.easyAjax({
                    type: 'POST',
                    url: url,
                    data: {'_token': token, '_method': 'DELETE'},
                    success: function (response) {
                        if (response.status == "success") {
                            $.unblockUI();
                            window.location.reload();
                        }
                    }
                });
            }
        });
    });

</script>


@endpush