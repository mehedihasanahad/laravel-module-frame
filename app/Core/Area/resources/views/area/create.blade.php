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
                <h1>Add Area</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Add Area</li>
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
            <h3 class="card-title">Add Area</h3>
        </div>
        <!-- /.card-header -->
        <form id="quickForm" method="POST" action="{{route('area.store')}}">
            @csrf
            <!-- <div class="card-body" x-data="{isShowDistrictSection: true, isShowDivisionSection: true, districtRequired: true}"> -->
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="">Area Type <span style="color:red">*</span> </label>
                        </div>
                    </div>
                    <!-- <div class="col-sm-9">
                        <div class="form-group">
                            <input type="radio" name="area_type" id="thanaId" value="3" checked @change="isShowDivisionSection= true; isShowDistrictSection= true; districtRequired= true;">
                            <label for="thanaId">Thana &nbsp; </label>
                            <input type="radio" name="area_type" id="districtId" value="2" @change="isShowDivisionSection= true; isShowDistrictSection= false; districtRequired= false;">
                            <label for="districtId">District &nbsp;</label>
                            <input type="radio" name="area_type" id="divisionId" value="1" @change="isShowDivisionSection= false; isShowDistrictSection= false; districtRequired= false;">
                            <label for="divisionId">Division</label>
                            @error('area_type')
                            <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div> -->
                    <div class="col-sm-9">
                        <div class="form-group">
                            <input type="radio" name="area_type" id="thanaId" value="3" checked >
                            <label for="thanaId">Thana &nbsp; </label>
                            <input type="radio" name="area_type" id="districtId" value="2" >
                            <label for="districtId">District &nbsp;</label>
                            <input type="radio" name="area_type" id="divisionId" value="1" >
                            <label for="divisionId">Division</label>
                            @error('area_type')
                            <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                </div>
               
                <div class="row" id="division" >
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="first_name">Division <span style="color:red">*</span> </label>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <select class="form-control " name="pare_id" id="divisionSelect" >
                                <option value="">Select Division</option>
                                @foreach ($divisions as $division)
                                 <option  value="{{ $division->id }}">{{ $division->area_nm }}</option>
                                @endforeach
                            </select>
                            @error('pare_id')
                            <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="row" id="district">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="">District <span style="color:red">*</span> </label>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <select class="form-control " :required="districtRequired" name="pare_id" id="districtSelect">
                                <option value="">Select District</option>
                            </select>
                            @error('pare_id')
                            <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="row" id="eng">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="first_name">Area Name (English) <span style="color:red">*</span> </label>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <input type="text" class="form-control" required data-valueMissing="Area Name (English) is required" data-typeMismatch="Please enter a valid area name (english)" name="area_nm" id="area_nm" placeholder="Enter Area Name English">
                            @error('area_nm')
                            <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row" id="ban">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="first_name">Area Name (Bangla) <span style="color:red">*</span> </label>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <input type="text" class="form-control" required data-valueMissing="Area Name (Bangla) is required" data-typeMismatch="Please enter a valid area name (bangla)" pattern="^[\u0980-\u09FF\s]+$" data-patternMismatch="Enter an area name in bangla" name="area_nm_ban" id="area_nm_ban" placeholder="Enter Area Name Bangla">
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
    // Area Types
    const areaTypeThana = document.getElementById("thanaId");
    const areaTypeDistrict = document.getElementById("districtId");
    const areaTypeDivision = document.getElementById("divisionId");

    // Input Fields Div
    const divisionInputfield = document.getElementById("division");
    const districtInputfield = document.getElementById("district");
    const areaNameBanglaInputfield = document.getElementById("eng");
    const areaNameEnglishInputfield = document.getElementById("ban");

    // Select Tags
    const divisionSelect = document.getElementById("divisionSelect");
    const districtSelect = document.getElementById("districtSelect");

    // Required add on all field and select
    divisionSelect.setAttribute('required','');
    divisionSelect.setAttribute('data-valueMissing','Division is required');
    districtSelect.setAttribute('required','');
    districtSelect.setAttribute('data-valueMissing','District is required');

    areaTypeThana.addEventListener("click", function(e){        
        divisionInputfield.style.display = 'flex';
        districtInputfield.style.display = 'flex';
        areaNameEnglishInputfield.style.display = 'flex';
        areaNameBanglaInputfield.style.display = 'flex';

        // Validation Control
        if (divisionSelect.hasAttribute("required") === false) {
            divisionSelect.setAttribute('required','');
        }

        if (districtSelect.hasAttribute("required") === false) {
            districtSelect.setAttribute('required','');
        }

    });

    areaTypeDistrict.addEventListener("click", function(e){
        districtInputfield.style.display = 'none';

        divisionInputfield.style.display = 'flex';
        areaNameEnglishInputfield.style.display = 'flex';
        areaNameBanglaInputfield.style.display = 'flex';

        // Validation Control
        if (divisionSelect.hasAttribute("required") === false) {
            divisionSelect.setAttribute('required','');
        }

        if (districtSelect.hasAttribute("required") === true) {
            districtSelect.removeAttribute('required','');
        }

    });

    areaTypeDivision.addEventListener("click", function(e){
        divisionInputfield.style.display = 'none';
        districtInputfield.style.display = 'none';

        areaNameEnglishInputfield.style.display = 'flex';
        areaNameBanglaInputfield.style.display = 'flex';

        // Validation Control
        if (divisionSelect.hasAttribute("required") === true) {
            divisionSelect.removeAttribute('required','');
        }

        if (districtSelect.hasAttribute("required") === true) {
            districtSelect.removeAttribute('required','');
        }

    });
</script>
<script>
    $(function() {

        // $('#area_nm_ban').on('input', function() {
        //     var banText = $(this).val();
        //     var banPattern = /^[\u0980-\u09FF\s]+$/; // Regular expression for Bangla characters

        //     if (!banPattern.test(banText)) {
        //         $(this).addClass('is-invalid'); // Add 'is-invalid' class for invalid input
        //     } else {
        //         $(this).removeClass('is-invalid'); // Remove 'is-invalid' class for valid input
        //     }
        // });
        $('#divisionSelect').on('change', function () {
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
                    success: function (data) {
                        $('#districtSelect').empty();
                        $('#districtSelect').append('<option value="">Select District</option>');
                        $.each(data, function (key, value) {
                            $('#districtSelect').append('<option value="' + value.id + '">' + value.area_nm + '</option>');
                        });
                    }
                });
            }
        });

    });
</script>
@endsection
