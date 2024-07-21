<?php

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/events/{filename}', function ($filename) {
    $path = 'public/events/' . $filename;

    // Check if file exists in storage
    if (!Storage::disk('public')->exists($path)) {
        abort(404);
    }

    // Get file content and MIME type
    $file = Storage::disk('public')->get($path);
    $type = Storage::disk('public')->mimeType($path);

    // Create and return the response
    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});
