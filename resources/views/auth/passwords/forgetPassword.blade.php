@include('layouts.partials.head')

<body class="auth-fluid-pages pb-0">

<div class="auth-fluid">
    <!--Auth fluid left content -->
    <div class="auth-fluid-form-box">
        <div class="align-items-center d-flex h-100">
            <div class="card-body">

                <!-- Logo -->
                <div class="auth-brand text-center text-lg-left">
                    <div class="auth-logo">
                        <a href=" {{ route('login') }}" class="logo logo-dark text-center">

                            @if(!empty($companyDetails->company_logo))
                                <span class="logo-lg">
                                    <img src=" {{ asset('uploads/'.$companyDetails->company_logo ) }}" alt=""
                                         height="45">
                                </span>
                            @else
                                <span class="logo-lg">
                                       <img src=" {{ asset('images/logo_default.png') }}" alt="" height="25">
                                </span>
                            @endif
                        </a>

                    </div>
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

                <!-- title-->
                <h4 class="mt-0">Recover Password</h4>
                <p class="text-muted mb-4">Enter your email address and we'll send you an email with instructions to
                    reset your password.</p>

                <!-- form -->
                <form action="{{ route('reset.token') }}" method="POST">
                    @csrf

                    <div class="form-group mb-3">
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
                            {{ __('Send Password Reset Link') }}
                        </button>
                    </div>

                </form>
                <!-- end form-->

                <!-- Footer-->
                <footer class="footer footer-alt">
                    <p class="text-muted">Back to <a href="{{ route('login') }}" class="text-muted ml-1"><b>Log
                                in</b></a></p>
                </footer>

            </div> <!-- end .card-body -->
        </div> <!-- end .align-items-center.d-flex.h-100-->
    </div>
    <!-- end auth-fluid-form-box-->

    <!-- Auth fluid right content -->
    <div class="auth-fluid-right text-center">
        <div class="auth-user-testimonial">
            <h2 class="mb-3 text-white">I love the color!</h2>
            <p class="lead"><i class="mdi mdi-format-quote-open"></i> I've been using your theme from the previous
                developer for our web app, once I knew new version is out, I immediately bought with no hesitation.
                Great themes, good documentation with lots of customization available and sample app that really fit our
                need. <i class="mdi mdi-format-quote-close"></i>
            </p>
            <h5 class="text-white">
                - Fadlisaad (Ubold Admin User)
            </h5>
        </div> <!-- end auth-user-testimonial-->
    </div>
    <!-- end Auth fluid right content -->
</div>
<!-- end auth-fluid-->

@include('layouts.partials.scripts')

</body>

