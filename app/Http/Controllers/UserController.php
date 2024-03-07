<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function destroy(User $user) {
        if($user->avatar != 'default-avatar.jpg') {
            Storage::delete('public/avatars/' . $user->avatar);
        }

        $user->delete();
        
        return response()->json(['success' => true]);
    }
}
