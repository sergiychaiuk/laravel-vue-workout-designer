<?php

namespace App\Http\Controllers;

use App\Models\WorkoutStyle;
use Illuminate\Http\Request;

class WorkoutStyleController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        return response()->json(WorkoutStyle::all(), 200);
    }
}
