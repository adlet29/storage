<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('moves', function (Blueprint $table) {
            $table->id();
            $table->integer('storage_id');
            $table->string('name');
            $table->string('series');
            $table->string('number');
            $table->string('sender_fio');
            $table->string('reciver_fio')->default(null);
            $table->date('send_date');
            $table->date('accept_date')->default(null);
            $table->string('status');
            $table->string('address');
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
        Schema::dropIfExists('moves');
    }
}
