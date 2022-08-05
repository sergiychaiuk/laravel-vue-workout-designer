<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')->group(function () {

    Route::post('register', 'AuthController@register');

    Route::post('login', 'AuthController@login');

    Route::get('refresh', 'AuthController@refresh');

    Route::middleware('auth:api')->group(function () {

        Route::get('user', 'AuthController@user');

        Route::post('logout', 'AuthController@logout');
    });
});

Route::group(['middleware' => 'auth:api'], function() {
    Route::post('account/update', 'UserController@update');
});

Route::get('muscle_groups', 'MuscleGroupController@index');

Route::get('sports_projectiles', 'SportsProjectileController@index');

Route::get('exercises', 'ExerciseController@index');
Route::get('/exercises/{exercise}', 'ExerciseController@show');
Route::post('/exercises', 'ExerciseController@store');
Route::post('/exercises/{exercise}', 'ExerciseController@update');

Route::get('muscles', 'MuscleController@index');
Route::get('/muscles/{muscle}', 'MuscleController@show');
Route::post('/muscles', 'MuscleController@store');
Route::post('/muscles/{muscle}', 'MuscleController@update');

Route::get('workouts', 'WorkoutController@index');
Route::get('/workouts/{workout}', 'WorkoutController@show');

Route::get('/account/workouts', 'UserController@workouts');
Route::get('/account/workouts/add/{workout}', 'UserController@addWorkout');

Route::get('workout_styles', 'WorkoutStyleController@index');

Route::delete('/account/workouts/{workout}', 'UserController@deleteWorkout');
Route::post('/account/workouts', 'UserController@createWorkout');

Route::put('/workouts/update_order_exercises', 'WorkoutController@updateOrderExercises');

Route::post('/workouts/{exercisesGroup}/exercises', 'WorkoutController@createExercise');
Route::delete('/workouts/{id}', 'WorkoutController@deleteExercise');
Route::put('/workouts/exercise', 'WorkoutController@editExercise');
Route::put('/account/workouts/{workout}', 'UserController@editWorkout');
