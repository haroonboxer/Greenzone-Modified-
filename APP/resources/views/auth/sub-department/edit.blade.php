<div class="modal-header bg-color-dark">
    <h3>
        <span class="profile-main-title white-color"><i
                class="m-menu__link-icon fas fa-sitemap text-primary"></i>&nbsp;&nbsp;{{ trans('global.departmentSubEdit') }}</span>
    </h3>
    <button type="button" class="close white-color" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;
    </button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-lg-12">
            <form enctype="multipart/form-data" id="requestForm" method="post">
                @csrf
                <input type="hidden" id="edit_record_id" name="id">
                <div class="row">
                    <div class=" col-lg-4">
                        <label for="directorate" class="title-custom">{{ trans('global.directorate') }}:
                            <span style="color:red;">*</span>
                        </label>
                        <select class="form-control select2" name="directorate" id="directorate"  onchange="bringDistricts('{{ route('bring-department-by-directorate-id') }}',this.value,'POST','department_response','1');">
                            <option value="">
                                {{ trans('global.select_', ['name' => trans('global.directorate')]) }}</option>
                            @foreach ($directorates as $direc)
                                <option value="{{ $direc->id }}" {{$direc->id == $subDepartment->directorate_id ? 'selected' : ''}}>{{ $direc->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-4">
                        <label for="department" class="title-custom">{{ trans('global.department') }}:
                            <span style="color:red;">*</span>
                        </label>
                        <div id="department_response">
                            <select class="form-control select2" name="department" id="department">
                                <option value="">
                                    {{ trans('global.select_', ['name' => trans('global.department')]) }}</option>
                                    @foreach ($departments as $dep)
                                        <option value="{{ $dep->id }}" {{$dep->id == $subDepartment->department_id ? 'selected' : ''}}>{{ $dep->name }}</option>
                                    @endforeach
                            </select>
                        </div>
                        <div class="department error-div" style="display:none;"></div>
                    </div>
                    <div class=" col-lg-4">
                        <label for="name_da" class="title-custom">{{ trans('global.name_da') }}:<span
                                style="color:red;">*</span></label>
                        <div id="name_da" class="errorDiv">
                            <input class="form-control m-input errorDiv" type="text" value="{{$subDepartment->name_da}}" name="name_da"
                                id="name_da" placeholder="{{ trans('global.name_da') }}">
                        </div>
                        <div class="name_da error-div" style="display:none;"></div>
                    </div>
                    <div class=" col-lg-4">
                        <label for="name_en" class="title-custom">{{ trans('global.name_pa') }}:<span
                                style="color:red;">*</span></label>
                        <div id="name_en" class="errorDiv">
                            <input class="form-control m-input errorDiv" type="text" value="{{$subDepartment->name_pa}}" name="name_pa"
                                id="name_en" placeholder="{{ trans('global.name_en') }}">
                        </div>
                        <div class="name_en error-div" style="display:none;"></div>
                    </div>
                </div>
                <div class="row p-5 mt-3" style="background-color: #fafafa">
                    <div class="col-lg-12">
                        <button type="button"
                            onclick="server_request_for_storing_record('{{ route('update-sub-department', $id) }}','requestForm','POST','response_div','submitBtn');"
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
