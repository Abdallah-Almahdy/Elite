<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Back\PrintJobController;


Route::get('/test-print-job', [App\Http\Controllers\test\TestPrintJobController::class, 'testOrder']);


Route::get('/print-jobs', [PrintJobController::class, 'index']);
Route::post('/print-jobs/{job}/in-progress', [PrintJobController::class, 'markAsInProgress']);
Route::post('/print-jobs/{job}/done', [PrintJobController::class, 'markAsDone']);
