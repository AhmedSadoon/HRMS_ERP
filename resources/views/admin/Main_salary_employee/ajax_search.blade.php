@if (@isset($data) and !@empty($data) and count($data) > 0)

<h3 style="text-align: center; font-size: 14px; font-weight: bold;color: brown;">مرأة البحث</h3>
<table id="example2" class="table table-bordered table-hover" style="width: 80%; margin: 0 auto;">

    
        <tr style="background-color: lightblue">
        <th>عدد الرواتب</th>
        <th>بأنتضار الارشفة</th>
        <th>عدد المؤرشف</th>
        <th>عدد الموقوف</th>
    </tr>
    <tr>
        <td>{{$other['counterSalaries']*1}}</td>
        <td>{{$other['counterSalariesWatingArchive']*1}}</td>
        <td>{{$other['counterSalariesDoneArchive']*1}}</td>
        <td>{{$other['counterSalariesStopped']*1}}</td>
    </tr>
</table>


<table id="example2" class="table table-bordered table-hover">

    <thead class="custom_thead">

        <th>كود</th>
        <th>اسم الموظف</th>
        <th>الفرع</th>
        <th>الادارة</th>
        <th>الوظيفة</th>
        <th>حالة الارشفة</th>
        <th>صورة الموظف</th>
        <th>العمليات</th>

    </thead>
    <tbody>
        @foreach ($data as $info)
            <tr>

                <td>{{ $info->employees_code }} </td>
                <td>{{ $info->emp_name }} </td>
                <td>{{ $info->branch_name }}</td>
                <td>{{ $info->department_name }}</td>
                <td>{{ $info->jobs_name }}</td>
                
                <td>
                    @if ($info->is_archived == 1)
                        مؤرشف
                    @else
                        مفتوح
                    @endif


                    @if ($info->is_open != 0)
                        <a href="#" class="btn btn-sm btn-success">عرض</a>
                    @endif
                </td>

                <td>
                    @if (!@empty($info->emp_photo))
                        <img src="{{ asset('assets/admin/uploads') . '/' . $info->emp_photo }}"
                            style="border-radius: 50%; width: 80px; height: 80px;"
                            class="rounded-circle" alt="صورة الموظف">
                    @else
                        @if ($info->emp_gender == 1)
                            <img src="{{ asset('assets/admin/images/boy.png') }}"
                                style="border-radius: 50%; width: 80px; height: 80px;"
                                class="rounded-circle" alt="صورة الموظف">
                        @else
                            <img src="{{ asset('assets/admin/images/woman.png') }}"
                                style="border-radius: 50%; width: 80px; height: 80px;"
                                class="rounded-circle" alt="صورة الموظف">
                        @endif
                    @endif
                </td>


                <td>
                    @if ($info->is_archived == 0)

                        <button data-id="{{ $info->id }}"
                            data-id="{{ $info->id }}"
                            class="btn btn-sm btn-danger are_you_shur delete_this_row">حذف</button>
                    @endif

                    <a href="{{route('MainSalaryEmployee.showSalaryDetails',$info->id)}}" class="btn btn-sm btn-success">التفاصيل</a>

                </td>

            </tr>
        @endforeach
    </tbody>
</table>
    <br>
    <div class="col-md-12" id="ajax_pagination_in_search">
        {{ $data->links('pagination::bootstrap-5') }}
    </div>
@else
    <p class="bg-danger text-center">عفواً لا توجد بيانات لعرضها</p>
@endif
