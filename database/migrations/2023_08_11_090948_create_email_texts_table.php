<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailTextsTable extends Migration
{
    public function up()
    {
        Schema::create('email_texts', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('content');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('email_texts');
    }
}
