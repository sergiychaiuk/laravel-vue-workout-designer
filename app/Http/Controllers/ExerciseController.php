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
}
