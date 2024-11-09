<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ProfessorController;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\EducationProgramController;
use App\Http\Controllers\SubjectController;

// Group routes với prefix 'accounts'
Route::apiResource('accounts', AccountController::class);

// Group routes với prefix 'departments'
Route::apiResource('departments', DepartmentController::class);

// Group routes với prefix 'professors'
Route::apiResource('professors', ProfessorController::class);

// Group routes với prefix 'majors'
Route::apiResource('majors', MajorController::class);

// Group routes với prefix 'education-programs'
Route::apiResource('education-programs', EducationProgramController::class);

// Group routes với prefix 'subjects'
Route::apiResource('subjects', SubjectController::class);
