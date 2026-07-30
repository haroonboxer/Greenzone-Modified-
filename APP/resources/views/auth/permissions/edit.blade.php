


<div class="row">
  <div class="col-lg-12">
    <div class="m-portlet">
      <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
          <div class="m-portlet__head-title">
            <h3 class="m-portlet__head-text">
              {{ trans('global.editRole') }}
            </h3>
          </div>
        </div>
      </div>
      <!--begin::Form-->
      @if($permission)
        <form class="m-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed" enctype="multipart/form-data" id="requestForm" method="post">
          <div class="m-portlet__body">
            <div class="form-group m-form__group row m-form__group_custom">
              <div class="col-lg-12">
                <label class="title-custom">{{ trans('global.nameRole') }} : <span style="color:red;">*</span></label>
                <input class="form-control m-input errorDiv" type="text" value="{{$roleName}}" name="name" id="name">
                <div class="name error-div" style="display:none;"></div>
              </div>
              <label class="title-custom" style="margin-top:10px">{{ trans('global.rolesP') }} : <span style="color:red;">*</span></label><br>
              <div class="col-lg-12 row">
                @foreach($permission as $value)
                <div class="col-lg-1">
                  <label>
                    {{ Form::checkbox('permission[]', $value->id, in_array($value->id, $rolePermissions) ? true : false, array('class' => 'name  form-check-input me-3')) }}
                    <b>{{ $value->name }}</b>
                  </label>
                    <div class="permission[] error-div" style="display:none;"></div>
                </div>
                
                <br/>
                @endforeach 
              </div>
            </div>
            <div class="form-group m-form__group row m-form__group_custom">
              <div class="col-lg-12">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                  <div class="m-form__actions m-form__actions--solid">
                    <button type="button" onclick="updateRecord('{{route('roles.update',$role->id)}}','requestForm','PUT','response_div')" class="btn btn-primary">{{ trans('global.submit') }}</button>
                    <button type="button" onclick="redirectFunction()" class="btn btn-secondary">{{ trans('global.back') }}</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          @csrf
        </form>
      @endif
      <!--end::Form-->
    </div>
  </div>
</div>
<script type="text/javascript">
$(".select-2").select2();
</script>
