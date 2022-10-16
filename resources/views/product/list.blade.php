@extends('layouts.admin')
@section('body')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Product Master</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-warning">
                        <div class="card-header">
                            <div class="col-6 pull-right">
                                <a class='btn btn-light btn-md card-title' href="{{ route('product.add') }}" >Add/Edit Product</a>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="product-list" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Product Type</th>
                                        <th>Available Quantity</th>
                                        <th>Sale Price Per Kg</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
</div>
@endsection
@section('scripts')
    @parent
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#product-list').DataTable( {
                "processing": true,
                "serverSide": true,
                "buttons": ["excel", "colvis"],
                "responsive": true,
                "ordering": false,
                "language": {
                    "searchPlaceholder": "Search Product Name."
                },
                "ajax":{
                     "url": "{{ route('product.server.list') }}",
                     "dataType": "JSON",
                     "type": "POST"
                },
                "columns": [
                    { "data": "name" },
                    { "data": "product_type" },
                    { "data": "available" },
                    { "data": "sale_price_per_kg" },
                    { "data": "options" }
                ]
            }).buttons().container().appendTo('#product-list_wrapper .col-md-6:eq(0)');
            
            @if(request()->session()->get('error'))
                toastr.error("<?=request()->session()->get('error')?>");
            @endif
            @if(request()->session()->get('message'))
                toastr.success("<?=request()->session()->get('message')?>");
            @endif
        });
    </script>
@endsection