@extends('layouts.app')

@section('title', __('Register'))

@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css" rel="stylesheet">
<div class="container{{ (\Auth::user()->role == 'master')? '' : '-fluid' }}">
    <div class="row">
        @if(\Auth::user()->role != 'master')
        <div class="col-md-2" id="side-navbar">
            @include('layouts.leftside-menubar')
        </div>
        @else
        <div class="col-md-3" id="side-navbar">
            <ul class="nav flex-column">
                <li class="nav-item active">
                    <a class="nav-link" href="{{ route('schools.index') }}"><i class="material-icons">gamepad</i> <span class="nav-link-text">@lang('Back to Manage School')</span></a>
                </li>
            </ul>
        </div>
        @endif
        <div class="col-md-8" id="main-container">
            @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
                {{-- Display View admin links --}}
                @if (session('register_school_id'))
                    <a href="{{ url('school/admin-list/' . session('register_school_id')) }}" target="_blank" class="text-white pull-right">@lang('View Admins')</a>
                @endif
            </div>
            @endif
            <div class="panel panel-default">
                <div class="page-panel-title">@lang('Register') {{ucfirst(session('register_role'))}}</div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" id="registerForm" action="{{ url('register/'.session('register_role')) }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                            <label for="last_name" class="col-md-4 control-label">* @lang('Last Name')</label>

                            <div class="col-md-6">
                                <input id="last_name" type="text" class="form-control" name="last_name" value="{{ old('last_name') }}"
                                    required>

                                @if ($errors->has('last_name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('last_name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('given_name') ? ' has-error' : '' }}">
                            <label for="given_name" class="col-md-4 control-label">* @lang('Given Name')</label>

                            <div class="col-md-6">
                                <input id="given_name" type="text" class="form-control" name="given_name" value="{{ old('given_name') }}"
                                    required>

                                @if ($errors->has('given_name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('given_name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('middle_name') ? ' has-error' : '' }}">
                            <label for="middle_name" class="col-md-4 control-label">* @lang('Middle Name')</label>

                            <div class="col-md-6">
                                <input id="middle_name" type="text" class="form-control" name="middle_name" value="{{ old('middle_name') }}"
                                    required>

                                @if ($errors->has('middle_name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('middle_name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">* @lang('E-Mail Address')</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('phone_number') ? ' has-error' : '' }}">
                            <label for="phone_number" class="col-md-4 control-label">* @lang('Phone Number')</label>

                            <div class="col-md-6">
                                <input id="phone_number" type="text" class="form-control" name="phone_number" value="{{ old('phone_number') }}">

                                @if ($errors->has('phone_number'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('phone_number') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">* @lang('Password')</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" class="col-md-4 control-label">* @lang('Confirm Password')</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation"
                                    required>
                            </div>
                        </div>
                        @if(session('register_role', 'student') == 'student')
                        <div class="form-group{{ $errors->has('section') ? ' has-error' : '' }}">
                            <label for="section" class="col-md-4 control-label">* @lang('Class and Section')</label>

                            <div class="col-md-6">
                                <select id="section" class="form-control" name="section" required>
                                    @foreach (session('register_sections') as $section)
                                    <option value="{{$section->id}}">@lang('Section'): {{$section->section_number}} @lang('Class'):
                                        {{$section->class->class_number}}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('section'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('section') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <!--<div class="form-group{{ $errors->has('birthday') ? ' has-error' : '' }}">
                            <label for="birthday" class="col-md-4 control-label">* @lang('Birthday')</label>

                            <div class="col-md-6">
                                <input id="birthday" type="text" class="form-control" name="birthday" value="{{ old('birthday') }}"
                                    required>

                                @if ($errors->has('birthday'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('birthday') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>-->
                        @endif
                        @if(session('register_role', 'teacher') == 'teacher')
                        <div class="form-group{{ $errors->has('department') ? ' has-error' : '' }}">
                            <label for="department" class="col-md-4 control-label">* @lang('Department')</label>

                            <div class="col-md-6">
                                <select id="department" class="form-control" name="department_id" required>
                                    @if (count(session('departments')) > 0)
                                        @foreach (session('departments') as $d)
                                            <option value="{{$d->id}}">{{$d->department_name}}</option>
                                        @endforeach
                                    @endif
                                </select>

                                @if ($errors->has('department'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('department') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('class_teacher') ? ' has-error' : '' }}">
                            <label for="class_teacher" class="col-md-4 control-label">@lang('Class Teacher')</label>

                            <div class="col-md-6">
                                <select id="class_teacher" class="form-control" name="class_teacher_section_id">
                                    <option selected="selected" value="0">@lang('Not Class Teacher')</option>
                                    @foreach (session('register_sections') as $section)
                                    <option value="{{$section->id}}">@lang('Section'): {{$section->section_number}} @lang('Class'):
                                        {{$section->class->class_number}}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('class_teacher'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('class_teacher') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        @endif

                        <div class="form-group{{ $errors->has('nationality') ? ' has-error' : '' }}">
                            <label for="nationality" class="col-md-4 control-label">* @lang('Nationality')</label>

                            <div class="col-md-6">
                                <input id="nationality" type="text" class="form-control" name="nationality" value="{{ old('nationality') }}"
                                    required>

                                @if ($errors->has('nationality'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('nationality') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('gender') ? ' has-error' : '' }}">
                            <label for="gender" class="col-md-4 control-label">@lang('Gender')</label>

                            <div class="col-md-6">
                                <select id="gender" class="form-control" name="gender">
                                    <option selected="selected">@lang('Male')</option>
                                    <option>@lang('Female')</option>
                                </select>

                                @if ($errors->has('gender'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('gender') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        @if(session('register_role', 'student') == 'student')
                        <!--<div class="form-group{{ $errors->has('session') ? ' has-error' : '' }}">
                            <label for="session" class="col-md-4 control-label">* @lang('Session')</label>

                            <div class="col-md-6">
                                <input id="session" type="text" class="form-control" name="session" value="{{ old('session') }}"
                                    required>

                                @if ($errors->has('session'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('session') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>-->

                        <div class="form-group{{ $errors->has('group') ? ' has-error' : '' }}">
                            <label for="group" class="col-md-4 control-label">@lang('Do you belong to indigenous people?')</label>

                            <div class="col-md-6">


                                <label class="radio-inline"><input type="radio" id="rad" name="" value="" />Yes</label>

                                   
                                
                                
                                <label class="radio-inline"><input type="radio" name="group" value="None" />No</label>
                                
                        
                                @if ($errors->has('group'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('group') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('registration_status') ? ' has-error' : '' }}">
                            <label for="registration_status" class="col-md-4 control-label">@lang('Registration Status')</label>

                            <div class="col-md-6">
                                <select id="registration_status" class="form-control" name="registration_status">
                                    <option selected="selected">@lang('')</option>
                                    <option>@lang('Active')</option>
                                    <option>@lang('Not-Active')</option>
                                </select>

                                @if ($errors->has('registration_status'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('registration_status') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('grade_level') ? ' has-error' : '' }}">
                            <label for="grade_level" class="col-md-4 control-label">@lang('Grade Level')</label>

                            <div class="col-md-6">
                                <select id="grade_level" class="form-control" name="grade_level">
                                    <option selected="selected">@lang('')</option>
                                    <option>@lang('Grade 11')</option>
                                    <option>@lang('Grade 12')</option>
                                </select>

                                @if ($errors->has('grade_level'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('grade_level') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('LRN') ? ' has-error' : '' }}">
                            <label for="LRN" class="col-md-4 control-label">* @lang('Learners Reference Number')</label>

                            <div class="col-md-6">
                                <select id="LRN" class="form-control" name="LRN">
                                    <option selected="selected">@lang('')</option>
                                    <option>@lang('1')</option>
                                    <option>@lang('2')</option>
                                    <option>@lang('3')</option>
									<option>@lang('4')</option>
                                    <option>@lang('5')</option>
                                </select>

                                @if ($errors->has('LRN'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('LRN') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <!-- -------- -->
                        <div class="form-group{{ $errors->has('requirements') ? ' has-error' : '' }}">
                            <label for="requirements" class="col-md-4 control-label">* @lang('Requirements Checklist')</label>

                            <div class="col-md-6">
                                <div class="checkbox">
                                    <label><input type="checkbox" name="requirements" value="Sample Data1">Option 1</label>
                                </div>
                                <div class="checkbox">
                                    <label><input type="checkbox" name="requirements" value="Sample Data2">Option 1</label>
                                </div>

                                @if ($errors->has('requirements'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('requirements') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <!-- -------- -->
                        <div class="form-group{{ $errors->has('semester') ? ' has-error' : '' }}">
                            <label for="semester" class="col-md-4 control-label">* @lang('Semester')</label>

                            <div class="col-md-6">
                                <select id="semester" class="form-control" name="semester">
                                    <option selected="selected">@lang('')</option>
                                    <option>@lang('1st Semester')</option>
                                    <option>@lang('2nd Semester')</option>
                                </select>

                                @if ($errors->has('semester'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('semester') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('strand') ? ' has-error' : '' }}">
                            <label for="strand" class="col-md-4 control-label">@lang('Strand')</label>

                            <div class="col-md-6">
                                <select id="strand" class="form-control" name="strand">
                                    <option selected="selected">@lang('')</option>
                                    <option>@lang('HUMSS')</option>
                                    <option>@lang('STEM')</option>
                                    <option>@lang('GAS')</option>
									<option>@lang('ABM')</option>
                                    
                                </select>

                                @if ($errors->has('strand'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('strand') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <!--<div class="form-group{{ $errors->has('religion') ? ' has-error' : '' }}">
                            <label for="religion" class="col-md-4 control-label">@lang('Religion')</label>

                            <div class="col-md-6">
                                <select id="religion" class="form-control" name="religion">
                                    <option selected="selected">@lang('Islam')</option>
                                    <option>@lang('Hinduism')</option>
                                    <option>@lang('Christianism')</option>
                                    <option>@lang('Buddhism')</option>
									<option>@lang('Catholic')</option>
                                    <option>@lang('Other')</option>
                                </select>

                                @if ($errors->has('religion'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('religion') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>-->

                        <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                            <label for="address" class="col-md-4 control-label">* @lang('Address')</label>

                            <div class="col-md-6">
                                <input id="address" type="text" class="form-control" name="address" value="{{ old('address') }}"
                                    required>

                                @if ($errors->has('address'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('address') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('barangay') ? ' has-error' : '' }}">
                            <label for="barangay" class="col-md-4 control-label">* @lang('Barangay')</label>

                            <div class="col-md-6">
                                <input id="barangay" type="text" class="form-control" name="barangay" value="{{ old('barangay') }}"
                                    required>

                                @if ($errors->has('barangay'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('barangay') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
                            <label for="city" class="col-md-4 control-label">* @lang('City/Municipality')</label>

                            <div class="col-md-6">
                                <input id="city" type="text" class="form-control" name="city" value="{{ old('city') }}"
                                    required>

                                @if ($errors->has('city'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('city') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('zipcode') ? ' has-error' : '' }}">
                            <label for="zipcode" class="col-md-4 control-label">* @lang('Zipcode')</label>

                            <div class="col-md-6">
                                <input id="zipcode" type="text" class="form-control" name="zipcode" value="{{ old('zipcode') }}"
                                    required>

                                @if ($errors->has('zipcode'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('zipcode') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('father_name') ? ' has-error' : '' }}">
                            <label for="father_name" class="col-md-4 control-label">* @lang('Father\'s Name')</label>

                            <div class="col-md-6">
                                <input id="father_name" type="text" class="form-control" name="father_name" value="{{ old('father_name') }}"
                                    required>

                                @if ($errors->has('father_name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('father_name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('father_phone_number') ? ' has-error' : '' }}">
                            <label for="father_phone_number" class="col-md-4 control-label">@lang('Father\'s Phone Number')</label>

                            <div class="col-md-6">
                                <input id="father_phone_number" type="text" class="form-control" name="father_phone_number"
                                    value="{{ old('father_phone_number') }}">

                                @if ($errors->has('father_phone_number'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('father_phone_number') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('mother_maiden_name') ? ' has-error' : '' }}">
                            <label for="mother_maiden_name" class="col-md-4 control-label">* @lang('Mother\'s Maiden Name')</label>

                            <div class="col-md-6">
                                <input id="mother_maiden_name" type="text" class="form-control" name="mother_maiden_name" value="{{ old('mother_maiden_name') }}"
                                    required>

                                @if ($errors->has('mother_maiden_name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('mother_maiden_name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('mother_phone_number') ? ' has-error' : '' }}">
                            <label for="mother_phone_number" class="col-md-4 control-label">* @lang('Mother\'s Phone Number')</label>

                            <div class="col-md-6">
                                <input id="mother_phone_number" type="text" class="form-control" name="mother_phone_number"
                                    value="{{ old('mother_phone_number') }}">

                                @if ($errors->has('mother_phone_number'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('mother_phone_number') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('home_tel') ? ' has-error' : '' }}">
                            <label for="home_tel" class="col-md-4 control-label">* @lang('Home TelephoneNo.')</label>

                            <div class="col-md-6">
                                <input id="home_tel" type="text" class="form-control" name="home_tel" value="{{ old('home_tel') }}"
                                    required>

                                @if ($errors->has('home_tel'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('home_tel') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('school_name') ? ' has-error' : '' }}">
                            <label for="school_name" class="col-md-4 control-label">* @lang('Previous School Name')</label>

                            <div class="col-md-6">
                                <input id="school_name" type="text" class="form-control" name="school_name" value="{{ old('school_name') }}"
                                    required>

                                @if ($errors->has('school_name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('school_name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('school_id') ? ' has-error' : '' }}">
                            <label for="school_id" class="col-md-4 control-label">* @lang('School Id')</label>

                            <div class="col-md-6">
                                <input id="school_id" type="text" class="form-control" name="school_id" value="{{ old('school_id') }}"
                                    required>

                                @if ($errors->has('school_id'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('school_id') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('school_address') ? ' has-error' : '' }}">
                            <label for="school_address" class="col-md-4 control-label">* @lang('School Address')</label>

                            <div class="col-md-6">
                                <input id="school_address" type="text" class="form-control" name="school_address" value="{{ old('school_address') }}"
                                    required>

                                @if ($errors->has('school_address'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('school_address') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('certificate') ? ' has-error' : '' }}">
                            <label for="certificate" class="col-md-4 control-label">@lang('Do you agree to terms and condition?')</label>

                            <div class="col-md-6">
                                <div class="radio">
                                <label><input type="radio" name="certificate" value="Agree" />Agree</label>
                                </div>

                                @if ($errors->has('certificate'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('certificate') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        @endif

                        <div class="form-group">
                            <label class="col-md-4 control-label">@lang('Upload Profile Picture')</label>
                            <div class="col-md-6">
                                <input type="hidden" id="picPath" name="pic_path">
                                @component('components.file-uploader',['upload_type'=>'profile'])
                                @endcomponent
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" id="registerBtn" class="btn btn-primary">
                                    @lang('Register')
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>
<script>
    $(function () {
        $('#birthday').datepicker({
            format: "yyyy-mm-dd",
        });
        $('#session').datepicker({
            format: "yyyy",
            viewMode: "years",
            minViewMode: "years"
        });
    });
    $('#registerBtn').click(function () {
        $("#registerForm").submit();
    });
</script>


@endsection
