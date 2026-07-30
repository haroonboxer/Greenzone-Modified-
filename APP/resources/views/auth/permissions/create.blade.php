<div class="card">
    <div class="card-header container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mt-3"> <i class="fas fa-sitemap text-primary"></i>&nbsp;
                <b>{{ trans('acu.create') }}</b>
            </h5>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-12">
                <form enctype="multipart/form-data" id="requestForm" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-lg-4">
                            <label class="title-custom">{{ trans('global.system') }} : <span
                                    style="color:red;">*</span></label>
                            <select class="form-control select2" name="systems[]" id="systems0">
                                <option value="">
                                    {{ trans('global.select_', ['name' => trans('global.system')]) }}</option>
                                @foreach ($systems as $sys)
                                    <option value="{{ $sys->id }}">{{ $sys->name_da }}</option>
                                @endforeach
                            </select>
                            <div class="systems0 error-div" style="display:none;"></div>
                        </div>

                        <div class="col-lg-4">
                            <label class="title-custom">{{ trans('global.permissions_name') }} : <span
                                    style="color:red;">*</span></label>
                            <input type="text" class="form-control m-input errorDiv" name="name[]" id="name0"
                                placeholder="{{ trans('global.permissions_name') }}">
                            <div class="name0 error-div" style="display:none;"></div>
                        </div>

                        <div class="col-lg-1">
                            <button type="button" onclick="add_more('more_div','{{ route('add_more') }}','1');"
                                id="add" style="margin-top: 2.2rem" class="btn btn-success"><i
                                    class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>

                    <div id="more_div">

                    </div>
                    <div class="row p-5 mt-3" style="background-color: #fafafa">
                        <div class="col-lg-12">
                            <button type="button"
                                onclick="save_record('{{ route('permission-store') }}','requestForm','POST','response_div','submitBtn');"
                                id="submitBtn" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i>
                                {{ trans('global.submit') }}</button>

                            <a class="btn btn-danger btn-sm" href="{{ route('permission-index') }}">
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
