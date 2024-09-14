@extends('layouts.admin')

@section('title')
    الشفتات
@endsection

@section('contentheader')
    قائمة الضبط
@endsection

@section('contentheaderactivelink')
    <a href="{{ route('shiftsTypes.index') }}">الشفتات</a>
@endsection

@section('contentheaderactive')
    عرض
@endsection

@section('content')
    <div class="col-12">
        <div class="card">

            <div class="card-header">
                <h3 class="card-title card_title_center">بيانات انواع شفتات الموظفين
                    <a class="btn btn-sm btn-warning" href="{{ route('shiftsTypes.create') }}">اضافة جديد</a>
                </h3>
            </div>

            <div class="row" style="padding: 5px">

                <div class="col-md-3">
                    <div class="form-group">
                        <label>نوع الشفت</label>
                        <select name="type_search" id="type_search" class="form-control">
                            <option selected value="all">بحث الكل</option>
                            <option value="1">صباحي</option>
                            <option value="2">مسائي</option>
                        </select>

                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>من عدد ساعات</label>
                        <input type="text" name="huor_from_range" id="huor_from_range" class="form-control"
                            value="" oninput="this.value=this.value.replace(/[^0-9.]/g,'');">

                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>الى عدد ساعات</label>
                        <input type="text" name="huor_to_range" id="huor_to_range" class="form-control" value=""
                            oninput="this.value=this.value.replace(/[^0-9.]/g,'');">

                    </div>
                </div>
            </div>
            <div class="card-body" id="ajax_ersponce_searchdiv">

                @if (@isset($Shifts_type) and !@empty($Shifts_type) and count($Shifts_type) > 0)
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
                            @foreach ($Shifts_type as $info)
                                <tr>
                                    <td>
                                        @if ($info->type == 1)
                                            صباحي
                                        @else
                                            مسائي
                                        @endif
                                    </td>

                                    <td>
                                        @php

                                            $time = date('h:i', strtotime($info->form_time));
                                            $newDatetime = date('A', strtotime($info->form_time));
                                            $newDateType = date($newDatetime == 'AM' ? 'صباحا' : 'مساءً');

                                        @endphp

                                        {{ $time }}
                                        {{ $newDateType }}
                                    </td>

                                    <td>
                                        @php

                                            $time = date('h:i', strtotime($info->to_time));
                                            $newDatetime = date('A', strtotime($info->to_time));
                                            $newDateType = date($newDatetime == 'AM' ? 'صباحا' : 'مساءً');

                                        @endphp

                                        {{ $time }}
                                        {{ $newDateType }}

                                    </td>

                                    <td>
                                        {{ $info->total_huor * 1 }}
                                    </td>

                                    <td @if ($info->active == 1) class='bg-success' @else class='bg-danger' @endif>
                                        @if ($info->active == 1)
                                            مفعل
                                        @else
                                            غير مفعل
                                        @endif
                                    </td>

                                    <td>{{ $info->added->name }}</td>

                                    <td>
                                        @if ($info->updatedBy > '0')
                                            {{ $info->updatedBy->name }}
                                        @else
                                            لايوجد
                                        @endif
                                    </td>

                                    <td>

                                        <a class="btn btn-sm btn-success"
                                            href="{{ route('shiftsTypes.edit', $info->id) }}">تعديل</a>
                                        <a class="btn btn-sm btn-danger are_you_shur"
                                            href="{{ route('shiftsTypes.destroy', $info->id) }}">حذف</a>

                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <br>
                    <div class="col-md-12">
                        {{ $Shifts_type->links('pagination::bootstrap-5') }}
                    </div>
                @else
                    <p class="bg-danger text-center">عفواً لا توجد بيانات لعرضها</p>
                @endif


            </div>
        </div>
    </div>


@endsection

@section('script')
    <script>
        $(document).ready(function() {

            $(document).on('change', '#type_search', function(e) {
                ajax_search();
            });

            $(document).on('input', '#huor_from_range', function(e) {
                ajax_search();
            });

            $(document).on('input', '#huor_to_range', function(e) {
                ajax_search();
            });

            function ajax_search() {
                var type_search = $("#type_search").val();
                var huor_from_range = $("#huor_from_range").val();
                var huor_to_range = $("#huor_to_range").val();

                jQuery.ajax({
                    url: '{{ route('shiftsTypes.ajaxSearch') }}',
                    type: 'post',
                    dataType: 'html',
                    cache: false,
                    data: {
                        "_token": '{{ csrf_token() }}',
                        type_search: type_search,
                        huor_from_range: huor_from_range,
                        huor_to_range: huor_to_range
                    },

                    success: function(data) {
                        $("#ajax_ersponce_searchdiv").html(data);
                    },
                    error: function() {
                        alert("عفواً حدث خطأ")
                    }

                });

                $(document).on('click', '#ajax_pagination_in_search a', function(e) {

                    e.preventDefault();
                    var type_search = $("#type_search").val();
                    var huor_from_range = $("#huor_from_range").val();
                    var huor_to_range = $("#huor_to_range").val();
                    var linkurl = $(this).attr("href");

                    jQuery.ajax({
                        url: linkurl,
                        type: 'post',
                        dataType: 'html',
                        cache: false,
                        data: {
                            "_token": '{{ csrf_token() }}',
                            type_search: type_search,
                            huor_from_range: huor_from_range,
                            huor_to_range: huor_to_range
                        },

                        success: function(data) {
                            $("#ajax_ersponce_searchdiv").html(data);
                        },
                        error: function() {
                            alert("عفواً حدث خطأ")
                        }

                    });


                });
            }

        });
    </script>
@endsection
