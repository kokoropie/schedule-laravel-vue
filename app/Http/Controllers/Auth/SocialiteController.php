<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirect()
    {
        return Socialite::driver("laravelpassport")->enablePKCE()->redirect();
    }

    public function callback()
    {
        $sUser = Socialite::driver("laravelpassport")->enablePKCE()->user();
        $user = User::find($sUser->getId());

        if (!$user) {
            $user = new User();
            $user->user_id = $sUser->getId();
            $user->password = Hash::make(time());
        }

        $user->email = $sUser->getEmail();
        $user->name = $sUser->getName();
        $user->email_verified_at = $sUser->user["email_verified_at"];

        if ($user->isDirty()) {
            $user->save();
        }

        auth()->login($user, true);

        return redirect()->route("home");
    }
}
