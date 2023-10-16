@include('layouts.partials.head')

<body class="auth-fluid-pages pb-0">

<div class="auth-fluid">
    <!--Auth fluid left content -->
    <div class="auth-fluid-form-box">
        <div class="align-items-center d-flex h-100">
            <div class="card-body">

                <div class="text-center">
                    <div class="auth-logo">
                        <a href=" {{ route('login') }}" class="logo logo-dark text-center">
                        </a>

                    </div>
                </div>
                <!-- Logo -->
                <div class="auth-brand text-center text-lg-left">
                    <div class="auth-logo">
                        <a href="index.html" class="logo logo-dark text-center">
                                    <span class="logo-lg">
                                        <img src="../assets/images/logo-dark.png" alt="" height="22">
                                    </span>
                        </a>

                        <a href="index.html" class="logo logo-light text-center">
                                    <span class="logo-lg">
                                        <img src="../assets/images/logo-light.png" alt="" height="22">
                                    </span>
                        </a>
                    </div>
                </div>


                <form method="POST" action="{{ route('2fa.post') }}">
                    @csrf

                    <p class="text-center">We sent code to your phone
                        : {{ substr($phoneNumber, 0, 3) . '******' . substr($phoneNumber,  -2) }}</p>

                    @if ($message = Session::get('success'))

                        <div class="row">

                            <div class="col-md-12">

                                <div class="alert alert-success alert-block">

                                    <button type="button" class="close" data-dismiss="alert">×</button>

                                    <strong>{{ $message }}</strong>

                                </div>

                            </div>

                        </div>

                    @endif



                    @if ($message = Session::get('error'))

                        <div class="row">

                            <div class="col-md-12">

                                <div class="alert alert-danger alert-block">

                                    <button type="button" class="close" data-dismiss="alert">×</button>

                                    <strong>{{ $message }}</strong>

                                </div>

                            </div>

                        </div>

                    @endif

                    <div class="form-group row">
                        <label for="code" class="col-md-4 col-form-label text-md-right">Code</label>
                        <div class="col-md-6">
                            <input id="code" type="text" class="form-control @error('code') is-invalid @enderror"
                                   name="code" value="{{ old('code') }}"  required autocomplete="code"  maxlength="8" autofocus >

                            @error('code')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                    </div>


                    <div class="form-group row mb-0">

                        <div class="col-md-8 offset-md-4">

                            <a class="btn btn-link" href="{{ route('2fa.resend') }}">Resend Code?</a>

                        </div>

                    </div>


                    <button type="submit" class="btn btn-primary btn-block">
                        {{ __('Submit') }}
                    </button>

                </form>
                <!-- end form-->


            </div> <!-- end .card-body -->
        </div> <!-- end .align-items-center.d-flex.h-100-->
    </div>
    <!-- end auth-fluid-form-box-->

    <!-- Auth fluid right content -->

</div>
<!-- end auth-fluid-->

@include('layouts.partials.scripts')
</body>

