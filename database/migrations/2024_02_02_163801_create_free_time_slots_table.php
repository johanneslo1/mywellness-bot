<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('free_time_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('search_request_id')
                ->constrained('search_requests')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->date('date');
            $table->string('time')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->string('url')->nullable();
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
        Schema::dropIfExists('free_time_slots');
    }
};
