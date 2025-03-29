<style>
    body:not(.layout-fixed) .main-sidebar {
        bottom: 0;
        float: none;
        left: 0;
        position: fixed;
        top: 0;
        height: 100vh;
        overflow-y: hidden;
        z-index: 1038;
    }

    aside {
        background: {{config('engine.adminPanel.sidebar.background_color') ? config('engine.adminPanel.sidebar.background_color') : '#343A40'}}                  !important;
    }

    aside .nav-pills .nav-link, aside a, aside a:hover, aside {
        color: {{config('engine.adminPanel.sidebar.text_color') ? config('engine.adminPanel.sidebar.text_color') : '#ffffff'}}                  !important;
    }

    .menu-active {
        background: {{config('engine.adminPanel.sidebar.selected_menu_color') ? config('engine.adminPanel.sidebar.selected_menu_color') : 'rgba(0, 0, 0, 0.3)'}}                  !important;
    }

    .menu-dropdown-active {
        background: {{config('engine.adminPanel.sidebar.selected_menu_dropdown_color') ? config('engine.adminPanel.sidebar.selected_menu_dropdown_color') : 'rgba(0, 0, 0, 0.1)'}}                  !important;
    }

    #sidebar i {
        color: {{config('engine.adminPanel.sidebar.menu_icon_color') ? config('engine.adminPanel.sidebar.menu_icon_color') : 'rgba(0, 0, 0, 0.6)'}}                  !important;
    }

</style>
<aside class="main-sidebar elevation-4">
    <!-- Brand Logo -->
    <a href="/dashboard" class="brand-link">
        <img
            src="{{ asset(config('engine.adminPanel.sidebar.title_img_path')) }}"
            alt="{{config('engine.adminPanel.sidebar.title_img_alt')}}"
            class="brand-image img-circle elevation-3"
            style="opacity: .8"
        />
        <span class="brand-text font-weight-light">{{config('engine.adminPanel.sidebar.title')}}</span>
    </a>
    <!-- Sidebar -->
    <div id="sidebar" class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2 border-bottom border-secondary">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                <li class="nav-header">Menus</li>
                <li class="nav-item">
                    <a href="/dashboard" class="nav-link {{request()->is('dashboard') ? 'menu-active' : ''}}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>

                <!-- user-sidebar -->
                @includeIf('dashboard.sidebar')
                <!-- user-sidebar -->

                @can('user-management')
                    <li class="nav-item {{
                        (request()->is('users*') || request()->is('roles*') || request()->is('permissions*')) ?
                        'menu-is-opening menu-open' : ''
                    }}">
                        <a href="#" class="nav-link {{
		                    (request()->is('users*') || request()->is('roles*') || request()->is('permissions*')) ?
                            'menu-dropdown-active' : ''
                        }}">
                            <i class="nav-icon fas fa-user-alt"></i>
                            <p>
                                Users Management
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('/users') }}"
                                   class="nav-link {{request()->is('users*') ? 'menu-active' : ''}}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Users</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/roles') }}"
                                   class="nav-link {{request()->is('roles*') ? 'menu-active' : ''}}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Roles</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/permissions') }}"
                                   class="nav-link {{request()->is('permissions*') ? 'menu-active' : ''}}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Permissions</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan

                @can('process-management')
                    <li class="nav-item {{
                        (request()->is('process-type*')|| request()->is('process-user-desk*')  || request()->is('process-status*') || request()->is('process-path*') ) ?
                        'menu-is-opening menu-open' : ''
                    }}">
                        <a href="#" class="nav-link {{
		                    (request()->is('process-type*') || request()->is('process-user-desk*')  || request()->is('process-status*') || request()->is('process-path*')) ?
                            'menu-dropdown-active' : ''
                        }}">
                            <i class="nav-icon fas fa-user-alt"></i>
                            <p>
                                Process Management
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('process-type.index') }}"
                                   class="nav-link {{request()->is('process-type*') ? 'menu-active' : ''}}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Process Types</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('process-user-desk.index') }}"
                                   class="nav-link {{request()->is('process-user-desk*') ? 'menu-active' : ''}}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Process User Desk</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('process-statuses.index') }}"
                                   class="nav-link {{request()->is('process-status*') ? 'menu-active' : ''}}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Process Status</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('process-path.index') }}"
                                   class="nav-link {{request()->is('process-path*') ? 'menu-active' : ''}}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Process Path</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
                <li class="nav-item {{request()->route()->getPrefix() ==='form-builder' ?'menu-is-opening menu-open' : ''}}">
                    <a href="#"
                       class="nav-link {{request()->route()->getPrefix() ==='form-builder' ? 'menu-dropdown-active' : '' }}">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>
                            Form Builder
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('form.index')}}" class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Forms</p>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a href="{{route('component.index')}}" class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Components</p>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a href="{{route('input.index')}}" class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Inputs</p>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a href="{{route('input-group.index')}}" class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Input Groups</p>
                            </a>
                        </li>
                    </ul>
                </li>


                @can('settings')
                    <li class="nav-item {{
                        (request()->is('document*')|| request()->is('area*') ) ?
                        'menu-is-opening menu-open' : ''
                    }}">
                        <a href="#" class="nav-link {{
		                    (request()->is('document*') || request()->is('area*')) ?
                            'menu-dropdown-active' : ''
                        }}">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>
                                Settings
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('/document') }}"
                                   class="nav-link {{request()->is('document*') ? 'menu-active' : ''}} ">
                                    <i class="nav-icon fas fa-file"></i>
                                    <p>Document</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/area') }}"
                                   class="nav-link {{request()->is('area*') ? 'menu-active' : ''}} ">
                                    <i class="nav-icon fa fa-map-marker"></i>
                                    <p>Area</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
