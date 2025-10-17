<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
})->name('welcome');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/settings', [App\Http\Controllers\SettingsController::class, 'edit'])->name('settings.edit');
Route::patch('/settings', [App\Http\Controllers\SettingsController::class, 'update'])->name('settings.update');

// Users Management Routes
Route::resource('users', App\Http\Controllers\UserController::class);

Route::prefix('master')->name('master.')->group(function () {
    // Worker routes - specific routes must come BEFORE resource route
    Route::get('worker/download-template', [App\Http\Controllers\WorkerController::class, 'downloadTemplate'])->name('worker.download-template');
    Route::post('worker/import', [App\Http\Controllers\WorkerController::class, 'import'])->name('worker.import');
    Route::delete('worker/delete-all', [App\Http\Controllers\WorkerController::class, 'deleteAll'])->name('worker.delete-all');
    Route::resource('worker', App\Http\Controllers\WorkerController::class);
    // Route::get('/page-worker', [WorkerController::class, 'index'])->name('Page_Worker');

    // Journal Pekerjaan
    Route::get('journal/download-template', [App\Http\Controllers\JournalWorkerController::class, 'downloadTemplate'])->name('journal.download-template');
    Route::post('journal/import', [App\Http\Controllers\JournalWorkerController::class, 'import'])->name('journal.import');
    Route::delete('journal/delete-all', [App\Http\Controllers\JournalWorkerController::class, 'deleteAll'])->name('journal.delete-all');
    Route::resource('journal', App\Http\Controllers\JournalWorkerController::class);

    // Material routes - specific routes must come BEFORE resource route
    Route::get('material/download-template', [App\Http\Controllers\MaterialController::class, 'downloadTemplate'])->name('material.download-template');
    Route::post('material/import', [App\Http\Controllers\MaterialController::class, 'import'])->name('material.import');
    Route::delete('material/delete-all', [App\Http\Controllers\MaterialController::class, 'deleteAll'])->name('material.delete-all');
    Route::resource('material', App\Http\Controllers\MaterialController::class);

    // Project routes - specific routes must come BEFORE resource route
    Route::get('project/download-template', [App\Http\Controllers\ProjectController::class, 'downloadTemplate'])->name('project.download-template');
    Route::post('project/import', [App\Http\Controllers\ProjectController::class, 'import'])->name('project.import');
    Route::resource('project', App\Http\Controllers\ProjectController::class);

    // Equipment routes - specific routes must come BEFORE resource route
    Route::get('equipment/download-template', [App\Http\Controllers\EquipmentController::class, 'downloadTemplate'])->name('equipment.download-template');
    Route::post('equipment/import', [App\Http\Controllers\EquipmentController::class, 'import'])->name('equipment.import');
    Route::delete('equipment/delete-all', [App\Http\Controllers\EquipmentController::class, 'deleteAll'])->name('equipment.delete-all');
    Route::resource('equipment', App\Http\Controllers\EquipmentController::class);

    // Estimation routes - specific routes must come BEFORE resource route
    Route::delete('estimation/delete-all', [App\Http\Controllers\EstimationController::class, 'deleteAll'])->name('estimation.delete-all');
    Route::resource('estimation', \App\Http\Controllers\EstimationController::class);
    Route::resource('category', App\Http\Controllers\CategoryController::class);
});

// Service Routes - Specific routes must come BEFORE resource route
Route::get('service/get-hpp-data', [App\Http\Controllers\ServiceController::class, 'getHppData'])->name('service.get-hpp-data');
Route::resource('service', App\Http\Controllers\ServiceController::class)->middleware('service.cache');
Route::post('service/{service}/submit', [App\Http\Controllers\ServiceController::class, 'submit'])->name('service.submit')->middleware('service.cache');
Route::post('service/{service}/approve', [App\Http\Controllers\ServiceController::class, 'approve'])->name('service.approve')->middleware('service.cache');
Route::post('service/{service}/comment', [App\Http\Controllers\ServiceController::class, 'addComment'])->name('service.comment');
Route::post('service/{service}/reject', [App\Http\Controllers\ServiceController::class, 'reject'])->name('service.reject')->middleware('service.cache');
Route::post('service/{service}/generate', [App\Http\Controllers\ServiceController::class, 'generate'])->name('service.generate')->middleware('service.cache');
Route::post('service/{service}/generate-form/{formNumber}', [App\Http\Controllers\ServiceController::class, 'generateForm'])->name('service.generate-form')->middleware('service.cache');
Route::get('service/{service}/export/excel/{classification}', [App\Http\Controllers\ServiceController::class, 'exportExcel'])->name('service.export.excel');
Route::get('service/{service}/debug-hpp-items', [App\Http\Controllers\ServiceController::class, 'debugHppItems'])->name('service.debug-hpp-items');
Route::post('service/{service}/regenerate-form-34', [App\Http\Controllers\ServiceController::class, 'regenerateForm34'])->name('service.regenerate-form-34')->middleware('service.cache');

// HPP Routes - Specific routes must come BEFORE resource route
Route::get('hpp/get-ahs-data', [App\Http\Controllers\HppController::class, 'getAhsDataAjax'])->name('hpp.get-ahs-data');
Route::get('hpp/get-ahs-data-only/{projectType}', [App\Http\Controllers\HppController::class, 'getAhsDataOnly'])->name('hpp.get-ahs-data-only');
Route::get('hpp/get-ahs-items/{estimationId}/{projectType}', [App\Http\Controllers\HppController::class, 'getAhsItems'])->name('hpp.get-ahs-items');
Route::get('hpp/{hpp}/get-estimation-items', [App\Http\Controllers\HppController::class, 'getEstimationItems'])->name('hpp.get-estimation-items');

// HPP Approval Flow Routes
Route::post('hpp/{hpp}/submit', [App\Http\Controllers\HppController::class, 'submit'])->name('hpp.submit');
Route::post('hpp/{hpp}/approve', [App\Http\Controllers\HppController::class, 'approve'])->name('hpp.approve');
Route::post('hpp/{hpp}/reject', [App\Http\Controllers\HppController::class, 'reject'])->name('hpp.reject');
Route::post('hpp/{hpp}/comment', [App\Http\Controllers\HppController::class, 'addComment'])->name('hpp.comment');
Route::post('hpp/{hpp}/start-review', [App\Http\Controllers\HppController::class, 'startReview'])->name('hpp.start-review');

Route::resource('hpp', App\Http\Controllers\HppController::class);

Route::view('support', 'support')->name('support');
