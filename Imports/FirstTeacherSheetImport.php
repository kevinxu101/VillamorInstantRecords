<?php

namespace App\Imports;

use App\User;
use App\Myclass;
use App\Section;
use App\Department;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;
use Validator;
class FirstTeacherSheetImport implements ToModel
{
    protected $class, $section, $department,$temp;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    protected $counter = 0;
    
    public function model(array $row)
    {

    

        if($this->counter == 0){

           $count = count($row);

            if($count == 10) $count -=2;
            
            if($count != 8){
         
                return false; 
            }

        }
            
        

   
     
        $this->temp = null;
        $this->department = null;
        $this->department = $row[7];

         
      
        if($this->counter > 0){
         


            if( $this->department == null){
                $this->temp == null;
            
        } else{
            
            if($this->getDepartmentId() == null) $this->temp = 'asdasda';
            else  {
                $this->temp = $this->getDepartmentId();
            };
          
        }

            return new User([
                'name'     => $row[0],
                'email'    => $row[1],
                'password' => Hash::make($row[2]),
                'role'           => 'teacher',
                'active'         => 1,
                'code'           => auth()->user()->code,
                'address'        => $row[3],
               'pic_path'       => '',
                'phone_number'   =>$row[4],
                'verified'       => 1,
                'nationality'    =>  $row[5] ?? '',
                'gender'         =>  $row[6],
                 'school_id'      => auth()->user()->school_id,
               'department_id'  => $this->temp,  //optional
             
            ]);
    
        }
      
       
        $this->counter++;
}
    

    
    public function getDepartmentId(){

        $temp =   Department::bySchool(auth()->user()->school_id)->where('department_name',$this->department)->pluck('id')->first();
      

        if($this->department != null){

            if( $temp)       return Department::bySchool(auth()->user()->school_id)->where('department_name',$this->department)->pluck('id')->first();
   
    
        }

    }
 


}
