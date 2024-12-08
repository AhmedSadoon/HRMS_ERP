@extends('layouts.admin')

@section('title')
    التحقيقات
@endsection



@section('css')
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('contentheader')
    قائمة التحقيقات
@endsection

@section('contentheaderactivelink')
    <a href="{{ route('MainEmployeesInvestigations.index') }}">التحقيقات الادارية</a>
@endsection

@section('contentheaderactive')
    عرض
@endsection

@section('content')
    <style>
        .modal-xl {
            max-width: 100%;
            margin: 0 auto;
            padding: 0px !important;
        }
    </style>
    <div class="col-12">
        <div class="card">

            <div class="card-header">
                <h3 class="card-title card_title_center">بيانات التحقيقات الادارية بالشهر المالي
                    ({{ $finance_cin_periods_data['month']->name }} لسنة {{ $finance_cin_periods_data['finance_yr'] }})

                </h3>
                @if ($finance_cin_periods_data['is_open'] == 1)
                    <button data-toggle="modal" data-target="#AddModal" class="btn btn-sm btn-success">اضافة جديد</button>
                @endif
            </div>

            <form action="{{ route('MainEmployeesInvestigations.print_search') }}" method="POST" target="_blank">
                @csrf
                <input type="hidden" name="the_finance_cin_periods_id" id="the_finance_cin_periods_id"
                    value="{{ $finance_cin_periods_data['id'] }}">

                <div class="row" style="padding: 5px">


                    <div class="col-md-3">
                        <div class="form-group">
                            <label>بحث بالموظفين</label>
                            <select name="employees_code_search" id="employees_code_search" class="form-control select2">
                                <option value="all">بحث بالكل</option>
                                @if (@isset($employees) && !@empty($employees))
                                    @foreach ($employees as $info)
                                        <option value="{{ $info->employees_code }}"> {{ $info->emp_name }}
                                            ({{ $info->employees_code }})
                                        </option>
                                    @endforeach
                                @endif
                            </select>

                        </div>
                    </div>

                    <div class="col-md-3 ">
                        <div class="form-group">
                            <label> بحث بنوع التحقيق</label>
                            <select name="is_auto" id="is_auto" class="form-control select2">
                                <option value="all">بحث بالكل</option>
                                <option value="1">تلقائي </option>
                                <option value="0">يدوي </option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3 ">
                        <div class="form-group">
                            <label> بحث بحالة الارشفة</label>
                            <select name="is_archived_search" id="is_archived_search" class="form-control select2">
                                <option value="all">بحث بالكل</option>
                                <option value="1">مؤرشف</option>
                                <option value="0">مفتوح</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2 ">
                        <div class="form-group">
                            <button type="post" class="btn btn-sm btn-primary custom_button">طباعة البحث</button>

                        </div>
                    </div>




                </div>
            </form>
            <div class="card-body" id="ajax_ersponce_searchdiv" style="padding: 0px 5px">

                @if (@isset($data) and !@empty($data) and count($data) > 0)
                    <table id="example2" class="table table-bordered table-hover">

                        <thead class="custom_thead">

                            <th>اسم الموظف</th>
                            <th>نوع التحقيق</th>
                            <th style="width: 30%">محتوى التحقيق</th>
                            <th>ملاحظات</th>
                            <th>الاضافة</th>
                            <th>التحديث</th>
                            <th>الحالة</th>
                            <th>العمليات</th>

                        </thead>
                        <tbody>
                            @foreach ($data as $info)
                                <>

                                    <td>{{ $info->emp_name }}</td>

                                    <td>
                                        @if ($info->is_auto == 1)
                                            تلقائي
                                        @else
                                            يدوي
                                        @endif
                                    </td>

                                    <td>
                                        {{ $info->content }}
                                    </td>

                                    <td>
                                        {{ $info->notes }}
                                    </td>



                                    <td>{{ \Carbon\Carbon::parse($info->created_at)->format('d-m-Y') }}</td>
                                    <td>
                                        @if($info->updated_by!="" )
                                        {{ \Carbon\Carbon::parse($info->updated_at)->format('d-m-Y') }}
                                        @else
                                        لا يوجد
                                        @endif
                                    </td>
                                    
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
                                                class="btn btn-sm btn-success load_edit_this_row">تعديل</button>

                                            <button data-id="{{ $info->id }}"
                                                class="btn btn-sm btn-danger are_you_shur delete_this_row">حذف</button>
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
    {{-- مودل الاضافة --}}
    <div class="modal fade" id="AddModal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content bg-info">
                <div class="modal-header">
                    <h4 class="modal-title">اضافة تحقيق جديد بالشهر المالي</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body" id="AddModalBady" style="background-color: white; color: black;">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>بيانات الموظفين</label>
                                <select name="employees_code_Add" id="employees_code_Add" class="form-control select2">
                                    <option disabled selected value="">اختر الموظف</option>
                                    @if (@isset($employees) && !@empty($employees))
                                        @foreach ($employees as $info)
                                            <option value="{{ $info->employees_code }}">
                                                {{ $info->emp_name }}
                                                ({{ $info->employees_code }})
                                            </option>
                                        @endforeach
                                    @endif
                                </select>

                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>محتوى التحقيق</label>
                                <textarea rows="6" type="text" name="the_content_add" id="the_content_add" class="form-control"></textarea>
                            </div>
                        </div>

                        <div class="col-md-12 ">
                            <div class="form-group">
                                <label>ملاحظات</label>
                                <input type="text" name="notes_add" id="notes_add" class="form-control" value="">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group text-center">
                                <button id="do_add_now" class="btn btn-sm btn-danger" type="submit" name="submit">اضف
                                    التحقيق</button>
                            </div>
                        </div>


                    </div>
                </div>

            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    {{-- مودل التعديل --}}

    <div class="modal fade" id="EditModal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content bg-info">
                <div class="modal-header">
                    <h4 class="modal-title">تعديل تحقيق الموظف بالشهر المالي</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body" id="EditModalBady" style="background-color: white; color: black;">


                </div>

            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->


