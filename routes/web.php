<?php

use App\Http\Controllers\ResultsController;
use App\Livewire\ShowResults;
use App\Livewire\UploadForm;
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

Route::get('/', UploadForm::class);
Route::get('/results/{jobId}', ShowResults::class);
