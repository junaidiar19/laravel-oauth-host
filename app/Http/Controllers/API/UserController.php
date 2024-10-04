<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUser()
    {
        $user = auth()->user();

        return response()->json($user);
    }
}
