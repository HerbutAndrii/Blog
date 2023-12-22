<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Jobs\EmailVerificationNotificationJob;
use App\Models\User;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function loginView() {
        return view('auth.login');
    }

    public function login(LoginRequest $request) {
        $user = User::where('email', $request->email)->first();

        if($user && Hash::check($request->password, $user?->password)) {
            auth()->login($user, $request->remember);
            return redirect()->intended(route('post.index'));
        } else {
            return back()->withErrors([
                'login' => 'Passwords or email addresses do not match'
            ])->withInput();
        }
    }

    public function registerView() {
        return view('auth.register');
    } 

    public function register(RegisterRequest $request) {
        $user = new User();
        $user->name = $request->name;
        $user->password = Hash::make($request->password);
        $user->email = $request->email;

        if($request->hasFile('avatar')) {
            $fileName = $request->file('avatar')->hashName();
            $request->file('avatar')->storeAs('public/avatars', $fileName);
            $user->avatar = $fileName;
        } else {
            Storage::copy('/public/layouts/default-avatar.jpg', 'public/avatars/default-avatar.jpg');
        }

        $user->save();

        dispatch(new EmailVerificationNotificationJob($user));

        auth()->login($user);

        session()->regenerate();
        
        return redirect(route('verification.notice'));
    }

    public function logout() {
        auth()->logout();

        session()->invalidate();

        session()->regenerateToken();

        return redirect(route('auth.login'));
    }
}
