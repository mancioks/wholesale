@extends('layouts.guest')

@section('content')
    <section class="vh-100" style="background-color: #77959c;">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col col-xl-10">
                    <div class="card" style="border-radius: 1rem;">
                        <div class="row g-0">
                            <div class="col-md-6 col-lg-5 d-none d-md-block">
                                <img src="https://bnvi.lt/wp-content/uploads/2023/02/siurblines-dbnvi.webp"
                                     alt="login form" class="img-fluid" style="border-radius: 1rem 0 0 1rem;" />
                            </div>
                            <div class="col-md-6 col-lg-7 d-flex align-items-center">
                                <div class="card-body p-4 p-lg-5 text-black">

                                    <form method="POST" action="{{ route('login') }}">
                                        @csrf
                                        <a href="{{ route('home') }}" class="d-flex align-items-center mb-3 pb-1">
                                            <img src="{{ asset(setting('logo')) }}" alt="" height="64" class="d-inline-block align-text-bottom ms-n2">
                                        </a>

                                        <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">{{ __('Login here') }}</h5>

                                        <div class="form-outline mb-2">
                                            <label class="form-label" for="email">{{ __('Email') }}</label>
                                            <input id="email" type="email" class="form-control form-control-sm @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                                            @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <div class="form-outline mb-2">
                                            <label class="form-label" for="password">{{ __('Password') }}</label>
                                            <input id="password" type="password" class="form-control form-control-sm @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                            @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                            <label class="form-check-label" for="remember">
                                                {{ __('Remember Me') }}
                                            </label>
                                        </div>

                                        <div class="pt-1 mb-4">
                                            <button class="btn btn-dark btn-block" type="submit">{{ __('Login') }}</button>
                                        </div>

                                        <a class="small text-muted" href="{{ route('password.request') }}">{{ __('Forgot password?') }}</a>
                                        <p class="mb-0 pb-lg-2" style="color: #393f81;">{{ __('Don\'t have an account?') }} <a href="{{ route('register') }}" style="color: #393f81;">{{ __('Register here') }}</a></p>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
