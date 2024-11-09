<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ProfessorController;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\EducationProgramController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ScheduleDepartmentController;
use App\Http\Controllers\ScheduleProfessorController;



Route::middleware(['auth', 'pdt'])->prefix('pdt')->group(function () {
    Route::get('/dashboard', [DepartmentController::class, 'index'])->name('pdt.departments.index');

    // Routes CRUD cho departments
    Route::get('/departments/create', [DepartmentController::class, 'create'])->name('pdt.departments.create');
    Route::post('/departments', [DepartmentController::class, 'store'])->name('pdt.departments.store');
    Route::get('/departments/{id}/edit', [DepartmentController::class, 'edit'])->name('pdt.departments.edit');
    Route::put('/departments/{id}', [DepartmentController::class, 'update'])->name('pdt.departments.update');
    Route::delete('/departments/{id}', [DepartmentController::class, 'destroy'])->name('pdt.departments.destroy');

    Route::resource('rooms', RoomController::class, ['as' => 'pdt']); // Quản lý phòng học

    // Routes CRUD cho Schedules
    Route::resource('schedules', ScheduleController::class, ['as' => 'pdt']);

    // Route để tạo thời khóa biểu tự động
    Route::post('schedules/generate', [ScheduleController::class, 'generate'])->name('pdt.schedules.generate');
    Route::post('/pdt/schedules/deleteAll', [ScheduleController::class, 'deleteAll'])->name('pdt.schedules.deleteAll');
});

// Routes dành cho 'department' với middleware 'auth' và 'department'
Route::middleware(['auth', 'department'])->prefix('department')->group(function () {
    Route::get('/dashboard', [DepartmentController::class, 'index'])->name('department.professors.index');

    // Routes CRUD cho Professors
    Route::resource('professors', ProfessorController::class, ['as' => 'department']);

    // Routes CRUD cho Majors
    Route::resource('majors', MajorController::class, ['as' => 'department']);

    // Routes CRUD cho Education Programs
    Route::resource('education_programs', EducationProgramController::class, ['as' => 'department']);

    // Routes CRUD cho Subjects
    Route::resource('subjects', SubjectController::class, ['as' => 'department']);
     // Routes CRUD cho Schedules
     Route::resource('schedules', ScheduleDepartmentController::class, ['as' => 'department']);

});

Route::middleware(['auth', 'professor'])->prefix('professor')->group(function () {
    Route::get('/dashboard', [ProfessorController::class, 'index'])->name('professor.schedules.index');

    // Routes CRUD cho Schedules
    Route::resource('schedules', ScheduleProfessorController::class, ['as' => 'professor']);
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');


Route::get('/', function () {
    return view('welcome');
});
