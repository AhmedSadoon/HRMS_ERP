@extends('layouts.admin')

@section('title')
    السلف المستديمة
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('contentheader')
    قائمة السلف المستديمة
@endsection

@section('contentheaderactivelink')
    <a href="{{ route('MainSalary_p_Loans.index') }}">السلف المستديمة</a>
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
                <h3 class="card-title card_title_center">بيانات السلف المستديمة للموظفين
                </h3>
                <button data-toggle="modal" data-target="#AddModal" class="btn btn-sm btn-success">اضافة جديد</button>

            </div>

            <form action="{{ route('MainSalary_p_Loans.print_search') }}" method="POST" target="_blank">
                @csrf


                <div class="row" style="padding: 5px">


                    <div class="col-md-3">
                        <div class="form-group">
                            <label>بحث بالموظفين</label>
                            <select name="employees_code" id="employees_code_search" class="form-control select2">
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


                    <div class="col-md-3 ">
                        <div class="form-group">
                            <label> بحث بحالة الارشفة</label>
                            <select name="is_archived" id="is_archived_search" class="form-control select2">
                                <option value="all">بحث بالكل</option>
                                <option value="1">مؤرشف</option>
                                <option value="0">مفتوح</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3 ">
                        <div class="form-group">
                            <label> بحث بحالة الصرف</label>
                            <select name="is_dismissail_done" id="is_dismissail_done_search" class="form-control select2">
                                <option value="all">بحث بالكل</option>
                                <option value="1">تم الصرف</option>
                                <option value="0">بأنتظار الصرف</option>
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
                                            <a href="{{ route('MainSalary_p_Loans.do_is_dismissail_done_now', $info->id) }}"
                                                class="btn btn-sm btn-primary are_you_shur">صرف الان</a>
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
                                        <button data-id="{{ $info->id }}"
                                            class="btn btn-sm btn-dark load_akast_details">الاقساط</button>
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

    <input type="hidden" value="@php echo date("Y-m-d"); @endphp" id="the_today_date">
    <div class="modal fade" id="AddModal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content bg-info">
                <div class="modal-header">
                    <h4 class="modal-title">اضافة سلفة مستديمة للموظفين</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body" id="AddModalBady" style="background-color: white; color: black;">

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>بيانات الموظفين</label>
                                <select name="employees_code_Add" id="employees_code_Add" class="form-control select2">
                                    <option disabled selected value="">اختر الموظف</option>
                                    @if (@isset($other['employees']) && !@empty($other['employees']))
                                        @foreach ($other['employees'] as $info)
                                            <option value="{{ $info->employees_code }}" data-s="{{ $info->emp_salary }}"
                                                data-dp="{{ $info->day_price }}">
                                                {{ $info->emp_name }}
                                                ({{ $info->employees_code }})
                                            </option>
                                        @endforeach
                                    @endif
                                </select>

                            </div>
                        </div>

                        <div class="col-md-4 related_employees_add" style="display: none">
                            <div class="form-group">
                                <label>راتب الموظف الشهري</label>
                                <input readonly type="text" name="emp_salary_add" id="emp_salary_add"
                                    class="form-control" value="0">
                            </div>
                        </div>

                        <div class="col-md-4 related_employees_add" style="display: none">
                            <div class="form-group">
                                <label>اجر اليوم الواحد</label>
                                <input readonly type="text" name="day_price_add" id="day_price_add"
                                    class="form-control" value="0">
                            </div>
                        </div>


                        <div class="col-md-4 ">
                            <div class="form-group">
                                <label>اجمالي قيمة السلفة المستديمة</label>
                                <input type="text" name="total_add" id="total_add" class="form-control"
                                    value="">
                            </div>
                        </div>

                        <div class="col-md-4 ">
                            <div class="form-group">
                                <label>عدد الشهور للاقساط</label>
                                <input type="text" name="month_number_add" id="month_number_add" class="form-control"
                                    value="">
                            </div>
                        </div>

                        <div class="col-md-4 ">
                            <div class="form-group">
                                <label>قيمة القسط الشهري</label>
                                <input readonly type="text" name="month_kast_value_add" id="month_kast_value_add"
                                    class="form-control" oninput="this.value=this.value.replace(/[^0-9.]/g,'');"
                                    value="">
                            </div>
                        </div>

                        <div class="col-md-4 ">
                            <div class="form-group">
                                <label>يبدأ سداد اول قسط في تاريخ</label>
                                <input type="date" name="year_and_month_start_date_add"
                                    id="year_and_month_start_date_add" class="form-control" value="">
                            </div>
                        </div>

                        <div class="col-md-8 ">
                            <div class="form-group">
                                <label>ملاحظات</label>
                                <input type="text" name="notes_add" id="notes_add" class="form-control"
                                    value="">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <hr>
                            <div class="form-group text-center">
                                <button style="margin-top: 33px" id="do_add_now" class="btn btn-sm btn-danger"
                                    type="submit" name="submit">اضف السلفة المستديمة</button>
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



    <div class="modal fade" id="AksatDetailsModal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content bg-info">
                <div class="modal-header">
                    <h4 class="modal-title">عرض تفاصيل اقساط سلفة المستديمة للموظف</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body" id="AksatDetailsModalBady" style="background-color: white; color: black;">


                </div>

            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <div class="modal fade" id="EditModal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content bg-info">
                <div class="modal-header">
                    <h4 class="modal-title">تعديل بيانات سلفة مستديمة للموظف</h4>
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

            $(document).on('change', '#employees_code_Add', function(e) {
                if ($(this).val == "") {
                    $(".related_employees_add").hide();
                    $("#emp_salary_add").val(0);
                    $("#day_price_add").val(0);

                } else {
                    var salary = $("#employees_code_Add option:selected").data("s");
                    var day_price = $("#employees_code_Add option:selected").data("dp");

                    $("#emp_salary_add").val(salary * 1);
                    $("#day_price_add").val(day_price * 1);
                    $(".related_employees_add").show();
                }
            });

            function recalculate_add_p_row() {
                var total_add = $("#total_add").val();
                var month_number_add = $("#month_number_add").val();

                if (total_add == "") {
                    total_add = 0;
                }

                if (month_number_add == "") {
                    month_number_add = 0;
                }

                if (total_add != 0 && month_number_add != 0) {
                    var month_kast_value_add = parseFloat(total_add) / parseFloat(month_number_add);
                    $("#month_kast_value_add").val(month_kast_value_add.toFixed(2) * 1);
                } else {
                    $("#month_kast_value_add").val(0);
                }

            }


            function recalculate_edit_p_row() {
                var total_edit = $("#total_edit").val();
                var month_number_edit = $("#month_number_edit").val();

                if (total_edit == "") {
                    total_edit = 0;
                }

                if (month_number_edit == "") {
                    month_number_edit = 0;
                }

                if (total_edit != 0 && month_number_edit != 0) {
                    var month_kast_value_edit = parseFloat(total_edit) / parseFloat(month_number_edit);
                    $("#month_kast_value_edit").val(month_kast_value_edit.toFixed(2) * 1);
                } else {
                    $("#month_kast_value_edit").val(0);
                }

            }

            $(document).on('input', '#total_edit', function(e) {
                recalculate_edit_p_row();
            });

            $(document).on('input', '#month_number_edit', function(e) {
                recalculate_edit_p_row();
            });

            $(document).on('input', '#total_add', function(e) {
                recalculate_add_p_row();
            });

            $(document).on('input', '#month_number_add', function(e) {
                recalculate_add_p_row();
            });

            $(document).on('click', '#do_add_now', function(e) {
                var employees_code_Add = $("#employees_code_Add").val();
                if (employees_code_Add == "") {
                    alert("من فضلك اختر الموظف");
                    $("#employees_code_Add").focus();
                    return false;
                }



                var total_add = $("#total_add").val();
                if (total_add == "") {
                    alert("من فضلك ادخل اجمالي السلفة");
                    $("#total_add").focus();
                    return false;
                }

                var month_number_add = $("#month_number_add").val();
                if (month_number_add == "") {
                    alert("من فضلك ادخل عدد شهور الاقساط");
                    $("#month_number_add").focus();
                    return false;
                }

                var month_kast_value_add = $("#month_kast_value_add").val();
                if (month_kast_value_add == "") {
                    alert("من فضلك ادخل اجمالي القسط");
                    $("#month_kast_value_add").focus();
                    return false;
                }

                var the_today_date = $("#the_today_date").val();
                var year_and_month_start_date_add = $("#year_and_month_start_date_add").val();
                if (year_and_month_start_date_add == "" || year_and_month_start_date_add == 0) {
                    alert("من فضلك اختار تاريخ سداد اول قسط");
                    $("#year_and_month_start_date_add").focus();
                    return false;
                }

                // if (year_and_month_start_date_add < the_today_date) {
                //     alert("من فضلك اختار تاريخ سداد يكون مساوي او اكبر من تاريخ اليوم");
                //     $("#year_and_month_start_date_add").focus();
                //     return false;
                // }

                var notes_add = $("#notes_add").val();
                var day_price_add = $("#day_price_add").val();
                var emp_salary_add = $("#emp_salary_add").val();

                jQuery.ajax({
                    url: '{{ route('MainSalary_p_Loans.checkExsistsBefor') }}',
                    type: 'post',
                    dataType: 'json',
                    cache: false,
                    data: {
                        "_token": '{{ csrf_token() }}',
                        employees_code: employees_code_Add,

                    },

                    success: function(data) {
                        if (data == 'exsists_befor') {
                            var result = confirm(
                                "يوجد سلفة مستديمة مفتوحة للموظف هل تريد الاستمرار"
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
                                url: '{{ route('MainSalary_p_Loans.store') }}',
                                type: 'post',
                                dataType: 'html',
                                cache: false,
                                data: {
                                    "_token": '{{ csrf_token() }}',
                                    employees_code: employees_code_Add,
                                    total: total_add,
                                    month_number: month_number_add,
                                    month_kast_value: month_kast_value_add,
                                    notes: notes_add,
                                    day_price: day_price_add,
                                    emp_salary: emp_salary_add,
                                    year_and_month_start_date: year_and_month_start_date_add,


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

            $(document).on('input', '#value_add', function(e) {
                var value_add = $(this).val();
                if (value_add == "") {
                    value_add = 0;
                }

                var day_price_add = $("#day_price_add").val();
                $("#total_add").val(value_add * day_price_add * 1);
            });

            $(document).on('click', '.doSingleCachPayNow', function(e) {
                var res=confirm("هل انت متأكد");
                if(!res){
                    return false;
                }
                var id = $(this).data('id');
                var idparent = $(this).data('idparent');
                jQuery.ajax({
                    url: '{{ route('MainSalary_p_Loans.doSingleCachPayNow') }}',
                    type: 'post',
                    dataType: 'json',
                    cache: false,
                    data: {
                        "_token": '{{ csrf_token() }}',
                        id: id,
                        idparent: idparent,

                    },

                    success: function(data) {
                        jQuery.ajax({
                            url: '{{ route('MainSalary_p_Loans.load_akast_details') }}',
                            type: 'post',
                            dataType: 'html',
                            cache: false,
                            data: {
                                "_token": '{{ csrf_token() }}',
                                id: idparent,

                            },

                            success: function(data) {
                                $("#AksatDetailsModalBady").html(data);
                                $("#AksatDetailsModal").modal("show");
                                $('.select2').select2();
                            },
                            error: function() {
                                alert("عفواً حدث خطأ")
                            }

                        });
                    },
                    error: function() {
                        alert("عفواً حدث خطأ")
                    }

                });
            });


        });

        $(document).on('click', '.load_edit_this_row', function(e) {
            var id = $(this).data('id');
            jQuery.ajax({
                url: '{{ route('MainSalary_p_Loans.load_edit_row') }}',
                type: 'post',
                dataType: 'html',
                cache: false,
                data: {
                    "_token": '{{ csrf_token() }}',
                    id: id,
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

        $(document).on('change', '#employees_code_edit', function(e) {
            if ($(this).val == "") {
                $(".related_employees_edit").hide();
                $("#emp_salary_edit").val(0);
                $("#day_price_edit").val(0);

            } else {
                var salary = $("#employees_code_edit option:selected").data("s");
                var day_price = $("#employees_code_edit option:selected").data("dp");

                $("#emp_salary_edit").val(salary * 1);
                $("#day_price_edit").val(day_price * 1);
                $(".related_employees_edit").show();
            }
        });

        $(document).on('input', '#value_edit', function(e) {
            var value_edit = $(this).val();
            if (value_edit == "") {
                value_edit = 0;
            }

            var day_price_edit = $("#day_price_edit").val();
            $("#total_edit").val(value_edit * day_price_edit * 1);
        });

        $(document).on('change', '#employees_code_search', function(e) {
            ajax_search();

        });

        $(document).on('change', '#is_dismissail_done_search', function(e) {
            ajax_search();

        });

        $(document).on('change', '#is_archived_search', function(e) {
            ajax_search();

        });

        function ajax_search() {
            var employees_code = $("#employees_code_search").val();
            var is_archived = $("#is_archived_search").val();
            var is_dismissail_done = $("#is_dismissail_done_search").val();

            jQuery.ajax({
                url: '{{ route('MainSalary_p_Loans.ajaxSearch') }}',
                type: 'post',
                dataType: 'html',
                cache: false,
                data: {
                    "_token": '{{ csrf_token() }}',
                    employees_code: employees_code,
                    is_archived: is_archived,
                    is_dismissail_done: is_dismissail_done
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
                var is_archived = $("#is_archived_search").val();
                var is_dismissail_done = $("#is_dismissail_done_search").val();

                var linkurl = $(this).attr("href");

                jQuery.ajax({
                    url: linkurl,
                    type: 'post',
                    dataType: 'html',
                    cache: false,
                    data: {
                        "_token": '{{ csrf_token() }}',
                        employees_code: employees_code,
                        is_archived: is_archived,
                        is_dismissail_done: is_dismissail_done
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

        $(document).on('click', '.delete_this_row', function(e) {
            var id = $(this).data('id');
            var the_finance_cin_periods_id = $("#the_finance_cin_periods_id").val();
            var main_salary_employee_id = $(this).data("main_sal_id");
            $("#backup_freeze_modal").modal("show");
            jQuery.ajax({
                url: '{{ route('MainSalaryLoans.delete_row') }}',
                type: 'post',
                dataType: 'json',
                cache: false,
                data: {
                    "_token": '{{ csrf_token() }}',
                    id: id,
                    the_finance_cin_periods_id: the_finance_cin_periods_id,
                    main_salary_employee_id: main_salary_employee_id,
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



        $(document).on('click', '.load_akast_details', function(e) {
            var id = $(this).data('id');

            jQuery.ajax({
                url: '{{ route('MainSalary_p_Loans.load_akast_details') }}',
                type: 'post',
                dataType: 'html',
                cache: false,
                data: {
                    "_token": '{{ csrf_token() }}',
                    id: id,

                },

                success: function(data) {
                    $("#AksatDetailsModalBady").html(data);
                    $("#AksatDetailsModal").modal("show");
                    $('.select2').select2();
                },
                error: function() {
                    alert("عفواً حدث خطأ")
                }

            });
        });


        $(document).on('click', '#do_edit_now', function(e) {
            var employees_code_edit = $("#employees_code_edit").val();
            if (employees_code_edit == "") {
                alert("من فضلك اختر الموظف");
                $("#employees_code_Add").focus();
                return false;
            }



            var total_edit = $("#total_edit").val();
            if (total_edit == "" || total_edit == 0) {
                alert("من فضلك ادخل اجمالي السلفة");
                $("#total_edit").focus();
                return false;
            }

            var month_number_edit = $("#month_number_edit").val();
            if (month_number_edit == "" || month_number_edit == 0) {
                alert("من فضلك ادخل عدد شهور الاقساط");
                $("#month_number_edit").focus();
                return false;
            }

            var month_kast_value_edit = $("#month_kast_value_edit").val();
            if (month_kast_value_edit == "") {
                alert("من فضلك ادخل اجمالي القسط");
                $("#month_kast_value_edit").focus();
                return false;
            }

            var the_today_date = $("#the_today_date").val();
            var year_and_month_start_date_edit = $("#year_and_month_start_date_edit").val();
            if (year_and_month_start_date_edit == "" || year_and_month_start_date_edit == 0) {
                alert("من فضلك اختار تاريخ سداد اول قسط");
                $("#year_and_month_start_date_edit").focus();
                return false;
            }

            if (year_and_month_start_date_edit < the_today_date) {
                alert("من فضلك اختار تاريخ سداد يكون مساوي او اكبر من تاريخ اليوم");
                $("#year_and_month_start_date_edit").focus();
                return false;
            }

            var notes_edit = $("#notes_edit").val();
            var day_price_edit = $("#day_price_edit").val();
            var emp_salary_edit = $("#emp_salary_edit").val();
            var id = $(this).data("id");
            $('#backup_freeze_modal').modal('show');

            jQuery.ajax({
                url: '{{ route('MainSalary_p_Loans.do_edit_row') }}',
                type: 'post',
                dataType: 'json',
                cache: false,
                data: {
                    "_token": '{{ csrf_token() }}',
                    employees_code: employees_code_edit,
                    total: total_edit,
                    month_number: month_number_edit,
                    month_kast_value: month_kast_value_edit,
                    notes: notes_edit,
                    day_price: day_price_edit,
                    emp_salary: emp_salary_edit,
                    year_and_month_start_date: year_and_month_start_date_edit,
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
    </script>
@endsection
