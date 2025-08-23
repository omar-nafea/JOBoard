<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\JobVacancyController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
  return view('welcome');
});

Route::middleware('auth')->group(function () {
  Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
  Route::get('/job-vacancies/{id}', [JobVacancyController::class, 'show'])->name('job-vacancies.show');
  Route::get('/job-vacancies/{id}/apply', [JobVacancyController::class, 'apply'])->name('job-vacancies.apply');
  Route::post('/job-vacancies/{id}/apply', [JobVacancyController::class, 'processApplication'])->name('job-vacancies.process-application');
  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::get('/job-applications', [JobApplicationController::class, 'index'])->name('job-applications.index');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
