<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfessorsTable extends Migration
{
    public function up()
    {
        Schema::create('professors', function (Blueprint $table) {
            $table->string('ProfessorID')->primary();
            $table->string('ProfessorName');
            $table->string('ProfessorGmail')->unique();
            $table->string('ProfessorPhone')->unique();
            $table->boolean('isLeaderDepartment')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('professors');
    }
}
