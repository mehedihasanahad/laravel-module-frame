@include('Dashboard::admin.subviews.header')
<!-- Site wrapper -->
<style>
    .content-wrapper {
        background: {{config('engine.adminPanel.layout.background_color') ? config('engine.adminPanel.layout.background_color') : '#F4F6F9'}} !important;
        color: {{config('engine.adminPanel.layout.text_color') ? config('engine.adminPanel.layout.text_color') : '#212530'}} !important;
    }
</style>
<div class="wrapper">
    <!-- Navbar -->
    @include('Dashboard::admin.subviews.navbar')
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    @include('Dashboard::admin.subviews.left-sidebar')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper p-1 p-md-3">
        <!-- Display session messages -->
        <div class="px-2">
            {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
            {!! Session::has('warning') ? '<div class="alert alert-warning alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("warning") .'</div>' : '' !!}
            {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}
        </div>
        <!-- Content Wrapper. Contains page content -->
        @yield('content')
        <!-- /.content-wrapper -->
    </div>

    @include('Dashboard::admin.subviews.footer')
</div>
<!-- ./wrapper -->
@include('Dashboard::admin.subviews.footer-bottom')
