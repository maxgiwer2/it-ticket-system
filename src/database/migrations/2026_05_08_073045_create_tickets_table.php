<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();
            $table->string('requester_name');
            $table->foreignId('department_id')->constrained();
            $table->string('phone');
            $table->foreignId('job_type_id')->constrained();
            $table->text('details');
            $table->enum('status', ['pending', 'processing', 'completed', 'more_info'])->default('pending');
            $table->string('attachment_path')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users');
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
        Schema::dropIfExists('tickets');
    }
}
