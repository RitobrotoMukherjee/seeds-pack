@extends('layouts.admin')
@section('body')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{ __('Payment Add Page') }}</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <form role="form" id="payment-add" method="POST" action="{{ route('payment.upsert') }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-warning">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('Payment Details') }}</h3>
                            </div>
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
                                    <div class="col-md-6 offset-md-3">
                                        <div class="form-group">
                                            <label for="customer_select">Select Customer</label>
                                            <select class="form-control " id="customer_select" name="payment[customer_id]" required>
                                                <option value=''>- Search Customer -</option>
                                                @foreach($customers as $customer)
                                                <option value='{{ $customer->id }}' >{{ $customer->name }}({{ $customer->mobile }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="payment_amount">Payment Amount</label>
                                            <input type="text" inputmode="decimal" class="form-control" id="payment_amount" placeholder="Payment Amount" name="payment[payment_amount]" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="payment_date">Payment Date</label>
                                            <input type="text" class="form-control" id="payment_date" placeholder="Payment Date" name="payment[payment_date]"  data-inputmask-alias="datetime" data-inputmask-inputformat="yyyy-mm-dd" data-mask required>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="row">
                                    <div class="col-md-6 offset-md-3 text-center">
                                        <button type="submit" class="btn btn-warning btn-block">Save</button>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                
            </form>
                    
        </div>
    </section>
</div>

    
@stop

@section('css')
    @parent
    <link rel="stylesheet" href="{{ config('app.asset_url') }}/vendor/adminlte/plugins/select2/css/select2.min.css">
@stop

@section('scripts')
    @parent
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.0/dist/jquery.validate.js"></script>
    <script src="{{ config('app.asset_url') }}/vendor/adminlte/plugins/select2/js/select2.min.js"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        
        
        $(document).ready(function(){  
            $("#customer_select").select2();
            //Datemask dd/mm/yyyy
            $('#datemask').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' });
            //Datemask2 mm/dd/yyyy
            $('#datemask2').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' });
            //Money Euro
            $('[data-mask]').inputmask();
            
            $("#payment-add").validate({
                rules:{
                    "payment[customer_id]": {
                        required: true,
                    },
                    "payment[payment_amount]":{
                        required:true,
                        number: true,
                    },
                    "payment[payment_date]":{
                        required:true
                    }
                }
            });
        });
    </script>
@stop