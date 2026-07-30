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
    <div class="card">
        <div class="card-header container-fluid p-4">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mt-3"> <i class="fas fa-users text-primary"></i>&nbsp;
                    <b>{{ trans('global.userList') }}</b>
                </h5>
                <div>
                    <a href="javascript:void()" onclick="addRecord('{{ route('create-user') }}','','GET','response_div')"
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
                        <input class="form-control m-input" type="text" name="nama" id="keyWord"
                            placeholder="{{ trans('global.search') }}"
                            onkeyup="filterRecords('{{ route('users') }}','GET','searchresult')">
                    </div>
                </form>
            </div>
            <!-- Card content goes here -->
            <div class="table-responsive" id="searchresult">
                <table class="table table-bordered table-hover table-checkable data-table" id="kt_datatable"
                    style="margin-top: 13px !important">
                    <thead>
                        <tr>
                            <th class="text-center">{{ trans('global.id') }}</th>
                            <th class="text-center">{{ trans('global.username') }}</th>
                            <th class="text-center">{{ trans('global.name') }}</th>
                            <th class="text-center">{{ trans('global.email') }}</th>
                            <th class="text-center">{{ trans('global.directorate') }}</th>
                            <th class="text-center">{{ trans('global.department') }}</th>
                            <th class="text-center">{{ trans('global.departmentSub') }}</th>
                            <th class="text-center">{{ trans('global.location') }}</th>
                            <th class="text-center">{{ trans('global.status') }}</th>
                            <th class="text-center">{{ trans('global.action') }}</th>
                        </tr>
                    </thead>
                    <tbody style="width: auto;overflow-x: auto;white-space: nowrap;">
                        @php $i=0; @endphp
                        @foreach ($record as $key => $rec)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $rec->username }}</td>
                                <td>{{ $rec->name }}</td>
                                <td>{{ $rec->email }}</td>
                                <td>{{ $rec->directorateName }}</td>
                                <td>{{ $rec->departmentName }}</td>
                                <td>{{ $rec->subDepartmentName }}</td>
                                <td>{{ $rec->provinceName }}</td>
                                <td>
                                    @if ($rec->deleted_at != '')
                                        <span class="badge badge-danger mr-1 mb-1">{{ trans('global.inactive') }}</span>
                                    @else
                                        <span class="badge badge-primary mr-1 mb-1">{{ trans('global.active') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-primary btn-sm dropdown-toggle" type="button"
                                            id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            {{ trans('global.action') }}
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item" href="javascript:void()"
                                                onclick="addRecord('{{ route('view-user', $rec->id) }}','','GET','response_div')">
                                                <i class="fas fa-eye text-primary"></i>&nbsp;
                                                <b>
                                                    {{ trans('global.view') }}
                                                </b>
                                            </a>
                                            @if ($rec->deleted_at != '')
                                                <a class="dropdown-item" href="javascript:void()"
                                                    onclick="destroy('{{ route('restore-user', $rec->id) }}','','GET','response_div')">
                                                    <i class="fas fa-user-check text-primary"></i>&nbsp;
                                                    <b>
                                                        {{ trans('global.userActive') }}
                                                    </b>
                                                </a>
                                            @else
                                                <a class="dropdown-item" href="javascript:void()"
                                                    onclick="destroy('{{ route('destroy-user', $rec->id) }}','','GET','response_div')">
                                                    <i class="fas fa-trash text-danger"></i>&nbsp;
                                                    <b>
                                                        {{ trans('global.userDeactive') }}
                                                    </b>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <!-- Pagination -->
                @if (!empty($record))
                    {!! $record->links('pagination') !!}
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
                    filter('{{ route('users') }}', 'GET', 'searchresult', 'searchForm');
                }
            });
        });
    </script>
@endsection
