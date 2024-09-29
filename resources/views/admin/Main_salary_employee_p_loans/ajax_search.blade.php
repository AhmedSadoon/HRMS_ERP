@if (@isset($data) and !@empty($data) and count($data) > 0)
<table id="example2" class="table table-bordered table-hover">

    <thead class="custom_thead">

        <th>اسم الموظف</th>
        <th>قيمة السلفة</th>
        <th>عدد الشهور</th>
        <th>قيمة القسط </th>
        <th>هل صرفت</th>
        <th>هل انتهت</th>
        <th>العمليات</th>

    </thead>
    <tbody>
        @foreach ($data as $info)
            <tr>

                <td>
                    {{ $info->emp_name }}
                    @if (!@empty($info->notes))
                        <br>
                        <span style="color: brown">ملاحظة:</span>{{ $info->notes }}
                    @endif
                </td>




                <td>
                    {{ $info->total * 1 }}
                </td>

                <td>
                    {{ $info->month_number * 1 }}
                </td>
                <td>
                    {{ $info->month_kast_value * 1 }}
                </td>


                <td>
                    @if ($info->is_dismissail_done == 1)
                        نعم
                    @else
                        لا
                    @endif

                    @if ($info->is_dismissail_done == 0 && $info->is_archived == 0)
                        <a href="{{route('MainSalary_p_Loans.do_is_dismissail_done_now',$info->id)}}" class="btn btn-sm btn-primary are_you_shur">صرف الان</a>
                    @endif
                </td>

                <td>
                    @if ($info->is_archived == 1)
                        نعم
                    @else
                        لا
                    @endif
                </td>

                <td>
                    @if ($info->is_archived == 0 and $info->is_dismissail_done == 0)
                        <button data-id="{{ $info->id }}"
                            class="btn btn-sm btn-primary load_edit_this_row">تعديل</button>

                        <a href="{{ route('MainSalary_p_Loans.delete_parent_loan', $info->id) }}"
                            class="btn btn-sm btn-danger are_you_shur">حذف</a>

                        
                    @endif
                    <button data-id="{{ $info->id }}" class="btn btn-sm btn-dark load_akast_details">الاقساط</button>
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
