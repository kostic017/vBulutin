<?php

namespace App\Http\Controllers\Frontend;

use Auth;
use Hash;

use App\User;
use App\Notifications\ConfirmEmail;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class UpdateController extends DashboardController
{
    public function profile(Request $request, string $profile): RedirectResponse
    {
        $errors = [];

        $user = User::where('username', $profile)->firstOrFail();
        $profile = $user->profile()->firstOrFail();

        $user->is_invisible = $request->is_invisible;

        if ($user->email !== $request->email || isNotEmpty($request->password) || isNotEmpty($request->password_confirm)) {
            if (isEmpty($request->password_current)) {
                $errors['password_current'] = 'Niste upisali trenutnu šifru.';
            } elseif (!Hash::check($request->password_current, $user->password)) {
                $errors['password_current'] = 'Upisali ste pogrešnu šifru.';
            } else {
                if ($user->email !== $request->email) {
                    $user->to_logout = true;
                    $user->is_confirmed = false;
                    $user->email = $request->email;
                    $user->email_token = str_random(30);
                    $user->notify(new ConfirmEmail($user->email_token));
                }

                if (isNotEmpty($request->password) || isNotEmpty($request->password_confirm)) {
                    if ($request->password !== $request->password_confirm) {
                        $errors['password_matching'] = 'Lozinke se ne poklapaju.';
                    } elseif (!isset($errors['password_current'])) {
                        $user->password = Hash::make($request->password);
                        $user->to_logout = true;
                    }
                }
            }
        }

        if (!empty($errors)) {
            return redirect()->back()->withErrors($errors)->withInput();
        }

        $profile->about = $request->about;
        $profile->birthday_on = $request->birthday_on;
        $profile->sex = $request->sex;
        $profile->job = $request->job;
        $profile->name = $request->name;
        $profile->residence = $request->residence;
        $profile->birthplace = $request->birthplace;
        $profile->avatar = $request->avatar;
        $profile->signature = $request->signature;

        $user->save();
        $profile->save();

        return redirect(route('public.profile.show', ['profile' => $user->username]))->with([
            'alert-type' => 'success',
            'message' => 'Podaci uspešno izmenjeni.'
        ]);
    }
}
