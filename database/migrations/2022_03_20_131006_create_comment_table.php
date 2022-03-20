<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comment', function (Blueprint $table) {
            $table->id()->unsigned();
            $table->bigInteger('news_id')->unsigned()->notNullable();
            $table->text('description')->notNullable();
            $table->string('title', 255)->notNullable();
            $table->boolean('status')->default(1)->notNullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('news_id')->references('id')->on('news');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comment');
    }
}
