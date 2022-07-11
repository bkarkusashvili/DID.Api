<?php

use App\Models\Social;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('socials', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->smallInteger('type')->nullable();
            $table->json('keywords')->nullable();
            $table->json('photos')->nullable();
            $table->json('materials')->nullable();
            $table->string('suggestedPhoto')->nullable();
            $table->text('text')->nullable();
            $table->text('photoText')->nullable();
            $table->text('comment')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('socials');
    }
}
