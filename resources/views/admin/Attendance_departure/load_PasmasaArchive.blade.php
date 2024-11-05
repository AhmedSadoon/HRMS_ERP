@if (@isset($attendance_departure_actions_excel) and !@empty($attendance_departure_actions_excel))
<table id="example2" class="table table-bordered table-hover">

    <thead class="custom_thead">
        <th>التاريخ</th>
        <th>التوقيت</th>
        <th>نوع البصمة</th>
        <th>السحب بواسطة</th>
     
        
       
    </thead>
    <tbody>
        @foreach ($attendance_departure_actions_excel as $info)
            <tr>
                <td>
                    @php
                        $dt=new DateTime($info->datetimeAction);
                        $date=$dt->format('Y-m-d');
                        $time=$dt->format('H:i');
                        $newDateTime=date("A",strtotime($info->datetimeAction));
                        $newDateTimeType=(($newDateTime=='AM')?'صباحا':'مساءً')
                    @endphp
                    {{$info->week_day_name_arabic}}
                    {{$date}}
                </td>

                <td>
                    {{$time}}
                    {{$newDateTimeType}}
                </td>

                <td>
                    @if ($info->action_type==1)
                        حضور
                    @else
                        انصراف
                        
                    @endif
                </td>

                <td>{{$info->added->name}} <br>
                
                    @php
                    $dt=new DateTime($info->created_at);
                    $date=$dt->format('Y-m-d');
                    $time=$dt->format('H:i');
                    $newDateTime=date("A",strtotime($info->created_at));
                    $newDateTimeType=(($newDateTime=='AM')?'صباحا':'مساءً')
                @endphp
                ({{$date}}) 
                ({{$time}}
                {{$newDateTimeType}})
                </td>               

            </tr>
        @endforeach
    </tbody>
</table>
@else
<p class="bg-danger text-center">عفواً لا توجد بيانات لعرضها</p>
@endif