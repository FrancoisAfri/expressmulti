@include('layouts.partials.head')
@section('page_dependencies')


@endsection

<body class="auth-fluid-pages pb-0">

<div class="auth-fluid"

     style="background: url( '{{ $loginBackground }}' ) center;">
    <!--Auth fluid left content -->
    <div class="auth-fluid-form-box">
        <div class="align-items-center d-flex h-100">
            <div class="card-body">

                <!-- Logo -->
                <div class="auth-brand text-center text-lg-left">

                </div>
                <div class="text-center">
                    <div class="auth-logo">
                        <a href=" {{ route('login') }}" class="logo logo-dark text-center">

                            @if(!empty($companyDetails->company_logo))
                                <span class="logo-lg">
                                    <img src=" {{ asset('uploads/'.$companyDetails->company_logo ) }}" alt=""
                                         height="85">
                                    <br>
                                    <h2>{{ $companyDetails->full_company_name ?? '' }}</h2>
                                </span>
                            @else
                                <span class="logo-lg">
                                       <img src=" {{ asset('images/logo_default.png') }}" alt="" height="45">
                                </span>
                            @endif
                        </a>

                    </div>
                </div>
                <!-- title-->
                <br>
                <br>
                <h4 class="mt-0">Sign In</h4>
                <p class="text-muted mb-4">Enter your email address and password to access account.</p>

                <!-- form -->
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif


                    <div class="form-group">
                        <label for="email"
                               class="col-md-4 col-form-label text-md-end">{{ __('Email / Phone  ') }}</label>

                        <div class="form-group">
                            {{--                            <input id="text" type="text" class="form-control @error('email') is-invalid @enderror"--}}
                            {{--                                   name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>--}}

                            <input id="username" type="text"
                                   class="form-control @error('username') is-invalid @enderror" name="username"
                                   value="{{ old('username') }}" required autofocus>

                            @error('username')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror

                        </div>
                    </div>

                    <div class="form-group">

                        <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                        <div class="form-group">
                            <input id="password" type="password"
                                   class="form-control @error('password') is-invalid @enderror" name="password" required
                                   autocomplete="current-password">

                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                    </div>


                    <div class="form-group mb-3">
                        <div class="custom-control custom-checkbox">
                            <input class="form-check-input" type="checkbox" name="remember"
                                   id="remember" {{ old('remember') ? 'checked' : '' }}>

                            <label class="form-check-label" for="remember">
                                {{ __('Remember Me') }}
                            </label>
                        </div>
                    </div>

                    {{--                    {!! app('captcha')->render() !!}--}}

                    <div class="form-group mb-0 text-center">

                        <button type="submit" class="btn btn-primary btn-block">
                            {{ __('Login') }}
                        </button>

                        @if (Route::has('password.request'))
                            <a class="btn btn-link" href="{{  route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        @endif
                    </div>
                    <!-- social-->
                    <div class="text-center mt-4">
                        <p class="text-muted font-16">Sign in with</p>
                        <ul class="social-list list-inline mt-3">
                            <li class="list-inline-item">
                                <a href="{{ url('auth/facebook') }}"
                                   class="social-list-item border-primary text-primary"><i
                                        class="mdi mdi-facebook"></i></a>
                            </li>
                            <li class="list-inline-item">
                                <a href="{{ url('auth/google') }}" class="social-list-item border-danger text-danger"><i
                                        class="mdi mdi-google"></i></a>
                            </li>
                        </ul>
                    </div>
                </form>
                <!-- end form-->

            </div> <!-- end .card-body -->

        </div> <!-- end .align-items-center.d-flex.h-100-->
        Powered by
        <span class="logo-lg">
            <img src=" {{ asset('images/logo_default.png') }}" alt="" height="30">
        </span>
    </div>
    <!-- end auth-fluid-form-box-->

    <!-- Auth fluid right content -->

    <!-- end Auth fluid right content -->
</div>

<!-- end auth-fluid-->
{{--@include('layouts.partials.footer')--}}
@include('layouts.partials.scripts')
</body>


