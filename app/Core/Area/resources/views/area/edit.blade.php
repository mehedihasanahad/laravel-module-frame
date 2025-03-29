@extends('Dashboard::admin.layout.index')

@section('custom-css')
<!-- daterange picker -->
<link rel="stylesheet" href="{{ asset('assets/css/daterangepicker.css') }}">
<!-- Tempusdominus Bootstrap 4 -->
<link rel="stylesheet" href="{{ asset('assets/css/tempusdominus-bootstrap-4.min.css') }}">
<!-- Select 2 -->
<link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
<style>
    .select2-search .select2-search__field {
        /*width: fit-content !important;*/
        display:none;
    }
</style>
@endsection
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Area</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Edit Area</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="card" >
        <div class="card-header">
            <h3 class="card-title">Edit Area</h3>
        </div>
        <form id="quickForm"  action="{{url('/area/update')}}" method="POST">
            @csrf
            <input type="hidden" name="id"  value="{{$id}}">
            <div class="card-body">
                <!-- Area Type -->
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="">Area Type <span style="color:red">*</span> </label>
                        </div>
                    </div>
                    <div class="col-sm-9">
                        <div class="form-group">
                            <input type="radio" id="thanaId" name="area_type" value="3" @if ($areaInfo->area_type == 3) checked @endif>
                            <label for="thanaId">Thana &nbsp;</label>
                            <input type="radio" id="districtId" name="area_type" value="2" @if ($areaInfo->area_type == 2) checked @endif >
                            <label for="districtId">District &nbsp;</label>
                            <input type="radio" id="divisionId" name="area_type" value="1" @if ($areaInfo->area_type == 1) checked @endif>
                            <label for="divisionId">Division</label>
                        </div>
                    </div>
                </div>

                <!-- Division -->
                <div class="row" id="division" @if ($areaInfo->area_type == 1) style="display:none" @endif>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="first_name">Division <span style="color:red">*</span> </label>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <select class="form-control" required name="pare_id" id="divisionSelect">
                                @foreach ($divisions as $division)
                                    <option value="{{ $division->id }}">{{ $division->area_nm }}</option>
                                @endforeach
                            </select>
                            @error('pare_id')
                            <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- District -->
                <div class="row" id="district" @if ($areaInfo->area_type == 2) style="display: none" @endif>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="">District <span style="color:red">*</span> </label>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <select class="form-control" required name="pare_id" id="districtSelect">
                                @foreach ($districts as $district)
                                    <option value="{{ $district->id }}" @if ($district->id == $areaInfo->pare_id) selected @endif>{{ $district->area_nm }}</option>
                                @endforeach
                            </select>
                            @error('pare_id')
                            <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                </div>


                <!-- Area Name (English) -->
                <div class="row" id="eng">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="first_name">Area Name (English) <span style="color:red">*</span> </label>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <input type="text" required data-valueMissing="Area Name (English) is required" data-typeMismatch="Please enter a valid area name (english)" name="area_nm" id="area_nm" class="form-control" value="{{ $areaInfo->area_nm }}">
                            @error('area_nm')
                            <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Area Name (Bangla) -->
                <div class="row" id="ban">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="first_name">Area Name (Bangla) <span style="color:red">*</span> </label>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <input type="text" required data-valueMissing="Area Name (Bangla) is required" data-typeMismatch="Please enter a valid area name (bangla)" pattern="^[\u0980-\u09FF\s]+$" data-patternMismatch="Enter an area name in bangla" name="area_nm_ban" class="form-control" id="area_nm_ban" value="{{ $areaInfo ->area_nm_ban }}">
                            @error('area_nm_ban')
                            <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>

        <!-- /.card-body -->
    </div>

    <!-- /.card -->
</section>
<!-- /.content -->
@endsection

@section('custom-scripts-links')
<!-- Select 2 -->
<script src="{{asset('assets/js/select2.min.js')}}"></script>
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
    $(function() {

        // $('#area_nm_ban').on('input', function() {
        //     var banText = $(this).val();
        //     var banPattern = /^[\u0980-\u09FF\s]+$/;

        //     if (!banPattern.test(banText)) {
        //         $(this).addClass('is-invalid');
        //     } else {
        //         $(this).removeClass('is-invalid');
        //     }
        // });

        $('input[name="area_type"]').on('click', function () {
            var areaType = $(this).val();

            if (areaType == '1') {
                $('#division').hide();
                $('#district').hide();
            }  if (areaType == '2') {
                $('#division').show();
                $('#district').hide();
            }  if (areaType=='3') {
                $('#division').show();
                $('#district').show();
            }
        });

        $('#divisionSelect').on('change', function() {
            var divisionId = $(this).val();

            if (divisionId === '') {
                // Show all districts
                $('#districtSelect').empty();
                $('#districtSelect').append('<option value="">All Districts</option>');
            } else {
                // Fetch districts based on selected division
                $.ajax({
                    url: '/districts/' + divisionId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#districtSelect').empty();
                        $('#districtSelect').append('<option value="">Select District</option>');
                        $.each(data, function(key, value) {
                            $('#districtSelect').append('<option value="' + value.id + '">' + value.area_nm + '</option>');
                        });
                    }
                });
            }
        });

    });
</script>
@endsection
