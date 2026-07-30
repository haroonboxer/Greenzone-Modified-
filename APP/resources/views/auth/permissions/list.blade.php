@extends('layouts.master')
@section('header-menu')
    @include('layouts.menu.user_management-menu')
@endsection
@section('header')
    {{ trans('words.Roles') }}
@endsection
@section('button')
    @can('role-create')
        <button type="button" class="btn btn-light-primary font-weight-bolder" data-toggle="modal"
            data-target="#add_modal">{{ trans('words.New Role') }}</button>
    @endcan
@endsection
@section('content')
    @if (Session::has('success'))
        <script>
            success("{{ Session::get('success') }}");
        </script>
    @endif
    <div class="card">
        <div class="card-header container-fluid p-4">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mt-3"> <i class="fas fa-sitemap text-primary"></i>&nbsp;
                    <b>{{ trans('ACU.list') }}</b>
                </h5>
                <div>
                    <a href="javascript:void()"
                        onclick="addRecord('{{ route('permission-create') }}','','GET','response_div')"
                        class="btn btn-primary m-btn m-btn--pill btn-sm  m-btn--icon m-btn--air">
                        <span><i class="fas	fa-save"></i><span>{{ trans('global.add') }}</span></span>
                    </a>
                    <a class="btn btn-secondary m-btn--icon btn-sm" id="collapsBtn" data-toggle="collapse"
                        href="#collapseDiv" role="button" aria-expanded="true" aria-controls="collapseDiv">
                        <span><i class="la la-arrows-v"></i><span>{{ trans('global.search') }}</span></span>
                    </a>

                    <a href="{{ route('home', session('current_mod')) }}" class="btn btn-danger m-btn--icon btn-sm">
                        <span><i class="fas fa-reply-all"></i> <span>{{ trans('global.back_home') }}</span></span>
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="code notranslate cssHigh collapse p-4 " id="collapseDiv">
                <form enctype="multipart/form-data" id="searchForm" method="get" autocomplete="off">
                    <div class="row d-flex p-3" style="background-color: #f5f5f5">
                        <input class="form-control m-input" type="text" name="keyWord" id="keyWord"
                            placeholder="{{ trans('global.search') }}"
                            onkeyup="filterRecords('{{ route('permission-index') }}','GET','searchresult')">
                    </div>
                </form>
            </div>
            <!-- Card content goes here -->
            <div class="table-responsive" id="searchresult">
                <table class="table table-striped- table-bordered table-hover table-checkable" style="font-size:14px;">
                    <thead>
                        <tr>
                            <th>{{ trans('global.id') }}</th>
                            <th>{{ trans('global.nameRole') }}</th>
                            <th>{{ trans('global.system') }}</th>
                            <th width="10%">{{ trans('global.action') }}</th>
                        </tr>
                    </thead>
                    <tbody style="width: auto;overflow-x: auto;white-space: nowrap;">
                        @php $i=0; @endphp
                        @foreach ($permissions as $key => $per)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $per->name }}</td>
                                <td>{{ $per->system_name }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-primary btn-sm dropdown-toggle" type="button"
                                            id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            {{ trans('global.action') }}
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item" href="javascript:void()"
                                                onclick="destroy('{{ route('destroy-permission', $per->id) }}','','GET','response_div')">
                                                <i class="fas fa-trash text-danger"></i>&nbsp;
                                                <b>
                                                    {{ trans('global.delete') }}
                                                </b>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <!-- Pagination -->
                @if (!empty($permissions))
                    {!! $permissions->links('pagination') !!}
                @endif
            </div>
        </div>
    </div>
@endsection
@section('js-code')
    <script>
        $(function() {
            $(document).on("keypress", ".keypressbutton", function(event) {
                var keyCode = event.which || event.keyCode;
                if (keyCode === 13) {
                    event.preventDefault();
                    filter('{{ route('acu-area') }}', 'GET', 'searchresult', 'searchForm');
                }
            });
        });
    </script>
@endsection
