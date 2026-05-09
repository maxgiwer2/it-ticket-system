<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateJobTypesAndCreateCollaborators extends Migration
{
    public function up()
    {
        Schema::table('job_types', function (Blueprint $table) {
            $table->string('category')->default('ทั่วไป')->after('name');
        });

        Schema::create('ticket_collaborators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ticket_collaborators');
        Schema::table('job_types', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
}
