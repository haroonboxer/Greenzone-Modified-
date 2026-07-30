@extends('layouts.master')
@section('header-menu')
    @include('layouts.menu.user_management-menu')
@endsection
@section('header')
    {{ trans('words.Roles') }}
@endsection
@section('content')
    <div class="card">
        <div class="card-header container-fluid p-4">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mt-3"> <i class="fas fa-sitemap text-primary"></i>&nbsp;
                    <b>{{ trans('global.directorateList') }}</b>
                </h5>
                <div>
                    <a href="javascript:void()"
                        onclick="javascript: show_modal_by_ajax('{{ route('create-directorate') }}','ajax_modal');"
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
            <div class="code notranslate cssHigh collapse table-responsive" id="collapseDiv">
                <div class="m-portlet__body table-responsive">
                    <form id="search_form" autocomplete="off">
                        <div class="row d-flex p-3" style="background-color: #f5f5f5">
                            <div class="col-lg-3">
                                <label class="title-custom">{{ trans('global.directorate') }}:</label>
                                <input type='search' class='form-control keypressbutton' style='' name='search_name'
                                    id="search_name" placeholder="{{ trans('global.directorate') }}" />
                            </div>
                        </div>
                        <div class="row d-flex p-3" style="background-color: #fafafa">
                            <div class="col-lg-12 mt-4">
                                <div class="m-form__actions m-form__actions--slid">
                                    <button type="submit" id="add" class="btn btn-primary"> <b><i
                                                class="fas fa-search"></i>
                                            {{ trans('global.search') }}</b></button> &nbsp;
                                    <button type="button" class="btn btn-warning" onclick="RestForm('search_form')">
                                        <b><i class="fas 	fa-eraser"></i> {{ trans('global.reset') }}</b></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Card content goes here -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-checkable data-table" id="kt_datatable"
                    style="margin-top: 13px !important">
                    <thead>
                        <tr>
                            <th class="text-center">نمبر</th>
                            <th class="text-center">{{ trans('global.name_da') }}</th>
                            <th class="text-center">{{ trans('global.name_pa') }}</th>
                            <th class="text-center">{{ trans('global.name_en') }}</th>
                            <th class="text-center">{{ trans('global.code') }}</th>
                            <th class="text-center">{{ trans('global.owner') }}</th>
                            <th class="text-center">{{ trans('global.action') }}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('js-code')
    <script>
        $(function() {
            //usage
            $(".datePicker").persianDatepicker({
                cellWidth: 45,
                cellHeight: 35,
                fontSize: 14
            });
            $(".datePicker").focus(function() {
                $(this).blur();
            });
        });

        function dataTable() {
            var params = $('#searchForm').serialize();
            $('#kt_datatable').DataTable({
                responsive: true,
                searchDelay: 500,
                processing: true,
                serverSide: true,
                "bInfo": true,
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "aaSorting": [
                    [0, "desc"]
                ],
                "info": true,
                "language": {
                    "sProcessing": 'لطفا منتظر باشد...<span class="spinner spinner-primary ml-10"></span>',
                    "sSearch": "جستجو",
                    "lengthMenu": "نمایش _MENU_ معلومات",
                    "info": "معلومات شماره _START_ الی _END_ مجموعه معلومات _TOTAL_",
                    "paginate": {
                        "previous": "قبلی",
                        "next": "بعدی",
                    },
                    "sEmptyTable": "دیتا موجود نیست"
                },
                ajax: {
                    url: "{{ route('directorate') }}",
                    data: {
                        name: $('#search_name').val(),
                    },
                },
                columns: [{
                        "data": 'id'
                    },
                    {
                        "data": 'name_da'
                    },
                    {
                        "data": 'name_pa'
                    },
                    {
                        "data": 'name_en'
                    },
                    {
                        "data": 'code'
                    },
                    {
                        "data": 'ownerName'
                    },
                    {
                        "data": 'action'
                    }
                ]
            });
        }

        $(document).ready(function() {

            dataTable();
            $('#search_form').submit(function(event) {
                event.preventDefault();
                if ($('#search_name').val() == '' && $('#search_province').val() == 'default' && $(
                        '#search_district').val() == 'default') {
                    warning('معلومات را درج نماید!')
                } else {
                    $('#kt_datatable').DataTable().destroy();
                    dataTable();
                }

            });
        });


        function RestForm(form_id) {
            $('#kt_datatable').DataTable().destroy();
            document.getElementById(form_id).reset();
            $('.select2').val('').trigger('change.select2');
            dataTable();
        }
    </script>
@endsection;
