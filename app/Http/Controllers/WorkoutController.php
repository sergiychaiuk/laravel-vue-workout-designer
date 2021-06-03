<?php

namespace App\Http\Controllers;

use App\Models\Workout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkoutController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
//        $workouts = Workout::with([
//            'workoutStyle',
//            'exercisesGroups',
//            'exercisesGroups.exercises',
//            'exercisesGroups.exercises.sportsProjectile',
//            'exercisesGroups.exercises.muscleGroup',
//            'exercisesGroups.exercises.muscles',
//            'users' => function ($query) {
//                $query->where('users.role', '=', 1);
//            }
//        ])->get();

        $workouts = Workout::with([
            'workoutStyle',
            'exercisesGroups',
            'exercisesGroups.exercises',
            'exercisesGroups.exercises.sportsProjectile',
            'exercisesGroups.exercises.muscleGroup',
            'exercisesGroups.exercises.muscles',
            'users'
        ])->whereHas('users', function ($query) {
            return $query->where('role', '=', 2);
        })->get();

        return response()->json($workouts, 200);
    }

    public function show(Workout $workout): \Illuminate\Http\JsonResponse
    {
        $response['workout'] = Workout::with([
            'workoutStyle',
            'exercisesGroups',
            'exercisesGroups.exercises',
            'exercisesGroups.exercises.sportsProjectile',
            'exercisesGroups.exercises.muscleGroup',
            'exercisesGroups.exercises.muscles',
            'users'
        ])->where('id', '=', $workout->id)->first();

        return response()->json($response, 200);
    }
}
