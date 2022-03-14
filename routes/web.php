<?php

use Illuminate\Support\Facades\Route;
use App\Point;
// import the storage facade
use Illuminate\Support\Facades\Storage;

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

Route::get('/', function () {
    $points = Point::select('*')->get();
    return view('welcome', ['points' => $points]);
});

Route::get('/images/{id_chat}', function ($id_chat) {
   
      $allFiles = Storage::disk('public')->files();
    //   dd($allFiles);
      $filesFiltered = [];
      foreach($allFiles as $file){
        if(strpos($file,$id_chat) !== false){
            $filesFiltered[] = Storage::disk('public')->get($file);  
        }
      }

      return $filesFiltered;
});

Route::match(['get', 'post'], '/botman', 'BotManController@handle');
Route::get('/botman/tinker', 'BotManController@tinker');
