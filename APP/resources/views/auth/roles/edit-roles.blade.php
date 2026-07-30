<div class="modal-header bg-color-dark">
    <h3>
        <span class="profile-main-title white-color"><i
                class="m-menu__link-icon fas fa-sitemap text-primary"></i>&nbsp;&nbsp;{{ trans('global.createDirectorate') }}</span>
    </h3>
    <button type="button" class="close white-color" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;
    </button>
</div>
<style>
    fieldset {
        border: 1px solid #ddd !important;
        min-width: 0;
        width: 100%;
        padding: 10px;
        position: relative;
        border-radius: 6px;
        background-color: #f3f3f3;
        margin-top: 10px;
        padding-left: 10px !important;
    }

    legend {
        font-size: 14px;
        font-weight: bold;
        margin-bottom: 0px;
        width: 55%;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 5px 5px 5px 10px;
        color: white;
        background-color: #3B3F51;
        opacity: 0.8;
    }
</style>
<div class="modal-body">
    <div class="row">
        <div class="col-lg-12">
            <form enctype="multipart/form-data" id="requestForm" method="post">
                @csrf
                <div class="row">
                    <div class=" col-lg-6">
                        <label for="system_id">{{ trans('words.Systems') }}</label>
                        <select class="form-control select2" name="system_id" id="system_id"
                            onchange="bringDistricts('{{ route('permission-details', 'store') }}',this.value,'GET','main-form-permissions','1');">
                            <option value="">--
                                {{ trans('words.Select_', ['name' => trans('words.Systems')]) }} --</option>
                            @foreach ($systems as $item)
                                <option value="{{ $item->id }}"
                                    {{ $item->id == $roles->system_id ? 'selected' : '' }}>
                                    {{ $item->name }}</option>
                            @endforeach
                        </select>
                        <div class="system_id error-div" style="display:none;"></div>
                    </div>
                    <div class=" col-lg-6">
                        <label>{{ trans('words.Name') }}</label>
                        <input type="text" class="form-control" name="name" value="{{ $roles->name }}">
                        <div class="invalid-feedback name_error"></div>
                    </div>
                    <div class="form-group col-12" id="main-form-permissions">
                        @if (count($permission) > 0)
                            <div class="row">
                                <div class="col-xs-12 col-md-6">
                                    <fieldset>
                                        <legend>
                                            {{ trans('words.Sections') }}
                                        </legend>
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <ul>
                                                    @php
                                                        $total_records = count($permission);
                                                    @endphp
                                                    @for ($i = 0; $i < $total_records / 2; $i++)
                                                        <li style="overflow-wrap: anywhere;">
                                                            <input type="checkbox" value="{{ $permission[$i]->id }}"
                                                                name="permissions[]"
                                                                {{ $roles->hasPermissionTo($permission[$i]->name) == true ? 'checked' : '' }}
                                                                id="add_role{{ $permission[$i]->id }}">
                                                            <label
                                                                for="add_role{{ $permission[$i]->id }}">{{ $permission[$i]->name }}</label>
                                                        </li>
                                                    @endfor
                                                </ul>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-xs-12 col-md-6">
                                    <fieldset>
                                        <legend>
                                            {{ trans('words.Sections') }}
                                        </legend>
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <ul>
                                                    @for ($i = $i; $i < $total_records; $i++)
                                                        <li style="overflow-wrap: anywhere;">
                                                            <input type="checkbox" value="{{ $permission[$i]->id }}"
                                                                name="permissions[]"
                                                                {{ $roles->hasPermissionTo($permission[$i]->name) == true ? 'checked' : '' }}
                                                                id="add_role{{ $permission[$i]->id }}">
                                                            <label
                                                                for="add_role{{ $permission[$i]->id }}">{{ $permission[$i]->name }}</label>
                                                        </li>
                                                    @endfor
                                                </ul>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>

                        @endif
                    </div>
                    <div class="permissions error-div" style="display:none;"></div>
                </div>
        </div>
        <div class="row p-5 mt-3" style="background-color: #fafafa">
            <div class="col-lg-12">
                <button type="button"
                    onclick="server_request_for_storing_record('{{ route('role-update', $roles->id) }}','requestForm','POST','response_div','submitBtn');"
                    id="submitBtn" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i>
                    {{ trans('global.submit') }}</button>
                <button class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-times"></i> {{ trans('global.close') }}</span>
                </button>
            </div>
        </div>
        </form>
    </div>
</div>
</div>
