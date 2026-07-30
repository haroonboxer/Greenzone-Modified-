<div class="modal-header bg-color-dark">
    <h3>
        <span class="profile-main-title white-color"><i
                class="m-menu__link-icon fas fa-sitemap text-primary"></i>&nbsp;&nbsp;{{ trans('global.createRoles') }}</span>
    </h3>
    <button class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
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
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        <div class="system_id error-div" style="display:none;"></div>
                    </div>
                    <div class=" col-lg-6">
                        <label>{{ trans('words.Name') }}</label>
                        <input type="text" class="form-control" name="name">
                        <div class="invalid-feedback name_error"></div>
                    </div>
                    <div class="form-group col-12" id="main-form-permissions">
                    </div>
                    <div class="permissions error-div" style="display:none;"></div>
                </div>

                <div class="row p-5 mt-3" style="background-color: #f5f5f5">
                    <div class="col-lg-12">
                        <button type="button"
                            onclick="server_request_for_storing_record('{{ route('role-store', 'store') }}','requestForm','POST','response_div','submitBtn');"
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
