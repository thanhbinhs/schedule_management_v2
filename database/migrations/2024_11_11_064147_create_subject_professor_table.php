<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubjectProfessorTable extends Migration
{
    public function up()
    {
        Schema::create('subject_professor', function (Blueprint $table) {
            $table->unsignedBigInteger('SubjectID');
            $table->string('ProfessorID');
            $table->timestamps();

            $table->foreign('SubjectID')->references('SubjectID')->on('subjects')->onDelete('cascade');
            $table->foreign('ProfessorID')->references('ProfessorID')->on('professors')->onDelete('cascade');

            $table->primary(['SubjectID', 'ProfessorID']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('subject_professor');
    }
}
