@if(env('APP_ENV') === 'local')
    <li class="nav-item">
        <a href="{{ route('application.list',\App\Libraries\Encryption::encodeId(1)) }}"
           class="nav-link {{(request()->is('application/list/*') && (\App\Libraries\Encryption::decodeId(request()->segment(3)) == 1)) ? 'menu-active': ''}}"
        >
            <i class="nav-icon fas fa-th"></i>
            <p>Test Module</p>
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ route('application.list',\App\Libraries\Encryption::encodeId(10)) }}"
           class="nav-link {{(request()->is('application/list/*') && (\App\Libraries\Encryption::decodeId(request()->segment(3)) == 10)) ? 'menu-active': ''}}"
        >
            <i class="nav-icon fas fa-th"></i>
            <p>Mutation</p>
        </a>
        <a href="{{ route('application.list',\App\Libraries\Encryption::encodeId(5)) }}"
           class="nav-link {{(request()->is('application/list/*') && (\App\Libraries\Encryption::decodeId(request()->segment(3)) == 5)) ? 'menu-active': ''}}"
        >
            <i class="nav-icon fas fa-th"></i>
            <p>MNO</p>
        </a>

        <a href="{{ route('application.list',\App\Libraries\Encryption::encodeId(12)) }}"
           class="nav-link {{(request()->is('application/list/*') && (\App\Libraries\Encryption::decodeId(request()->segment(3)) == 12)) ? 'menu-active': ''}}"
        >
            <i class="nav-icon fas fa-th"></i>
            <p>VSAT</p>
        </a>
    </li>

    {{--    <li class="nav-item">--}}
    {{--        <a href="{{ route('form.create',['process_type_id'=>1,'form_type'=>1]) }}" class="nav-link"--}}
    {{--            {{request()->is('form/create/*') ? 'menu-active': ''}} >--}}
    {{--            <i class="nav-icon fas fa-cog"></i>--}}
    {{--            <p>Form</p>--}}
    {{--        </a>--}}
    {{--    </li>--}}

@endif

