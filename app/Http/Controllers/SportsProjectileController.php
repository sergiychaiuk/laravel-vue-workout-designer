<?php

namespace App\Http\Controllers;

use App\Models\SportsProjectile;

class SportsProjectileController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        return response()->json(SportsProjectile::all(), 200);
    }
}
