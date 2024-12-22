@extends('layouts.admin')

@section('title')
    سجل مراقبة النظام
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('contentheader')
    قائمة مراقبة النظام
@endsection

@section('contentheaderactivelink')
    <a href="{{ route('SystemMonitoring.index') }}">المراقبة</a>
@endsection

@section('contentheaderactive')
    عرض
@endsection

@section('content')
    <div class="col-12">
        <div class="card">

            <div class="card-header">

            </div>

            <div class="row" style="padding: 5px">



                <div class="col-md-3">
                    <div class="form-group">
                        <label>بحث بالاقسام</label>
                        <select name="alert_modules_id" id="alert_modules_id" class="form-control select2">

                            <option value="all">بحث بالكل</option>
                            @if (@isset($other['alert_modules']) && !@empty($other['alert_modules']))
                                @foreach ($other['alert_modules'] as $info)
                                    <option value="{{ $info->id }}">{{ $info->name }}</option>
                                @endforeach
                            @endif
                        </select>

                    </div>

                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>بحث بالحركات</label>
                        <select name="alert_movetype_id" id="alert_movetype_id" class="form-control select2">

                            <option value="all">بحث بالكل</option>
                            @if (@isset($other['alert_movetype']) && !@empty($other['alert_movetype']))
                                @foreach ($other['alert_movetype'] as $info)
                                    <option value="{{ $info->id }}">{{ $info->name }}</option>
                                @endforeach
                            @endif
                        </select>

                    </div>

                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>بحث بالموظفين</label>
                        <select name="employees_code" id="employees_code" class="form-control select2">
                            <option value="all">بحث بالكل</option>
                            @if (@isset($other['employees']) && !@empty($other['employees']))
                                @foreach ($other['employees'] as $info)
                                    <option value="{{ $info->employees_code }}"> {{ $info->emp_name }}
                                        ({{ $info->employees_code }})
                                    </option>
                                @endforeach
                            @endif
                        </select>

                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>بحث بالمستخدمين</label>
                        <select name="admin_id" id="admin_id" class="form-control select2">
                            <option value="all">بحث بالكل</option>
                            @if (@isset($other['admins']) && !@empty($other['admins']))
                                @foreach ($other['admins'] as $info)
                                    <option value="{{ $info->id }}"> {{ $info->name }}</option>
                                @endforeach
                            @endif
                        </select>

                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>بحث بحالة التمييز</label>
                        <select name="is_marked" id="is_marked" class="form-control">
                            <option value="all">بحث بالكل</option>
                            <option value="1">نعم</option>
                            <option value="0">لا</option>

                        </select>

                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>بحث من تاريخ </label>
                        <input type="date" class="form-control" name="form_date" id="form_date">

                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>بحث الى تاريخ </label>
                        <input type="date" class="form-control" name="to_date" id="to_date">

                    </div>
                </div>



            </div>


            <div class="card-body" id="ajax_responce_searchDiv">

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
                                                class="btn btn-sm btn-danger  do_undo_mark are_you_shur">تمييز</button>
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
                    <div class="col-md-12">
                        {{ $data->links('pagination::bootstrap-5') }}
                    </div>
                @else
                    <p class="bg-danger text-center">عفواً لا توجد بيانات لعرضها</p>
                @endif


            </div>
        </div>
    </div>


@endsection

@section('script')
    <script src="{{ asset('assets/admin/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(".select2").select2({
            theme: 'bootstrap4'
        });


        $(document).ready(function() {

            $(document).on('change', '#alert_modules_id', function(e) {
                ajax_search();
            });

            $(document).on('change', '#alert_movetype_id', function(e) {
                ajax_search();
            });

            $(document).on('change', '#employees_code', function(e) {
                ajax_search();
            });

            $(document).on('change', '#is_marked', function(e) {
                ajax_search();
            });

            $(document).on('change', '#form_date', function(e) {
                ajax_search();
            });

            $(document).on('change', '#to_date', function(e) {
                ajax_search();
            });

            $(document).on('change', '#admin_id', function(e) {
                ajax_search();
            });



            function ajax_search() {
                var alert_modules_id = $("#alert_modules_id").val();
                var alert_movetype_id = $("#alert_movetype_id").val();
                var employees_code = $("#employees_code").val();
                var is_marked = $("#is_marked").val();
                var form_date = $("#form_date").val();
                var to_date = $("#to_date").val();
                var admin_id = $("#admin_id").val();

                jQuery.ajax({
                    url: '{{ route('SystemMonitoring.ajaxSearch') }}',
                    type: 'post',
                    dataType: 'html',
                    cache: false,
                    data: {
                        "_token": '{{ csrf_token() }}',
                        alert_modules_id: alert_modules_id,
                        alert_movetype_id: alert_movetype_id,
                        employees_code: employees_code,
                        is_marked: is_marked,
                        form_date: form_date,
                        to_date: to_date,
                        admin_id: admin_id,


                    },

                    success: function(data) {
                        $("#ajax_responce_searchDiv").html(data);
                    },
                    error: function() {
                        alert("عفواً حدث خطأ")
                    }

                });

                $(document).on('click', '#ajax_pagination_in_search a', function(e) {

                    e.preventDefault();
                    var alert_modules_id = $("#alert_modules_id").val();
                    var alert_movetype_id = $("#alert_movetype_id").val();
                    var employees_code = $("#employees_code").val();
                    var is_marked = $("#is_marked").val();
                    var form_date = $("#form_date").val();
                    var to_date = $("#to_date").val();
                    var admin_id = $("#admin_id").val();
                    jQuery.ajax({
                        url: linkurl,
                        type: 'post',
                        dataType: 'html',
                        cache: false,
                        data: {
                            "_token": '{{ csrf_token() }}',
                            alert_modules_id: alert_modules_id,
                            alert_movetype_id: alert_movetype_id,
                            employees_code: employees_code,
                            is_marked: is_marked,
                            form_date: form_date,
                            to_date: to_date,
                            admin_id: admin_id,
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

            
            $(document).on('click', '.do_undo_mark', function(e) {
                e.preventDefault();
                var id=$(this).data("id");

                jQuery.ajax({
                    url: '{{ route('SystemMonitoring.do_undo_mark') }}',
                    type: 'post',
                    dataType: 'html',
                    cache: false,
                    data: {
                        "_token": '{{ csrf_token() }}',
                        id: id,
                   
                    },

                    success: function(data) {
                        ajax_search();
                    },
                    error: function() {
                        alert("عفواً حدث خطأ")
                    }

                });

            });

        });
    </script>
@endsection
