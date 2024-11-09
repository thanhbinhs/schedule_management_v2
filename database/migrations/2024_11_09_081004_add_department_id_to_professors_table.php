<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDepartmentIdToProfessorsTable extends Migration
{
    public function up()
    {
        Schema::table('professors', function (Blueprint $table) {
            $table->string('DepartmentID')->after('ProfessorPhone');
            $table->foreign('DepartmentID')->references('DepartmentID')->on('departments')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('professors', function (Blueprint $table) {
            $table->dropForeign(['DepartmentID']);
            $table->dropColumn('DepartmentID');
        });
    }
}
