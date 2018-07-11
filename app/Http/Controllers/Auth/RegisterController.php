<?php

namespace App\Http\Controllers\Auth;

use Hash;
use Validator;

use App\User;
use App\Helpers\Logger;
use App\Notifications\ConfirmEmail;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RegisterController extends Controller {
    use RegistersUsers;

    public function __construct() {
        $this->middleware('guest');
    }

    protected function validator(array $data) {
        return Validator::make($data, [
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    public function register(Request $request) {
        $this->validator($request->all())->validate();

        if (is_captcha_set() && !validate_captcha($request->{'g-recaptcha-response'}, $request->ip())) {
            Logger::log($request->ip() . ' has failed captcha.', 'error', __METHOD__);
            return alert_redirect(route('register'), 'error', __('auth.captcha-failed'));
        }

        event(new Registered($user = $this->create($request->all())));

        return $this->registered($request, $user) ?: redirect($this->redirectPath());
    }

    protected function registered(Request $request, $user) {
        return alert_redirect('/', 'info', __('auth.confirmation-sent'));
    }

    protected function create(array $data) {
        $user = new User;
        $user->username = $data['username'];
        $user->email = $data['email'];
        $user->email_token = str_random(30);
        $user->password = Hash::make($data['password']);
        $user->save();

        $user->notify(new ConfirmEmail($user->id, $user->email_token));
        return $user;
    }

    public function confirm($id, $token) {
        try {
            $user = User::where('id', $id)
                ->where('email_token', $token)
                ->firstOrFail();
            $user->email_token = null;
            $user->save();
            return alert_redirect('/', 'success', __('auth.can-login'));
        } catch (ModelNotFoundException $e) {
            Logger::log(request()->ip() . ' has failed to confirm his email address.', 'error', __METHOD__);
        }
    }
}
