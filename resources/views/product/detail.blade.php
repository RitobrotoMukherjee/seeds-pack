@extends('layouts.admin')
@section('body')

@php

@endphp
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{ ucwords($product->name) }} </h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card card-warning">
                            <div class="card-header">
                                <h3 class="card-title">Product Details</h3>
                            </div>
                            <div class="card-body ">
                                <dl>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <dt>Product Name</dt>
                                            <dd>{{ $product->name }}</dd>
                                        </div>
                                        <div class="col-md-6">
                                            <dt>Product Type</dt>
                                            <dd>{{ $product_type[$product->type] }}</dd>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <dt>Available Stock</dt>
                                            <dd>{{ $product->available }} Kgs</dd>
                                        </div>
                                        <div class="col-md-6">
                                            <dt>Sale Price Per Kg</dt>
                                            <dd>&#8377; {{ $product->sale_price_per_kg }}</dd>
                                        </div>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card card-warning">
                            <div class="card-header">
                                <h3 class="card-title">Purchase Details</h3>
                            </div>
                            <div class="card-body ">
                                <table class="table table-responsive table-hover text-nowrap" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th style="width: 40%;">Purchase From</th>
                                            <th style="width: 21%;">Purchase Date</th>
                                            <th style="width: 21%;">Purchase Qty</th>
                                            <th style="width: 31%;">Purchase Price(Kg)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(isset($product->productpurchase_detail))
                                            @foreach($product->productpurchase_detail as $dtl)
                                            <tr>
                                                <td style="width: 40%;">{{ ucwords($dtl->purchase_from) }}</td>
                                                <td style="width: 21%;">{{ date('d-m-Y', strtotime($dtl->purchase_date)) }}</td>
                                                <td style="width: 21%;">{{ $dtl->purchase_quantity }} Kgs</td>
                                                <td style="width: 31%;">&#8377; {{ $dtl->purchase_price_per_kg }}</td>
                                            </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

    
@stop

@section('css')
    @parent
@stop

@section('scripts')
    @parent
    
@stop