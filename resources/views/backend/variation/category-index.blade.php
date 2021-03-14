@push('title')
    Variation category
@endpush
@extends('layouts.backend.app')
@push('style')

@endpush
@section('content')
    <!-- Start Breadcrumbbar -->
    <div class="breadcrumbbar">
        <div class="row align-items-center">
            <div class="col-md-8 col-lg-8">
                <h4 class="page-title">Variation category</h4>
                <div class="breadcrumb-list">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Variation category</li>
                    </ol>
                </div>
            </div>
            <div class="col-md-4 col-lg-4">
                <div class="widgetbar">
                    <a href="{{ route('product.index') }}" class="btn btn-primary">Product</a>
                </div>
            </div>
        </div>
    </div>
    <!-- End Breadcrumbbar -->
    <!-- Start Contentbar -->
    <div class="contentbar">
        <!-- Start row -->
        <div class="row">
            <!-- Start col -->
            <div class="col-lg-12">
                <div class="card m-b-30">
                    <div class="card-header">
                        <h5 class="card-title">Variation category </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="datatable" class="display table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Product</th>
                                        <th>Variations</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Name</th>
                                        <th>Product</th>
                                        <th>Variations</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End col -->
        </div>
        <!-- End row -->
    </div>
    <!-- End Contentbar -->
    <!-- Modal -->
    <div class="modal fade" id="editVariationCategoryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Variation Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="row" enctype="multipart/form-data">
                        <div class="form-group col-12">
                            <label for="name">Variation Category Name</label>
                            <input type="hidden" value="" id="variationCategoryId" name="id">
                            <input type="text" class="form-control" id="variationCategoryName" name="name"
                                placeholder="name" value="{{ old('name') }}">
                            @error('name')
                                <div class="alert alert-danger" role="alert">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group col-12">
                            <button type="button" class="btn btn-success mr-1 col-12" id="save-variation">SAVE</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')

    <script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
    <script>
        $(function() {
            $('#datatable').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: '{!! route('variationCategory.index') !!}',
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'product',
                        name: 'product'
                    },
                    {
                        data: 'variations',
                        name: 'variations'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                ],
                initComplete: function() {
                    this.api().columns().every(function() {
                        var column = this;
                        var input = document.createElement("input");
                        $(input).appendTo($(column.footer()).empty())
                            .on('change', function() {
                                column.search($(this).val(), false, false, true).draw();
                            });
                    });
                }
            });
        });

    </script>
    <script>
        function editVariationCategory(id, name) {

            $('#variationCategoryId').val(id);
            $('#variationCategoryName').val(name);
            $('#editVariationCategoryModal').modal('show');
        }
        $(document).ready(function() {


            $('#save-variation').click(function() {
                var this_button = $(this);
                $.ajax({
                    type: 'PATCH',
                    url: "variationCategory/" + $(this).parent().parent().find(
                        '#variationCategoryId').val(),
                    cache: false,
                    data: {
                        _token: '{{ csrf_token() }}',
                        name: $(this).parent().parent().find('#variationCategoryName').val(),
                    },

                    beforeSend: function() {
                        $('#save-variation').prop("disabled", true);
                    },
                    complete: function() {
                        $('#save-variation').prop("disabled", false);
                    },
                    success: function(data) {
                        if (data.type == 'success') {
                            $('#modal').modal('hide');
                            $('#modal-from').trigger("reset");
                            Swal.fire({
                                position: 'top-end',
                                icon: data.type,
                                title: data.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                            setTimeout(function() {
                                location.reload();
                            }, 800); //
                        } else {
                            Swal.fire({
                                icon: data.type,
                                title: 'Oops...',
                                text: data.message,
                                footer: 'Something went wrong!'
                            });
                        }
                    },
                    error: function(xhr) {
                        var errorMessage = '<div class="card bg-danger">\n' +
                            ' <div class="card-body text-center p-5">\n' +
                            ' <span class="text-white">';
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            errorMessage += ('' + value + '<br>');
                        });
                        errorMessage += '</span>\n' +
                            ' </div>\n' +
                            ' </div>';
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            footer: errorMessage
                        });
                    },
                });
            });

        });

    </script>
@endpush
