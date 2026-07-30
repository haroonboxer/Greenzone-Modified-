<div id="section_div_{{ $number }}" class="row d-flex p-3">
    <div class="col-lg-4">
        <label class="title-custom">{{ trans('global.system') }} : <span style="color:red;">*</span></label>
        <select class="form-control select2" name="systems[]" id="systems{{ $number }}">
            <option value="">
                {{ trans('global.select_', ['name' => trans('global.system')]) }}</option>
            @foreach ($systems as $sys)
                <option value="{{ $sys->id }}">{{ $sys->name_da }}</option>
            @endforeach
        </select>
        <div class="systems0 error-div" style="display:none;"></div>
    </div>

    <div class="col-lg-4">
        <label class="title-custom">{{ trans('global.permissions_name') }} : <span style="color:red;">*</span></label>
        <input type="text" class="form-control m-input errorDiv" name="name[]" id="name{{ $number }}"
            placeholder="{{ trans('global.permissions_name') }}">
        <div class="name{{ $number }} error-div" style="display:none;"></div>
    </div>
    <div class="col-lg-1">
        <button type="button" id="section_div_btn_{!! $number !!}" class="btn btn-danger"
            onclick="remove_more('section_div_{{ $number }}',{{ $number }},'section_div_btn')"
            style="margin-top: 2.2rem;">
            <i class="fa fa-minus"></i></button>
    </div>
</div>

<script>
    $('.select2').select2({
        width: '100%'
    });
</script>
