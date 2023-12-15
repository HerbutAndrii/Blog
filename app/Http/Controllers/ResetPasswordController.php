<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    public function request() {
        return view('auth.forgot-password');
    }

    public function email(Request $request) {
        $request->validate(['email' => ['required', 'email']]);
 
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if($status === Password::RESET_LINK_SENT) {
            return back()->with('status', trans($status));
        }

        return back()->withInput()->withErrors(['email' => trans($status)]);
    }

    public function reset(Request $request) {
        return view('auth.reset-password', ['request' => $request]);
    }

    public function update(ResetPasswordRequest $request) {     
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));
     
                $user->save();     
            }
        );

        if($status === Password::PASSWORD_RESET) {
            return redirect(route('auth.login'))->with('status', trans($status));
        }

        return back()->withInput()->withErrors(['email' => trans($status)]);
    }
}
