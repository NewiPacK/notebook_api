<?php

namespace App\Http\Controllers\Api\V1;

/**
 * @OA\Info(
 *     title="Notebook API",
 *     version="1.0.0",
 *     description="API для записной книжки",
 *     @OA\Contact(
 *         name="Владимир Пак",
 *         email="newpack97@gmail.com, vladimirpack@icloud.com"
 *     )
 * )
 */

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/v1/register",
     *     summary="Регистрация нового пользователя",
     *     description="Создает нового пользователя и возвращает его данные",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="fullnamme", type="string", example="Иванов Иван Иванович"),
     *             @OA\Property(property="email", type="string", example="ivan@example.com"),
     *             @OA\Property(property="password", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Пользователь успешно зарегистрирован"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Некорректные данные"
     *     )
     * )
     */
    public function register(StoreUserRequest $request)
    {
        return User::create($request->all());
    }

    /**
     * @OA\Post(
     *     path="/api/v1/login",
     *     summary="Авторизация пользователя",
     *     description="Вход для пользователя по email и паролю, возвращает токен",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", example="ivan@example.com"),
     *             @OA\Property(property="password", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешная авторизация"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Неверный email или пароль",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Wrong email or password")
     *         )
     *     )
     * )
     */
    public function login(LoginUserRequest $request)
    {
        if (!Auth::attempt($request->only(['email', 'password']))) {
            return response()->json([
                'message'  => 'Wrong email or password'
            ], 401);
        }

        $user = User::query()->where('email', $request->email)->first();
        $user->tokens()->delete();
        return response()->json([
            'user'  => $user,
            'token' =>  $user->createToken("Token of  user:  {$user->name}")->plainTextToken
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/logout",
     *     summary="Выход пользователя",
     *     description="Удаляет текущий токен доступа пользователя",
     *     @OA\Response(
     *         response=200,
     *         description="Успешный выход",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Token removed")
     *         )
     *     )
     * )
     */
    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Token removed'
        ]);
    }
}
