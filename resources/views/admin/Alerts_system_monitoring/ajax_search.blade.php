@if (@isset($data) and !@empty($data) and count($data) > 0)
<table id="example2" class="table table-bordered table-hover">

    <thead class="custom_thead">
        <th>تسلسل</th>

        <th>قسم</th>
        <th>نوع الحركة</th>
        <th style="width:30% ">البيان</th>
        <th>تاريخ الحركة</th>
        <th>هل مميز</th>
    </thead>  
    <tbody>
        @foreach ($data as $info)
        <tr  @if ($info->is_marked == 1) style="background-color:lightgoldenrodyellow;"  @endif>

                <td> {{ $info->id }} </td>
                <td> {{ $info->alert_modules_name }} </td>
                <td> {{ $info->alert_movetype_name }} </td>
                <td>{{ $info->content }}</td>



                <td>{{ $info->created_at }}</td>

                <td>
                    @if ($info->is_marked == 0)
                        لا <br>
                        <button data-id="{{ $info->id }}"
                            class="btn btn-sm btn-danger do_undo_mark are_you_shur">تمييز</button>
                    @else
                        نعم <br>
                        <button data-id="{{ $info->id }}"
                            class="btn btn-sm btn-danger do_undo_mark are_you_shur">الغاء</button>
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
