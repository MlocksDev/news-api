<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImageNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('image_news', function (Blueprint $table) {
            $table->id()->unsigned();
            $table->bigInteger('news_id')->unsigned()->notNullable();
            $table->text('image', 100)->notNullable();
            $table->string('description', 255)->notNullable();
            $table->boolean('active')->default(1)->notNullable();
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
        Schema::dropIfExists('image_news');
    }
}
