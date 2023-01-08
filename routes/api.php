<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CvController;
use App\Http\Controllers\EmployerController;
use App\Http\Controllers\JobadvertisementController;
use App\Http\Controllers\JobapplicationController;
use App\Http\Controllers\JobseekerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*
|---------------------|
| MUNKAADÓK ÚTVONALAI |
|---------------------|
*/
// ----- PUBLIKUS ÚTVONALAK -----
Route::middleware(['cors'])->group(function () {
    // Új munkaadó létrehozása az adatbázisban.
    Route::post('/employers', [EmployerController::class, 'store'])
        ->name('employers.store');
});
// ----- PRIVÁT ÚTVONALAK -----
Route::group(['middleware' => ['auth:sanctum', 'cors']], function () {
    // Létező munkaadó módosítása az adatbázisban.
    Route::put('/employers/{employer}', [EmployerController::class, 'update'])
        ->name('employers.update');
    // Létező munkaadó törlése az adatbázisból.
    Route::delete('/employers/{employer}', [EmployerController::class, 'destroy'])
        ->name('employers.destroy');
    // Az összes munkaadó lekérdezése az adatbázisból.
    Route::get('/employers', [EmployerController::class, 'index'])
        ->name('employers.index');
    // Létező munkaadó lekérdezése az adatbázisból.
    Route::get('/employers/{employer}', [EmployerController::class, 'show'])
        ->name('employers.show');
    // ----- KOMBINÁLT ÚTVONALAK -----
    // A munkaadó összes álláshirdetésének lekérdezése az adatbázisból.
    Route::get('/employerJobadvertisements/{employer}', [EmployerController::class, 'jobadvertisements'])
        ->name('employers.jobadvertisements');
});

/*
|---------------------------|
| ÁLLÁSHIRDETÉSEK ÚTVONALAI |
|---------------------------|
*/
// ----- PUBLIKUS ÚTVONALAK -----
Route::middleware(['cors'])->group(function () {
    // Az összes álláshirdetés lekérdezése az adatbázisból.
    Route::get('/jobadvertisements', [JobadvertisementController::class, 'index'])
        ->name('jobadvertisements.index');
    // Egy álláshirdetés lekérdezése az adatbázisból.
    Route::get('/jobadvertisements/{jobadvertisement}', [JobadvertisementController::class, 'show'])
        ->name('jobadvertisements.show');
});
// ----- PRIVÁT ÚTVONALAK -----
Route::group(['middleware' => ['auth:sanctum', 'cors']], function () {
    // Új álláshirdetés létrehozása az adatbázisban.
    Route::post('/jobadvertisements', [JobadvertisementController::class, 'store'])
        ->name('jobadvertisements.store');
    // Létező álláshirdetés módosítása az adatbázisban.
    Route::put('/jobadvertisements/{jobadvertisement}', [JobadvertisementController::class, 'update'])
        ->name('jobadvertisements.update');
    // Létező álláshirdetés törlése az adatbázisból.
    Route::delete('/jobadvertisements/{jobadvertisement}', [JobadvertisementController::class, 'destroy'])
        ->name('jobadvertisements.destroy');
});
/*
|------------------------|
| ÁLLÁSKERESŐK ÚTVONALAI |
|------------------------|
*/
// ----- PUBLIKUS ÚTVONALAK -----
Route::middleware(['cors'])->group(function () {
    // Új álláskereső létrehozása az adatbázisban.
    Route::post('/jobseekers', [JobseekerController::class, 'store'])
        ->name('jobseekers.store');
});
// ----- PRIVÁT ÚTVONALAK -----
Route::group(['middleware' => ['auth:sanctum', 'cors']], function () {
    // Létező álláskereső módosítása az adatbázisban.
    Route::put('/jobseekers/{jobseeker}', [JobseekerController::class, 'update'])
        ->name('jobseekers.update');
    // Létező álláskereső törlése az adatbázisból.
    Route::delete('/jobseekers/{jobseeker}', [JobseekerController::class, 'destroy'])
        ->name('jobseekers.destroy');
    // Az összes álláskereső lekérdezése az adatbázisból.
    Route::get('/jobseekers', [JobseekerController::class, 'index'])
        ->name('jobseekers.index');
    // Létező álláskereső lekérdezése az adatbázisból.
    Route::get('/jobseekers/{jobseeker}', [JobseekerController::class, 'show'])
        ->name('jobseekers.show');
    // ----- KOMBINÁLT ÚTVONALAK -----
    // Az álláskereső összes önéletrajzának lekérdezése.
    Route::get('/jobseekerCvs/{jobseeker}', [JobseekerController::class, 'cvs'])
        ->name('jobseekers.cvs');
    // Az álláskereső összes jelentkezésének lekérdezése.
    Route::get('/jobseekerJobapplications/{jobseeker}/', [JobseekerController::class, 'jobapplications'])
        ->name('jobseekers.jobapplications');
    // Az álláskereső adott álláshirdetésre történő jelentkezésének lekérdezése az adatbázisból.
    Route::get('/jobseekerJobapplications/{jobseeker}/{jobadvertisement}', [JobseekerController::class, 'jobseeker_jobapplications'])
        ->name('jobseekers.jobseeker_jobapplications');
});

/*
|------------------------|
| ÖNÉLETRAJZOK ÚTVONALAI |
|------------------------|
*/
// ----- PRIVÁT ÚTVONALAK -----
Route::group(['middleware' => ['auth:sanctum', 'cors']], function () {
    // Új önéletrajz létrehozása az adatbázisban.
    Route::post('/cvs', [CvController::class, 'store'])
        ->name('cvs.store');
    // Létező önéletrajz lekérdezése az adatbázisból.
    Route::get('/cvs/{cv}', [CvController::class, 'show'])
        ->name('cvs.show');
    // Létező önéletrajz törlése az adatbázisból.
    Route::delete('/cvs/{cv}', [CvController::class, 'destroy'])
        ->name('cvs.destroy');
});

/*
|-------------------------|
| JELENTKEZÉSEK ÚTVONALAI |
|-------------------------|
*/
// ----- PRIVÁT ÚTVONALAK -----
Route::group(['middleware' => ['auth:sanctum', 'cors']], function () {
    // Új jelentkezés létrehozása.
    Route::post('/jobapplications', [JobapplicationController::class, 'store'])
        ->name('jobapplications.store');
    // Az összes jelentkezés lekérdezése.
    Route::get('/jobapplications', [JobapplicationController::class, 'index'])
        ->name('jobapplications.index');
    // Létező jelentkezés törlése.
    Route::delete('/jobapplications/{jobapplication}', [JobapplicationController::class, 'destroy'])
        ->name('jobapplications.destroy');
    // Jelentkezés állapotának módosítása.
    Route::patch('/jobapplications/{jobapplication}', [JobapplicationController::class, 'status'])
        ->name('jobapplications.status');
});

/*
|-------------------------|
| AUTENTIKÁCIÓS ÚTVONALAK |
|-------------------------|
*/
// ----- PUBLIKUS ÚTVONALAK -----
Route::middleware(['cors'])->group(function () {
    // A felhasználó bejelentkeztetése a szerveren.
    Route::post('/login', [AuthController::class, 'login'])
        ->name('login');
});
// ----- PRIVÁT ÚTVONALAK -----
Route::group(['middleware' => ['auth:sanctum', 'cors']], function () {
    // A felhasználó kijelentkeztetése a szerveren.
    Route::post('/logout', [AuthController::class, 'logout']);
});
