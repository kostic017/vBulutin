<?php

namespace App\Http\Controllers\Auth;

use Session;

use App\User;
use App\Profile;
use App\Notifications\ConfirmEmail;
use App\Http\Controllers\Controller;
use App\Exceptions\InvalidEmailTokenException;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        if (!validate_captcha($request->{'g-recaptcha-response'}, $request->ip())) {
            throw new \App\Exceptions\CaptchaFailedException('register');
        }

        event(new Registered($user = $this->create($request->all())));
        // $this->guard()->login($user);

        return $this->registered($request, $user)
                        ?: redirect($this->redirectPath());
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        return redirect(route('login'))->with([
            'alert-type' => 'info',
            'message' => __('auth.confirmation-sent')
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user = new User;
        $user->username = $data['username'];
        $user->email = $data['email'];
        $user->email_token = str_random(30);
        $user->password = Hash::make($data['password']);
        $user->save();

        $profile = new Profile;
        $profile->user_id = $user->id;
        $profile->save();

        $user->notify(new ConfirmEmail($user->email_token));
        return $user;
    }

    /**
     * Confirms user's email address.
     *
     * @param  string  $token
     * @return \Illuminate\Http\RedirectResponse;
     */
    public function confirm(string $token)
    {
        try {
            $user = User::where('email_token', $token)->firstOrFail();
            $user->email_token = null;
            $user->save();
            return redirect(route('login'))->with([
                'alert-type' => 'success',
                'message' => 'Sada se možete logovati.'
            ]);
        } catch (ModelNotFoundException $e) {
            throw new InvalidEmailTokenException($token);
        }
    }
}
