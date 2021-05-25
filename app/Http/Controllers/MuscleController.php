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
}
