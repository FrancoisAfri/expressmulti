<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

/**
 * Description of AuthController
 *
 * @author Joel
 */
class AuthController extends \App\Http\Controllers\Controller {

    public function authenticateUser(Request $request) {
        if ($request->has("email") && $request->has("password")) {
            $user = User::where('email', $request->email)->first();
								 
            if ($user) {
                if (Hash::check($request->get('password'), $user->password)) {
                    unset($user->password);
                    $person = $user->load('person');
                    return response()->json(['success' => true, 'user' => $person], Response::HTTP_OK);
                } else {
                    return response()->json(['success' => false, 'msg' => 'invalid email or password'], 401);
                }
            } else {
                return response()->json(['success' => false, 'msg' => 'invalid email or password'], 401);
            }
        } else {
            return response()->json(['success' => false, 'msg' => 'invalid request'], 401);
        }
    }

}
