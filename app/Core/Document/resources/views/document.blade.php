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
<!-- Commont CSS Add -->
<link rel="stylesheet" href="{{ asset('assets/css/common.css') }}">
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
                    <h1>Document Managements</h1>
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
                    <div class="card">
                        <div class="card-header p-2">
                            <ul class="nav nav-pills">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#details" data-toggle="tab">Document Name</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#pass_change" data-toggle="tab">Process Document</a>
                                </li>
                                <li class="nav-item ml-auto"> <!-- Use ml-auto class to align the button to the right -->
                                    <button class="btn btn-sm btn-primary open-add-modal" id="tab1" data-target="#details" type="button">
                                        <span class="fa fa-plus"></span> New Document
                                    </button>
                                    <button class="btn btn-sm btn-primary open-add-modal-process" id="tab2" data-target="#pass_change" type="button">
                                        <span class="fa fa-plus"></span> New Process Document
                                    </button>
                                </li>


                            </ul>


                        </div><!-- /.card-header -->

                        <div class="card-body">
                            <div class="tab-content">
                                <div class="">
                                    <h3 id="card-title1" class="card-title">Document Name list</h3>
                                    <h3 id="card-title2" class="card-title">Process Document List</h3>

                                </div>
                                <div class="active tab-pane" id="details">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Document Name</th>
                                                <th>Simple File</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>

                                    </table>
                                    <!-- /.post -->
                                </div>


                                <!-- /.tab-pane -->
                                <div class="tab-pane" id="pass_change">
                                    <table id="example3" class="table table-bordered table-striped">
                                        <thead>
                                            <tr class="text-center">
                                                <th>#</th>
                                                <th>Document Name</th>
                                                <th>Process Type</th>
                                                <th>Required Status</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>


                                        </tbody>

                                    </table>
                                </div>

                                <!-- /.tab-pane -->
                            </div>
                            <!-- /.tab-content -->
                        </div><!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            
        </div>
       
        <div class="modal fade" id="AddDocumentModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Add Documents</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="addform" method="POST" action="{{ url('/document/add') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="document_name">Document Name</label>
                                            <input type="text" required data-valueMissing="Document Name is required" data-typeMismatch="Please enter a valid document name" id="document_name" name="document_name" class="form-control validation" placeholder="Document name">
                                        </div>
                                    </div>

                                </div>
                                <div class="form-group">
                                    <label for="max_size">Max Size <span>(KB)</span></label>
                                    <input type="number" required data-valueMissing="Max Size is required" data-typeMismatch="Please enter a valid Max Size" class="form-control validation" id="" name="max_size" placeholder="Enter maximum size">
                                </div>
                                <div class="form-group">
                                    <label for="max_size">Min Size <span>(KB)</span></label>
                                    <input type="number" required data-valueMissing="Min Size is required" data-typeMismatch="Please enter a valid Min Size" class="form-control validation" id="" name="min_size" placeholder="Enter minimum size">
                                </div>


                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="simple_file">Simple file </label>
                                            <input type="file" name="simple_file" class="form-control" id="" placeholder="Simple file">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <select class="form-control validation" required name="status" id="status">
                                                <option value="1">Active</option>
                                                <option value="0">Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <!-- /.card-body -->
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="AddProcessDocumentModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">New Process Documents</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="processaddform" method="POST" action="{{ url('/process-document/add') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">

                                <div class="row">
                                    <label class="col-3" for="name">Document Name</label>
                                    <div class="form-group col-6 validation">
                                        <select class="form-control" required data-valueMissing="Document Name is required" data-typeMismatch="Please enter a valid document name" name="doc_name" id="doc_name">
                                            <option value="">Select Document Name</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-3" for="max_size">Process Type</label>
                                    <div class="form-group col-6 validation">
                                        <select class="form-control " required name="process_name" id="processType">
                                            <option value="">Process Type</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-3" for="">Others</label>
                                    <div class="form-group col-6">

                                        <input type="number" class="form-control" id="" name="others" placeholder="Enter number 1,2,3">
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-3" for="">Required Status</label>
                                    <div class="form-group col-6 validation">

                                        <input type="radio" name="is_required" value="1" class="" id="requiredStatus1" placeholder="Simple file" checked>
                                        <label for="">Mandatory</label>
                                        <input type="radio" name="is_required" value="0" class="" id="requiredStatus2" placeholder="Simple file">
                                        <label for="">Optional</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-3" for="simple_file">Enable Auto Suggtion</label>
                                    <div class="form-group col-6">

                                        <input type="radio" name="autosuggest_status" value="1" class="" id="eas1" placeholder="Simple file">
                                        <label for="">Yes</label>
                                        <input type="radio" name="autosuggest_status" value="0" class="" id="eas2" placeholder="Simple file" checked>

                                        <label for="">No</label>
                                    </div>
                                </div>


                                <div class="row">
                                    <label class="col-3" for="ile"> Status</label>
                                    <div class="form-group col-6 validation">
                                        <input type="radio" name="Status" value="1" class="" id="active" placeholder="" checked>
                                        <label for="">Active</label>
                                        <input type="radio" name="Status" value="0" class="" id="inactive" placeholder="">
                                        <label for="">Inactive</label>
                                    </div>
                                </div>
                            </div>


                            <!-- /.card-body -->
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

            <!-- /.row -->
        </div><!-- /.container-fluid -->
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
    $(function() {

        $('#tab2').hide();
        $('#tab1').show();
        $('#card-title1').show();
        $('#card-title2').hide();
        $('.nav-link').on('click', function() {
            var targetTab = $(this).attr('href');
            if (targetTab === '#details') {
                $('#tab1').show();
                $('#tab2').hide();
                $('#card-title1').show();
                $('#card-title2').hide();
            } else if (targetTab === '#pass_change') {
                $('#tab1').hide();
                $('#tab2').show();
                $('#card-title2').show();
                $('#card-title1').hide();
            }
        });
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "iDisplayLength": '{{ 25 }}',
            "processing": true,
            "serverSide": true,
            "searching": true,
            "ajax": {
                "url": '/document-data',
                "type": 'GET',
            },
            "columns": [{
                    "data": null,
                    "render": function(data, type, row, meta) {
                        // Calculate the serial number based on the row index
                        var serialNumber = meta.row + meta.settings._iDisplayStart + 1;
                        return serialNumber;
                    },
                    "orderable": false,
                    "searchable": false
                },
                {
                    "data": "name",
                    "name": "name"
                },
                {
                    "data": "simple_file",
                    "name": "simple_file",
                    "render": function(data, type, row) {
                        if (data) {
                            return '<a href="' + data + '" download>Download</a>';
                        } else {
                            return '';
                        }
                    }
                },
                {
                    "data": "status",
                    "name": "status",
                    "render": function(data, type, row) {
                        if (data === 1) {
                            return '<span style="color: green;">Active</span>';
                        } else {
                            return '<span style="color: red;">Inactive</span>';
                        }
                    }
                },
                {
                    "data": "action",
                    "name": "action",
                    "orderable": false,
                    "searchable": false,



                }
            ]
        });

        $("#example3").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,

            "ajax": {
                "url": "/process-data",
                "type": "GET"
            },
            "columns": [{
                    "data": null,
                    "render": function(data, type, row, meta) {
                        // Calculate the serial number based on the row index
                        var serialNumber = meta.row + meta.settings._iDisplayStart + 1;
                        return serialNumber;
                    },
                    "orderable": false,
                    "searchable": false
                },
                {
                    "data": "name"
                },
                {
                    "data": "process_type_name"
                },
                {
                    "data": "is_required",
                    "render": function(data, type, row) {
                        return data == 1 ? 'Mandatory' : 'Optional';
                    }
                },
                {
                    "data": "doc_list_status",
                    "render": function(data, type, row) {
                        var status = data == 1 ? '<span style="color: green;">Active</span>' : '<span style="color: red;">Inactive</span>';
                        return status;
                    }
                },
                {
                    "data": "action",
                    "name": "action",
                    "orderable": false,
                    "searchable": false,
                   
                }

            ]

        }).buttons().container().appendTo('#example3_wrapper .col-md-6:eq(0)');
        $('#birth_date').datetimepicker({
            format: 'YYYY-MM-DD'
        });

        // $('#addform').validate({
        //     rules: {

        //         name: {
        //             required: true
        //         },
        //         max_size: {
        //             required: true
        //         },
        //         min_size: {
        //             required: true
        //         },

        //         status: {
        //             required: true
        //         },


        //     },
        //     messages: {
        //         name: {
        //             required: "Please enter your first name",
        //         },
        //         status: {
        //             required: "Please enter your status"
        //         },
        //         min_size: {
        //             required: "Please enter your status"
        //         },
        //         max_size: {
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
        // $('#processaddform').validate({
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
    

        $('#birth_date').datetimepicker({
            format: 'YYYY-MM-DD'
        });

       
        $('.open-add-modal').click(function(e) {
            e.preventDefault();
            $('#AddDocumentModal').modal('show');
        });

        $('.open-add-modal-process').click(function(e) {
            e.preventDefault();
            $.ajax({
                url: '/fetch-document-data',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    var docNames = response.docNames;
                    var processTypes = response.processTypes;

                    var docNameSelect = $('#doc_name');
                    docNameSelect.empty();
                    docNameSelect.append('<option value="">Select Document Name</option>');
                    $.each(docNames, function(key, value) {
                        docNameSelect.append('<option value="' + key + '">' + value + '</option>');
                    });

                    // Populate process types dropdown
                    var processTypeSelect = $('#processType');
                    processTypeSelect.empty();
                    processTypeSelect.append('<option value="">Process Type</option>');
                    $.each(processTypes, function(key, value) {
                        processTypeSelect.append('<option value="' + key + '">' + value + '</option>');
                    });
                    $('#AddProcessDocumentModal').modal('show');
                },
                error: function() {
                    console.log('An error occurred while fetching document data.');
                }
            });

        });


       
    });
</script>
    <script>
        CommonFunction.validForm('addform');
        CommonFunction.validForm('processaddform');
    </script>
@endsection
