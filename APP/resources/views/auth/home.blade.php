@extends('layouts.master')

@section('header-menu')
    @include('layouts.menu.user_management-menu')
@endsection
@section('header')
    {{ trans('words.Dashboard') }}
@endsection
<style>
    a {
        color: #153a81;
        background: none;
        text-decoration: none;
    }

    a :hover {
        cursor: pointer;
        color: #153a81;
        text-decoration: none;
    }

    .card {
        box-shadow: 0 0 10px #ccc;
        transition: transform .3s ease;
    }

    .content {
        padding: unset !important;
    }

    div .card:hover {
        color: #000000;
        border-radius: 5px;
        box-shadow: 0 0 15px #153a81;
        -webkit-transform: scale(1.05);
    }
</style>
@section('content')
    <div class="row mt-8">
        <div class="col-sm-12 col-md-4 mb-1">
            <div class="card h-100 bg-hover-gray-100">
                <a href="{{ route('users') }}" class="text-dark">
                    <div class="card-body p-9 text-dark">
                        <div class="d-flex justify-content-around">
                            <div>
                                <img src="{{ asset('icons/group.png') }}" alt="" style="max-width: 40px !important;">
                            </div>
                            <div class="fs-2hx font-weight-bolder font-size-h1 ">
                                {{ trans('words.Total_', ['name' => trans('words.Users')]) }}
                            </div>
                            <div class="fs-2hx font-weight-bolder font-size-h1 ">
                                {{ App\Models\User::count('id') }}
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-sm-12 col-md-4 mb-1">
            <div class="card h-100 bg-hover-gray-100">
                <a href="{{ route('roles') }}" class="text-dark">
                    <div class="card-body p-9 text-dark">
                        <div class="d-flex justify-content-around">
                            <div>
                                <img src="{{ asset('icons/role.png') }}" alt="" style="max-width: 40px !important;">
                            </div>
                            <div class="fs-2hx font-weight-bolder font-size-h1 ">
                                {{ trans('words.Total_', ['name' => trans('words.Roles')]) }}
                            </div>
                            <div class="fs-2hx font-weight-bolder font-size-h1 ">
                                {{ Spatie\Permission\Models\Role::count('id') }}
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-sm-12 col-md-4 mb-1">
            <div class="card h-100 bg-hover-gray-100 text-dark">
                <div class="card-body p-9">
                    <div class="d-flex justify-content-around">
                        <div>
                            <img src="{{ asset('icons/permissions.png') }}" alt=""
                                style="max-width: 40px !important;">
                        </div>
                        <div class="fs-2hx font-weight-bolder font-size-h1 ">
                            {{ trans('words.Total_', ['name' => trans('words.Permissions')]) }}
                        </div>
                        <div class="fs-2hx font-weight-bolder font-size-h1 ">
                            {{ $permission = Spatie\Permission\Models\Permission::count('id') }}
                        </div>
                    </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
