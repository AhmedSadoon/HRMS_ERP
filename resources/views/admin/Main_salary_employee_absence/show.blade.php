@extends('layouts.admin')

@section('title')
    الغيابات
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('contentheader')
    قائمة الاجور
@endsection

@section('contentheaderactivelink')
    <a href="{{ route('MainSalaryAbsence.index') }}">الغيابات</a>
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
                <h3 class="card-title card_title_center">بيانات الغيابات للشهر المالي
                    ({{ $finance_cin_periods_data['month']->name }} لسنة {{ $finance_cin_periods_data['finance_yr'] }})

                </h3>
                @if ($finance_cin_periods_data['is_open'] == 1)
                    <button data-toggle="modal" data-target="#AddModal" class="btn btn-sm btn-success">اضافة جديد</button>
                @endif
            </div>

            {{-- البحث --}}
           <form action="{{route('MainSalaryAbsence.print_search')}}" method="POST" target="_blank">
            @csrf
            <input type="hidden" name="the_finance_cin_periods_id" id="the_finance_cin_periods_id" value="{{ $finance_cin_periods_data['id'] }}">

            <div class="row" style="padding: 5px">


                <div class="col-md-3">
                    <div class="form-group">
                        <label>بحث بالموظفين</label>
                        <select name="employees_code_search" id="employees_code_search" class="form-control select2">
                            <option value="all">بحث بالكل</option>
                            @if (@isset($employees_search) && !@empty($employees_search))
                                @foreach ($employees_search as $info)
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
                                            <button data-id="{{ $info->id }}" data-main_sal_id="{{ $info->main_salary_employee_id }}"
                                                class="btn btn-sm btn-success load_edit_this_row">تعديل</button>
                
                                            <button data-id="{{ $info->id }}" data-main_sal_id="{{ $info->main_salary_employee_id }}"
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
                    <h4 class="modal-title">اضافة جزاءات للموظفين بالشهر المالي</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body" id="AddModalBady" style="background-color: white; color: black;">

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>بيانات الموظفين</label>
                                <select name="employees_code_Add" id="employees_code_Add" class="form-control select2">
                                    <option disabled selected value="">اختر الموظف</option>
                                    @if (@isset($employees) && !@empty($employees))
                                        @foreach ($employees as $info)
                                            <option value="{{ $info->employees_code }}" data-s="{{ $info->EmployeeData['emp_salary'] }}"
                                                data-dp="{{ $info->EmployeeData['day_price'] }}"> {{ $info->EmployeeData['emp_name'] }}
                                                ({{ $info->employees_code }})
                                            </option>
                                        @endforeach
                                    @endif
                                </select>

                            </div>
                        </div>

                        <div class="col-md-3 related_employees_add" style="display: none">
                            <div class="form-group">
                                <label>راتب الموظف الشهري</label>
                                <input readonly type="text" name="emp_salary_add" id="emp_salary_add"
                                    class="form-control" value="0">
                            </div>
                        </div>

                        <div class="col-md-3 related_employees_add" style="display: none">
                            <div class="form-group">
                                <label>اجر اليوم الواحد</label>
                                <input readonly type="text" name="day_price_add" id="day_price_add" class="form-control"
                                    value="0">
                            </div>
                        </div>


                 

                        <div class="col-md-3 ">
                            <div class="form-group">
                                <label>عدد ايام الغياب</label>
                                <input type="text" name="value_add" id="value_add" class="form-control"
                                    oninput="this.value=this.value.replace(/[^0-9.]/g,'');" value="">
                            </div>
                        </div>

                        <div class="col-md-3 ">
                            <div class="form-group">
                                <label>اجمالي قيمة الغياب</label>
                                <input type="text" readonly name="total_add" id="total_add" class="form-control"
                                    value="0">
                            </div>
                        </div>

                        <div class="col-md-3 ">
                            <div class="form-group">
                                <label>ملاحظات</label>
                                <input type="text" name="notes_add" id="notes_add" class="form-control"
                                    value="">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group text-center">
                                <hr>
                                <button  id="do_add_now" class="btn btn-sm btn-danger"
                                    type="submit" name="submit">اضف الغياب</button>
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
                    <h4 class="modal-title">تعديل جزاءات للموظفين بالشهر المالي</h4>
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

            $(document).on('click', '#do_add_now', function(e) {
                var employees_code_Add = $("#employees_code_Add").val();
                if (employees_code_Add == "") {
                    alert("من فضلك اختر الموظف");
                    $("#employees_code_Add").focus();
                    return false;
                }

              

                var value_add = $("#value_add").val();
                if (value_add == "") {
                    alert("من فضلك ادخل عدد ايام الغياب");
                    $("#value_add").focus();
                    return false;
                }

                var total_add = $("#total_add").val();
                if (total_add == "") {
                    alert("من فضلك ادخل اجمالي الغياب");
                    $("#total_add").focus();
                    return false;
                }

                var notes_add = $("#notes_add").val();
                var day_price_add = $("#day_price_add").val();


                var the_finance_cin_periods_id = $('#the_finance_cin_periods_id').val();
                jQuery.ajax({
                    url: '{{ route('MainSalaryAbsence.checkExsistsBefor') }}',
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
                                "يوجد سجل غياب سابقة مسجلة للموظف من قبل هل تريد الاستمرار"
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
                                url: '{{ route('MainSalaryAbsence.store') }}',
                                type: 'post',
                                dataType: 'html',
                                cache: false,
                                data: {
                                    "_token": '{{ csrf_token() }}',
                                    employees_code: employees_code_Add,
                                    finance_cin_periods_id: the_finance_cin_periods_id,
                                    value: value_add,
                                    total: total_add,
                                    notes: notes_add,
                                    day_price: day_price_add,

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

   

        $(document).on('change', '#is_archived_search', function(e) {
            ajax_search();

        });

        function ajax_search() {
            var employees_code = $("#employees_code_search").val();
            var is_archived = $("#is_archived_search").val();
            var the_finance_cin_periods_id = $('#the_finance_cin_periods_id').val();

            jQuery.ajax({
                url: '{{ route('MainSalaryAbsence.ajaxSearch') }}',
                type: 'post',
                dataType: 'html',
                cache: false,
                data: {
                    "_token": '{{ csrf_token() }}',
                    employees_code: employees_code,
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

        $(document).on('click', '.delete_this_row', function(e) {
            var id = $(this).data('id');
            var the_finance_cin_periods_id = $("#the_finance_cin_periods_id").val();
            var main_salary_employee_id = $(this).data("main_sal_id");
            $("#backup_freeze_modal").modal("show");
            jQuery.ajax({
                url: '{{ route('MainSalaryAbsence.delete_row') }}',
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


        // تعديل

        $(document).on('click', '.load_edit_this_row', function(e) {
            var id = $(this).data('id');
            var the_finance_cin_periods_id = $("#the_finance_cin_periods_id").val();
            var main_salary_employee_id = $(this).data("main_sal_id");
            jQuery.ajax({
                url: '{{ route('MainSalaryAbsence.load_edit_row') }}',
                type: 'post',
                dataType: 'html',
                cache: false,
                data: {
                    "_token": '{{ csrf_token() }}',
                    id: id,
                    the_finance_cin_periods_id: the_finance_cin_periods_id,
                    main_salary_employee_id: main_salary_employee_id
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
            var main_salary_employee_id = $(this).data("main_sal_id");
            var id = $(this).data("id");

            if (employees_code_edit == "") {
                alert("من فضلك اختر الموظف");
                $("#employees_code_edit").focus();
                return false;
            }

         

            var value_edit = $("#value_edit").val();
            if (value_edit == "") {
                alert("من فضلك ادخل عدد ايام الغياب");
                $("#value_edit").focus();
                return false;
            }

            var total_edit = $("#total_edit").val();
            if (total_edit == "") {
                alert("من فضلك ادخل اجمالي الغياب");
                $("#total_edit").focus();
                return false;
            }

            var notes_edit = $("#notes_edit").val();
            var day_price_edit = $("#day_price_edit").val();


            var the_finance_cin_periods_id = $('#the_finance_cin_periods_id').val();
            $('#backup_freeze_modal').modal('show');

            jQuery.ajax({
                url: '{{ route('MainSalaryAbsence.do_edit_row') }}',
                type: 'post',
                dataType: 'html',
                cache: false,
                data: {
                    "_token": '{{ csrf_token() }}',
                    employees_code: employees_code_edit,
                    the_finance_cin_periods_id: the_finance_cin_periods_id,
                    value: value_edit,
                    total: total_edit,
                    notes: notes_edit,
                    day_price: day_price_edit,
                    main_salary_employee_id:main_salary_employee_id,
                    id:id

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
