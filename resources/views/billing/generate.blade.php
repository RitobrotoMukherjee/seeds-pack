@extends('layouts.admin')
@section('body')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{ __('Generate Bill') }}</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <form role="form" id="bill-add" method="POST" action="{{ route('bill.upsert') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" class="form-control" id="product_data" name="product[details]" />
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-warning">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('Customer Details') }}</h3>
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
                                            <select class="form-control " id="customer_select" name="customer[id]" onchange="customerSelect(this)">
                                                <option value=''>- Search Customer -</option>
                                                @foreach($customers as $customer)
                                                <option value='{{ $customer->id }}' >{{ $customer->name }}({{ $customer->mobile }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="customer_name">Customer Name</label>
                                            <input type="text" class="form-control" id="customer_name" placeholder="Customer Name" name="customer[name]" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="customer_city_village">Customer City/Village</label>
                                            <input type="text" class="form-control" id="customer_city_village" placeholder="Customer City Village" name="customer[city_village]" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="outstanding_amount">Outstanding Amount(Auto System Fill)</label>
                                            <input type="text" class="form-control" id="outstanding_amount" placeholder="Outstanding Amount" value="0" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="paid_amount">Last Paid Amount(Auto System Fill)</label>
                                            <input type="text" class="form-control" id="paid_amount" placeholder="Paid Amount" value="0" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="paid_date">Last Paid Date(Auto System Fill)</label>
                                            <input type="text" class="form-control" id="paid_date" placeholder="Last Paid Date" readonly value="{{ $today }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-warning">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('Product Details') }}</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 offset-md-3">
                                        <div class="form-group">
                                            <label for="product_select">Select Product</label>
                                            <select class="form-control " id="product_select" onchange="productSelect(this)">
                                                <option value=''>- Search Product -</option>
                                                @foreach($products as $product)
                                                <option value='{{ $product->id }}' >{{ $product->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="product_type">Product Type</label>
                                            <select class="form-control " id="product_type" style="pointer-events: none;" >
                                                <option value=''>- Select Product Type -</option>
                                                @foreach($product_type as $k => $v)
                                                <option id="product-type-{{ $k }}" value='{{ $k }}' >{{ $v }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="available">Availability Stock in Kgs(Auto Fill)</label>
                                            <input type="text" class="form-control" id="available" placeholder="Available in Kgs" value="0" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="sale_price_per_kg">Sale Price Per Kgs(Auto Fill)</label>
                                            <input type="text" class="form-control" id="sale_price_per_kg" placeholder="Sale Price Per Kgs" value="0" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="quantity">Quantity Needed In Kgs</label>
                                            <input type="text" inputmode="decimal" class="form-control" id="quantity" name="ajax[quantity]" placeholder="Quantity In Kgs" required >
                                        </div>
                                    </div>
                                    <div class="col-md-12 text-center mt-5">
                                        <div class="form-group">
                                            <label ></label>
                                            <button type="button" class="btn btn-lg btn-warning" onclick="addProduct()">Add Product To bill</button>
                                        </div>
                                    </div>
                                </div>
                                <hr style="border-top: 1px solid white;">
                                <div class="row">
                                    <div class='table-responsive-md col-md-12'>
                                        <table class='table'>
                                            <thead>
                                                <tr>
                                                    <th style='width: 25px'>Product Name</th>
                                                    <th style='width: 15px'>Quantity Available</th>
                                                    <th style='width: 15px'>Quantity Need</th>
                                                    <th style='width: 20px'>Price Per Unit</th>
                                                    <th style='width: 25px'>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody id="product_table">
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-warning">
                            <div class="card-header">
                                <h3 class="card-title">{{ __("Bill Details") }}</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="billing_date">Billing Date</label>
                                            <input type="text" class="form-control" id="billing_date" placeholder="Billing Date" name="bill[billing_date]"  data-inputmask-alias="datetime" data-inputmask-inputformat="yyyy-mm-dd" data-mask required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="invoice_number">Invoice No</label>
                                            <input type="text" class="form-control" id="invoice_number" placeholder="Invoice No." name="bill[invoice_number]" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="net_amount">Net Amount</label>
                                            <input type="text" inputmode="decimal" class="form-control" id="net_amount" placeholder="Net Amount" name="bill[net_amount]" value="0" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="transporter_name">Transporter Name</label>
                                            <input type="text" class="form-control" id="transporter_name" placeholder="Transporter Name" name="bill[transporter_name]" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="dispatched_date">Dispatch Date</label>
                                            <input type="text" class="form-control" id="dispatched_date" placeholder="Dispatch Date" name="bill[dispatched_date]"  data-inputmask-alias="datetime" data-inputmask-inputformat="yyyy-mm-dd" data-mask required>
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
        const NET_AMOUNT = document.getElementById('net_amount');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        function productSelect(elm){
            let id = parseInt(elm.value);
            if(!isNaN(id)){
                $.ajax("{{ route('ajax.product.detail') }}", {
                    type: 'POST',  // http method
                    data: {product_id: id},
                    success: function (data, status, xhr) {
                        document.getElementById("product-type-"+data.type).setAttribute("selected","");
                        document.getElementById("available").value = data.available  + " Kgs";
                        document.getElementById("sale_price_per_kg").value = data.sale_price_per_kg;
                        document.getElementById("quantity").value = 0;
                    },
                    error: function (jqXhr, textStatus, errorMessage) {
                        console.log(textStatus);
                        toastr.error(errorMessage);
                    }
                });
            }else{
                toastr.error("Select Product First");
            }
        }
        
        function addProduct(){
            const table = document.getElementById("product_table");
            const product_id = parseInt(document.getElementById("product_select").value);
            const unit_price = document.getElementById("sale_price_per_kg").value;
            const quantity = parseFloat(document.getElementById("quantity").value);
            
            if(isNaN(product_id) || product_id === 0){
                return toastr.error("Select A Product First");
            }
            if(isNaN(quantity) || quantity === 0){
                return toastr.error("Product Quantity Cannot Be Zero");
            }

            let product = {
                product_id, unit_price, quantity
            };

            $.ajax("{{ route('ajax.product.cart') }}", {
                type: 'POST',
                data: { product },
                success: function (data, status, xhr) {
                    const { products, net_amount } = data;
                    document.getElementById("product_data").value = JSON.stringify(products);
                    let template = ``;
                    for(var key in products){
                        template += 
                                `
                            <tr id="product_table_${key}">
                                <td>${products[key].product_name}</td>
                                <td>${products[key].available} Kgs</td>
                                <td>${products[key].quantity_need} Kgs</td>
                                <td>${products[key].unit_price}</td>
                                <td>${products[key].unit_price * products[key].quantity_need}</td>
                            </tr>
                            `;
                    }
                    table.innerHTML =  template;
                    NET_AMOUNT.value = parseFloat(NET_AMOUNT.value) + parseFloat(net_amount);
                },
                error: function (jqXhr, textStatus, errorMessage) {
                    toastr.error(errorMessage);
                }
            });
        }
        
        function customerSelect(elm){
            let id = parseInt(elm.value);
            if(!isNaN(id)){
                $.ajax("{{ route('ajax.customer.detail') }}", {
                    type: 'POST',  // http method
                    data: {customer_id: id},
                    success: function (data, status, xhr) {
                        document.getElementById("customer_name").value = data.name;
                        document.getElementById("customer_city_village").value = data.city_village;
                        document.getElementById("outstanding_amount").value = data.outstanding_amount;
                        document.getElementById("paid_amount").value = (data.last_paid_amount) ? data.last_paid_amount : 0 ;
                        document.getElementById("paid_date").value = (data.last_paid_date) ? data.last_paid_date : null;
                    },
                    error: function (jqXhr, textStatus, errorMessage) {
                        toastr.error(errorMessage);
                    }
                });
            }else{
                toastr.error("Select The customer");
            }
        };
        
        $(document).ready(function(){  
            $("#customer_select").select2();
            $("#product_select").select2();
            //Datemask dd/mm/yyyy
            $('#datemask').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' });
            //Datemask2 mm/dd/yyyy
            $('#datemask2').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' });
            //Money Euro
            $('[data-mask]').inputmask();
            
            $("#bill-add").validate({
                rules:{
                    "customer[name]": {
                        required: true,
                    },
                    "customer[city_village]":{
                        required:true,
                        maxlength: 50
                    },
                    "bill[net_amount]":{
                        required:true,
                        number: true
                    },
                    "ajax[quantity]":{
                        required:true,
                        number: true
                    }
                }
            });
        });
    </script>
@stop