<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function update(Request $request) {
        $user = User::find(Auth::user()->id);
        $user->update($request->all());
        return response()->json(['status' => 'success', 'user' => $user], 200);
    }
}
