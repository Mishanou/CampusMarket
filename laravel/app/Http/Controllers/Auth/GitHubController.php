<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class GitHubController extends Controller
{
    // Метод для отправки пользователя на страницу авторизации GitHub
    public function redirect()
    {
        return Socialite::driver('github')->redirect();
    }

    // Метод для обработки ответа от GitHub
    public function callback()
    {
        try {
            $githubUser = Socialite::driver('github')->user();
        } catch (Exception $e) {
            return redirect('/login')->with('error', 'Что-то пошло не так при авторизации через GitHub.');
        }

        // Проверяем, существует ли уже пользователь с таким github_id или email
        $user = User::where('github_id', $githubUser->getId())
                    ->orWhere('email', $githubUser->getEmail())
                    ->first();

        if ($user) {
            // Если пользователь найден, обновляем его данные GitHub (на случай, если совпал только email)
            $user->update([
                'github_id' => $githubUser->getId(),
                'github_token' => $githubUser->token,
                'github_refresh_token' => $githubUser->refreshToken,
            ]);
        } else {
            // Если пользователя нет в базе — создаем новую учетную запись
            $user = User::create([
                'name' => $githubUser->getName() ?? $githubUser->getNickname(),
                'email' => $githubUser->getEmail(),
                'github_id' => $githubUser->getId(),
                'github_token' => $githubUser->token,
                'github_refresh_token' => $githubUser->refreshToken,
                'password' => null, // Пароль не нужен, вход по OAuth
            ]);
        }

        // Авторизуем пользователя в системе
        Auth::login($user);

        return redirect('/');
    }
}