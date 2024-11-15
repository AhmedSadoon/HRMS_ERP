@if (@isset($data) and !@empty($data) and count($data) > 0)
<table id="example2" class="table table-bordered table-hover">

    <thead class="custom_thead">
        <th>كود</th>
        <th style="width: 22%">اسم الموظف</th>
        <th>الفرع</th>
        <th style="width: 15%">الادارة</th>
        <th>الوظيفة</th>
        <th>نزول الرصيد</th>
        <th>الصورة</th>
        <th>الرصيد</th>
    </thead>
    <tbody>
        @foreach ($data as $info)
            <tr>
                <td> {{ $info->employees_code }} </td>
                <td> {{ $info->emp_name }} 
                    <br>
                 <span style="color: brown;">
                    @if ($info->function_status == 1)
                    بالخدمة
                    @else
                    خارج الخدمة
                    @endif
                 </span>
                </td>
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
                    @if ($info->is_active_for_vaccation == 1)
                        نعم
                    @else
                      ليس بعد
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

                  
                    <a class="btn btn-sm btn-info"
                        href="{{ route('EmployeeVacationsBalance.show', $info->id) }}">عرض</a>
                       
                    
                        
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
