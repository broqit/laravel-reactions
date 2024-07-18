<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReactionsTable extends Migration
{
    public function up()
    {
        Schema::create('reactions', function (Blueprint $table) {
            $table->id();
            $table->morphs('reactable');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reactions');
    }
}
