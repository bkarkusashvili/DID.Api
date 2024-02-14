<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivatedSitesTable extends Migration
{
    public function up()
    {
        Schema::create('activated_sites', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('site_id');
            $table->string('payment_transaction_id');
            $table->integer('duration_month');
            $table->timestamps();
            
            // Define foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('site_id')->references('id')->on('sites')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('activated_sites');
    }
}
