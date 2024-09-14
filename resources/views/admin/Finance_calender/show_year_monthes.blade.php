@if (@isset($finance_cin_periods) and !@empty($finance_cin_periods))
<table id="example2" class="table table-bordered table-hover">

    <thead class="custom_thead">
        <th>اسم الشهر عربي</th>
        <th>اسم الشهر انكليزي</th>
        <th>تاريخ البداية</th>
        <th>تاريخ النهاية</th>
        <th>عدد الايام</th>
        <th>حالة الشهر</th>
        <th>الاضافة بواسطة</th>
        <th>التحديث بواسطة</th>
       
    </thead>
    <tbody>
        @foreach ($finance_cin_periods as $info)
            <tr>
                <td>
                    {{ $info->Month->name }}
                </td>

                <td>
                    {{ $info->Month->name_en }}
                </td>

                <td>
                    {{ $info->start_date_m }}
                </td>

                <td>
                    {{ $info->end_date_m }}
                </td>

                <td>
                    {{ $info->number_of_dats }}
                </td>

                <td>
                    @if ($info->is_open==1)
                        مفتوح
                        @elseif ($info->is_open==2)
                        مؤرشف
                        @else
                        مغلق
                        
                    @endif
                </td>

                <td>{{$info->added->name}}</td>

                <td>
                    @if ($info->updatedBy > '0')
                    {{$info->updatedBy->name}}
                    @else
                    لايوجد
                    @endif
                </td>

               

            </tr>
        @endforeach
    </tbody>
</table>
@else
<p class="bg-danger text-center">عفواً لا توجد بيانات لعرضها</p>
@endif