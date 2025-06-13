<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Rename kelas to jurusan
            $table->renameColumn('kelas', 'jurusan');
            
            // Add new fields
            $table->string('nis')->nullable()->after('email');
            $table->string('nip')->nullable()->after('nis');
            $table->enum('status', ['pending', 'active', 'rejected'])->default('pending')->after('role');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('jurusan', 'kelas');
            $table->dropColumn(['nis', 'nip', 'status']);
        });
    }
}
