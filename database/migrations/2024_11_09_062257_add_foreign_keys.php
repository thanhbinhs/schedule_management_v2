<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeys extends Migration
{
    public function up()
    {
        Schema::table('departments', function (Blueprint $table) {
            // Thêm khóa ngoại cho LeaderDepartmentID tham chiếu đến ProfessorID trong bảng professors
            $table->foreign('LeaderDepartmentID')
                  ->references('ProfessorID')
                  ->on('professors')
                  ->onDelete('set null');
        });

        Schema::table('majors', function (Blueprint $table) {
            // Thêm khóa ngoại cho DepartmentID tham chiếu đến DepartmentID trong bảng departments
            $table->foreign('DepartmentID')
                  ->references('DepartmentID')
                  ->on('departments')
                  ->onDelete('cascade');
        });

        Schema::table('subjects', function (Blueprint $table) {
            // Thêm khóa ngoại cho DepartmentID tham chiếu đến DepartmentID trong bảng departments
            $table->foreign('DepartmentID')
                  ->references('DepartmentID')
                  ->on('departments')
                  ->onDelete('cascade');
        });

        Schema::table('education_programs', function (Blueprint $table) {
            // Thêm khóa ngoại cho MajorID tham chiếu đến MajorID trong bảng majors
            $table->foreign('MajorID')
                  ->references('MajorID')
                  ->on('majors')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('departments', function (Blueprint $table) {
            // Xóa khóa ngoại
            $table->dropForeign(['LeaderDepartmentID']);
        });

        Schema::table('majors', function (Blueprint $table) {
            // Xóa khóa ngoại
            $table->dropForeign(['DepartmentID']);
        });

        Schema::table('subjects', function (Blueprint $table) {
            // Xóa khóa ngoại
            $table->dropForeign(['DepartmentID']);
        });

        Schema::table('education_programs', function (Blueprint $table) {
            // Xóa khóa ngoại
            $table->dropForeign(['MajorID']);
        });
    }
}
