@extends('layouts.app')

@section('title', __('Edit'))

@section('content')
<div class="container{{ (\Auth::user()->role == 'master')? '' : '-fluid' }}">
    <div class="row">
        @if(\Auth::user()->role != 'master')
        <div class="col-md-2" id="side-navbar">
            @include('layouts.leftside-menubar')
        </div>
        @endif
        <div class="col-md-{{ (\Auth::user()->role == 'master')? 12 : 8 }}" id="main-container">
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
            @endif
            <div class="panel panel-default">
                <div class="page-panel-title">@lang('Edit')</div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ url('edit/user') }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="user_id" value="{{$user->id}}">
                        <input type="hidden" name="user_role" value="{{$user->role}}">
                        
                        <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                            <label for="last_name" class="col-md-4 control-label">@lang('Last Name')</label>

                            <div class="col-md-6">
                                <input id="last_name" type="text" class="form-control" name="last_name" value="{{ $user->last_name }}"
                                    >

                                @if ($errors->has('last_name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('last_name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('given_name') ? ' has-error' : '' }}">
                            <label for="given_name" class="col-md-4 control-label">@lang('Given Name')</label>

                            <div class="col-md-6">
                                <input id="given_name" type="text" class="form-control" name="given_name" value="{{ $user->given_name }}"
                                    >

                                @if ($errors->has('given_name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('given_name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('middle_name') ? ' has-error' : '' }}">
                            <label for="middle_name" class="col-md-4 control-label">@lang('Middle Name')</label>

                            <div class="col-md-6">
                                <input id="middle_name" type="text" class="form-control" name="middle_name" value="{{ $user->middle_name }}"
                                    >

                                @if ($errors->has('middle_name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('middle_name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">@lang('E-Mail Address')</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email"
                                    value="{{ $user->email }}">

                                @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('phone_number') ? ' has-error' : '' }}">
                            <label for="phone_number" class="col-md-4 control-label">@lang('Phone Number')</label>

                            <div class="col-md-6">
                                <input id="phone_number" type="text" class="form-control" name="phone_number"
                                    value="{{ $user->phone_number }}">

                                @if ($errors->has('phone_number'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('phone_number') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('nationality') ? ' has-error' : '' }}">
                            <label for="nationality" class="col-md-4 control-label">@lang('Nationality')</label>

                            <div class="col-md-6">
                                <input id="nationality" type="text" class="form-control" name="nationality" value="{{ $user->nationality }}"
                                    required>

                                @if ($errors->has('nationality'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('nationality') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        @if($user->role == 'teacher')
                        <div class="form-group{{ $errors->has('department') ? ' has-error' : '' }}">
                            <label for="department" class="col-md-4 control-label">@lang('Department')</label>

                            <div class="col-md-6">
                                <select id="department" class="form-control" name="department_id">
                                    @if (count($departments)) > 0)
                                    @foreach ($departments as $d)
                                    <option value="{{$d->id}}" @if ($d->id == old('department_id', $user->department_id))
											selected="selected"
										@endif
										>{{$d->department_name}}</option>
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
                                    @foreach ($sections as $section)
                                    <option value="{{$section->id}}" @if ($section->id == old('class_teacher_section_id', $user->section_id))
											selected="selected"
										@endif
										>@lang('Section'): {{$section->section_number}} @lang('Class'):
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

                        <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                            <label for="address" class="col-md-4 control-label">@lang('Address')</label>

                            <div class="col-md-6">
                                <input id="address" type="text" class="form-control" name="address"
                                    value="{{ $user->address }}">

                                @if ($errors->has('address'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('address') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        

                        <!--<div class="form-group{{ $errors->has('about') ? ' has-error' : '' }}">
                            <label for="about" class="col-md-4 control-label">@lang('About')</label>

                            <div class="col-md-6">
                                <textarea id="about" class="form-control" name="about">{{ $user->about }}</textarea>

                                @if ($errors->has('about'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('about') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>-->
                        
                        @if($user->role == 'student')

                        <div class="form-group{{ $errors->has('barangay') ? ' has-error' : '' }}">
                            <label for="barangay" class="col-md-4 control-label">@lang('Barangay')</label>

                            <div class="col-md-6">
                                <input id="barangay" type="text" class="form-control" name="barangay" value="{{ $user->studentInfo['barangay']}}"
                                    required>

                                @if ($errors->has('barangay'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('barangay') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
                            <label for="city" class="col-md-4 control-label">@lang('City/Municipality')</label>

                            <div class="col-md-6">
                                <input id="city" type="text" class="form-control" name="city" value="{{ $user->studentInfo['city'] }}"
                                    required>

                                @if ($errors->has('city'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('city') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('zipcode') ? ' has-error' : '' }}">
                            <label for="zipcode" class="col-md-4 control-label">@lang('Zipcode')</label>

                            <div class="col-md-6">
                                <input id="zipcode" type="text" class="form-control" name="zipcode" value="{{ $user->studentInfo['zipcode'] }}"
                                    required>

                                @if ($errors->has('zipcode'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('zipcode') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('group') ? ' has-error' : '' }}">
                            <label for="group" class="col-md-4 control-label">@lang('Do you belong to indigenous people?')</label>
                            
                            <div class="col-md-6">
                                <input id="group" type="text" class="form-control" name="group" value="{{ $user->studentInfo['group'] }}" readonly>

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
                                    <option selected="selected">@lang('A')</option>
                                    <option>@lang('B')</option>
                                    <option>@lang('C')</option>
                                    <option>@lang('D')</option>
									<option>@lang('E')</option>
                                    <option>@lang('F')</option>
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
                                    <option selected="selected">@lang('A')</option>
                                    <option>@lang('B')</option>
                                    <option>@lang('C')</option>
                                    <option>@lang('D')</option>
									<option>@lang('E')</option>
                                    <option>@lang('F')</option>
                                </select>

                                @if ($errors->has('grade_level'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('grade_level') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('LRN') ? ' has-error' : '' }}">
                            <label for="LRN" class="col-md-4 control-label">@lang('Learners Reference Number')</label>

                            <div class="col-md-6">
                                <select id="LRN" class="form-control" name="LRN">
                                    <option selected="selected">@lang('A')</option>
                                    <option>@lang('B')</option>
                                    <option>@lang('C')</option>
                                    <option>@lang('D')</option>
									<option>@lang('E')</option>
                                    <option>@lang('F')</option>
                                </select>

                                @if ($errors->has('LRN'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('LRN') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('requirements') ? ' has-error' : '' }}">
                            <label for="requirements" class="col-md-4 control-label">@lang('Requirements Checklist')</label>

                            <div class="col-md-6">
                                <!--<p><input type="checkbox" name="requirements" value="AS" />AS</p>
                                <p><input type="checkbox" name="requirements" value="AF" />AF</p>-->

                                <input id="group" type="text" class="form-control" name="group" value="{{ $user->studentInfo['requirements'] }}" readonly>

                                @if ($errors->has('requirements'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('requirements') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('semester') ? ' has-error' : '' }}">
                            <label for="semester" class="col-md-4 control-label">@lang('Semester')</label>

                            <div class="col-md-6">
                                <select id="semester" class="form-control" name="semester" value="{{ $user->studentInfo['semester'] }}">
                                    <option>@lang('A')</option>
                                    <option>@lang('B')</option>
                                    <option>@lang('C')</option>
                                    <option>@lang('D')</option>
									<option>@lang('E')</option>
                                    <option>@lang('F')</option>
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
                                <select id="strand" class="form-control" name="strand" value="{{ $user->studentInfo['strand'] }}">
                                    <option>@lang('A')</option>
                                    <option>@lang('B')</option>
                                    <option>@lang('C')</option>
                                    <option>@lang('D')</option>
									<option>@lang('E')</option>
                                    <option>@lang('F')</option>
                                </select>

                                @if ($errors->has('strand'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('strand') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <!--<div class="form-group{{ $errors->has('birthday') ? ' has-error' : '' }}">
                            <label for="birthday" class="col-md-4 control-label">* @lang('Birthday')</label>

                            <div class="col-md-6">
                                <input id="birthday" type="text" class="form-control" name="birthday" required>

                                @if ($errors->has('birthday'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('birthday') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('session') ? ' has-error' : '' }}">
                            <label for="session" class="col-md-4 control-label">* @lang('Session')</label>

                            <div class="col-md-6">
                                <input id="session" type="text" class="form-control" name="session" required>

                                @if ($errors->has('session'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('session') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>-->


                        <div class="form-group{{ $errors->has('father_name') ? ' has-error' : '' }}">
                            <label for="father_name" class="col-md-4 control-label">@lang('Father\'s Name')</label>

                            <div class="col-md-6">
                                <input id="father_name" type="text" class="form-control" name="father_name"
                                    value="{{ $user->studentInfo['father_name'] }}" required>

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
                                <input id="father_phone_number" type="text" class="form-control"
                                    name="father_phone_number" value="{{ $user->studentInfo['father_phone_number'] }}">

                                @if ($errors->has('father_phone_number'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('father_phone_number') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('mother_maiden_name') ? ' has-error' : '' }}">
                            <label for="mother_maiden_name" class="col-md-4 control-label">@lang('Mother\'s MaidenName')</label>

                            <div class="col-md-6">
                                <input id="mother_maiden_name" type="text" class="form-control" name="mother_maiden_name"
                                    value="{{ $user->studentInfo['mother_maiden_name'] }}" required>

                                @if ($errors->has('mother_maiden_name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('mother_maiden_name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('mother_phone_number') ? ' has-error' : '' }}">
                            <label for="mother_phone_number" class="col-md-4 control-label">@lang('Mother\'s Phone Number')</label>

                            <div class="col-md-6">
                                <input id="mother_phone_number" type="text" class="form-control"
                                    name="mother_phone_number" value="{{ $user->studentInfo['mother_phone_number'] }}">

                                @if ($errors->has('mother_phone_number'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('mother_phone_number') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('home_tel') ? ' has-error' : '' }}">
                            <label for="home_tel" class="col-md-4 control-label">@lang('Home TelephoneNo.')</label>

                            <div class="col-md-6">
                                <input id="home_tel" type="text" class="form-control" name="home_tel" value="{{ $user->studentInfo['home_tel'] }}"
                                    required>

                                @if ($errors->has('home_tel'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('home_tel') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('school_name') ? ' has-error' : '' }}">
                            <label for="school_name" class="col-md-4 control-label">@lang('Previous School Name')</label>

                            <div class="col-md-6">
                                <input id="school_name" type="text" class="form-control" name="school_name" value="{{ $user->studentInfo['school_name'] }}"
                                    required>

                                @if ($errors->has('school_name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('school_name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('school_id') ? ' has-error' : '' }}">
                            <label for="school_id" class="col-md-4 control-label">@lang('School Id')</label>

                            <div class="col-md-6">
                                <input id="school_id" type="text" class="form-control" name="school_id" value="{{ $user->studentInfo['school_id'] }}"
                                    required>

                                @if ($errors->has('school_id'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('school_id') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('school_address') ? ' has-error' : '' }}">
                            <label for="school_address" class="col-md-4 control-label">@lang('School Address')</label>

                            <div class="col-md-6">
                                <input id="school_address" type="text" class="form-control" name="school_address" value="{{ $user->studentInfo['school_address'] }}"
                                    required>

                                @if ($errors->has('school_address'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('school_address') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        
                        <div style="display:none" class="form-group{{ $errors->has('certificate') ? ' has-error' : '' }}">
                            <label for="certificate" class="col-md-4 control-label">@lang('Certify')</label>

                            <div class="col-md-6">
                                <p><input type="radio" name="certificate" value="Yes" />Yes</p>
                                <p><input type="radio" name="certificate" value="No" />No</p>


                                @if ($errors->has('certificate'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('certificate') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                    
                        @endif

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <a href="javascript:history.back()" class="btn btn-danger" style="margin-right: 2%;"
                                    role="button">@lang('Cancel')</a>
                                <input type="submit" role="button" class="btn btn-success" value="@lang('Save')">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css"
    rel="stylesheet">

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>
<script>
    $(function () {
        $('#birthday').datepicker({
            format: "yyyy-mm-dd",
        });
        $('#birthday').datepicker('setDate',
            "{{ Carbon\Carbon::parse($user->studentInfo['birthday'])->format('Y-d-m') }}");
        $('#session').datepicker({
            format: "yyyy",
            viewMode: "years",
            minViewMode: "years"
        });
        $('#session').datepicker('setDate',
            "{{ Carbon\Carbon::parse($user->studentInfo['session'])->format('Y') }}");
    });
</script>
@endsection