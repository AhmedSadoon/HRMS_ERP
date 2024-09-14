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


        </thead>
        <tbody>
            @foreach ($data as $info)
                <tr>

                    <td>
                        {{ $info->emp_name }}
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
