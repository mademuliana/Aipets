<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SignInController extends Controller
{
    public function __invoke(Request $request)
    {
        if (!$token = auth()->attempt($request->only('username','password'))) {
            return response(null,401);
        }
        return response()->json(compact('token'));
    }
}
