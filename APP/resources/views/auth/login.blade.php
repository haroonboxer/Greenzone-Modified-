<!doctype html>
<html lang="en">

    <head>
        <title>{{ trans('words.Login Page') }}</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="{{ asset('login-style.css') }}">
        <link rel="shortcut icon" href="{{ asset('logo.png') }}" type="image/x-icon">
        <link href="{!! asset('assets/font/font.css') !!}" rel="stylesheet" type="text/css" />
        <style>
            * {
                font-family: B Nazanin;
            }

            body {
                position: relative;
                width: 100%;
                height: 100vh;
                background-image: url('moi2.jpg');
                background-size: cover;
                background-position: center;
            }
        </style>
    </head>

    <body>
        <section class="ftco-section">
            <div class="container">
                <div class="row justify-content-start">
                    <div class="col-md-6 col-lg-6">
                        <div class="d-flex justify-content-center">
                            <div class="w-100 text-center">
                                <img class="pt-0 mt-0 mb-1" src="{{ asset('logo.png') }}" alt="" width="100"
                                    height="100">
                                <div style="background-color:rgba(17, 47, 122,0.5);padding:5px; border-radius:10px">
                                    <h4 class="m-login__title"
                                        style="color:#fff;font-size:1.8rem;font-weight:270; font-family:B Nazanin;">
                                        {{ trans('global.title-1') }}</h4>
                                    <h3 class="m-login__title"
                                        style="color:#fff;font-size:1.8rem;font-weight:270; font-family:B Nazanin">
                                        {{ trans('global.title-2') }}
                                    </h3>
                                    <h3 class="m-login__title"
                                        style="color:#fff;font-size:1.8rem;font-weight:270; font-family:B Nazanin">
                                        {{ trans('global.title-3') }}
                                    </h3>
                                    <h3 class="m-login__title"
                                        style="color:#fff;font-size:1.8rem;font-weight:270; font-family:B Nazanin">
                                        {{ trans('global.title-4') }}
                                    </h3>
                                </div>
                                @if (session()->has('token_expired_error'))
                                    <h4 class="text-center alert-danger text-danger">
                                        {{ trans('words.Token Expired') }}</h4>
                                @endif
                            </div>
                        </div>

                        {{-- <form method="POST" action="{{ route('login') }}" class="signin-form mt-4">
                        <div class="text-danger text-center" style="font-size:18px">
                            @error('username')
                                <div class="alert alert-dismissible fade show" role="alert"
                                    style="background-color:rgba(17, 47, 122,0.5);padding:10px; border-radius:15px">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <i class="fas fa-times-circle mr-2 text-danger"></i>
                                    </button>
                                    <i class="la la-check-square"></i>
                                    <strong style="color:red">{{ $errors->first('username') }}</strong>
                                </div>
                            @enderror
                        </div>
                        @csrf
                        <div class="form-group mb-3 rtl">
                            <input type="text" name="username"
                                class="form-control rtl @error('username') is-invalid @enderror"
                                placeholder="{{ trans('words.Enter Your_', ['name' => trans('words.Email')]) }}"
                                required style="border-radius:15px" />
                            @error('username')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group mb-3 rtl">
                            <input type="password" id="password" name="password"
                                class="form-control rtl @error('password') is-invalid @enderror"
                                autocomplete="current-password"
                                placeholder="{{ trans('words.Enter Your_', ['name' => trans('words.Password')]) }}"
                                required style="border-radius:15px" />
                            <span id="toShowPassword"
                                style="float:left; font-size: 1.1rem; margin-top: -2.1rem; margin-left: 0.6rem;"
                                class="fa fa-eye"></span>
                            <span id="toHidePassword"
                                style="float:left; font-size: 1.1rem; margin-top: -2.1rem; margin-left: 0.6rem;"
                                class="fa fa-eye-slash d-none"></span>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <button type="submit" class="form-control btn btn-sm  submit px-3 font-weight-bolder"
                                id="signin_submit"
                                style="background-color:rgba(17, 47, 122,0.8);border-radius:15px; font-size:1.2rem; letter-spacing:1.8px; font-family:B Nazanin;color:#fff">
                                {{ trans('words.Login') }}
                            </button>
                        </div>
                    </form> --}}
                    </div>
                </div>
            </div>
        </section>
    </body>
    <script src="{{ asset('jquery-3.7.0.min.js') }}"></script>
    <script>
        $('#toShowPassword').click(function() {
            $('#toShowPassword').addClass('d-none');
            $('#toHidePassword').removeClass('d-none');
            $('#password').attr('type', 'text');
        });
        $('#toHidePassword').click(function() {
            $('#toShowPassword').removeClass('d-none');
            $('#toHidePassword').addClass('d-none');
            $('#password').attr('type', 'password');
        });

        var timeout = ({{ config('session.lifetime') }} * 60000) - 10000;
        setTimeout(function() {
            window.location.reload(1);
        }, timeout);
    </script>

</html>
