<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePurchaseQuantityPriceInProductpurchaseDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('productpurchase_details', function (Blueprint $table) {
            $table->bigInteger('purchase_quantity')->nullable()->change();
        });
    }
}
