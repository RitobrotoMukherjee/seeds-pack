<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeHamaliChargesAndAmountInBillings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('billings', function (Blueprint $table) {
            $table->bigInteger('hamali_charges')->nullable()->change();
            $table->bigInteger('net_amount')->nullable()->change();
        });
        
        Schema::table('invoice_details', function (Blueprint $table) {
            $table->bigInteger('unit_price')->nullable()->change();
        });
        
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('sale_price_per_kg', 16, 2)->nullable()->change();
        });
        
        Schema::table('productpurchase_details', function (Blueprint $table) {
            $table->decimal('purchase_price_per_kg', 16, 2)->nullable()->change();
        });
    }
}
