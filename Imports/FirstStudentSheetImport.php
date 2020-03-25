<?php

namespace App\Imports;

use App\User;
use App\StudentInfo;
use App\Myclass;
use App\Section;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\ToModel;

class FirstStudentSheetImport implements ToModel
{
    protected $class, $section, $temp;

    protected $counter =0;
    /**
    * @param array $row
    *   
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {  

        if($this->counter == 0){

            $count = count($row);

            if($count == 29) $count -=2;
            
            if($count != 27){
         
                return false; 
            }

        }
            
        
        
        $this->section =  $row[6];

 
        $this->temp  = null;
       
            
        if($this->counter  == 1){
     
          
            if( $this->section == null){
                
                $this->temp == null;
                
            } 
            else{
            
                if($this->getSectionId() == null) $this->temp = -1;
                else $this->temp = $this->getSectionId();
            }
          
       


            $user = [
                'name'           =>  $row[0],
                'email'          => $row[1],
                'password'       => Hash::make($row[2]),
                'active'         => 1,
                'role'           => 'student',
                'school_id'      => auth()->user()->school_id,
                'code'           => auth()->user()->code,
                'student_code'   => auth()->user()->school_id.date('y').substr(number_format(time() * mt_rand(), 0, '', ''), 0, 5),
                'address'        => $row[3],
                'about'          => $row[4],
                'pic_path'       => '',
                'phone_number'   => $row[5],
                'verified'       => 1,
                'section_id'     => $this->temp,
                'blood_group'    => $row[7],
                'nationality'    => $row[8],
                'gender'         => $row[9],
            ];
    
            User::create($user);

            $model = User::where('email', '=', $row[1])->pluck('id')->first();
    

        
            $student_info = [
                'student_id'           => $model,
                'session'              => $row[10] ?? now()->year,
                'version'              => $row[11] ?? '',
                'group'                => $row[12] ?? '',
                'birthday'             => $row[13]?? date('Y-m-d'),
                'religion'             => $row[14] ?? '',
                'father_name'          => $row[15],
                'father_phone_number'  => $row[16] ?? '',
                'father_national_id'   => $row[17] ?? '',
                'father_occupation'    => $row[18] ?? '',
                'father_designation'   => $row[19] ?? '',
                'father_annual_income' =>$row[20] ?? '',
                'mother_name'          => $row[21],
                'mother_phone_number'  => $row[22] ?? '',
                'mother_national_id'   => $row[23] ?? '',
                'mother_occupation'    => $row[24] ?? '',
                'mother_designation'   => $row[25] ?? '',
                'mother_annual_income' => $row[26] ?? '',
                'user_id' => auth()->user()->id,
            ];
            
            create(StudentInfo::class, $student_info);














       }

      
    
  
    $this->counter ++; 
}



    public function getSectionId(){
     
        $section = Section::where('section_number', $this->section)->pluck('id')->first();
 
        if($this->section != null){
            if( $section)       return  Section::where('section_number', $this->section)->pluck('id')->first();
      
        }
    }
           
    
    
      
    
               
  

   
}
