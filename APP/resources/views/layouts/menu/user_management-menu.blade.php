<li class="menu-item">
    <a href="{{ route('user-management-sys', session()->get('system_id')) }}" class="menu-link"
        style="background-color:{{ Route::currentRouteName() == 'user-management-sys' ? '#2E62AE' : '' }}">
        <span class="menu-text">{{ trans('words.Dashboard') }}</span>
    </a>
</li>
<ul class="menu-nav">
    <li class="menu-item menu-item-open menu-item-here menu-item-submenu menu-item-rel  {{ Route::currentRouteName() == 'users' || Route::currentRouteName() == 'roles' ? 'menu-item-active' : '' }} menu-item-open-dropdown"
        data-menu-toggle="click" aria-haspopup="true">
        <a href="javascript:;" class="menu-link menu-toggle">
            <span class="menu-text"><i class="fas fa-user-cog text-white me-1"></i>&nbsp; مدیریت سیستم </span>
            <i class="menu-arrow"></i>
        </a>
        <div class="menu-submenu menu-submenu-classic menu-submenu-left" data-hor-direction="menu-submenu-left">
            <ul class="menu-subnav">
                @can('users-menu')
                    <li class="menu-item {{ Route::currentRouteName() == 'users' ? 'menu-item-active' : '' }}"
                        aria-haspopup="true">
                        <a href="{{ route('users') }}" class="menu-link">
                            <span class="svg-icon menu-icon">
                                <i class="fas fa-user text-primary"></i> </span>
                            <span class="menu-text">{{ trans('words.Users') }}</span>
                        </a>
                    </li>
                @endcan
                @can('roles-menu')
                    <li class="menu-item  {{ Route::currentRouteName() == 'roles' ? 'menu-item-active' : '' }}"
                        aria-haspopup="true">
                        <a href="{{ route('roles') }}" class="menu-link">
                            <span class="svg-icon menu-icon">
                                <i class="fa-solid fas fa-sitemap text-primary"></i> </span>
                            <span class="menu-text">{{ trans('words.Roles') }}</span>
                        </a>
                    </li>
                @endcan

                @can('roles-menu')
                    <li class="menu-item  {{ Route::currentRouteName() == 'permission-index' ? 'menu-item-active' : '' }}"
                        aria-haspopup="true">
                        <a href="{{ route('permission-index') }}" class="menu-link">
                            <span class="svg-icon menu-icon">
                                <i class="fa-solid fas fa-sitemap text-primary"></i> </span>
                            <span class="menu-text">{{ trans('global.permissions') }}</span>
                        </a>
                    </li>
                @endcan
                <li class="menu-item  {{ Route::currentRouteName() == 'directorate' ? 'menu-item-active' : '' }}"
                    aria-haspopup="true">
                    <a href="{{ route('directorate') }}" class="menu-link">
                        <span class="svg-icon menu-icon">
                            <i class="fa-solid fas fa-home text-primary"></i> </span>
                        <span class="menu-text">{{ trans('global.directoreate') }}</span>
                    </a>
                </li>
                <li class="menu-item  {{ Route::currentRouteName() == 'department' ? 'menu-item-active' : '' }}"
                    aria-haspopup="true">
                    <a href="{{ route('department') }}" class="menu-link">
                        <span class="svg-icon menu-icon">
                            <i class="fa-solid fas fa-map-marked text-primary"></i> </span>
                        <span class="menu-text">{{ trans('global.department') }}</span>
                    </a>
                </li>
                <li class="menu-item  {{ Route::currentRouteName() == 'sub-department' ? 'menu-item-active' : '' }}"
                    aria-haspopup="true">
                    <a href="{{ route('sub-department') }}" class="menu-link">
                        <span class="svg-icon menu-icon">
                            <i class="fa-solid fas fa-building text-primary"></i> </span>
                        <span class="menu-text">{{ trans('global.departmentSubs') }}</span>
                    </a>
                </li>
            </ul>
        </div>
    </li>
</ul>
