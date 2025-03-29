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
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Users</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Users</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">DataTable with default features</h3>
                <a class="btn btn-sm btn-primary float-right" href="{{ url('/user/add') }}"> <span class="fa fa-plus"></span> New User</a>
            </div>



            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr class="text-center">
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>NID Number</th>
                            <th>Date of Birth</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($users as $user)
                        <tr class="text-center">
                            <td>{{ $user->first_name }}</td>
                            <td>{{ $user->last_name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->national_id }}</td>
                            <td>{{ $user->birth_date}}</td>
                            <td>
                                @if ($user->status == 1)
                                <span style="color: green;">Active</span>
                                @else
                                <span style="color: red;">Inactive</span>
                                @endif
                            </td>

                            <td class="text-center">
                                <a class="open-modal" data-id="{{ Crypt::encryptString($user->id) }}"><span class="fas fa-eye m-1"></span></a>
                                <a class="open-edit-modal" data-id="{{ Crypt::encryptString($user->id) }}"><span class="fas fa-edit m-1"></span></a>
                                <a>
                                    <span class="fas fa-trash text-danger m-1 delete-user" data-id="{{ Crypt::encryptString($user->id) }}"></span>
                                </a>
                            </td>

                        </tr>
                        @endforeach

                    </tbody>
                    <tfoot>
                        <tr class="text-center">
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>NID Number</th>
                            <th>Date of Birth</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <!-- /.card-body -->
        </div>

        <!-- Add a modal container for User Details -->
        <div class="modal fade" id="viewUserDetailsModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title">User Details</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <td>
                                        <strong style="margin-right: 10px;">First Name:</strong>
                                        <span id="modalFirstName"></span>
                                    </td>
                                    <td>
                                        <strong style="margin-right: 10px;">Last Name:</strong>
                                        <span id="modalLastName"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong style="margin-right: 10px;">Email:</strong>
                                        <span id="modalEmail"></span>
                                    </td>
                                    <td>
                                        <strong style="margin-right: 10px;">User Group ID:</strong>
                                        <span id="modalUserGroupID"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong style="margin-right: 10px;">Status:</strong>
                                        <span id="modalStatus"></span>
                                    </td>
                                    <td>
                                        <strong style="margin-right: 10px;">NID Number:</strong>
                                        <span id="modalNationalID"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong style="margin-right: 10px;">Date of Birth:</strong>
                                        <span id="modalBirthDate"></span>
                                    </td>
                                    <td>
                                        <strong style="margin-right: 10px;">Present Address:</strong>
                                        <span id="modalPresentAddress"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong style="margin-right: 10px;">Permanent Address:</strong>
                                        <span id="modalPermanentAddress"></span>
                                    </td>
                                    <td>
                                        <strong style="margin-right: 10px;">Gender:</strong>
                                        <span id="modalGender"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong style="margin-right: 10px;">Mobile:</strong>
                                        <span id="modalMobile"></span>
                                    </td>

                                </tr>
                            </tbody>
                        </table>

                        <div class="modal-footer float-right">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End the modal container for User Details -->
        <!-- /.card -->

        <!-- Add a modal container for Edit User Details -->

        <div class="modal fade" id="edituserDetailsModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title">Edit User Details</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">


                        <form id="quickForm" method="post" action="{{ url('/user/updateUserDetails') }}">
                            @csrf
                            <div class="card-body">
                                <input type="hidden" name="userId" id="userIdInput">

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="first_name">First Name</label>
                                            <input type="text" id="firstNameInput" name="first_name" class="form-control" placeholder="Enter first name">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="last_name">Last Name</label>
                                            <input type="text" name="last_name" class="form-control" id="lastNameInput" placeholder="Enter last name">
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="nid">National ID</label>
                                            <input type="text" name="national_id" class="form-control" id="nationalIDInput" placeholder="Enter national id">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Date of Birth</label>
                                            <div class="input-group date" id="birth_date" data-target-input="nearest">
                                                <input type="text" name="birth_date" id="birthDateInput" class="form-control datetimepicker-input" data-target="#birth_date" />
                                                <div class="input-group-append" data-target="#birth_date" data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <select class="form-control" name="status" id="">
                                                <option value="1">Active</option>
                                                <option value="0">Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <!-- /.card-body -->
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save Change</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- End the modal container for Edit User Details -->
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
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            // "buttons": ["copy", "csv", "excel", "pdf", "print"]
            "buttons": [{
                extend: 'pdf',
                text: 'Download as PDF',
                className: 'btn-sm',
                title: 'PDF',
            }, ]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });
        $('#birth_date').datetimepicker({
            format: 'YYYY-MM-DD'
        });


        $('.delete-user').click(function() {
            if (!confirm("Are you sure you want to delete this user?")) {
                return false;
            }
            var userId = $(this).data('id');
            var $userRow = $(this).closest('tr');
            $.ajax({
                url: '/user/delete/' + userId,
                method: 'GET',
                success: function(response) {
                    $userRow.remove();
                    alert(" Successfully Delete Your information")
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });
    });
</script>
@endsection
