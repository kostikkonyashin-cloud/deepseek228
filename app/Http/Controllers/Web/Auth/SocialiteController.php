<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\SocialiteAccount;
use App\Models\SocialiteProvider;
use App\Models\User;
use Auth;
use Exception;
use Illuminate\Support\Str;
use Laravel\Socialite\Socialite;

class SocialiteController extends Controller
{
    public function create(string $provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function store(string $provider)
    {
        try {
            $socialiteProvider = SocialiteProvider::where('name', $provider)->first();

            if (!$socialiteProvider) {
                throw new Exception("Провайдер '{$provider}' не найден в системе");
            }

            $socialiteUser = Socialite::driver($provider)->user();

            $socialAccount = SocialiteAccount::where('socialite_provider_id', $socialiteProvider->id)
                ->where('socialite_account_id', $socialiteUser->getId())
                ->first();

            if ($socialAccount) {
                Auth::login($socialAccount->user);
            } else {
                $user = User::where('email', $socialiteUser->getEmail())->first();
                $clientRole = Role::where('slug', 'client')->first();

                if (!$user) {
                    $user = User::create([
                        'full_name' => $socialiteUser->getName() ?? 'user_' . Str::random(8),
                        'login' => $socialiteUser->getNickname() ?? 'user_' . Str::random(8),
                        'email' => $socialiteUser->getEmail(),
                        'password' => bcrypt(Str::random(16)),
                        'is_blocked' => false,
                        'role_id' => $clientRole->id
                    ]);
                }

                SocialiteAccount::create([
                    'user_id' => $user->id,
                    'socialite_account_id' => $socialiteUser->getId(),
                    'socialite_provider_id' => $socialiteProvider->id,
                ]);

                Auth::login($user);
            }

            session()->regenerate();

            return redirect()->route('index')->with('success', 'Вход выполнен');

        } catch (Exception $e) {
            return redirect()->route('login.create')->with('error', 'Ошибка авторизации: ' . $e->getMessage());
        }
    }
}
