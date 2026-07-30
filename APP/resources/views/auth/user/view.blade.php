<div class="card">
    <div class="card-header container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mt-3"> <i class="fas fa-user-plus text-primary"></i>&nbsp;
                <b>{{ trans('global.viewUser') }}</b>
            </h5>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-12">
                <div style="background-color:#FAFAFA" class="mb-2">
                    <div class="row">
                        <div class="col-lg-9">
                            <div class="row pl-3">
                                <div class="col-lg-4">
                                    <label for="name" class="title-custom">
                                        {{ trans('words.Name And Last Name') }}:
                                    </label><br />
                                    <span class="data-custom">{{ $data->name }}</span>

                                </div>
                                <div class="col-lg-4">
                                    <label for="username" class="title-custom">
                                        {{ trans('words.Username') }}:
                                    </label><br />
                                    <span class="data-custom">{{ $data->username }}</span>
                                </div>
                                <div class="col-lg-4">
                                    <label for="email" class="title-custom">
                                        {{ trans('words.Email') }}:
                                    </label><br />
                                    <span class="data-custom">{{ $data->email }}</span>
                                </div>
                            </div>
                            <div class="row pl-3 mt-2">
                                <div class=" col-lg-4">
                                    <label for="directoreate" class="title-custom">
                                        {{ trans('global.directoreate') }}:
                                    </label><br />
                                    <span class="data-custom">{{ $data->directorateName }}</span>
                                </div>

                                <div class=" col-lg-4">
                                    <label for="province" class="title-custom">
                                        {{ trans('global.department') }}:
                                    </label><br />
                                    <span class="data-custom">{{ $data->departmentName }}</span>
                                </div>

                                <div class=" col-lg-4">
                                    <label for="departmentSub" class="title-custom">
                                        {{ trans('global.departmentSub') }}:
                                    </label><br />
                                    <span class="data-custom">{{ $data->subDepartmentName }}</span>
                                </div>
                            </div>
                            <div class="row pl-3 mt-2">
                                <div class=" col-lg-4">
                                    <label for="province" class="title-custom">
                                        {{ trans('global.province') }}:
                                    </label><br />
                                    <span class="data-custom">{{ $data->provinceName }}</span>
                                </div>

                                <div class=" col-lg-4">
                                    <label for="system" class="title-custom">
                                        {{ trans('global.system') }}:
                                    </label><br />
                                    @foreach ($system as $sys)
                                        <span class="data-custom badge badge-primary">{{ $sys->name }}</span>
                                    @endforeach
                                </div>
                                <div class=" col-lg-4">
                                    <label for="system" class="title-custom">
                                        {{ trans('global.role') }}:
                                    </label><br />
                                    @foreach ($role as $r)
                                        <span class="data-custom badge badge-primary">{{ $r->name }}</span>
                                    @endforeach
                                </div>
                                <div class=" col-lg-4">
                                    <label for="system" class="title-custom">
                                        {{ trans('global.status') }}:
                                    </label><br />
                                    <span class="data-custom">{{ $data->status }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="row col-lg-12">
                                <div class="col-lg-6">
                                    <label class="title-custom">{{ trans('global.image') }}:</label><br>
                                    <label style="cursorLpointer">
                                        <img src="{{ asset($data->image != '' ? 'storage/' . $data->image : 'img/user_male.png') }}"
                                            id="output" class="image responsive img-thumbnail"
                                            style="width:80%; cursor: pointer; margin-top: 10px;"
                                            onclick="$('#recimage').click();">
                                        <div class="image error-div" style="display:none;"></div>
                                    </label>
                                </div>
                                <div class="col-lg-6">
                                    <label class="title-custom">{{ trans('home.signature') }}:</label><br>
                                    <label style="cursorLpointer">
                                        <img src="{{ asset($data->signature != '' ? 'storage/' . $data->signature : 'img/finger.png') }}"
                                            id="signature" class="image responsive img-thumbnail"
                                            style="width:80%; cursor: pointer; margin-top: 10px;"
                                            onclick="$('#recimage').click();">
                                        <div class="signature error-div" style="display:none;">
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row p-5 mt-3" style="background-color: #fafafa">
                    <div class="col-lg-12">
                        <button type="button"
                            onclick="addRecord('{{ route('edit-user', $data->id) }}','','GET','response_div')"
                            id="submitBtn" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i>
                            {{ trans('global.edit') }}
                        </button>
                        <a class="btn btn-danger btn-sm" href="{{ route('users') }}">
                            <span aria-hidden="true"><i class="fas fa-times"></i>
                                {{ trans('global.back') }}
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
