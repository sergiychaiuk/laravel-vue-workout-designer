<?php

namespace App\Http\Controllers;

use App\Models\ExercisesGroup;
use App\Models\User;
use App\Models\Workout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function update(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = User::find(Auth::user()->id);
        $user->update($request->all());
        return response()->json(['status' => 'success', 'user' => $user], 200);
    }

    public function workouts(): \Illuminate\Http\JsonResponse
    {
        $workouts = Workout::with([
            'workoutStyle',
            'exercisesGroups',
            'exercisesGroups.exercises',
            'exercisesGroups.exercises.sportsProjectile',
            'exercisesGroups.exercises.muscleGroup',
            'exercisesGroups.exercises.muscles',
            'user'
        ])->whereHas('user', function ($query) {
            return $query->where('id', '=', Auth::user()->id);
        })->get();

        return response()->json($workouts, 200);
    }

    public function addWorkout(Workout $workout): \Illuminate\Http\JsonResponse
    {
        $addWorkout = Workout::create([
            'name' => $workout->name,
            'workout_style_id' => $workout->workout_style_id,
            'user_id' => Auth::user()->id,
        ]);

        foreach ($workout->exercisesGroups as $exercisesGroup) {
            $addExercisesGroup = ExercisesGroup::create([
                'name' => $exercisesGroup->name,
                'workout_id' => $addWorkout->id,
                'order_eg' => $exercisesGroup->order_eg,
            ]);

            foreach ($exercisesGroup->exercises as $exercise) {
                $addExercisesGroup->exercises()->attach($exercise->id, [
                    'approaches' => $exercise->pivot->approaches,
                    'repetition' => $exercise->pivot->repetition,
                    'weight' => $exercise->pivot->weight,
                    'order_ege' => $exercise->pivot->order_ege,
                ]);
            }
        }

        return response()->json($addWorkout->id, 200);
    }

    public function deleteWorkout(Workout $workout): \Illuminate\Http\JsonResponse
    {
        $workout->delete();
        return response()->json(null, 204);
    }

    public function createWorkout(Request $request): \Illuminate\Http\JsonResponse
    {
        $createWorkout = Workout::create([
            'name' => $request->name,
            'workout_style_id' => $request->workout_style_id,
            'user_id' => Auth::user()->id,
            'is_published' => $request->is_published,
        ]);

        foreach ($request->exercises_groups as $exercisesGroup) {
            ExercisesGroup::create([
                'name' => $exercisesGroup['name'],
                'workout_id' => $createWorkout->id,
                'order_eg' => $exercisesGroup['order'],
            ]);
        }

        return response()->json($createWorkout->id, 201);
    }

    public function editWorkout(Workout $workout, Request $request): \Illuminate\Http\JsonResponse
    {
        $workout->update([
            'name' => $request->name,
            'workout_style_id' => $request->workout_style_id,
            'is_published' => $request->is_published,
        ]);

        foreach ($workout->exercisesGroups as $exercisesGroup) {
            if (($k = array_search($exercisesGroup['id'], array_column($request->exercises_groups, 'id'))) !== false) {
                $exercisesGroup->update([
                    'name' => $request->exercises_groups[$k]['name'],
                    'order_eg' => $request->exercises_groups[$k]['order_eg'],
                ]);
            }
            else {
                $exercisesGroup->delete();
            }
        }

        foreach ($request->exercises_groups as $exercises_group) {
            if (!$exercises_group['id']) {
                ExercisesGroup::create([
                    'name' => $exercises_group['name'],
                    'workout_id' => $workout->id,
                    'order_eg' => $exercises_group['order_eg'],
                ]);
            }
        }

        return response()->json('true', 200);
    }
}
