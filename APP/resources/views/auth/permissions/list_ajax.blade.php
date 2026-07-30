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
                        <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
                    url: "{{ route('permission-index') }}",
                    data: dataString,
                    type: 'get',
                    beforeSend: function() {
                        $('#searchresult').html(
                            '<div class="loading d-flex justify-content-center w-100"> <span></span> <span></span> <span></span> <span></span> <span></span> </div>'
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
