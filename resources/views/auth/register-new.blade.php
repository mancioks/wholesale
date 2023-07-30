@extends('layouts.guest')

@section('content')
    <section class="vh-100" style="background-color: #fff;">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col col-xl-10">
                    <div class="card" style="border-radius: 1rem;box-shadow: 0 0 45px 0 rgba(0,0,0,0.1);">
                        <div class="row g-0">
                            <div class="col-md-6 col-lg-5 d-none d-md-block">
                                <img src="https://bnvi.lt/wp-content/uploads/2023/02/siurblines-dbnvi.webp"
                                     alt="login form" class="img-fluid" style="border-radius: 1rem 0 0 1rem;" />
                            </div>
                            <div class="col-md-6 col-lg-7 d-flex align-items-center">
                                <div class="card-body p-4 p-lg-5 text-black">

                                    <form method="POST" action="{{ route('register') }}">
                                        @csrf
                                        <a href="{{ route('home') }}" class="d-flex align-items-center mb-3 pb-1">
                                            <img src="{{ asset(setting('logo')) }}" alt="" height="64" class="d-inline-block align-text-bottom ms-n2">
                                        </a>

                                        <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">{{ __('Create new account') }}</h5>

                                        <div class="form-outline mb-2">
                                            <label class="form-label" for="name">{{ __('User name') }}</label>
                                            <input id="name" type="text" class="form-control form-control-sm @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                            @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

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
                                            <label class="form-label" for="phone">{{ __('Phone number') }}</label>
                                            <input id="phone" type="text" class="form-control form-control-sm @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required autocomplete="phone">
                                            @error('phone')
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

                                        <div class="form-outline mb-2">
                                            <label class="form-label" for="password-confirm">{{ __('Confirm password') }}</label>
                                            <input id="password-confirm" type="password" class="form-control form-control-sm" name="password_confirmation" required autocomplete="new-password">
                                        </div>

                                        <div class="pt-1 mb-4">
                                            <button class="btn btn-dark btn-block" type="submit">{{ __('Register') }}</button>
                                        </div>

                                        <p class="mb-0 pb-lg-2" style="color: #393f81;">{{ __('Have account?') }} <a href="{{ route('login') }}" style="color: #393f81;">{{ __('Login here') }}</a></p>
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
