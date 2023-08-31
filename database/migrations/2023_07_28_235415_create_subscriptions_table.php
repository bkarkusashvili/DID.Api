<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('template_id');
            $table->integer('duration');
            $table->timestamps();

            // Add any additional constraints or indexes here if needed
        });
    }

    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
}
