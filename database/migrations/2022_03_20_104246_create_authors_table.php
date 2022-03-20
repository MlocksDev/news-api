<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('authors', function (Blueprint $table) {

            $table->id()->unsigned();
            $table->bigInteger('users_id')->unsigned()->notNullable();
            $table->string('name', 45)->notNullable();
            $table->string('lastname', 60)->notNullable();
            $table->enum('gender', ['F', 'M'])->notNullable();
            $table->boolean('active')->default(1)->notNullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('users_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('authors');
    }
}
