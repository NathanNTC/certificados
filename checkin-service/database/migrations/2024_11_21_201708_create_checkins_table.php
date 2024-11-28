<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckinsTable extends Migration
{
    public function up()
    {
        Schema::create('checkins', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id'); // Relaciona com o evento
            $table->string('email'); // Usuário que realizou o check-in
            $table->string('codigo'); // Código do evento usado no check-in
            $table->timestamps();

            // Chave estrangeira
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('checkins');
    }
}
