@extends('Dashboard::admin.layout.index')

@section('custom-css')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('assets/css/datatables/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/datatables/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/datatables/buttons.bootstrap4.min.css') }}">
<!-- daterange picker -->
<link rel="stylesheet" href="{{ asset('assets/css/daterangepicker.css') }}">
<!-- Tempusdominus Bootstrap 4 -->
<link rel="stylesheet" href="{{ asset('assets/css/tempusdominus-bootstrap-4.min.css') }}">
<!-- jQuery -->
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->

<div class="content">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Document Name</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>

                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- /.col -->
                <div class="col-md-12">
                    <form id="quickForm" method="POST" action="{{ url('/document/update') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <input type="hidden" name="id"  value="{{$id}}">

                            <div class="row">
                                <label class="col-3" for="document_name">Document Name</label>
                                <div class="form-group col-6 validation">

                                    <input type="text" required data-valueMissing="Document Name is required" data-typeMismatch="Please enter a valid document name" id="document_name" name="name" value="{{ $name }}"class="form-control" placeholder="Document name">
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-3" for="max_size">Max Size <span>(KB)</label>
                                <div class="form-group col-6 validation">

                                    <input type="number" required data-valueMissing="Max Size is required" data-typeMismatch="Please enter a valid max size" class="form-control" id="max_size" value="{{ $max_size }}" name="max_size" placeholder="Enter maximum size">
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-3" for="max_size">Min Size <span>(KB)</span></label>
                                <div class="form-group col-6 validation">

                                    <input type="number" required data-valueMissing="Min Size is required" data-typeMismatch="Please enter a valid min size" class="form-control" id="min_size"  value="{{ $min_size }}" name="min_size" placeholder="Enter minimum size">
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-3" for="simple_file">Simple file</label>
                                <div class="form-group col-6">

                                    <input type="file" name="simple_file" value="{{ $simple_file }}" class="form-control" id="simple_file" placeholder="Simple file">
                                </div>
                            </div>


                            <div class="row">
                                <label class="col-3" for="status">Status</label>
                                <div class="form-group col-3 validation">

                                    <select class="form-control" required name="status" value="{{ $status }}" id="status">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                </div>
                <!-- /.card-body -->
                <div class="modal-footer justify-content-between">
                   
                    <button type="submit" class="btn btn-primary">Save Change</button>
                </div>
                </form>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>

    </section>
    <!-- /.content -->
</div>

<!-- /.content-wrapper -->

@endsection
@section('custom-scripts-links')
<!-- DataTables  & Plugins -->
<script src="{{ asset('assets/js/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('assets/js/datatables/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{ asset('assets/js/datatables/dataTables.responsive.min.js')}}"></script>
<script src="{{ asset('assets/js/datatables/responsive.bootstrap4.min.js')}}"></script>
<script src="{{ asset('assets/js/datatables/dataTables.buttons.min.js')}}"></script>
<script src="{{ asset('assets/js/datatables/buttons.bootstrap4.min.js')}}"></script>
<script src="{{ asset('assets/js/datatables/jszip.min.js')}}"></script>
<script src="{{ asset('assets/js/datatables/pdfmake.min.js')}}"></script>
<script src="{{ asset('assets/js/datatables/buttons.print.min.js')}}"></script>
<script src="{{ asset('assets/js/datatables/buttons.html5.min.js')}}"></script>
<script src="{{ asset('assets/js/datatables/vfs_fonts.js')}}"></script>
<script src="{{ asset('assets/js/datatables/userDetailsView.js')}}"></script>
<script src="{{ asset('assets/js/datatables/edituserDetail.js')}}"></script>

<!-- jquery-validation -->
<script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/js/additional-methods.min.js') }}"></script>
<!-- moment -->
<script src="{{ asset('assets/js/moment.min.js') }}"></script>
<!-- date-range-picker -->
<script src="{{ asset('assets/js/daterangepicker.js') }}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ asset('assets/js/tempusdominus-bootstrap-4.min.js') }}"></script>
@endsection

@section('custom-scripts')
<script>
        CommonFunction.validForm('quickForm');
    </script>
<script>
//   $('#quickForm').validate({
//             rules: {

//                 name: {
//                     required: true
//                 },

//                 status: {
//                     required: true
//                 },


//             },
//             messages: {
//                 name: {
//                     required: "Please enter your Document name",
//                 },
//                 status: {
//                     required: "Please enter your status"
//                 },

//             },
//             errorElement: 'span',
//             errorPlacement: function(error, element) {
//                 error.addClass('invalid-feedback');
//                 element.closest('td').append(error);
//             },
//             highlight: function(element, errorClass, validClass) {
//                 $(element).addClass('is-invalid');
//             },
//             unhighlight: function(element, errorClass, validClass) {
//                 $(element).removeClass('is-invalid');
//             }
//         });
</script>
@endsection