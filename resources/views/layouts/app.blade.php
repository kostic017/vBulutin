<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="author" content="Nikola Kostić">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name') }}</title>

        <link rel="icon" href="{{ asset("favicon.ico") }}">

        <link rel="dns-prefetch" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet">

        <link rel="stylesheet" href="{{ asset('vendor/toastr/toastr.min.css') }}">
        <link rel="stylesheet" href="{{ asset('vendor/fontawesome/css/fontawesome-all.min.css') }}">

        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        @yield("styles")

        <script src="{{ asset('js/app.js') }}"></script>
        <script src="{{ asset('js/script.js') }}"></script>

        <script src="{{ asset('vendor/toastr/toastr.min.js') }}"></script>

        @yield('scripts')

        @if (Session::has('message'))
            <script>
                $(function() {
                    toastr.options.closeButton = true;
                    const type = "{{ Session::get('level') }}";
                    const message = "{{ Session::get('message') }}";
                    switch (type) {
                        case 'info': toastr.info(message); break;
                        case 'warning': toastr.warning(message); break;
                        case 'success': toastr.success(message); break;
                        case 'error': toastr.error(message); break;
                    }
                });
            </script>
        @endif

    </head>

    <body>
        <div id="top"></div>

        <div id="app">
            <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
                <div class="container">
                    @if (isset($current_board))
                        <a class="navbar-brand" href="{{ route('public.show', ['board_address' => $current_board->address]) }}">{{ $current_board->title }}</a>
                    @else
                        <a class="navbar-brand" href="{{ route('website.index') }}">{{ config('app.name') }}</a>
                    @endif
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav mr-auto"></ul>
                        <ul class="navbar-nav ml-auto">
                            <li><a class="nav-link" href="{{ route('website.user.index') }}">Korisnici</a></li>
                            @if (isset($is_admin) && $is_admin)
                                <li><a class="nav-link" href="{{ route('admin.index', ['board_address' => $current_board->address]) }}">Admin panel</a></li>
                            @endif
                            @guest
                                <li>
                                    <div class="dropdown" id="dropdown-login">
                                        <a class="dropdown-toggle nav-link" href="#" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            {{ __('auth.login') }}
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-login">
                                            <form class="px-4 py-3" method="post" action="{{ route('login') }}">
                                                @csrf
                                                <div class="form-group">
                                                    <label for="login-email">{{ __("auth.name-or-mail") }}</label>
                                                    <input type="text" id="login-email" name="email" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" value="{{ old('email') }}">
                                                    @include('includes.error', ['error_key' => 'username'])
                                                </div>
                                                <div class="form-group">
                                                    <label for="login-password">{{ __('auth.password') }}</label>
                                                    <input type="password" id="login-password" name="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}">
                                                    @include('includes.error', ['error_key' => 'password'])
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="remember">{{ __('auth.remember') }}</label>
                                                </div>
                                                <div class="mt-3">
                                                    @include('includes.recaptcha', ['recaptcha_action' => __('auth.login')])
                                                </div>
                                            </form>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="{{ route('register') }}">{{ __('auth.register') }}</a>
                                            <a class="dropdown-item" href="{{ route('password.request') }}">{{ __('auth.forgot') }}</a>
                                        </div>
                                    </div>
                                </li>
                            @else
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        {{ Auth::user()->name ?? Auth::user()->username }} <span class="caret"></span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route_user_show(Auth::user()) }}">
                                            <i class="fas fa-user"></i> Profil
                                        </a>
                                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="fas fa-sign-out-alt"></i> {{ __('auth.logout') }}
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="post" style="display: none;">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                            @endguest
                        </ul>
                    </div>
                </div>
            </nav>

            <main class="container-fluid">
                @yield('content')
            </main>

            <section class="footer">
                <div>
                    @if (isset($current_board))
                        @php($owner = $current_board->owner)
                        <p>
                            Vlasnik ovog foruma je <a href="{{ route_user_show($owner) }}">{{ $owner->username }}</a>.
                            <a href="{{ route('website.index') }}">Napravi i ti svoj forum</a>
                        </p>
                    @endif
                    Copyright &copy; 2017-{{ date('Y') }} Nikola Kostić
                </div>
            </section>
        </div>

    </body>
</html>
