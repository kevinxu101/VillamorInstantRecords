{{--<div class="well" style="font-size: 15px;">@lang('Choose Field to Display')</div>--}}
<style>
  #grade-labels > .label{
    margin-right: 1%;
  }
</style>

<br />
<br />
<form action="{{url('grades/save-grade')}}" method="POST">
  {{csrf_field()}}
  <input type="hidden" name="section_id" value="{{$section_id}}">
  <input type="hidden" name="course_id" value="{{$course_id}}">
  <input type="hidden" name="exam_id" value="{{$exam_id}}">
  <input type="hidden" name="teacher_id" value="{{$teacher_id}}">
  <div class="table-responsive">
    <table class="table table-condensed table-hover" id="marking-table">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">@lang('Code')</th>
          <th scope="col">@lang('Name')</th>
          <th scope="col">@lang('Attendance')</th>
          <th scope="col">@lang('Written Work Total Score')</th>
          <th scope="col">@lang('Performance Tasks Total Score') </th>
          <th scope="col">@lang('Quarterly Assessment Total Score') </th>

        </tr>
      </thead>
      <tbody>
        
        
        @foreach ($grades as $grade)
        <input type="hidden" name="grade_ids[]" value="{{$grade->id}}">
        <tr>
          <th scope="row">{{($loop->index + 1)}}</th>
          <td>{{$grade->student->student_code}}</td>
          <td>{{$grade->student->name}}</td>
          @if($grade->exam->term == 'first_quarter')
            <td>
              <input type="number" name="attendance[]" class="form-control input-sm" placeholder="@lang('Total Attendance')" value="{{$grade->attendance}}"  max="100">
            </td>
            <td>
              <input type="number" name="quiz1[]" class="form-control input-sm input-sm" value="{{$grade->quiz1}}"
                placeholder="@lang('Written Work')" max="{{$grade->course->quiz_fullmark}}">
            </td>

            <td>
              <input type="number" name="assign1[]" class="form-control input-sm" placeholder="@lang('Performance Tasks')" value="{{$grade->assignment1}}" max="{{$grade->course->a_fullmark}}">
            </td>

            <td>
              <input type="number" name="ct1[]" class="form-control input-sm" placeholder="@lang('Quarterly Assessment')" value="{{$grade->ct1}}" max="{{$grade->course->ct_fullmark}}">
            </td>
          @endif
          @if($grade->exam->term == 'second_quarter')
            <td>
              <input type="number" name="attendance[]" class="form-control input-sm" placeholder="@lang('Total Attendance')" value="{{$grade->attendance}}"  max="100">
            </td>
            <td>
              <input type="number" name="quiz1[]" class="form-control input-sm input-sm" value="{{$grade->quiz1_2Q}}"
                placeholder="@lang('Written Work')" max="{{$grade->course->quiz_fullmark}}">
            </td>

            <td>
              <input type="number" name="assign1[]" class="form-control input-sm" placeholder="@lang('Performance Tasks')" value="{{$grade->assignment1_2Q}}" max="{{$grade->course->a_fullmark}}">
            </td>

            <td>
              <input type="number" name="ct1[]" class="form-control input-sm" placeholder="@lang('Quarterly Assessment')" value="{{$grade->ct1_2Q}}" max="{{$grade->course->ct_fullmark}}">
            </td>
          @endif
        </tr>
        @endforeach
        
      </tbody>
    </table>
  </div>
  <div style="text-align:center;">
    <input type="submit" name="save" class="btn btn-primary" value="@lang('Submit')">
  </div>
</form>

<script>
  /*
  $(function () {
    for (var i = 6; i < 21; i++) {
      if (i == 10 || i == 13)
        continue;
      $("#marking-table td:nth-child(" + i + "),#marking-table th:nth-child(" + i + ")").hide();
    }
    $(":checkbox").change(function () {
      if ($(this).is(':checked')) {
        $("#marking-table td:nth-child(" + $(this).val() + "),#marking-table th:nth-child(" + $(this).val() +
          ")").show();
      } else {
        $("#marking-table td:nth-child(" + $(this).val() + "),#marking-table th:nth-child(" + $(this).val() +
          ")").hide();
      }
    });
  });
  */
</script>