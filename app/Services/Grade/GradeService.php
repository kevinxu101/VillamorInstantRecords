<?php
namespace App\Services\Grade;

use App\Grade;
use App\Gradesystem;
use App\Exam;
use App\Course;
use App\Section;
use App\Myclass;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GradeService {

  public $grades;
  public $gradesystems;
  public $course_id;
  public $exam_id;
  public $teacher_id;
  public $section_id;
  public $exams;
  // Calculation marks starts
  public $final_att_mark;
  public $final_assignment_mark;
  public $final_quiz_mark;
  public $final_ct_mark;
  public $final_finalExam_mark;
  public $final_practical_mark;
  public $quizCount;
  public $assignmentCount;
  public $ctCount;
  public $quizSum;
  public $assignmentSum;
  public $ctSum;
  public $field;
  public $grade;
  public $maxFieldNum;
  public $fieldCount;
  public $full_field_mark;
  public $field_percentage;
  public $avg_field_sum;
  public $final_default_value;
  // Calculation marks ends

  public function isLoggedInUserStudent(){
    return auth()->user()->role == 'student';
  }

  public function getExamByIdsFromGrades($grades){
    $examIds = $grades->map(function($grade){
      return $grade->exam_id;
    });
    $exams = Exam::whereIn('id', $examIds)
                  ->orderBy('id','desc')
                  ->get();
    return $exams;
  }

  public function getStudentGradesWithInfoCourseTeacherExam($student_id){
    return Grade::with(['student','course','teacher','exam'])
                  ->where('student_id', $student_id)
                  ->orderBy('exam_id')
                  ->latest()
                  ->get();
  }

  public function getGradeSystemBySchoolId($grades){
    $grade_system_name = isset($grades[0]->course->grade_system_name) ? $grades[0]->course->grade_system_name : false;
    return ($grade_system_name)?Gradesystem::where('school_id', auth()->user()->school_id)
                        ->where('grade_system_name', $grade_system_name)
                        //->groupBy('grade_system_name')
                        ->get() : Gradesystem::select('grade_system_name')
                        ->where('school_id', auth()->user()->school_id)
                        ->distinct()
                        ->get();
  }

  public function getGradeSystemByname($grade_system_name){
    return Gradesystem::where('school_id', auth()->user()->school_id)
                        ->where('grade_system_name', $grade_system_name)
                        ->get();
  }

  public function gradeIndexView($view){
    return view($view,[
        'grades' => $this->grades,
        'gradesystems' => $this->gradesystems,
        'exams' => $this->exams,
      ]);
  }

  public function getGradeSystemBySchoolIdGroupByName($grades){
    $grade_system_name = isset($grades[0]->course->grade_system_name) ? $grades[0]->course->grade_system_name : false;
    
    return ($grade_system_name)?Gradesystem::where('school_id', auth()->user()->school_id)
                        ->where('grade_system_name', $grade_system_name)
                        //->groupBy('grade_system_name')
                        ->get() : Gradesystem::select('grade_system_name')
                        ->where('school_id', auth()->user()->school_id)
                        ->distinct()
                        ->get();
  }

  public function gradeTeacherIndexView($view){
    return view($view,[
        'grades' => $this->grades,
        'gradesystems' => $this->gradesystems
      ]);
  }

  public function gradeCourseIndexView($view){
    return view($view,[
        'grades' => $this->grades,
        'gradesystems' => $this->gradesystems,
        'course_id' => $this->course_id,
        'exam_id' => $this->exam_id,
        'teacher_id' => $this->teacher_id,
        'section_id' => $this->section_id,
      ]);
  }

  public function getGradesByCourseExam($course_id, $exam_id){
    return Grade::with('course','student')
                ->where('course_id', $course_id)
                ->where('exam_id',$exam_id)
                ->get();
  }

  public function calculateGpaFromTotalMarks($grades, $course){
    foreach($grades as $key => $grade){ 
      // Calculate GPA from Total marks
      $tb = Grade::find($grade['id']);
      $totalMarks = $this->calculateMarks($course, $grade);
      $quarterly_grade = $this->getQuarterlyGrade($totalMarks,$course);
      $tb->marks = $totalMarks;
      $tb->marks_final = $quarterly_grade;
      $tbc[] = $tb->attributesToArray();
      return $tbc;
}

}

  public function getActiveExamIds(){
    return Exam::where('school_id', auth()->user()->school_id)
                  ->where('active',1)
                  ->pluck('id');
  }

  public function getCourseBySectionIdExamIds($section_id, $examIds){
    return Course::where('section_id',$section_id)
                  ->whereIn('exam_id', $examIds)
                  ->pluck('id')
                  ->toArray();
  }

  public function getGradesByCourseId($courses){
    return Grade::with(['student','course','exam'])
                ->whereIn('course_id', $courses)
                ->get();
  }

  public function getClassesBySchoolId(){
    return Myclass::where('school_id',auth()->user()->school->id)->get();
  }

  public function getSectionsByClassIds($classIds){
    return Section::whereIn('class_id',$classIds)
                  ->orderBy('section_number')
                  ->get();
  }

  public function getCourseByCourseId(){
    return Course::find($this->course_id);
  }

  
  public function saveCalculatedGPAFromTotalMarks($tbc){
    try{
      if(count($tbc) > 0)
        return \Batch::update('grades',(array) $tbc,'id');
    }catch(\Exception $e){
      return "OOps, an error occured";
    }
  }

    public function calculateMarks($course, $grade){
        $this->grade = $grade;
        
        $quarterly_grade = 0;
        $this->quizCount = 1;
        $this->assignmentCount = 1;
        $this->ctCount = 1;

        // Quiz
        $this->field = 'quiz';
        $this->fieldCount = 1;
        $this->maxFieldNum = 1;
        $this->quizSum = $this->getMarkSum();
        // Assignment
        $this->field = 'assignment';
        $this->fieldCount = 1;
        $this->maxFieldNum = 1;
        $this->assignmentSum = $this->getMarkSum();
        // Class Test
        $this->field = 'ct';
        $this->fieldCount = 1;
        $this->maxFieldNum = 1;
        $this->ctSum = $this->getMarkSum();
        
        
              // Percentage related calculation
              // Attendance
              $this->full_field_mark = $course->att_fullmark;
              $this->field_percentage = 0;
              $this->avg_field_sum = $this->grade['attendance'];
              $this->final_default_value = $this->grade['attendance'];
              $this->final_att_mark = $this->getFieldFinalMark();
              // Quiz
              $this->full_field_mark = $course->quiz_fullmark;
              $this->field_percentage = 25;
              $this->avg_field_sum = $this->quizCount > 0 ? $this->quizSum/$this->quizCount : 0;
              $this->final_default_value = $this->quizSum;
              $this->final_quiz_mark = $this->getFieldFinalMark();
              // Assignment
              $this->full_field_mark = $course->a_fullmark;
              $this->field_percentage = 50;
              $this->avg_field_sum = $this->assignmentCount > 0 ? $this->assignmentSum/$this->assignmentCount : 0;
              $this->final_default_value = $this->assignmentSum;
              $this->final_assignment_mark = $this->getFieldFinalMark();
              // Class Test
              $this->full_field_mark = $course->ct_fullmark;
              $this->field_percentage = 25;
              $this->avg_field_sum = $this->ctCount > 0 ? $this->ctSum/$this->ctCount : 0;
              $this->final_default_value = $this->ctSum;
              $this->final_ct_mark = $this->getFieldFinalMark();

              $totalMarks = $this->getTotalCalculatedMarks();
            
              return $totalMarks;
    }

    public function getQuarterlyGrade($totalMarks){

        //fuck u sa next batch kayo mag aayos nito
        
          if($totalMarks <= 3.99){
            return $quarterly_grade = 60;
          }
          else if($totalMarks >= 4.00 && $totalMarks <= 7.99)
          {
            return $quarterly_grade = 61;
          }
          else if($totalMarks >= 8.00 && $totalMarks <= 11.99)
          {
            return $quarterly_grade = 62;
          }
          else if($totalMarks >= 12.00 && $totalMarks <= 15.99)
          {
            return $quarterly_grade = 63;
          }
          else if($totalMarks >= 16.00 && $totalMarks <= 19.99)
          {
            return $quarterly_grade = 64;
          }
          else if($totalMarks >= 20.00 && $totalMarks <= 23.99)
          {
            return $quarterly_grade = 65;
          }
          else if($totalMarks >= 24.00 && $totalMarks <= 27.99)
          {
            return $quarterly_grade = 66;
          }
          else if($totalMarks >= 28.00 && $totalMarks <= 31.99)
          {
            return $quarterly_grade = 67;
          }
          else if($totalMarks >= 32.00 && $totalMarks <= 35.99)
          {
            return $quarterly_grade = 68;
          }
          else if($totalMarks >= 36.00 && $totalMarks <= 39.99)
          {
            return $quarterly_grade = 69;
          }
          else if($totalMarks >= 40.00 && $totalMarks <= 43.99)
          {
            return $quarterly_grade = 70;
          }
          else if($totalMarks >= 44.00 && $totalMarks <= 47.99)
          {
            return $quarterly_grade = 71;
          }
          else if($totalMarks >= 48.00 && $totalMarks <= 51.99)
          {
            return $quarterly_grade = 72;
          }
          else if($totalMarks >= 52.00 && $totalMarks <= 55.99)
          {
            return $quarterly_grade = 73;
          }
          else if($totalMarks >= 56.00 && $totalMarks <= 59.99)
          {
            return $quarterly_grade = 74;
          }
          else if($totalMarks >= 60.00 && $totalMarks <= 61.59)
          {
            return $quarterly_grade = 75;
          }
          else if($totalMarks >= 61.60 && $totalMarks <= 63.19)
          {
            return $quarterly_grade = 76;
          }
          else if($totalMarks >= 63.20 && $totalMarks <= 64.79)
          {
            return $quarterly_grade = 77;
          }
          else if($totalMarks >= 64.80 && $totalMarks <= 66.39)
          {
            return $quarterly_grade = 78;
          }
          else if($totalMarks >= 66.40 && $totalMarks <= 67.99)
          {
            return $quarterly_grade = 79;
          }
          else if($totalMarks >= 68.00 && $totalMarks <= 69.59)
          {
            return $quarterly_grade = 80;
          }
          else if($totalMarks >= 69.60 && $totalMarks <= 71.19)
          {
            return $quarterly_grade = 81;
          }
          else if($totalMarks >= 71.20 && $totalMarks <= 72.79)
          {
            return $quarterly_grade = 82;
          }
          else if($totalMarks >= 72.80 && $totalMarks <= 74.39)
          {
            return $quarterly_grade = 83;
          }
          else if($totalMarks >= 74.40 && $totalMarks <= 75.59)
          {
            return $quarterly_grade = 84;
          }
          else if($totalMarks >= 76.00 && $totalMarks <= 77.59)
          {
            return $quarterly_grade = 85;
          }
          else if($totalMarks >= 77.60 && $totalMarks <= 79.19)
          {
            return $quarterly_grade = 86;
          }
          else if($totalMarks >= 79.20 && $totalMarks <= 80.79)
          {
            return $quarterly_grade = 87;
          }
          else if($totalMarks >= 80.80 && $totalMarks <= 82.39)
          {
            return $quarterly_grade = 88;
          }
          else if($totalMarks >= 82.40 && $totalMarks <= 83.99)
          {
            return $quarterly_grade = 89;
          }
          else if($totalMarks >= 84.00 && $totalMarks <= 85.59)
          {
            return $quarterly_grade = 90;
          }
          else if($totalMarks >= 85.60 && $totalMarks <= 87.19)
          {
            return $quarterly_grade = 91;
          }
          else if($totalMarks >= 87.20 && $totalMarks <= 88.79)
          {
            return $quarterly_grade = 92;
          }
          else if($totalMarks >= 88.80 && $totalMarks <= 90.39)
          {
            return $quarterly_grade = 93;
          }
          else if($totalMarks >= 90.40 && $totalMarks <= 91.99)
          {
            return $quarterly_grade = 94;
          }
          else if($totalMarks >= 92.00 && $totalMarks <= 93.59)
          {
            return $quarterly_grade = 95;
          }
          else if($totalMarks >= 93.60 && $totalMarks <= 95.19)
          {
            return $quarterly_grade = 96;
          }
          else if($totalMarks >= 95.20 && $totalMarks <= 96.79)
          {
            return $quarterly_grade = 97;
          }
          else if($totalMarks >= 96.80 && $totalMarks <= 98.39)
          {
            return $quarterly_grade = 98;
          }
          else if($totalMarks >= 98.40 && $totalMarks <= 99.99)
          {
            return $quarterly_grade = 99;
          }
          else if($totalMarks = 100)
          {
            return $quarterly_grade = 100;
          }
  
      }

     
   
   
      public function getMarkSum(){
      $fieldSum = 0;
      if($this->fieldCount > 0){
          $fieldGradeArray = array();
          for($i=1; $i<=$this->maxFieldNum; ++$i){
            array_push($fieldGradeArray,$this->grade["{$this->field}{$i}"]);
          }
          rsort($fieldGradeArray);
          $largest = array_slice($fieldGradeArray, 0, $this->fieldCount);

          foreach($largest as $l){
            $fieldSum += $l;
          }
        } else {
          for($i=1; $i<=5; ++$i){
            if (isset($this->grade["{$this->field}{$i}"]))
              $fieldSum += $this->grade["{$this->field}{$i}"];
          }
        }
      return $fieldSum;
    }

    public function getFieldFinalMark(){
      return ($this->full_field_mark > 0)? (($this->field_percentage*$this->avg_field_sum)/$this->full_field_mark) : $this->final_default_value;
    }
      
    public function getTotalCalculatedMarks(){
        return $this->final_quiz_mark + $this->final_assignment_mark + $this->final_ct_mark;

    }

    public function calculateGpa($gradeSystem, $totalMarks){
      $totalMarks = round($totalMarks);
      foreach($gradeSystem as $gs){
        if($totalMarks > $gs->from_mark && $totalMarks <= $gs->to_mark){
          return $gs->point;
        }
      }
      return 'Something went wrong.';
    }

    public function getTerm(){
      return Exam::where('school_id', auth()->user()->school_id)
                          ->where('term', 'second_term')
                          ->get();
    }

    public function getCourseType($exam_id){
      return Course::where('exam_id', $exam_id)
                    ->pluck('course_type');
    }

    public function updateGrade($request, $course,$course_type){
        $i = 0;
        $course_type =  $course->course_type;
        $quiz_fullmark = $course->quiz_fullmark;
        $a_fullmark = $course->a_fullmark;
        $ct_fullmark = $course->ct_fullmark;
        
        foreach($request->grade_ids as $id){
          if($course_type == 'Core Subject'){
            $totalMarks = ((($request->quiz1[$i]/$quiz_fullmark) * 25) + (($request->assign1[$i]/$a_fullmark) *50) +  (($request->ct1[$i]/$ct_fullmark) *25));
          }
          if($course_type == 'Academic Track'){
            $totalMarks = ((($request->quiz1[$i]/$quiz_fullmark) * 25) + (($request->assign1[$i]/$a_fullmark) *45) +  (($request->ct1[$i]/$ct_fullmark) *30));
          }
          if($course_type == 'Work Immersion/ Culminating Activity'){
            $totalMarks = ((($request->quiz1[$i]/$quiz_fullmark) * 35) + (($request->assign1[$i]/$a_fullmark) *40) +  (($request->ct1[$i]/$ct_fullmark) *25));
          }
          if($course_type == 'TVL/ Sports/ Arts and Design Track'){
            $totalMarks = ((($request->quiz1[$i]/$quiz_fullmark) * 20) + (($request->assign1[$i]/$a_fullmark) *60) +  (($request->ct1[$i]/$ct_fullmark) *20));
          }

          $quarterly_grade = $this->getQuarterlyGrade($totalMarks,$course);

            $tb = Grade::find($id);
            $tb->attendance = $request->attendance[$i];
            $tb->quiz1 = $request->quiz1[$i];
            $tb->assignment1 = $request->assign1[$i];
            $tb->ct1 = $request->ct1[$i];
            $tb->marks = $totalMarks;
            $tb->user_id = Auth::user()->id;
            $tb->marks_final = $totalMarks;
            $tb->marks_final = $quarterly_grade;
            $tb->created_at = date('Y-m-d H:i:s');
            $tb->updated_at = date('Y-m-d H:i:s');
            $tbc[] = $tb->attributesToArray();
            $i++;
        }
        return $tbc;
    }

    public function returnRouteWithParameters($route_name){
      return redirect()->route($route_name, [
        'teacher_id' => $this->teacher_id,
        'course_id' => $this->course_id,
        'exam_id' => $this->exam_id,
        'section_id' => $this->section_id,
      ]);
    }
}