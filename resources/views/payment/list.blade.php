@extends('layouts.admin')
@section('body')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Payment Master</h1>
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
                                <a class='btn btn-light btn-md card-title' href="{{ route('payment.add') }}" >Add Payment</a>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="payment-list" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Customer Name</th>
                                        <th>Payment Amount</th>
                                        <th>Payment Date</th>
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
            $('#payment-list').DataTable( {
                "processing": true,
                "serverSide": true,
                "buttons": ["excel", "colvis"],
                "responsive": true,
                "ordering": false,
                "language": {
                    "searchPlaceholder": "Search Customer Name."
                },
                "ajax":{
                     "url": "{{ route('payment.server.list') }}",
                     "dataType": "JSON",
                     "type": "POST"
                },
                "columns": [
                    { "data": "customer_name" },
                    { "data": "payment_amount" },
                    { "data": "payment_date" }
                ]
            }).buttons().container().appendTo('#payment-list_wrapper .col-md-6:eq(0)');
            
            @if(request()->session()->get('error'))
                toastr.error("<?=request()->session()->get('error')?>");
            @endif
            @if(request()->session()->get('message'))
                toastr.success("<?=request()->session()->get('message')?>");
            @endif
        });
    </script>
@endsection