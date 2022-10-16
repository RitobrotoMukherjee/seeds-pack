@extends('layouts.admin')
@section('body')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{ __('Product Add Page') }}</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <form role="form" id="product-add" method="POST" action="{{ route('product.upsert') }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-warning">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('Product Details') }}</h3>
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
                                            <label for="product_select">Select Product</label>
                                            <select class="form-control " id="product_select" name="product[id]" onchange="productSelect(this)">
                                                <option value=''>- Search Product -</option>
                                            @foreach($products as $product)
                                                <option value='{{ $product->id }}' >{{ $product->name }}</option>
                                            @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="product_name">Product Name</label>
                                            <input type="text" class="form-control" id="product_name" placeholder="Product Name" name="product[name]" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="product_type">Product Type</label>
                                            <select class="form-control " id="product_type" name="product[type]" required>
                                                <option value=''>- Select Product Type -</option>
                                                @foreach($product_type as $k => $v)
                                                <option id="product-type-{{ $k }}" value='{{ $k }}' >{{ $v }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="available">Available Stock in Kgs(Auto System Fill)</label>
                                            <input type="text" inputmode="decimal" class="form-control" id="available" placeholder="Available in Kgs" value="0" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="sale_price_per_kg">Sale Price Per Kgs</label>
                                            <input type="text" inputmode="decimal" class="form-control" id="sale_price_per_kg" placeholder="Sale Price Per Kgs" name="product[sale_price_per_kg]" required>
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
                                <h3 class="card-title">{{ __('Purchase Details') }}</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="purchase_from">Purchase From</label>
                                            <input type="text" class="form-control" id="purchase_from" placeholder="Purchase From" name="purchase_details[purchase_from]" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="purchase_quantity">Purchase Quantity(In Kgs)</label>
                                            <input type="text" inputmode="decimal" class="form-control" id="purchase_quantity" placeholder="Purchase Quantity" name="purchase_details[purchase_quantity]" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="purchase_date">Purchase Date</label>
                                            <input type="text" class="form-control" id="purchase_date" placeholder="Purchase Date" name="purchase_details[purchase_date]"  data-inputmask-alias="datetime" data-inputmask-inputformat="yyyy-mm-dd" data-mask required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="purchase_price_per_kg">Purchase Price Per Kg</label>
                                            <input type="text" inputmode="decimal" class="form-control" id="purchase_price_per_kg" placeholder="Purchase Price Per Kg" name="purchase_details[purchase_price_per_kg]" required>
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
        
        function productSelect(elm){
            let id = parseInt(elm.value);
            if(id !== ''){
                $.ajax("{{ route('ajax.product.detail') }}", {
                    type: 'POST',  // http method
                    data: {product_id: id},
                    success: function (data, status, xhr) {
                        console.log(data);
                        document.getElementById("product_name").value = data.name;
                        document.getElementById("product-type-"+data.type).setAttribute("selected","");
                        document.getElementById("available").value = data.available;
                        document.getElementById("sale_price_per_kg").value = data.sale_price_per_kg;
                    },
                    error: function (jqXhr, textStatus, errorMessage) {
                        console.log(textStatus);
                        toastr.error(errorMessage);
                    }
                });
            }else{
                toastr.error("Fill Up The Product Form");
            }
        }
        
        $(document).ready(function(){  
            $("#product_select").select2();
            //Datemask dd/mm/yyyy
            $('#datemask').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' });
            //Datemask2 mm/dd/yyyy
            $('#datemask2').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' });
            //Money Euro
            $('[data-mask]').inputmask();
            
            $("#product-add").validate({
                rules:{
                    "product[name]": {
                        required: true,
                    },
                    "product[type]":{
                        required:true
                    },
                    "product[sale_price_per_kg]":{
                        required:true
                    }
                }
            });
        });
    </script>
@stop