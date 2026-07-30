@if ($type == 'store')
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
                                    <input type="checkbox" value="{{ $permission[$i]->id }}" name="permissions[]"
                                        id="add_role{{ $permission[$i]->id }}">
                                    <label for="add_role{{ $permission[$i]->id }}">{{ $permission[$i]->name }}</label>
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
                                    <input type="checkbox" value="{{ $permission[$i]->id }}" name="permissions[]"
                                        id="add_role{{ $permission[$i]->id }}">
                                    <label for="add_role{{ $permission[$i]->id }}">{{ $permission[$i]->name }}</label>
                                </li>
                            @endfor
                        </ul>
                    </div>
                </div>
            </fieldset>
        </div>
    </div>
@endif
@if ($type == 'edit')
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
                                    <input type="checkbox" value="{{ $permission[$i]->id }}" name="permissions[]"
                                        {{ $role->hasPermissionTo($permission[$i]->name) == true ? 'checked' : '' }}
                                        id="add_role{{ $permission[$i]->id }}">
                                    <label for="add_role{{ $permission[$i]->id }}">{{ $permission[$i]->name }}</label>
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
                                    <input type="checkbox" value="{{ $permission[$i]->id }}" name="permissions[]"
                                        {{ $role->hasPermissionTo($permission[$i]->name) == true ? 'checked' : '' }}
                                        id="add_role{{ $permission[$i]->id }}">
                                    <label for="add_role{{ $permission[$i]->id }}">{{ $permission[$i]->name }}</label>
                                </li>
                            @endfor
                        </ul>
                    </div>
                </div>
            </fieldset>
        </div>
    </div>
@endif
@if ($type == 'role_details')
    <select class="form-control" multiple name="roles[]" id="roles" required>
        @foreach ($roles as $item)
            <option value="{{ $item->name }}" style="direction: ltr !important;">{{ $item->name }}</option>
        @endforeach
    </select>
@endif
@if ($type == 'edit_role_details')
    <select class="form-control" multiple name="roles[]" id="edit_roles" required>
        @foreach ($roles as $item)
            <option value="{{ $item->name }}" style="direction: ltr !important;"
                {{ \App\Models\User::find($user_id)->hasRole($item->name) ? 'selected' : '' }}>{{ $item->name }}
            </option>
        @endforeach
    </select>
@endif
