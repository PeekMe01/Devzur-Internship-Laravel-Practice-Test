<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callbackGoogle()
    {
        try {
            $google_user = Socialite::driver('google')->user();

            $user = User::where('google_id', $google_user->getId())->first();

            if(!$user){

                $new_user = User::create([
                    'name' => $google_user->getName(),
                    'email' => $google_user->getEmail(),
                    'user_type' => 'user',
                    'google_id' => $google_user->getId(),
                    'profile_photo_path' => $google_user->getAvatar(),
                ]);

                Cart::create([
                    'user_id' => $new_user->id,
                ]);

                Auth::login($new_user);

                return redirect()->intended('home');

            }else{
                Auth::login($user);
            }
        } catch (\Throwable $th) {
            dd('Something went wrong!' . $th->getMessage());
        }
    }
}
