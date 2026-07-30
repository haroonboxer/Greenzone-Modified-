<!--begin::record-->
@if($rolePermissions)
  <div class="row">
    <div class="col-lg-12">
      <div class="m-portlet">
        <div class="m-portlet__head">
          <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
              <h3 class="m-portlet__head-text">{{ trans('global.showRole') }}</h3>
            </div>
          </div>
          <div class="m-portlet__head-tools">
            <ul class="m-portlet__nav">
            <li class="m-portlet__nav-item">
                  <a href="#" onclick="redirectFunction()" class="btn btn-secondary m-btn--custom m-btn--icon btn-sm">
                      <span><i class="fa fa-reply-all"></i> <span>{{ trans('global.back') }}</span></span>
                  </a>
              </li>
            </ul>
          </div>
        </div>      
        <div class="m-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed">
          <div class="m-portlet__body">
            <div class="form-group m-form__group row m-form__group_custom">
              <div class="col-lg-4 col-md-4">
                    <div class="form-group">
                     <label class="title-custom"><span class="m-widget12__text2">{{ trans('global.roles') }} :</span></label><br>
                        @if(!empty($rolePermissions))
                            @foreach($rolePermissions as $v)
                                <span class="m-badge m-badge--warning m-badge--wide" style="margin-top:10px">{{ $v->name }}</span><br>
                            @endforeach
                        @endif
                    </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>  
@endif
<!--end::record-->