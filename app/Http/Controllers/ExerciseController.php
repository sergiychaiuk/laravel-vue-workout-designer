<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use Illuminate\Http\Request;

class ExerciseController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        $exercises = Exercise::with(['sportsProjectile', 'muscleGroup', 'muscles'])->get();
        return response()->json($exercises, 200);
    }

    public function show(Exercise $exercise): \Illuminate\Http\JsonResponse
    {
        $response['exercise'] = $exercise;
        $response['muscles'] = $exercise->muscles;
        return response()->json($response, 200);
    }
}
