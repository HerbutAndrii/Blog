<?php

namespace App\Http\Controllers;

use App\Jobs\EmailVerificationNotificationJob;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    public function notice(Request $request) {
        if($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('post.index'));
        }

        return view('auth.verify-email');
    }

    public function verify(EmailVerificationRequest $request) {
        if($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('post.index'));
        }

        $request->fulfill();
 
        return redirect(route('post.index'));
    }

    public function send(Request $request) {
        if($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('post.index'));
        }
        
        dispatch(new EmailVerificationNotificationJob($request->user()));
 
        return back()->with('message', 'Verification link sent!');
    }
}
