<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->string('RoomID');
            $table->date('date');
            $table->unsignedTinyInteger('session_number'); // 1-4 tương ứng với các ca học
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->string('professor_id')->nullable();
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('RoomID')->references('RoomID')->on('rooms')->onDelete('cascade');
            $table->foreign('subject_id')->references('SubjectID')->on('subjects')->onDelete('set null');
            $table->foreign('professor_id')->references('ProfessorID')->on('professors')->onDelete('set null');

            // Ràng buộc duy nhất để tránh trùng lịch cho cùng một phòng, ngày và ca học
            $table->unique(['RoomID', 'date', 'session_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
}
