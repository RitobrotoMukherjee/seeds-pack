<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateColumnsAndAssociationsInReturnProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('return_products', function (Blueprint $table) {
            $table->dropConstrainedForeignId('billing_id');
            $table->foreignId('customer_id')->after('id')->constrained()->onDelete('cascade');
        });
    }
}
