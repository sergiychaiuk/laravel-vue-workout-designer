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

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $img = $request->file('image')->store('public/muscles');

        $muscle = Muscle::create([
            'name' => $request->name,
            'muscle_group_id' => $request->muscle_group_id,
            'image' => str_replace('public/muscles', '', $img),
            'description' => $request->description,
            'functions' => $request->functions,
        ]);

        $muscle->exercises()->sync(explode(',', $request->exercises));

        return response()->json([$muscle, $muscle->exercises], 201);
    }

    public function update(Muscle $muscle, Request $request): \Illuminate\Http\JsonResponse
    {
        if ($request->file('image')) {
            $img = $request->file('image')->store('public/muscles');
            $img = str_replace('public/muscles', '', $img);
        } else {
            $img = $muscle->image;
        }

        $muscle->update([
            'name' => $request->name,
            'muscle_group_id' => $request->muscle_group_id,
            'image' => $img,
            'description' => $request->description,
            'functions' => $request->functions,
        ]);

        $muscle->exercises()->sync(explode(',', $request->exercises));

        return response()->json('success', 200);
    }
}
