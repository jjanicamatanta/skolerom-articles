<?php

use App\Http\Controllers\ArticleController;
use App\Models\GrepGoalRelationModel;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('filterarticle/v1/post/', [ArticleController::class, 'index']);

Route::get('post/{post}/childs/', [ArticleController::class, 'getChildArticles']);


// Route::get('test', function () {













//     return;
//     $url = "https://mi.cibertec.edu.pe/MatriculaExtension/ApiClient/ListarProductoWeb?page=0";

//     $apiReponse = json_decode(file_get_contents($url), false);
//     // dd($courses);
//     $total = $apiReponse->data->pagination->totalPages;

//     $courses = new Collection();


//     for ($i = 1; $i <= $total; $i++) {
//         $url = "https://mi.cibertec.edu.pe/MatriculaExtension/ApiClient/ListarProductoWeb?page=$i";
//         $data = json_decode(file_get_contents($url), false);

//         foreach ($data->data->data as $item)
//             $courses->push($item);
//     }

//     dd($courses->filter(function ($course) {
//         echo $course->AgrupadorProductoWeb;
//         if (in_array($course->AgrupadorProductoWeb, ['CERTIFICACIONES', 'TECNOLOGÍA'])) {
//             if (500 <= $course->Precio && $course->Precio <= 1300) {
//                 return true;
//             }
//         }
//         return false;
//     })->sortBy('Precio'));
// });
