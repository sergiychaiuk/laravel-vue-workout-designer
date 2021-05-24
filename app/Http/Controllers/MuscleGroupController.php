<?php

namespace App\Http\Controllers;


use App\Models\MuscleGroup;
use Illuminate\Http\Request;

class MuscleGroupController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        return response()->json(MuscleGroup::all(), 200);
    }
}
