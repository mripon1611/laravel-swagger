<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hash;
use App\Models\User;
use Illuminate\Support\Facades\Hash as FacadesHash;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/login",
     *      operationId="Login",
     *      tags={"Authentication"},
     *      summary="User Login",
     *      description="User Login Here",
     *      @OA\RequestBody(
     *          @OA\JsonContent(),
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *               @OA\Schema(
     *                  type="object",
     *                  required={"email","password"},
     *                  @OA\Property(property="email",type="email"),
     *                  @OA\Property(property="password",type="password"),
     *              )
     *          ),
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Login Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=201,
     *          description="Login Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entry",
     *          @OA\JsonContent()
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Request Not Found"
     *      )
     *     )
     */

     public function login(Request $request){
        $validated = $request->validate(
            [
                'email' => 'required|email|unique:users',
                'password' => 'required',
            ]
        );

        return $request->all();
    }



    /**
     * @OA\Post(
     *      path="/api/register",
     *      operationId="Register",
     *      tags={"Authentication"},
     *      summary="User Register",
     *      description="User Register Here",
     *      @OA\RequestBody(
     *          @OA\JsonContent(),
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *               @OA\Schema(
     *                  type="object",
     *                  required={"name","email","password","password_confirmation"},
     *                  @OA\Property(property="name",type="text"),
     *                  @OA\Property(property="email",type="email"),
     *                  @OA\Property(property="password",type="password"),
     *                  @OA\Property(property="password_confirmation",type="password"),
     *              )
     *          ),
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Register Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=201,
     *          description="Register Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entry",
     *          @OA\JsonContent()
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Request Not Found"
     *      )
     *     )
     */
    
    public function register(Request $request){
        $validated = $request->validate(
            [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|confirmed',
            ]
        );
        $data =$request->all();
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        $success['token'] = $user->createToken('authToken')->accessToken;
        $success['name'] = $user->name;
        return response()->json(['success'=>$success]);
    }
}
