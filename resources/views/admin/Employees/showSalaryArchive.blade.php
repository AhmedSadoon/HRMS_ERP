@if (@isset($data) and !@empty($data))
<table id="example2" class="table table-bordered table-hover">

    <thead class="custom_thead">
        <th>قيمة الراتب</th>
        <th>تاريخ التغيير</th>
        <th>الاضافة بواسطة</th>
       
    </thead>
    <tbody>
        @foreach ($data as $info)
            <tr>
                <td>
                    {{ $info->value*1 }}
                </td>

                <td>
                    {{$info->created_at}}
                </td>


                <td>{{$info->added->name}}</td>

                

               

            </tr>
        @endforeach
    </tbody>
</table>
@else
<p class="bg-danger text-center">عفواً لا توجد بيانات لعرضها</p>
@endif