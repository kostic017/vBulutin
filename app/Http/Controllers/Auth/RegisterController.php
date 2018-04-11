<?php

namespace App\Http\Controllers\Auth;

use Session;
use App\User;
use Illuminate\Http\Request;
use App\Notifications\ConfirmEmail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

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
            'message' => 'We have sent you a confirmation link. Please check your mail.'
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

        $user->notify(new ConfirmEmail($user->email_token));
        return $user;
    }

    public function confirm(string $token) {
        if (User::where("email_token", $token)->first()) {
            $this->guard()->login($user);
            return redirect($this->redirectPath())->with([
                'alert-type' => 'success',
                'message' => 'You have successfully confirmed your email.'
            ]);
        }
        $message = 'RegisterController@confirm: ';
        $message .= "No user associated with provided token ('{$token}').";
        $this->$logger->addRecord("error", $message);
        return redirect(route('login'))->with([
            'alert-type' => 'error',
            'message' => 'Token is wrong or expired! You may need to register again.'
        ]);
    }
}
