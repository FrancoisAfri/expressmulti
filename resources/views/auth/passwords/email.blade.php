@include('layouts.partials.head')
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


                                <span class="logo-lg">
                                       <img src=" {{ $logo }}" alt="" height="45">
                                </span>

                                </a>


                            </div>
                            <p class="text-muted mb-4 mt-3">Enter your email address and we'll send you an email with instructions to reset your password.</p>
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

                        <!-- form -->
                        <form method="POST" action="{{ route('reset.no_token') }}">
                            @csrf

                            <div class="form-group mb-3">
                                <label for="emailaddress">Email address</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                       name="email" value="{{ old('email') }}"
                                       placeholder="Enter your email" required autocomplete="email" autofocus>

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group mb-0 text-center">
                                <button type="submit" class="btn btn-primary waves-effect waves-light btn-block">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>

                        </form>

                    </div> <!-- end card-body -->
                </div>
                <!-- end card -->

                <div class="row mt-3">
                    <div class="col-12 text-center">
                        <p class="text-muted">Back to <a href="{{ route('login') }}" class="text-muted ml-1"><b>Log
                                    in</b></a></p>
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
            <img src=" {{ asset('images/logo_default.png') }}" alt="" height="30">
        </span>
</footer>

@include('layouts.partials.scripts')
</body>
