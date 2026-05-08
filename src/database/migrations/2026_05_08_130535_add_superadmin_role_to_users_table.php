<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSuperadminRoleToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('superadmin', 'admin', 'staff') DEFAULT 'staff'");
    }

    public function down()
    {
        \DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'staff') DEFAULT 'staff'");
    }
}
