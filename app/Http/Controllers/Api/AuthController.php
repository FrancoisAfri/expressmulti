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
			// connect to the right database
			if (!empty($user->database_name))
			{
				$tenantDatabaseConfig = [
					'driver'    => 'pgsql',
					'host'      => env('DB_HOST', '127.0.0.1'),
					'database'  => $user->database_name,
					'username'  => env('DB_USERNAME'),
					'password'  => env('DB_PASSWORD'),
					'charset' => 'utf8',
					'prefix' => '',
					'prefix_indexes' => true,
					'schema' => 'public',
					'sslmode' => 'prefer',
				];

				// reconnect to database
				\Config::set("database.connections.$user->database_name", $tenantDatabaseConfig);
				DB::purge($user->database_name);
				DB::reconnect($user->database_name);
				DB::setDefaultConnection($user->database_name);
				// get user from database
				$user = User::where('email', $request->email)->first();
			}
            if ($user) {
                if (Hash::check($request->get('password'), $user->password)) {
					$user->user_fcm_token = !empty($request->user_fcm_token) ? $request->user_fcm_token : '';
					$user->online = 1;
                    $user->update();
					// connect to the right database.
					
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
