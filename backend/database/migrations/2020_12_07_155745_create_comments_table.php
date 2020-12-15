<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('status')->default(true);
            $table->boolean('form_status')->nullable();
            $table->boolean('done')->nullable();
            $table->boolean('is_readonly')->nullable();
            $table->string('message')->nullable();
            $table->bigInteger('index')->nullable();
            $table->bigInteger('position_x')->nullable();
            $table->bigInteger('position_y')->nullable();
            $table->bigInteger('window_y')->nullable();
            $table->bigInteger('position_form_x')->nullable();
            $table->bigInteger('position_form_y')->nullable();
            $table->unsignedBigInteger('page_id')->nullable();
            $table->foreign('page_id')->references('id')->on('pages');
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
        Schema::dropIfExists('comments');
    }
}
