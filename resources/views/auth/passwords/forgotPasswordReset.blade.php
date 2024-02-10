@include('layouts.partials.head')
@section('page_dependencies')

@endsection

<body class="authentication-bg" style="background: url( '{{ $loginBackground }}' ); height: fit-content">

<div class="account-pages mt-5 mb-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card bg-pattern">

                    <div class="card-body p-4">

                        <div class="text-center w-75 m-auto">
                            <div class="auth-logo">
                                <a href=" {{ route('login') }}" class="logo logo-dark text-center">

                                    @if(!empty($companyDetails->company_logo))
                                        <span class="logo-lg">
                                    <img src=" {{ asset('uploads/'.$companyDetails->company_logo ) }}" alt=""
                                         height="50">
                                    <br>
{{--                                    <h2>{{ $companyDetails->full_company_name ?? '' }}</h2>--}}
                                </span>
                                    @else
                                        <span class="logo-lg">
                                       <img src=" {{ asset('images/logo_default.png') }}" alt="" height="50">
                                </span>
                                    @endif
                                </a>
                            </div>
                            <h4 class="mt-0">Recover Password</h4>
                            <p class="text-muted mb-4">Enter your email address and we'll send you an email with instructions to
                                reset your password.</p>
                        </div>

                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('reset.token') }}">
                            @csrf

                            <div class="form-group mb-3">

                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                       name="email" value="{{$email ?? old('email') }}"
                                       placeholder="Enter your email" required autocomplete="new-password">

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group mb-3">

                                <div class="input-group input-group-merge">
                                    <input id="password" type="password"
                                           class="form-control @error('password') is-invalid @enderror" name="password"
                                           value="{{ old('password') }}"
                                           placeholder="Enter New Password" required autocomplete="email" autofocus>
                                    <div class="input-group-append" data-password="false">
                                        <div class="input-group-text">
                                            <span class="password-eye"></span>
                                        </div>

                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
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
                        </form>

                    </div> <!-- end card-body -->
                </div>


                <!-- end card -->

                {{--                <div class="row mt-3">--}}
                {{--                    <div class="col-12 text-center">--}}
                {{--                        <p><a href="auth-recoverpw.html" class="text-white-50 ml-1">Forgot your password?</a></p>--}}

                {{--                        <p class="text-white-50">Don't have an account? <a href="auth-register.html"--}}
                {{--                                                                           class="text-white ml-1"><b>Sign Up</b></a>--}}
                {{--                        </p>--}}
                {{--                    </div> <!-- end col -->--}}
                {{--                </div>--}}
                <!-- end row -->

            </div> <!-- end col -->
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</div>

<footer class="footer footer-alt text-white-50 align-content-lg-end">
    Powered by
    <span class="logo-lg">
            <img src=" {{ asset('images/logo_default.png') }}" alt="" height="30">
        </span>
</footer>

@include('layouts.partials.scripts')
</body>



