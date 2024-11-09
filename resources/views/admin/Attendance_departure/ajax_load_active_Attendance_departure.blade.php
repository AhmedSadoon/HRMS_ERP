<br>
<p style="padding: 5px;border: 1px solid black; text-align: right; border-radius: 10px; background-color: #d9edf7;" class="text-center">ملاحظة:اكبر تاريخ بصمة مسجلة بالنظام هو ({{$max_attend_date}})</p>
@if (@isset($other['data']) and !@empty($other['data']) and count($other['data']) > 0)
<table id="example2" class="table table-bordered table-hover" >

    <thead class="custom_thead">

        <th>التاريخ</th>
        <th>الحضور</th>
        <th>الانصراف</th>
        <th>البصمات</th>
        <th>متغيرات</th>
        <th>خصم ايام</th>
        <th>هل اجازة</th>
        <th>حضور متأخر</th>
        <th>انصراف مبكر</th>
        <th>اذون ساعات</th>
        <th>ساعات عمل</th>
        <th>غياب ساعات</th>
        <th>اضافي ساعات</th>
        <th>ملاحظات</th>
        <th>هل تم اخذ اجراء</th>
        

    </thead>
    <tbody>
        @foreach ($other['data'] as $info)
            <tr>

                <td>
                    {{$info->the_day_date}}
                    {{$info->week_day_name_arabic}}
                </td>
                <td>
                    
                    @if($info->time_in!=null )
                        @php                       
                                echo date("H:i",strtotime($info->time_in));
                        @endphp
                    @endif
                  
                </td>
                <td>
                    @if($info->time_out!=null )
                    @php                       
                            echo date("H:i",strtotime($info->time_out));
                    @endphp
                @endif
                </td>

                <td>
                    <div style="width: 10vw">
                        <button data-id="{{$info->id}}" class="btn btn-sm btn-danger load_my_action">عرض الحركات ({{$info->attendance_departure_actions_Counter*1}})</button></td>
                    </div>                    
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>

            </tr>
        @endforeach
    </tbody>
</table>
   
@else
    <p class="bg-danger text-center">عفواً لا توجد بيانات لعرضها</p>
@endif
