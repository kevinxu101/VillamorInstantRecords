<div class="table-responsive">
  <table class="table table-bordered table-hover">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">@lang('Student Code')</th>
      <th scope="col">@lang('Student Name')</th>
      <th scope="col">@lang('Attendance')</th>
        @for($i=1;$i<=1;$i++)
          <th scope="col">Written Work (25 %)</th>
        @endfor
        @for($i=1;$i<=1;$i++)
          <th scope="col">Performance Tasks (50%)</th>
        @endfor
        @for($i=1;$i<=1;$i++)
          <th scope="col">Quarterly Assessment(25%)</th>
        @endfor
      <th scope="col">@lang('Initial Grade')</th>
      <th scope="col">Quarterly Grade</th>
  
    </tr>
  </thead>
  <tbody>
    
    @foreach ($grades as $grade)
    <tr>
      <th scope="row">{{($loop->index + 1)}}</th>
      <td>{{$grade->student->student_code}}</td>
      <td><a href="{{url('user/'.$grade->student->student_code)}}">{{$grade->student->name}}</a></td>
      @if($grade->exam->term == 'first_quarter')
        <td>{{$grade->attendance}}</td>
        <td>{{$grade->quiz1}}</td>
        <td>{{$grade->assignment1}}</td>
        <td>{{$grade->ct1}}</td>
        <td>{{$grade->marks}}</td>
        <td>{{$grade->marks_final}}</td>
      @endif
      @if($grade->exam->term == 'second_quarter')
        <td>{{$grade->attendance}}</td>
        <td>{{$grade->quiz1_2Q}}</td>
        <td>{{$grade->assignment1_2Q}}</td>
        <td>{{$grade->ct1_2Q}}</td>
        <td>{{$grade->marks_2Q}}</td>
        <td>{{$grade->marks_final_2Q}}</td>
        @endif
    </tr>
    @endforeach
  </tbody>
</table>
</div>