@endsection

@section('script')
    <script src="{{ asset('assets/admin/plugins/select2/js/select2.full.min.js') }}"></script>

    <script>
        $(".select2").select2({
            theme: 'bootstrap4'
        });

        $(document).ready(function() {


            $(document).on('click', '#do_add_now', function(e) {
                var employees_code_Add = $("#employees_code_Add").val();
                if (employees_code_Add == "") {
                    alert("من فضلك اختر الموظف");
                    $("#employees_code_Add").focus();
                    return false;
                }

                var the_content_add = $("#the_content_add").val();
                if (the_content_add == "") {
                    alert("من فضلك ادخل محتوى التحقيق");
                    $("#the_content_add").focus();
                    return false;
                }



                var notes_add = $("#notes_add").val();


                var the_finance_cin_periods_id = $('#the_finance_cin_periods_id').val();
                jQuery.ajax({
                    url: '{{ route('MainEmployeesInvestigations.checkExsistsBefor') }}',
                    type: 'post',
                    dataType: 'json',
                    cache: false,
                    data: {
                        "_token": '{{ csrf_token() }}',
                        employees_code: employees_code_Add,
                        the_finance_cin_periods_id: the_finance_cin_periods_id,

                    },

                    success: function(data) {
                        if (data == 'exsists_befor') {
                            var result = confirm(
                                "يوجد سجل تحقيق سابق مسجل للموظف بهذا الشهر المالي من قبل هل تريد الاستمرار"
                            );
                            if (result == true) {
                                var flagResult = true;
                            } else {
                                var flagResult = false;

                            }
                        } else {
                            var flagResult = true;

                        }

                        if (flagResult == true) {
                            $('#backup_freeze_modal').modal('show');

                            jQuery.ajax({
                                url: '{{ route('MainEmployeesInvestigations.store') }}',
                                type: 'post',
                                dataType: 'html',
                                cache: false,
                                data: {
                                    "_token": '{{ csrf_token() }}',
                                    employees_code: employees_code_Add,
                                    finance_cin_periods_id: the_finance_cin_periods_id,
                                    content: the_content_add,
                                    notes: notes_add,


                                },

                                success: function(data) {
                                    ajax_search();
                                    setTimeout(() => {
                                        $('#backup_freeze_modal').modal(
                                            'hide');
                                    }, 1000);

                                },
                                error: function() {

                                    setTimeout(() => {
                                        $('#backup_freeze_modal').modal(
                                            'hide');
                                    }, 1000);
                                    alert("عفواً حدث خطأ");
                                }

                            });

                        }
                    },
                    error: function() {
                        alert("عفواً حدث خطأ")
                    }

                });

            });

            // تعديل

            $(document).on('click', '.load_edit_this_row', function(e) {
                var id = $(this).data('id');
                var the_finance_cin_periods_id = $("#the_finance_cin_periods_id").val();
                jQuery.ajax({
                    url: '{{ route('MainEmployeesInvestigations.load_edit_row') }}',
                    type: 'post',
                    dataType: 'html',
                    cache: false,
                    data: {
                        "_token": '{{ csrf_token() }}',
                        id: id,
                        the_finance_cin_periods_id: the_finance_cin_periods_id,

                    },

                    success: function(data) {
                        $("#EditModalBady").html(data);
                        $("#EditModal").modal("show");
                        $('.select2').select2();
                    },
                    error: function() {
                        alert("عفواً حدث خطأ")
                    }

                });
            });

            $(document).on('click', '#do_edit_now', function(e) {
                var employees_code_edit = $("#employees_code_edit").val();
                var id = $(this).data("id");

                if (employees_code_edit == "") {
                    alert("من فضلك اختر الموظف");
                    $("#employees_code_edit").focus();
                    return false;
                }

                var the_content_edit = $("#the_content_edit").val();
                if (the_content_edit == "") {
                    alert("من فضلك ادخل محتوى التحقيق");
                    $("#the_content_edit").focus();
                    return false;
                }



                var notes_edit = $("#notes_edit").val();


                var the_finance_cin_periods_id = $('#the_finance_cin_periods_id').val();
                $('#backup_freeze_modal').modal('show');

                jQuery.ajax({
                    url: '{{ route('MainEmployeesInvestigations.do_edit_row') }}',
                    type: 'post',
                    dataType: 'html',
                    cache: false,
                    data: {
                        "_token": '{{ csrf_token() }}',
                        employees_code: employees_code_edit,
                        the_finance_cin_periods_id: the_finance_cin_periods_id,
                        content: the_content_edit,
                        notes: notes_edit,
                        id: id

                    },

                    success: function(data) {
                        ajax_search();
                        setTimeout(() => {
                            $('#backup_freeze_modal').modal(
                                'hide');
                        }, 1000);

                    },
                    error: function() {

                        setTimeout(() => {
                            $('#backup_freeze_modal').modal(
                                'hide');
                        }, 1000);
                        alert("عفواً حدث خطأ");
                    }

                });

            });

            $(document).on('click', '.delete_this_row', function(e) {
                var id = $(this).data('id');
                var the_finance_cin_periods_id = $("#the_finance_cin_periods_id").val();
                $("#backup_freeze_modal").modal("show");
                jQuery.ajax({
                    url: '{{ route('MainEmployeesInvestigations.delete_row') }}',
                    type: 'post',
                    dataType: 'json',
                    cache: false,
                    data: {
                        "_token": '{{ csrf_token() }}',
                        id: id,
                        the_finance_cin_periods_id: the_finance_cin_periods_id,
                    },

                    success: function(data) {
                        ajax_search();
                        setTimeout(() => {
                            $("#backup_freeze_modal").modal("hide");
                        }, 1000);
                    },
                    error: function() {
                        setTimeout(() => {
                            $("#backup_freeze_modal").modal("hide");
                        }, 1000);
                        alert("عفواً حدث خطأ");
                    }
                });
            });

            $(document).on('change', '#employees_code_search', function(e) {
                ajax_search();

            });

            $(document).on('change', '#is_auto', function(e) {
                ajax_search();

            });

            $(document).on('change', '#is_archived_search', function(e) {
                ajax_search();

            });

            function ajax_search() {
                var employees_code = $("#employees_code_search").val();
                var is_auto = $("#is_auto").val();
                var is_archived = $("#is_archived_search").val();
                var the_finance_cin_periods_id = $('#the_finance_cin_periods_id').val();

                jQuery.ajax({
                    url: '{{ route('MainEmployeesInvestigations.ajaxSearch') }}',
                    type: 'post',
                    dataType: 'html',
                    cache: false,
                    data: {
                        "_token": '{{ csrf_token() }}',
                        employees_code: employees_code,
                        is_auto: is_auto,
                        is_archived: is_archived,
                        the_finance_cin_periods_id: the_finance_cin_periods_id
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
                    var employees_code = $("#employees_code_search").val();
                    var is_auto = $("#is_auto").val();
                    var is_archived = $("#is_archived_search").val();
                    var the_finance_cin_periods_id = $('#the_finance_cin_periods_id').val();

                    var linkurl = $(this).attr("href");

                    jQuery.ajax({
                        url: linkurl,
                        type: 'post',
                        dataType: 'html',
                        cache: false,
                        data: {
                            "_token": '{{ csrf_token() }}',
                            employees_code: employees_code,
                            is_auto: is_auto,
                            is_archived: is_archived,
                            the_finance_cin_periods_id: the_finance_cin_periods_id
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
