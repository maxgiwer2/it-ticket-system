<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketSurveysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_surveys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->unique()->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('rating_speed');   // ความรวดเร็ว 1-5
            $table->unsignedTinyInteger('rating_manner');  // ความสุภาพของเจ้าหน้าที่ 1-5
            $table->unsignedTinyInteger('rating_quality'); // คุณภาพ/ผลของงาน 1-5
            $table->text('comment')->nullable();
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
        Schema::dropIfExists('ticket_surveys');
    }
}
