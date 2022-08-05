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

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $img = $request->file('image')->store('public/exercises');

        $exercise = Exercise::create([
            'name' => $request->name,
            'muscle_group_id' => $request->muscle_group_id,
            'image' => str_replace('public/exercises', '', $img),
            'description' => $request->description,
            'starting_position' => $request->starting_position,
            'execution_trajectory' => $request->execution_trajectory,
            'technique_execution' => $request->technique_execution,
            'sports_projectile_id' => $request->sports_projectile_id,
        ]);

        $exercise->muscles()->sync(explode(',', $request->muscles));

        return response()->json([$exercise, $exercise->muscles], 201);
    }

    public function update(Exercise $exercise, Request $request): \Illuminate\Http\JsonResponse
    {
        if ($request->file('image')) {
            $img = $request->file('image')->store('public/exercises');
            $img = str_replace('public/exercises', '', $img);
        } else {
            $img = $exercise->image;
        }

        $exercise->update([
            'name' => $request->name,
            'muscle_group_id' => $request->muscle_group_id,
            'image' => $img,
            'description' => $request->description,
            'starting_position' => $request->starting_position,
            'execution_trajectory' => $request->execution_trajectory,
            'technique_execution' => $request->technique_execution,
            'sports_projectile_id' => $request->sports_projectile_id,
        ]);

        $exercise->muscles()->sync(explode(',', $request->muscles));

        return response()->json('success', 200);
    }
}
