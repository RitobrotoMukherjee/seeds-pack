<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropCustomersColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function(Blueprint $table) {
            $table->dropColumn(['mobile', 'address', 'pincode']);
        });

        Schema::table('billings', function(Blueprint $table) {
            $table->dropColumn(['lr_no', 'destination']);
        });

        Schema::table('payments', function(Blueprint $table) {
            $table->string('payment_mode', 50)->after('payment_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
