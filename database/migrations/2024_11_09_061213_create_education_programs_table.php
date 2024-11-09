<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEducationProgramsTable extends Migration
{
    public function up()
    {
        Schema::create('education_programs', function (Blueprint $table) {
            $table->id('EducationProgramID');
            $table->string('EducationProgramName');
            $table->unsignedBigInteger('MajorID');
            $table->json('SubjectList');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('education_programs');
    }
}
