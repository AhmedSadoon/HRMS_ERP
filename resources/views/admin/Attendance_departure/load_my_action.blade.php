@if (@isset($Attendance_departure_actions) and !@empty($Attendance_departure_actions))
<table id="example2" class="table table-bordered table-hover">

    <thead class="custom_thead">
        <th>التاريخ</th>
        <th>نوع البصمة</th>        
        <th>التوقيت بنظام 24 ساعة</th>
        <th>التوقيت بنظام 12 ساعة</th>        
        <th>هل البصمة مفعلة</th>        
        <th>طريقة الاضافة</th>        
        <th>السحب بواسطة</th>
     
        
       
    </thead>
    <tbody>
        @foreach ($Attendance_departure_actions as $info)
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
                    @if ($info->action_type==1)
                        حضور
                    @else
                        انصراف
                        
                    @endif
                </td>

               

                <td>
                    @php                       
                    echo date("H:i",strtotime($info->datetimeAction));
                    @endphp
                </td>

             

                <td>
                    {{$time}}
                    {{$newDateTimeType}}
                </td>

                <td>
                    @if ($info->it_is_active_with_parent==1)
                        نعم
                    @else
                        لا
                        
                    @endif

                    @if ($info->it_is_active_with_parent==1)

                        @if ($info->datetimeAction==$parent['datetime_in'])
                            <br> اخذت كحضور
                        @endif

                        @if ($info->datetimeAction==$parent['datetime_out'])
                        <br> اخذت كأنصراف
                    @endif
                    
                    @endif

                    
                </td>

                <td>
                    @if ($info->added_method==1)
                        الي
                    @else
                        يدوي
                        
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