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

                        <div class="text-center mb-4">
                            <div class="auth-logo">
                                <a href=" {{ route('login') }}" class="logo logo-dark text-center">

                                    @if(!empty($companyDetails->company_logo))
                                        <span class="logo-lg">
                                    <img src=" {{ asset('uploads/'.$companyDetails->company_logo ) }}" alt=""
                                         height="45">
                                </span>
                                    @else
                                        <span class="logo-lg">
                                       <img src=" {{ asset('images/logo_default.png') }}" alt="" height="45">
                                </span>
                                    @endif
                                </a>

                            </div>
                        </div>

                        <div class="text-center w-75 m-auto">
                            <img src="{{ $avatar }}" height="88" alt="user-image"
                                 class="rounded-circle shadow">
                            <h4 class="text-dark-50 text-center mt-3">Hi ! {{ $firstName}} </h4>
                            <p class="text-muted mb-4">Enter your password to access the admin.</p>
                        </div>


                        <form method="POST" action="{{ route('login.unlock') }}">
                            @csrf

                            <div class="form-group mb-3">
                                <label for="password">{{ __('Password') }}</label>
                                <input id="password" type="password"
                                       class="form-control @error('password') is-invalid @enderror" name="password"
                                       required
                                       autocomplete="current-password">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

{{--                            @captcha--}}

                            <div class="form-group mb-0 text-center">
                                <button type="submit" class="btn btn-primary btn-block">
                                    {{ __('Unlock') }}
                                </button>
                            </div>

                        </form>

                    </div> <!-- end card-body -->
                </div>
                <!-- end card -->

                <div class="row mt-3">
                    <div class="col-12 text-center">


                        <p class="text-white-50">Not you? return <a href="{{ route('logout') }}" class="text-white ml-1"
                                                                    onclick="event.preventDefault();
                       document.getElementById('logout-form').submit();">

                                <b>Sign Out</b>
                            </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div> <!-- end col -->
                </div>
                <!-- end row -->

            </div> <!-- end col -->
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</div>
<!-- end page -->


<footer class="footer footer-alt text-white-50">
    Powered by
    <span class="logo-lg">
            <a href="https://mkhayamk.co.za/">
                <img src="{{ asset('images/logo_default.png') }}" style="width:30px;height:30px;">
            </a>
        </span>
</footer>


@include('layouts.partials.scripts')
</body>
