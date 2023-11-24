<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
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
            auth()->login($user);
            return redirect(route('post.index'));
        } else {
            return back()->withErrors([
                'Login' => 'Passwords or email addresses do not match'
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
            $fileName = $request->file('avatar')->getClientOriginalName();
            $request->file('avatar')->storeAs('public/avatars', $fileName);
            $user->avatar = $fileName;
        } else {
            Storage::put('public/avatars/default-avatar.jpg', 
                    Storage::get('/public/layouts/default-avatar.jpg'));
        }

        $user->save();

        auth()->login($user);
        return redirect(route('post.index'));
    }

    public function logout() {
        auth()->logout();
        session()->regenerate();
        return redirect("/");
    }
}
