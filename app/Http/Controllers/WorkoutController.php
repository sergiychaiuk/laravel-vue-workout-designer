<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\ExercisesGroup;
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
            'user'
        ])->whereHas('user', function ($query) {
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
            'user'
        ])->where('id', '=', $workout->id)->first();

        return response()->json($response, 200);
    }

    public function updateOrderExercises(Request $request): \Illuminate\Http\JsonResponse
    {
        $exercisesGroup = ExercisesGroup::with(['exercises'])->where('id', '=', $request->id)->first();

        foreach ($request->exercises as $exercise) {
            $exercisesGroup->exercises()->updateExistingPivot($exercise['id'], [
                'order_ege' => $exercise['pivot']['order_ege'],
            ]);
        }

        return response()->json('Update order exercises success', 200);
    }

    public function createExercise(ExercisesGroup $exercisesGroup, Request $request): \Illuminate\Http\JsonResponse
    {
        $exercisesGroup->exercises()->attach($request->id, [
            'approaches' => $request->approaches,
            'repetition' => $request->repetition,
            'weight' => $request->weight,
            'order_ege' => $request->order_ege,
        ]);
        return response()->json('Add exercise success',200);
    }

    public function editExercise(Request $request): \Illuminate\Http\JsonResponse
    {
        DB::table('exercises_group_exercises')
            ->where('id', '=', $request->item['pivot']['id'])
            ->update([
                'exercise_id' => $request->item['id'],
                'approaches' => $request->item['pivot']['approaches'],
                'repetition' => $request->item['pivot']['repetition'],
                'weight' => $request->item['pivot']['weight'],
            ]);

        return response()->json('Update exercise success',200);
    }

    public function deleteExercise($id): \Illuminate\Http\JsonResponse
    {
        DB::table('exercises_group_exercises')->where('id', '=', $id)->delete();

        return response()->json(null, 204);
    }
}
