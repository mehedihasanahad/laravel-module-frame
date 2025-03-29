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
                    <h1>Edit Process Document</h1>
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
                    <form id="editProcessModal" method="POST" action="{{ url('/process-document/update') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <input type="hidden" name="id" value="{{$id}}"  id="documentIdInput2">

                            <div class="row">
                                <label class="col-3" for="max_size">Document Name</label>
                                <div id="document_name" class="form-group col-6 validation">
                                    <select class="form-control" name="process_name" id="documentName" required>
                                        <option value="">Select Document Name</option>
                                        @foreach ($docNames as $docId => $docName)
                                        <option value="{{ $docId }}" @if ($documentData->doc_name == $docId) selected @endif>{{ $docName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <label class="col-3" for="max_size">Process Type</label>
                                <div class="form-group col-6 validation">
                                    <select class="form-control" required name="process_name" id="processTypeup">
                                        <option value="">Process Type</option>
                                        @foreach ($processTypes as $processTypeId => $processTypeName)
                                        <option value="{{ $processTypeId }}" @if ($documentData->process_name == $processTypeId) selected @endif>{{ $processTypeName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <label class="col-3" for="">Order</label>
                                <div class="form-group col-6">
                                    <input type="number" class="form-control" value="{{ $doc_list_for_service->order }}" id="orderup" name="order" placeholder="Enter number 1,2,3">
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-3" for="">Required Status</label>
                                <div class="form-group col-6 validation">
                                    <input type="radio" name="is_required" value="1" class="" id="requiredStatus1up" placeholder="Simple file" checked>
                                    <label for="">Mandatory</label>
                                    <input type="radio" name="is_required" value="0" class="" id="requiredStatus2up" placeholder="Simple file">
                                    <label for="">Optional</label>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-3" for="simple_file">Enable Auto Suggtion</label>
                                <div class="form-group col-6">
                                    <input type="radio" name="autosuggest_status" value="1" class="" id="eas1up" placeholder="Simple file">
                                    <label for="">Yes</label>
                                    <input type="radio" name="autosuggest_status" value="0" class="" id="eas2up" placeholder="Simple file" checked>
                                    <label for="">No</label>
                                </div>
                            </div>

                            <div class="row">
                                <label class="col-3" for="ile"> Status</label>
                                <div class="form-group col-6 validation">
                                    <input type="radio" name="status" value="1" class="" id="activeup" placeholder="" checked>
                                    <label for="">Active</label>
                                    <input type="radio" name="status" value="0" class="" id="inactiveup" placeholder="">
                                    <label for="">Inactive</label>
                                </div>
                            </div>

                        </div>
                </div>
                <!-- /.card-body -->
                <div class="modal-footer justify-content-between">
                    
                    <button id="submit-btn" type="submit" class="btn btn-primary">Save Change</button>
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
        CommonFunction.validForm('editProcessModal');
    </script>
<script>
    // $('#editProcessModal').validate({
    //     rules: {

    //         doc_name: {
    //             required: true
    //         },
    //         process_name: {
    //             required: true
    //         },
    //         is_required: {
    //             required: true
    //         },

    //         Status: {
    //             required: true
    //         },


    //     },
    //     messages: {
    //         doc_name: {
    //             required: "Please enter your Document name",
    //         },
    //         process_name: {
    //             required: "Please enter your Process Document name"
    //         },
    //         is_required: {
    //             required: "Please check Required Status"
    //         },
    //         Status: {
    //             required: "Please enter your status"
    //         },

    //     },
    //     errorElement: 'span',
    //     errorPlacement: function(error, element) {
    //         error.addClass('invalid-feedback');
    //         element.closest('td').append(error);
    //     },
    //     highlight: function(element, errorClass, validClass) {
    //         $(element).addClass('is-invalid');
    //     },
    //     unhighlight: function(element, errorClass, validClass) {
    //         $(element).removeClass('is-invalid');
    //     }
    // });
</script>

@endsection