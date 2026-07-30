<table class="table table-bordered table-hover table-checkable data-table" id="kt_datatable"
    style="margin-top: 13px !important">
    <thead>
        <tr>
            <th class="text-center">نمبر</th>
            <th class="text-center">{{ trans('global.username') }}</th>
            <th class="text-center">{{ trans('global.name') }}</th>
            <th class="text-center">{{ trans('global.email') }}</th>
            <th class="text-center">{{ trans('global.directorate') }}</th>
            <th class="text-center">{{ trans('global.department') }}</th>
            <th class="text-center">{{ trans('global.departmentSub') }}</th>
            <th class="text-center">{{ trans('global.location') }}</th>
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
                    <div class="dropdown">
                        <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ trans('global.action') }}
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="javascript:void()"
                                onclick="destroy('{{ route('destroy-permission', $rec->id) }}','','GET','response_div')">
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
@if (!empty($record))
    {!! $record->links('pagination') !!}
@endif
<script type="text/javascript">
    $(document).ready(function() {
        $('.pagination a').on('click', function(event) {
            event.preventDefault();
            if ($(this).attr('href') != '#') {
                document.cookie = "no=" + $(this).text();
                var dataString = '';
                item = $('#keyWord').val();
                var params = $('#item').val();
                dataString += "&page=" + $(this).attr('id') + "&ajax=" + 1 + "&params=" + params +
                    "&type=search";
                $.ajax({
                    url: "{{ route('users') }}",
                    data: dataString,
                    type: 'get',
                    beforeSend: function() {
                        $('#searchresult').html(
                            '<span style="position:relative;left:30%;"><img alt="" src="{{ asset('img/loader.gif') }}" /></span>'
                        );
                    },
                    success: function(response) {
                        $('#searchresult').html(response);
                    }
                });
            }
        });
    });
</script>
