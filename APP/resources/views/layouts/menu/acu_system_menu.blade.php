<li class="menu-item">
    <a href="{{ route('home') }}" class="menu-link"
        style="background-color:{{ Route::currentRouteName() == 'user-management-sys' ? '#2E62AE' : '' }}">
        <i class="fas fa-home text-white me-1"></i>&nbsp;
        <span class="menu-text">{{ trans('words.Dashboard') }}</span>
    </a>
</li>
<ul class="menu-nav">
    @can('area-list')
        <li class="menu-item menu-item-open menu-item-here menu-item-submenu menu-item-rel  {{ Route::currentRouteName() == 'acu-area' || Route::currentRouteName() == 'zone' || Route::currentRouteName() == 'shared.index' || Route::currentRouteName() == 'gozar' ? 'menu-item-active' : '' }} menu-item-open-dropdown"
            data-menu-toggle="click" aria-haspopup="true">
            <a href="javascript:;" class="menu-link menu-toggle">
                <span class="menu-text"><i class="fas fa-layer-group text-white me-1"></i>&nbsp;
                    {{ trans('global.section') }}</span>
                <i class="fas menu-arrow"></i>
            </a>
            <div class="menu-submenu menu-submenu-classic menu-submenu-left" data-hor-direction="menu-submenu-left">
                <ul class="menu-subnav">
                    <li class="menu-item {{ Route::currentRouteName() == 'acu-area' ? 'menu-item-active' : '' }}"
                        aria-haspopup="true">
                        <a href="{{ route('acu-area') }}" class="menu-link">
                            <span class="svg-icon menu-icon">
                                <i class="fas fa-sitemap text-primary"></i> </span>
                            <span class="menu-text">{{ trans('global.acuArea') }}</span>
                        </a>
                    </li>
                    <li class="menu-item {{ Route::currentRouteName() == 'zone' ? 'menu-item-active' : '' }}"
                        aria-haspopup="true">
                        <a href="{{ route('zone') }}" class="menu-link">
                            <span class="svg-icon menu-icon">
                                <i class="fas fa-sitemap text-primary"></i> </span>
                            <span class="menu-text">{{ trans('global.zone') }}</span>
                        </a>
                    </li>
                    <li class="menu-item  {{ Route::currentRouteName() == 'shared.index' ? 'menu-item-active' : '' }}"
                        aria-haspopup="true">
                        <a href="{{ route('shared.index') }}" class="menu-link">
                            <span class="svg-icon menu-icon">
                                <i class="fa-solid fas fa-map text-primary"></i> </span>
                            <span class="menu-text">{{ trans('global.shared') }}</span>
                        </a>
                    </li>
                    <li class="menu-item  {{ Route::currentRouteName() == 'gozar' ? 'menu-item-active' : '' }}"
                        aria-haspopup="true">
                        <a href="{{ route('gozar') }}" class="menu-link">
                            <span class="svg-icon menu-icon">
                                <i class="fa-solid fas fa-map-marked text-primary"></i> </span>
                            <span class="menu-text">{{ trans('global.gozar') }}</span>
                        </a>
                    </li>

                    <li class="menu-item  {{ Route::currentRouteName() == 'search-index' ? 'menu-item-active' : '' }}"
                        aria-haspopup="true">
                        <a href="{{ route('search-index') }}" class="menu-link">
                            <span class="svg-icon menu-icon">
                                <i class="fa-solid fas fa-search text-primary"></i> </span>
                            <span class="menu-text">{{ trans('global.search') }}</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>
    @endcan

    @can('fugitive-view')
        <li class="menu-item menu-item-open menu-item-here menu-item-submenu menu-item-rel  {{ Route::currentRouteName() == 'fugitive-index' || Route::currentRouteName() == 'view-fugitive' || Route::currentRouteName() == 'case-partner-view' || Route::currentRouteName() == 'case-witnesses-view' ? 'menu-item-active' : '' }} menu-item-open-dropdown"
            data-menu-toggle="click" aria-haspopup="true">
            <a href="javascript:;" class="menu-link menu-toggle">
                <span class="menu-text">
                    <i class="fas fa-handshake-slash text-white me-1"></i>&nbsp;
                    {{ trans('global.fugitive') }}</span>
                <i class="fas menu-arrow"></i>
            </a>
            <div class="menu-submenu menu-submenu-classic menu-submenu-left" data-hor-direction="menu-submenu-left">
                <ul class="menu-subnav">
                    <li class="menu-item {{ Route::currentRouteName() == 'fugitive-index' ? 'menu-item-active' : '' }}"
                        aria-haspopup="true">
                        <a href="{{ route('fugitive-index') }}" class="menu-link">
                            <span class="svg-icon menu-icon">
                                <i class="fas fa-handshake-slash text-primary"></i> </span>
                            <span class="menu-text">{{ trans('global.fugitive') }}</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>
    @endcan
</ul>
