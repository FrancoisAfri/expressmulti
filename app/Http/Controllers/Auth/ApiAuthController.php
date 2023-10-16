<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\HRPerson;
use App\Models\User;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

use Illuminate\Support\Facades\Auth;

class ApiAuthController extends Controller
{
    use ApiResponser;


    public function register(Request $request)
    {

        $attr = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed'
        ]);

        $user = User::create([
            'name' => $attr['name'],
            'password' => bcrypt($attr['password']),
            'email' => $attr['email']
        ]);

        $person = new HRPerson();
        $person->first_name = $request['name'];
        $person->email = $request['email'];
        $person->status = 1;
        $user->addPerson($person);

        return $this->success([
            'message' => 'Successfully created user!',
            "status" => 201,
            'token' => $user->createToken('API Token')->plainTextToken
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|',
            'password' => 'required|string|min:6',
            'remember_me' => 'boolean'
        ]);

        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            return $this->error('Credentials not match', 401);
        }


//        $user = $request->user();
        $tokenResult = auth()->user()->createToken('Personal Access Token');
        $token = $tokenResult;


        if ($request->remember_me){
            $token->expires_at = Carbon::now()->addWeeks(1);
        }

        return response()->json([
            'access_token' => $tokenResult->accessToken->token,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->accessToken->updated_at
            )->toDateTimeString()
        ]);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Tokens Revoked'
        ];
    }
}
