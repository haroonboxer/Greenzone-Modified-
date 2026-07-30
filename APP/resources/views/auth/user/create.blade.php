<div class="card">
    <div class="card-header container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mt-3"> <i class="fas fa-user-plus text-primary"></i>&nbsp;
                <b>{{ trans('global.crateUser') }}</b>
            </h5>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-12">
                <form enctype="multipart/form-data" id="requestForm" method="post" autocomplete="off">
                    @csrf
                    <div style="background-color:#FAFAFA" class="mb-2">
                        <div class="row">
                            <div class="col-lg-9">
                                <div class="row pl-3">
                                    <div class="col-lg-4">
                                        <label for="name" class="col-form-label">
                                            {{ trans('words.Name And Last Name') }}:
                                            <span style="color:red;">*</span>
                                        </label>
                                        <input type="text" class="form-control" name="name" id="name">
                                        <div class="name error-div" style="display: none"></div>
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="username" class="col-form-label">
                                            {{ trans('words.Username') }}:
                                            <span style="color:red;">*</span>
                                        </label>
                                        <input type="text" class="form-control" name="username" id="username">
                                        <div class="username error-div" style="display: none"></div>
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="email" class="col-form-label">
                                            {{ trans('words.Email') }}:
                                            <span style="color:red;">*</span>
                                        </label>
                                        <input id="email" type="email" class="form-control" name="email">
                                        <div class="email error-div" style="display: none"></div>
                                    </div>
                                </div>
                                <div class="row pl-3 mt-2">
                                    <div class=" col-lg-4">
                                        <label for="directoreate" class="title-custom">
                                            {{ trans('global.directoreate') }}:
                                            <span style="color:red;">*</span>
                                        </label>
                                        <select class="form-control select2" name="directorate" id="directorate"
                                            onchange="bring_districts('{{ route('bring-department-directorate-id') }}',this.value,'POST','department_response','1');">
                                            <option value="">
                                                {{ trans('global.select_', ['name' => trans('global.directoreate')]) }}
                                            </option>
                                            @foreach ($directorates as $direct)
                                                <option value="{{ $direct->id }}">{{ $direct->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="directorate error-div" style="display:none;"></div>
                                    </div>

                                    <div class=" col-lg-4">
                                        <label for="province" class="title-custom">
                                            {{ trans('global.department') }}:
                                            <span style="color:red;">*</span>
                                        </label>
                                        <select class="form-control select2" name="department" id="department_response"
                                            onchange="bring_districts('{{ route('bring-sub-department-department-id') }}',this.value,'POST','departmentSub','1');">
                                            <option value="">
                                                {{ trans('global.select_', ['name' => trans('global.department')]) }}
                                            </option>
                                        </select>
                                        <div class="department error-div" style="display:none;"></div>
                                    </div>

                                    <div class=" col-lg-4">
                                        <label for="departmentSub" class="title-custom">
                                            {{ trans('global.departmentSub') }}:
                                            <span style="color:red;">*</span>
                                        </label>
                                        <select class="form-control select2" name="departmentSub" id="departmentSub">
                                            <option value="">
                                                {{ trans('global.select_', ['name' => trans('global.departmentSub')]) }}
                                            </option>
                                        </select>
                                        <div class="departmentSub error-div" style="display:none;"></div>
                                    </div>
                                </div>
                                <div class="row pl-3 mt-2">
                                    <div class=" col-lg-4">
                                        <label for="province" class="title-custom">
                                            {{ trans('global.province') }}:
                                            <span style="color:red;">*</span>
                                        </label>
                                        <select class="form-control select2" name="province" id="province">
                                            <option value="">
                                                {{ trans('global.select_', ['name' => trans('global.province')]) }}
                                            </option>
                                            @foreach ($provinces as $pro)
                                                <option value="{{ $pro->id }}">{{ $pro->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="province error-div" style="display:none;"></div>
                                    </div>

                                    <div class=" col-lg-4">
                                        <label for="system" class="title-custom">
                                            {{ trans('global.system') }}:
                                            <span style="color:red;">*</span>
                                        </label>
                                        <select class="form-control select2" name="system_id[]" id="system_id" multiple>
                                            <option value="" disabled>
                                                {{ trans('global.select_', ['name' => trans('global.system')]) }}
                                            </option>
                                            @foreach ($systems as $sys)
                                                <option value="{{ $sys->id }}">{{ $sys->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="system_id error-div" style="display:none;"></div>
                                    </div>
                                    <div class=" col-lg-4">
                                        <label for="system" class="title-custom">
                                            {{ trans('global.role') }}:
                                            <span style="color:red;">*</span>
                                        </label>
                                        <div id="section_result">
                                            <select class="form-control select2" name="roles[]" id="roles"
                                                multiple placeholder="select">
                                                <option value="" disabled>
                                                    {{ trans('global.select_', ['name' => trans('global.role')]) }}
                                                </option>
                                            </select>
                                        </div>
                                        <div class="roles error-div" style="display:none;"></div>
                                    </div>
                                    <div class=" col-lg-4">
                                        <label for="system" class="title-custom">
                                            {{ trans('global.status') }}:
                                            <span style="color:red;">*</span>
                                        </label>
                                        <div id="section_result">
                                            <select class="form-control select2" name="status" id="status">
                                                <option value="">
                                                    {{ trans('global.select_', ['name' => trans('global.status')]) }}
                                                </option>
                                                <option value="active">
                                                    {{ trans('global.active') }}
                                                </option>
                                                <option value="inactive">
                                                    {{ trans('global.inactive') }}
                                                </option>
                                            </select>
                                        </div>
                                        <div class="status error-div" style="display:none;"></div>
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="password" class="title-custom">{{ trans('words.Password') }}:
                                            <span style="color:red;">*</span>
                                        </label>
                                        <input type="password" class="form-control" name="password" id="password">
                                        <div class="password error-div" style="display: none"></div>
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="password_confirmation" class="title-custom">
                                            {{ trans('words.Confirm Password') }}:
                                            <span style="color:red;">*</span>
                                        </label>

                                        <input type="password" class="form-control" name="password_confirmation"
                                            id="password_confirmation" onkeyup="ConfirmPassword()">
                                        <div class="password_confirmation error-div" style="display: none"></div>
                                        <span id="msg"><span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="row col-lg-12">
                                    <div class="col-lg-6">
                                        <label class="title-custom">{{ trans('global.image') }}:</label><br>
                                        <label style="cursorLpointer">
                                            <input type="file" name="image" id="image" class="textbox"
                                                accept="image/*" onchange="loadFileImage(event)" hidden>
                                            <img src="{{ asset('img/user_male.png') }}" id="output"
                                                class="image responsive img-thumbnail"
                                                style="width:80%; cursor: pointer; margin-top: 10px;"
                                                onclick="$('#recimage').click();">
                                            <div class="image error-div" style="display:none;"></div>
                                        </label>
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="title-custom">{{ trans('home.signature') }}:</label><br>
                                        <label style="cursorLpointer">
                                            <input type="file" name="signature" id="file" class="textbox"
                                                accept="image/*" onchange="loadFileSignature(event)" hidden>
                                            <img src="{{ asset('img/finger.png') }}" id="signature"
                                                class="image responsive img-thumbnail"
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
                                onclick="save_record('{{ route('store-user') }}','requestForm','POST','response_div','submitBtn');"
                                id="submitBtn" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i>
                                {{ trans('global.submit') }}
                            </button>
                            <a class="btn btn-danger btn-sm" href="{{ route('users') }}">
                                <span aria-hidden="true"><i class="fas fa-times"></i>
                                    {{ trans('global.close') }}
                                </span>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).on('change', '#system_id', function() {
        var value = $(this).val();
        if (value == '') {
            $('#main-form-permissions').html('');
            return false;
        }
        $.ajax({
            url: "{{ route('role-details', 'details') }}",
            type: 'get',
            data: {
                'system_id': value
            },
            dataType: 'html',
            beforeSend: function() {
                $(".m-page-loader.m-page-loader--base").css("display", "block");
            },
            success: function(data) {
                $('#section_result').html(data);
                $('#section_result').show();
                $('#roles').select2({
                    width: '100%'
                });
                $(".m-page-loader.m-page-loader--base").css("display", "none");
            },
            error: function() {
                error_function("{{ trans('words.Please Try Again') }}");
                $(".m-page-loader.m-page-loader--base").css("display", "none");
            }
        });
    });
    var loadFileImage = function(event, id) {
        var image = document.getElementById('output');
        image.src = URL.createObjectURL(event.target.files[0]);
    }
    var loadFileSignature = function(event, id) {
        var image = document.getElementById('signature');
        image.src = URL.createObjectURL(event.target.files[0]);
    }

    function ShowHideConf() {
        if ($('#password_confirmation').attr("type") == "text") {
            $('#password_confirmation').attr('type', 'password');
            $('#icon-passConf').addClass("la-eye");
            $('#icon-passConf').removeClass("la-eye-slash");
        } else if ($('#password_confirmation').attr("type") == "password") {
            $('#password_confirmation').attr('type', 'text');
            $('#icon-passConf').removeClass("la-eye");
            $('#icon-passConf').addClass("la-eye-slash");
        }
    }

    function ConfirmPassword() {
        var pass = $('#password').val();
        var conf = $('#password_confirmation').val();
        if (conf == pass) {
            $("#msg").css("color", "green");
            $('#msg').html("{{ trans('global.pass_match') }}");
        } else {
            $("#msg").css("color", "red");
            $('#msg').html("{{ trans('global.pass_not_match') }}");
        }
    }
</script>
