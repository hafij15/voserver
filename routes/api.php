<?php
use App\Appointment;
use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::put('update_prescription/{id}', function(Request $request, $id) {
    $article = Appointment::findOrFail($id);
    $article->update(array(
        'prescribe_medicines' => $request->prescribe_medicines,
        'isServiced' => 1
    ));

    return $article;
});
