@if (@isset($data) and !@empty($data) and count($data) > 0)
<table id="example2" class="table table-bordered table-hover">

    <thead class="custom_thead">

        <th>اسم الموظف</th>
        <th>نوع الجزاء</th>
        <th>عدد الايام</th>
        <th>اجمالي</th>
        <th>تاريخ الاضافة</th>
        <th>تاريخ التحديث</th>
        <th>الحالة</th>
        <th>العمليات</th>

    </thead>
    <tbody>
        @foreach ($data as $info)
            <tr>

                <td>
                    {{ $info->emp_name }}
                    @if (!@empty($info->notes))
                        <br>
                        <span style="color: brown">ملاحظة:</span>{{$info->notes}}
                    @endif
                </td>

                <td>
                    @if ($info->sactions_type == 1)
                        جزاء ايام
                    @elseif ($info->sactions_type == 2)
                        جزاء بصمة
                    @elseif ($info->sactions_type == 3)
                        جزاء تحقيق
                    @else
                        لم يحدد
                    @endif
                </td>

                <td>
                    {{ $info->value * 1 }}
                </td>

                <td>
                    {{ $info->total * 1 }}
                </td>

                <td>{{ \Carbon\Carbon::parse($info->updated_at)->format('d-m-Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($info->updated_at)->format('d-m-Y') }}</td>





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
                    @if ($info->is_archived == 0)
                        <button data-id="{{ $info->id }}"
                            data-main_sal_id="{{ $info->main_salary_employee_id }}"
                            class="btn btn-sm btn-success load_edit_this_row">تعديل</button>

                        <button data-id="{{ $info->id }}"
                            data-main_sal_id="{{ $info->main_salary_employee_id }}"
                            class="btn btn-sm btn-danger are_you_shur delete_this_row">حذف</button>
                    @endif
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
