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
            $table->boolean('status');
            $table->boolean('form_status');
            $table->boolean('done');
            $table->boolean('is_readonly');
            $table->string('message')->nullable();
            $table->bigInteger('index');
            $table->bigInteger('position_x');
            $table->bigInteger('position_y');
            $table->bigInteger('window_y');
            $table->bigInteger('position_form_x');
            $table->bigInteger('position_form_y');
            $table->unsignedBigInteger('page_id');
            $table->foreign('page_id')->references('id')->on('pages');
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
