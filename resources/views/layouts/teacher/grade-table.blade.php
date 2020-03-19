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
  
    </tr>
  </thead>
  <tbody>
    @foreach ($grades as $grade)
    <tr>
      <th scope="row">{{($loop->index + 1)}}</th>
      <td>{{$grade->student->student_code}}</td>
      <td><a href="{{url('user/'.$grade->student->student_code)}}">{{$grade->student->name}}</a></td>
      <td>{{$grade->attendance}}</td>
      @for($i=1;$i<=1;$i++)
        <td>{{$grade['quiz'.$i]}}</td>
      @endfor
      @for($i=1;$i<=1;$i++)
        <td>{{$grade['assignment'.$i]}}</td>
      @endfor
      @for($i=1;$i<=1;$i++)
        <td>{{$grade['ct'.$i]}}</td>
      @endfor


      <td>{{$grade->marks}}</td>
   
    </tr>
    @endforeach
  </tbody>
</table>
</div>
