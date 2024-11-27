<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; // Import the Auth facade
use Validator;
use Illuminate\Http\JsonResponse;
use App\Http\MyUtil;

class RegisterController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return MyUtil::sendError('Validation Error.', $validator->errors(), 400);
        }

        else {
            if(User::where('email', '=', $request->email)->get()->first() === null)
            {
                $input = $request->all();
                $input['password'] = bcrypt($input['password']);
                $user = User::create($input);
                $success['token'] = $user->createToken('MyApp')->plainTextToken;
                $success['name'] = $user->name;
                return MyUtil::sendResponse($success, 'User register successfully.');
            }
            else
            {
                return MyUtil::sendError('User already exists.', [], 400);
            }
        }
    } 

    public function login(Request $request): JsonResponse
    {
        // Specify the guard to use for authentication
        if (Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::guard('web')->user();

            // remove tokens history
            DB::table('personal_access_tokens')->where('tokenable_id', $user->id)->delete();
            return MyUtil::sendResponse($user->createToken('MyApp')->plainTextToken, 'User login successfully.');
        } else {
            return MyUtil::sendError('Unauthorised.', ['error'=>'Unauthorized'], 401);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return MyUtil::sendResponse([], 'User logout successfully.');
    }
}