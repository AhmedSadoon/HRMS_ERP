
@if (@isset($data) and !@empty($data) and count($data)>0)
<table id="example2" class="table table-bordered table-hover">

    <thead class="custom_thead">
        <th>نوع الشفت</th>
        <th>يبدأ من الساعة</th>
        <th>ينتهي بالساعة</th>
        <th>عدد ساعات الشفت</th>
        <th>حالة التفعيل</th>
        <th>الاضافة بواسطة</th>
        <th>التحديث بواسطة</th>
        <th>العمليات</th>
    </thead>
    <tbody>
        @foreach ($data as $info)
            <tr>
                <td>
                    @if ($info->type == 1)
                        صباحي
                    @elseif ($info->type == 2)
                        مسائي
                        @else
                        يوم كامل
                    @endif
                </td>
                
                <td>
                    @php
                       
                        $time=date("h:i",strtotime($info->form_time));
                        $newDatetime=date("A",strtotime($info->form_time));
                        $newDateType=date($newDatetime=='AM')?'صباحا':'مساءً';

                    @endphp

                    {{$time}}
                    {{$newDateType}}
                </td>

                <td>
                    @php
                    
                    $time=date("h:i",strtotime($info->to_time));
                    $newDatetime=date("A",strtotime($info->to_time));
                    $newDateType=date(($newDatetime=='AM')?'صباحا':'مساءً');

                @endphp

                {{$time}}
                {{$newDateType}}
                  
                </td>

                <td>
                    {{ $info->total_huor*1 }}
                </td>

                <td   @if ($info->active==1)  class='bg-success' @else class='bg-danger' @endif >
                    @if ($info->active==1) مفعل @else غير مفعل @endif
                </td>

                <td>{{$info->added->name}}</td>

                <td>
                    @if ($info->updatedBy > '0')
                    {{$info->updatedBy->name}}
                    @else
                    لايوجد
                    @endif
                </td>

                <td>

                    <a class="btn btn-sm btn-success"
                        href="{{ route('shiftsTypes.edit', $info->id) }}">تعديل</a>
                    @if ($info->CounterUse==0)
                    <a class="btn btn-sm btn-danger are_you_shur"
                    href="{{ route('shiftsTypes.destroy', $info->id) }}">حذف</a>
                    @endif
                       
                </td>

            </tr>
        @endforeach
    </tbody>
</table>
<br>
<div class="col-md-12" id="ajax_pagination_in_search">
    {{$data->links('pagination::bootstrap-5')}}
</div>
@else
<p class="bg-danger text-center">عفواً لا توجد بيانات لعرضها</p>
@endif