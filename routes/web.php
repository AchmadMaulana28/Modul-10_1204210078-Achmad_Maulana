<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmployeeController;
// use GuzzleHttp\Psr7\Request;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Stmt\Return_;
use Symfony\Component\HttpKernel\Profiler\Profile;

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

Route::get('home', HomeController::class)->name('home');

Route::get('profile', ProfileController::class)->name('profile');

Route::resource('employees', EmployeeController::class);






        /*
            |--------------------------------------------------------------------------|
            | Fungsi dari Routes Di bawah ini adalah:                                       |
            |--------------------------------------------------------------------------|

            |       Route untuk mengambil gambar dari File directory dan dimasukin ke project
                route yang menangani permintaan GET ke URL /local-disk Ketika permintaan ini diterima,
                route tersebut akan menggunakan metode Storage::disk('local')->put()
                untuk menyimpan sebuah file teks ke dalam direktori lokal (local) pada aplikasi Laravel.

            |File yang disimpan akan bernama local-example.txt
             dan akan berisi konten This is local example content

            |
            */

        Route::get('/local-disk', function() {
            Storage::disk('local')->put('local-example.txt', 'This is local example content');
            return asset('storage/local-example.txt');
        });

        Route::get('/public-disk', function() {
            Storage::disk('public')->put('public-example.txt', 'This is public example content');
            return asset('storage/public-example.txt');
        });

        Route::get('/retrieve-local-file', function() {
            if (Storage::disk('local')->exists('local-example.txt')) {
                $contents = Storage::disk('local')->get('local-example.txt');
            } else {
                $contents = 'File does not exist';
            }

            return $contents;
        });


        //
        Route::get('/retrieve-public-file', function() {
            if (Storage::disk('public')->exists('public-example.txt')) {
                $contents = Storage::disk('public')->get('public-example.txt');
            } else {
                $contents = 'File does not exist';
            }

            return $contents;
        });


        //
        Route::get('/download-local-file', function() {
            return Storage::download('local-example.txt', 'local file');
        });


        //
        Route::get('/download-public-file', function() {
            return Storage::download('public/public-example.txt', 'public file');
        });


        //
        Route::get('/file-url', function() {
            $url = Storage::url('local-example.txt');
            return $url;
        });


        //
        Route::get('/file-size', function() {
            $size = Storage::size('local-example.txt');
            return $size;
        });

        //
        Route::get('/file-path', function() {
            $path = Storage::path('local-example.txt');
            return $path;
        });

        //
        Route::get('/upload-example', function() {
            return view('upload_example');
        });


        //
        Route::post('/upload-example', function(Request $request) {
            $path = $request->file('avatar')->store('public');
            return $path;
        })->name('upload_example');


        //
        Route::get('/delete-local-file', function(Request $request) {
            Storage::disk('local')->delete('local-example.txt');
            return 'Deleted';
        });

        //
        Route::get('/delete-public-file', function(Request $request) {
            Storage::disk('public')->delete('public-example.txt');
            return 'Deleted';
        });

        //
        Route::get('download-file/{employeeId}',
                    [EmployeeController::class, 'downloadFile']
                   )->name('employees.downloadFile');


    // Route untuk  memanggil data dari table pada excel table pada db

    Route::get('getEmployees', [EmployeeController::class, 'getData'])
                ->name('employees.getData');

    // Route dibawah ini untuk mengeksport data ke excel 
    Route::get('exportExcel', [EmployeeController::class, 'exportExcel'])->name('employees.exportExcel');

    // Route dibawah ini untuk mengeksport data ke pdf file 
    Route::get('exportPdf', [EmployeeController::class, 'exportPdf'])->name('employees.exportPdf');