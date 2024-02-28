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
                                    <img src=" {{ asset('uploads/'.$companyDetails->company_logo ) }}" alt="" height="100">
                                </span>
                                    @else
                                        <span class="logo-lg">
                                       <img src=" {{ asset('images/logo_default.png') }}" alt="" height="100">
                                </span>
                                    @endif
                                </a>
                            </div>
                            <p class="text-muted mb-4 mt-3">Don't have an account? Create your account, it takes less than a minute</p>
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
                        <form method="POST" action="{{ route('reset.password.post') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <div class="form-group">
                                <label for="fullname"> Email</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{$email ?? old('email') }}"
                                       placeholder="Enter your email"     required autocomplete="new-password" >
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="password">OLd Password</label>
                                <div class="input-group input-group-merge">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" value="{{ old('password') }}"
                                           placeholder="Enter Current Password"     required autocomplete="email" autofocus>

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    <div class="input-group-append" data-password="false">
                                        <div class="input-group-text">
                                            <span class="password-eye"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="password">  New Password</label>
                                <div class="input-group input-group-merge">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation"
                                           placeholder="Enter New Password"  required autocomplete="new-password">
                                    <div class="input-group-append" data-password="false">
                                        <div class="input-group-text">
                                            <span class="password-eye"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-0 text-center">
                                <button type="submit" class="btn btn-primary waves-effect waves-light btn-block" >
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                        </form>
                    </div> <!-- end card-body -->
                </div>
            </div>
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</div>
<!-- end page -->
<footer class="footer footer-alt text-white-50">
    <div class="display: inline-block">
		<h4 class="text-muted text-center text-md-center"> Powered by
		<span class="logo-lg">
			<img src=" {{ asset('images/logo_default.png') }}" alt="" height="30">
		</span>
	</div>
</footer>
@include('layouts.partials.scripts')
</body>