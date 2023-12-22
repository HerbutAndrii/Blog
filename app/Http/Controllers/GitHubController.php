<?php

namespace App\Http\Controllers;

use App\Models\User;
use Laravel\Socialite\Facades\Socialite;

class GitHubController extends Controller
{
    public function gitHubRedirect() {
        return Socialite::driver('github')->redirect();
    }

    public function gitHubCallback() {
        $githubUser = Socialite::driver('github')->user();
    
        $user = User::firstOrCreate([
            'email' => $githubUser->email
        ], [
            'name' => $githubUser->name,
            'email' => $githubUser->email,
            'password' => encrypt('password.github')
        ]);

        $user->markEmailAsVerified();

        auth()->login($user, true);

        return redirect()->intended(route('post.index'));
    }
}
