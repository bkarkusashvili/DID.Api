<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TemplateController;
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

$adminResources = [
    'category' => CategoryController::class,
    'template' => TemplateController::class,
];

Route::prefix('admin')->group(function () use ($adminResources) {
    // collect($adminResources)->each(function ($item, $key) {
    //     Route::get($key . '/export', [$item, 'export'])->name($key . '.export');
    //     Route::post($key . '/column/{id}/{column}/{value}', [$item, 'column'])->name($key . '.column');
    //     Route::post($key . '/updateRow', [$item, 'updateRow'])->name($key . '.updateRow');
    // });

    Route::resources($adminResources);
});
