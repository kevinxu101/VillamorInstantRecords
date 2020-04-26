<?php

namespace App\Imports;

use App\User;
use App\StudentInfo;
use App\Myclass;
use App\Section;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class FirstStudentSheetImport implements OnEachRow, WithHeadingRow
{
    protected $class, $section;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function onRow(Row $row)
    {   
        $rowIndex = $row->getIndex();

        if($rowIndex >= 200)
            return; // Not more than 200 rows at a time

        $row = $row->toArray();

        $this->class = (string) $row[__('class')];
        $this->section = (string) $row[__('section')];

        $user = [
            'last_name'      => $row[__('last_name')],
            'given_name'     => $row[__('given_name')],
            'middle_name'    => $row[__('middle_name')],
            'email'          => $row[__('email')],
            'password'       => Hash::make($row[__('password')]),
            'active'         => 1,
            'role'           => 'student',
            'school_id'      => auth()->user()->school_id,
            'code'           => auth()->user()->code,
            'student_code'   => auth()->user()->school_id.date('y').substr(number_format(time() * mt_rand(), 0, '', ''), 0, 5),
            'address'        => $row[__('address')],
            //'about'          => $row[__('about')],
            'pic_path'       => '',
            'phone_number'   => $row[__('phone_number')],
            'verified'       => 1,
            'section_id'     => $this->getSectionId(),
            //'blood_group'    => $row[__('blood_group')],
            'nationality'    => $row[__('nationality')],
            'gender'         => $row[__('gender')],
        ];

        $tb = create(User::class, $user);

        $student_info = [
            'student_id'                => $tb->id,
            'registration_status'       => $row[__('registration_status')] ?? '',
            'grade_level'               => $row[__('grade_level')] ?? '',
            'LRN'                       => $row[__('LRN')] ?? '',
            'requirements'              => $row[__('requirements')] ?? '',
            'semester'                  => $row[__('semester')] ?? '',
            'strand'                    => $row[__('strand')] ?? '',
            'group'                     => $row[__('group')] ?? '',
            'barangay'                  => $row[__('barangay')] ?? '',
            'city'                      => $row[__('city')] ?? '',
            'zipcode'                   => $row[__('zipcode')] ?? '',            
            'home_tel'                  => $row[__('home_tel')] ?? '',
            'father_name'               => $row[__('father_name')],
            'father_phone_number'       => $row[__('father_phone_number')] ?? '',
            'mother_maiden_name'        => $row[__('mother_name')],
            'mother_phone_number'       => $row[__('mother_phone_number')] ?? '',
            'school_name'               => $row[__('school_name')] ?? '',
            'school_id'                 => $row[__('school_id')] ?? '',
            'school_address'            => $row[__('school_address')] ?? '',
            'certificate'               => $row[__('certificate')] ?? '',            
            'user_id'                   => auth()->user()->id,
            //'session'              => $row[__('session')] ?? now()->year,
            //'version'              => $row[__('version')] ?? '',
            //'group'                => $row[__('group')] ?? '',
            //'birthday'             => $row[__('birthday')]?? date('Y-m-d'),
            //'father_national_id'   => $row[__('father_national_id')] ?? '',
            //'father_occupation'    => $row[__('father_occupation')] ?? '',
            //'father_designation'   => $row[__('father_designation')] ?? '',
            //'father_annual_income' => $row[__('father_annual_income')] ?? '',
            //'mother_national_id'   => $row[__('mother_national_id')] ?? '',
            //'mother_occupation'    => $row[__('mother_occupation')] ?? '',
            //'mother_designation'   => $row[__('mother_designation')] ?? '',
            //'mother_annual_income' => $row[__('mother_annual_income')] ?? '',
        ];
        
        create(StudentInfo::class, $student_info);
    }

    public function getSectionId(){
        if(!empty($this->class) && !empty($this->section)){
            $class_id = Myclass::bySchool(auth()->user()->school_id)->where('class_number', $this->class)->pluck('id')->first();

            $section = Section::where('class_id', $class_id)->where('section_number', $this->section)->pluck('id')->first();

            return $section;
        } else {
            return 0;
        }
    }
}
