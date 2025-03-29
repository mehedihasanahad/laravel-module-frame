<!-- Profile -->
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Profile</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item active">User Profile</li>
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
                                <a class="nav-link {{$errors->password_change->any() ? '':'active' }}  "
                                   href="#details" data-toggle="tab">Details</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{$errors->password_change->any() ? 'active':'' }} "
                                   href="#pass_change" data-toggle="tab">Password Change</a>
                            </li>
                        </ul>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <div class=" {{$errors->password_change->any() ? '':'active' }} tab-pane" id="details">
                                <form id="profileForm" method="POST" enctype="multipart/form-data" class="row"
                                      action="{{route('profile.update',\App\Libraries\Encryption::encodeId(auth()->id()))}}">
                                    @csrf
                                    @method('PATCH')
                                    <div class="col-md-6">
                                        <div class="form-horizontal">
                                            <div class="form-group row">
                                                <label for="first_name" class="col-sm-4 col-form-label">First
                                                    Name</label>
                                                <div class="col-sm-8 validation">
                                                    <input type="text" required data-valueMissing="First Name is required" data-typeMismatch="Please enter a valid first name" name="first_name" class="form-control"
                                                           id="first_name"
                                                           value="{{ old('first_name',Auth::user()->first_name)}}">
                                                    @error('first_name')
                                                    <span class="text-danger">{{$message}}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="last_name" class="col-sm-4 col-form-label">Last
                                                    name</label>
                                                <div class="col-sm-8 validation">
                                                    <input type="text"  required data-valueMissing="Last Name is required" data-typeMismatch="Please enter a valid last name" name="last_name" class="form-control"
                                                           id="last_name"
                                                           value="{{ old('last_name',Auth::user()->last_name)}}">
                                                    @error('first_name')
                                                    <span class="text-danger">{{$message}}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="national_id" class="col-sm-4 col-form-label">National
                                                    ID</label>
                                                <div class="col-sm-8 validation">
                                                    <input type="number"  required data-valueMissing="National ID is required" data-typeMismatch="Please enter a valid national id" name="national_id" class="form-control"
                                                           id="national_id"
                                                           value="{{old('national_id', Auth::user()->national_id) }}">
                                                    @error('national_id')
                                                    <span class="text-danger">{{$message}}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="birth_date" class="col-sm-4 col-form-label">DOB</label>
                                                <div class="col-sm-8 validation">
                                                    <input type="date" name="birth_date"
                                                           required data-valueMissing="Date of Birth is required" data-typeMismatch="Please enter a valid date of birth"
                                                           class="form-control datetimepicker-input" id="birth_date"
                                                           value="{{old('birth_date',Auth::user()->birth_date) }}"
                                                    />
                                                    @error('birth_date')
                                                    <span class="text-danger">{{$message}}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="present_address" class="col-sm-4 col-form-label">Present
                                                    Address  </label>
                                                <div class="col-sm-8">
                                                        <textarea
                                                            name="present_address" id="present_address"
                                                            class="form-control">{{  old('present_address',Auth::user()->present_address)}}</textarea>
                                                    @error('present_address')
                                                    <span class="text-danger">{{$message}}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="permanent_address"
                                                       class="col-sm-4 col-form-label">Permanent Address  </label>
                                                <div class="col-sm-8">
                                                        <textarea
                                                            name="permanent_address"
                                                            id="permanent_address"
                                                            class="form-control">{{ old('permanent_address',Auth::user()->permanent_address)}}</textarea>
                                                    @error('permanent_address')
                                                    <span class="text-danger">{{$message}}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label
                                                    class="col-sm-4 col-form-label" for="gender">Gender</label>
                                                <div class="col-sm-8 validation">
                                                    <select class="form-control" required name="gender" id="gender">
                                                        <option value="">--Select One--</option>
                                                        <option
                                                            value="male" {{Auth::user()->gender === 'male'  ? 'selected' : ''}}>
                                                            Male
                                                        </option>
                                                        <option
                                                            value="female" {{Auth::user()->gender === 'female'  ? 'selected' : ''}}>
                                                            Female
                                                        </option>
                                                        <option
                                                            value="others" {{Auth::user()->gender === 'others'  ? 'selected' : ''}}>
                                                            Others
                                                        </option>
                                                    </select>
                                                    @error('gender')
                                                    <span class="text-danger">{{$message}}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="mobile" class="col-sm-4 col-form-label">Mobile</label>
                                                <div class="col-sm-8">
                                                    <input type="number" required data-valueMissing="Mobile Number is required" data-typeMismatch="Please enter a valid mobile number" name="mobile" class="form-control" id="mobile" value="{{ old('mobile',Auth::user()->mobile)  }}">
                                                    @error('mobile')
                                                    <span class="text-danger">{{$message}}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-sm-1"></div>
                                    <div class="col-md-5 col-sm-5 col-sm-offset-1">
                                        <div class="card card-default" id="browseimagepp">
                                            <div class="row">
                                                <div class="col-sm-6 col-md-4 addImages" style="max-height:300px;">
                                                    <label class="center-block image-upload" for="user_pic"
                                                           style="margin: 0">
                                                        <figure>
                                                            <img id="user_pic_preview" alt="" class="img-responsive img-thumbnail"
                                                                 src="{{ (!empty(auth()->user()->photo) ? asset(auth()->user()->photo) : asset('assets/images/adminLTE/avatar.png')) }}"/>
                                                        </figure>
                                                        <input type="hidden" id="user_pic_base64"
                                                               name="user_pic_base64"/>
                                                        @if(!empty(auth()->user_pic))
                                                            <input type="hidden" name="user_pic"
                                                                   value="{{auth()->user_pic}}"/>
                                                        @endif
                                                    </label>
                                                </div>
                                                <div class="col-sm-6 col-md-8">
                                                    <h4 id="profile_image">
                                                        <label class="text-left required-star">Profile image</label>
                                                    </h4>
                                                    <span class="text-success col-lg-8 text-left"
                                                          style="font-size: 9px; font-weight: bold; display: block;">[File Format: *.jpg/ .jpeg/ .png | Width 300PX, Height 300PX]</span>

                                                    <span id="user_err" class="text-danger col-lg-8 text-left"
                                                          style="font-size: 10px;"> {!! $errors->first('applicant_photo','<span class="help-block">:message</span>') !!}</span>
                                                    <div class="clearfix"><br/></div>
                                                    <label class="btn btn-primary btn-file d-none">
                                                        <i class="fa fa-picture-o" aria-hidden="true"></i>
                                                        {{--                                                            {!! trans('Users::messages.browse') !!}--}}
                                                        <input type="file"
                                                               class="custom-file-input input-sm {{!empty(auth()->user_pic) ? '' : 'required'}}"
                                                               required data-valueMissing="Profile image is required" data-typeMismatch="Please enter a valid profile image"
                                                               name="user_pic"
                                                               id="user_pic"
                                                               onchange="imageUploadWithCroppingAndDetect(this, 'user_pic_preview', 'user_pic_base64')"
                                                               size="300x300"/>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="justify-content-end">
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div>
                                </form>
                                <!-- /.post -->
                            </div>
                            <!-- /.tab-pane -->
                            <div class="tab-pane {{$errors->password_change->any() ? 'active':'' }} "
                                 id="pass_change">
                                <div class="alert alert-dismissible alertDiv" style="display:none">
                                    <span id="alert_span"></span>
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×
                                    </button>
                                </div>
                                    <form id="changePasswordForm" method="post" action="{{route('user.change-password')}}">
                                        @csrf
                                        @method('PATCH')
                                        <div class="form-group row">
                                            <label class="col-md-2 col-form-label" for="password">Password</label>
                                            <div class="col-md-10 validation">
                                                <div class="mb-1">
                                                    <input type="password" name="password"
                                                           required data-valueMissing="Password is required" data-typeMismatch="Please enter a valid password"
                                                           class="form-control col-md-8" id="password"
                                                           placeholder="Old Password">
                                                    <!-- <div class="input-group-append">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-lock"></i></span>
                                                    </div> -->
                                                </div>
                                                @if($errors->password_change->has('password'))
                                                    <span class="text-danger">{{ $errors->password_change->first('password') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-2 col-form-label" for="new_password">New
                                                Password</label>
                                            <div class="col-md-10 validation">
                                                <div class="mb-1">
                                                    <input type="password" class="form-control col-md-8"
                                                           required data-valueMissing="New Password is required" data-typeMismatch="Please enter a valid new password"
                                                           id="new_password" name="new_password"
                                                           placeholder="New Password">
                                                    <!-- <div class="input-group-append">
                                                            <span class="input-group-text">
                                                                <i class="fas fa-lock"></i>
                                                            </span>
                                                    </div> -->
                                                </div>
                                                @if($errors->password_change->has('new_password'))
                                                    <span class="text-danger">{{$errors->password_change->first('new_password')}}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-2 col-form-label" for="confirm_password">Confirm
                                                Password</label>
                                            <div class="col-md-10 validation">
                                                <div class="mb-1">
                                                    <input type="password" class="form-control col-md-8"
                                                    required data-valueMissing="Confirm Password is required" data-typeMismatch="Please enter a valid confirm password"
                                                           id="confirm_password" placeholder="Confirm Password"
                                                           name="confirm_password">
                                                    <!-- <div class="input-group-append">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-lock"></i></span>
                                                    </div> -->
                                                </div>
                                                @if($errors->password_change->has('confirm_password'))
                                                    <span class="text-danger">{{$errors->password_change->first('confirm_password')}}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-2"></div>
                                            <div class="col-md-10">
                                                <button type="submit" class="btn btn-primary" id="update_pass">
                                                    Change password
                                                </button>
                                            </div>
                                        </div>
                                    </form>

                                @endif
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
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

<!-- scripts -->
@section('custom-scripts')
<script>
    CommonFunction.validForm('profileForm');
    CommonFunction.validForm('changePasswordForm');
</script>
    @include('dashboard.profile.subviews.image-upload')
@endsection
<!-- scripts -->
<!-- Profile -->
