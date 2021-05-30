<?php

namespace App\Http\Controllers;

use App\Models\Muscle;
use Illuminate\Http\Request;

class MuscleController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        $muscles = Muscle::with(['muscleGroup', 'exercises'])->get();
        return response()->json($muscles, 200);
    }

    public function show(Muscle $muscle): \Illuminate\Http\JsonResponse
    {
        $response['muscle'] = $muscle;
        $response['exercises'] = $muscle->exercises;
        return response()->json($response, 200);
    }
}
