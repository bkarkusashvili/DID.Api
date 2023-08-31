<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubscriptionFieldsToSitesTable extends Migration
{
    public function up()
    {
        Schema::table('sites', function (Blueprint $table) {
            $table->integer('subscription_product_id')->nullable();
            $table->integer('subscription_table_id')->nullable();
        });
    }

    public function down()
    {
        Schema::table('sites', function (Blueprint $table) {
            $table->dropColumn('subscription_product_id');
            $table->dropColumn('subscription_table_id');
        });
    }
}
