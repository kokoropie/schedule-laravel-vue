<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\ScheduleDetailController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::get('/info', function() {
//     phpinfo();
// });


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/data/export', [DataController::class, 'export'])->name('profile.data.export');
    Route::post('/profile/data/import', [DataController::class, 'import'])->name('profile.data.import');

    Route::resource('/teacher', TeacherController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('/subject', SubjectController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('/schedule', ScheduleController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
    Route::resource('/schedule.detail', ScheduleDetailController::class)->only(['store', 'update', 'destroy']);

    Route::get('/{schedule:schedule_id}/{day?}', [HomeController::class, 'schedule'])->name('home.schedule')->where('schedule', '[0-9]+')->where('day', '[0-9]{4}-[0-9]{2}-[0-9]{2}');
    Route::get('/{day?}', [HomeController::class, 'index'])->name('home')->where('day', '[0-9]{4}-[0-9]{2}-[0-9]{2}');
});

require __DIR__.'/auth.php';
