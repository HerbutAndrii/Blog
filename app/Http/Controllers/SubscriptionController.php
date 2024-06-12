<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function subscribe(Request $request, User $user) {
        if($user->id === $request->user()->id) {
            return response()->json(['message' => 'You can not subscribe to yourself.'], 400);
        }

        if(! $user->hasSubscriber($request->user())) {
            $user->subscribers()->attach($request->user());
        }

        return response()->json([
            'subscribers' => $user->subscribers()->count()
        ]);
    }

    public function unsubscribe(Request $request, User $user) {
        if($user->hasSubscriber($request->user())) {
            $user->subscribers()->detach($request->user());
        }

        return response()->json([
            'subscribers' => $user->subscribers()->count()
        ]);
    }
}
