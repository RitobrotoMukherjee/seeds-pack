@extends('layouts.admin')
@section('body')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Customer Master</h1>
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
                        <!-- /.card-header -->
                        <div class="card-body">
                            @if ($errors->any())
                                <div class='row'>
                                    <div class="col-md-6 offset-md-3 alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-md-12">
                                    <form role="form" id="get-report" method="GET" action="{{ route('customer.report') }}" >
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="from_date">From Date</label>
                                                    <input type="text" class="form-control" id="from_date" placeholder="Select From Date" name="filters[from_date]" value="{{ old('filters.from_date') }}" data-inputmask-alias="datetime" data-inputmask-inputformat="yyyy-mm-dd" data-mask required >
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="to_date">To Date</label>
                                                    <input type="text" class="form-control" id="to_date" placeholder="Select To Date" name="filters[to_date]" value="{{ old('filters.to_date') }}" data-inputmask-alias="datetime" data-inputmask-inputformat="yyyy-mm-dd" data-mask required>
                                                </div>
                                            </div>
                                            <div class="col-md-4 ">
                                                <div class="form-group">
                                                    <label for="select_customer">Select Customer</label>
                                                    <select class="form-control " id="select_customer" name="filters[customer_id]" required>
                                                        <option value=''>- Search Customer -</option>
                                                    @foreach($customers as $customer)
                                                        <option value='{{ $customer->id }}' >{{ $customer->name }}</option>
                                                    @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 offset-md-3 text-center">
                                                <button type="submit" class="btn btn-warning btn-block">Get Report</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @if(isset($details['payment_data']))
                            <br><br>
                                <dl>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <dt>Name</dt>
                                            <dd>{{ $details['payment_data']['customer']['name'] }}</dd>
                                        </div>
                                        <div class="col-md-6">
                                            <dt>Address</dt>
                                            <dd>{{ $details['payment_data']['customer']['address'] }}</dd>
                                        </div>
                                    </div>
                                    <br><br>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <dt>Outstanding Amount</dt>
                                            <dd>{{ $details['payment_data']['billing_amount'] - $details['payment_data']['paid_amount'] }}</dd>
                                        </div>
                                        <div class="col-md-6">
                                            <dt>Last Paid At</dt>
                                            <dd>{{ $details['payment_data']['last_paid_at'] }}</dd>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <dt>Total Billing Amount</dt>
                                            <dd>{{ $details['payment_data']['billing_amount'] }}</dd>
                                        </div>
                                        <div class="col-md-6">
                                            <dt>Total Paid Amount</dt>
                                            <dd>{{ $details['payment_data']['paid_amount'] }}</dd>
                                        </div>
                                    </div>
                                </dl>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @if(isset($details['billing_data']))
            @php $configure = $details['billing_data']; @endphp
            <div class="row">
                <div class="col-12">
                    <div class="card card-warning">
                        <div class="card-body">
                            <table id="customer-list" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Unit Price</th>
                                        <th>Net Amount</th>
                                    </tr>
                                </thead>
                                
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>
    
</div>
@endsection

@section('css')
    @parent
    <link rel="stylesheet" href="{{ config('app.asset_url') }}/vendor/adminlte/plugins/select2/css/select2.min.css">
@stop

@section('scripts')
    @parent
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.0/dist/jquery.validate.js"></script>
    <script src="{{ config('app.asset_url') }}/vendor/adminlte/plugins/select2/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#select_customer").select2();
            //Datemask dd/mm/yyyy
            $('#datemask').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' });
            //Datemask2 mm/dd/yyyy
            $('#datemask2').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' });
            //Money Euro
            $('[data-mask]').inputmask();
            
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            <?php if(isset($configure)){?>
            $('#customer-list').DataTable( {
                "paging": true,
                "buttons": ["print", "colvis"],
                "responsive": true,
                "ordering": false,
                "data": <?= json_encode($configure) ?>
            }).buttons().container().appendTo('#report-list_wrapper .col-md-6:eq(0)');
            <?php } ?>
            
            
            @if(request()->session()->get('error'))
                toastr.error("<?=request()->session()->get('error')?>");
            @endif
            @if(request()->session()->get('message'))
                toastr.success("<?=request()->session()->get('message')?>");
            @endif
        });
    </script>
@endsection