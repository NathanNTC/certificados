<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('inscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id'); // ID do evento
            $table->string('user_email'); // Email do usuário
            $table->timestamps();

            // Índices e restrições
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade'); // Chave estrangeira
            $table->unique(['event_id', 'user_email']); // Garantir que não haja duplicatas
        });
    }

    public function down()
    {
        Schema::dropIfExists('inscriptions');
    }
};
