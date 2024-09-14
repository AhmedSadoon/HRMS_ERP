@if (@isset($data) and !@empty($data) and count($data) > 0)
    <table id="example2" class="table table-bordered table-hover">

        <thead class="custom_thead">
            <th>كود</th>
            <th>اسم الموظف</th>
            <th>الفرع</th>
            <th>الادارة</th>
            <th>الوظيفة</th>
            <th>الحالة الوظيفية</th>
            <th>الصورة الشخصية</th>
            <th>العمليات</th>
        </thead>
        <tbody>
            @foreach ($data as $info)
                <tr>
                    <td> {{ $info->employees_code }} </td>
                    <td> {{ $info->emp_name }} </td>
                    <td> {{ $info->Branch->name }} </td>
                    <td>
                        @if (!@empty($info->Department->name))
                            {{ $info->Department->name }}
                        @endif

                    </td>

                    <td>
                        @if (!@empty($info->Job->name))
                            {{ $info->Job->name }}
                        @endif

                    </td>

                    <td>
                        @if ($info->function_status == 1)
                            بالخدمة
                        @else
                            خارج الخدمة
                        @endif

                    </td>



                    <td>
                        @if (!empty($info->emp_photo))
                            <img src="{{ asset('assets/admin/uploads') . '/' . $info->emp_photo }}"
                                style="border-radius: 50%; width: 80px; height: 80px;" class="rounded-circle"
                                alt="صورة الموظف">
                        @else
                            @if ($info->emp_gender == 1)
                                <img src="{{ asset('assets/admin/images/boy.png') }}"
                                    style="border-radius: 50%; width: 80px; height: 80px;" class="rounded-circle"
                                    alt="صورة الموظف">
                            @else
                                <img src="{{ asset('assets/admin/images/woman.png') }}"
                                    style="border-radius: 50%; width: 80px; height: 80px;" class="rounded-circle"
                                    alt="صورة الموظف">
                            @endif
                        @endif
                    </td>



                    <td>

                        <a class="btn btn-sm btn-success" href="{{ route('Employees.edit', $info->id) }}">تعديل</a>
                        <a class="btn btn-sm btn-info" href="{{ route('Employees.show', $info->id) }}">المزيد</a>
                        <a class="btn btn-sm btn-danger are_you_shur"
                            href="{{ route('Employees.destroy', $info->id) }}">حذف</a>

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
