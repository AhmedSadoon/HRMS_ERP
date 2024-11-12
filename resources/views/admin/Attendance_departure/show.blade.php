@extends('layouts.admin')

@section('title')
    البصمة
@endsection

@section('contentheader')
    قائمة جهاز البصمة
@endsection

@section('contentheaderactivelink')
    <a href="{{ route('MainSalarySanctions.index') }}">بصمة الموظفين</a>
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
                <h3 class="card-title card_title_center">
                    @if ($finance_cin_periods_data['is_open']==1)
                                <a href="{{route('AttendanceDeparture.uploadExcelFile',$finance_cin_periods_data['id'])}}" class="btn btn-sm btn-danger" style="float: right;">رفع ملف البصمة</a>                
                    @endif
                    بيانات بصمات الموظفين بالشهر المالي
                    ({{ $finance_cin_periods_data['month']->name }} لسنة {{ $finance_cin_periods_data['finance_yr'] }})

                </h3>

            </div>

            <form action="{{ route('MainSalarySanctions.print_search') }}" method="POST" target="_blank">
                @csrf
                <input type="hidden" name="the_finance_cin_periods_id" id="the_finance_cin_periods_id"
                    value="{{ $finance_cin_periods_data['id'] }}">

                <div class="row" style="padding: 5px">


                    <div class="col-md-4">
                        <div class="form-group">
                            <label>بحث بالموظفين</label>
                            <select name="employees_code_search" id="employees_code_search" class="form-control">
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

                </div>
            </form>
            <p style="text-align: center; color:black;">
                @if (@isset($last_attendance_departure_actions_excel_data) and !@empty($last_attendance_departure_actions_excel_data))
                ملاحظة : تاريخ اخر حركة بصمة مسجلة بالنظام (
                    @php
                        $dt=new DateTime($last_attendance_departure_actions_excel_data['datetimeAction']);
                        $date=$dt->format("Y-m-d");
                        $time=$dt->format("h:i");
                        //$newDateTime=strtolower(date("a",strtotime($last_attendance_departure_actions_excel_data['datetimeAction'])));
                        $newDateTime=strtolower($dt->format("a"));
                        $newDateTimeType=(($newDateTime=="am")?'صباحاً':'مساءً');
                    @endphp
                    {{$date}} 
                    {{$time}}
                    {{$newDateTimeType}} 

                    بواسطة ({{$last_attendance_departure_actions_excel_data['added_by_name']}})
                    )   
                @else                    
                
                لا يوجد
                @endif
            </p>
            <div class="card-body" id="ajax_ersponce_searchdiv" style="padding: 0px 5px">

                @if (@isset($data) and !@empty($data) and count($data) > 0)
                    <table id="example2" class="table table-bordered table-hover">

                        <thead class="custom_thead">

                            <th>كود</th>
                            <th>اسم الموظف</th>
                            <th>الفرع</th>
                            <th>الادارة</th>
                            <th>الوظيفة</th>
                            <th>صورة الموظف</th>
                            <th>العمليات</th>

                        </thead>
                        <tbody>
                            @foreach ($data as $info)
                                <tr>

                                    <td>{{ $info->employees_code }} </td>
                                    <td>{{ $info->emp_name }} </td>
                                    <td> {{ $info->Branch->name }} </td>
                                    <td>
                                        @if (!@empty($info->Department->name))
                                            {{ $info->Department->name }}
                                        @endif

                                    </td>

                                    <td>
                                        @if (!@empty($info->Job->name))
                                            {{ $info->Job->name }}
                                        @endif

                                    </td>



                                    <td>
                                        @if (!@empty($info->emp_photo))
                                            <img src="{{ asset('assets/admin/uploads') . '/' . $info->emp_photo }}"
                                                style="border-radius: 50%; width: 80px; height: 80px;"
                                                class="rounded-circle" alt="صورة الموظف">
                                        @else
                                            @if ($info->emp_gender == 1)
                                                <img src="{{ asset('assets/admin/images/boy.png') }}"
                                                    style="border-radius: 50%; width: 80px; height: 80px;"
                                                    class="rounded-circle" alt="صورة الموظف">
                                            @else
                                                <img src="{{ asset('assets/admin/images/woman.png') }}"
                                                    style="border-radius: 50%; width: 80px; height: 80px;"
                                                    class="rounded-circle" alt="صورة الموظف">
                                            @endif
                                        @endif
                                    </td>


                                    <td>


                                        <a href="{{ route('AttendanceDeparture.showPasmaDetails', ['employees_code'=>$info->employees_code,'finance_cin_periods_id'=>$finance_cin_periods_data['id']]) }}"
                                            class="btn btn-sm btn-success">التفاصيل</a>
                                        <a target="_blank"
                                            href="{{ route('AttendanceDeparture.print_onePasmasearch', $info->id) }}"
                                            class="btn btn-sm btn-primary" style="color: white">طباعة </a>

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
                                            <option value="{{ $info->employees_code }}"
                                                data-s="{{ $info->EmployeeData['emp_salary'] }}"
                                                data-dp="{{ $info->EmployeeData['day_price'] }}">
                                                {{ $info->EmployeeData['emp_name'] }}
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
                                <label>نوع الجزاء</label>
                                <select name="sactions_type_add" id="sactions_type_add" class="form-control">
                                    <option disabled selected value="">اختر النوع</option>
                                    <option value="1">جزاء ايام</option>
                                    <option value="2">جزاء بصمة</option>
                                    <option value="3">جزاء تحقيق</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3 ">
                            <div class="form-group">
                                <label>عدد اليوم الجزاء</label>
                                <input type="text" name="value_add" id="value_add" class="form-control"
                                    oninput="this.value=this.value.replace(/[^0-9.]/g,'');" value="">
                            </div>
                        </div>

                        <div class="col-md-3 ">
                            <div class="form-group">
                                <label>اجمالي قيمة الجزاء</label>
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

                        <div class="col-md-3">
                            <div class="form-group text-left">
                                <button style="margin-top: 33px" id="do_add_now" class="btn btn-sm btn-danger"
                                    type="submit" name="submit">اضف الجزاء</button>
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

                var sactions_type_add = $("#sactions_type_add").val();
                if (sactions_type_add == "") {
                    alert("من فضلك اختر نوع الجزاء");
                    $("#sactions_type_add").focus();
                    return false;
                }

                var value_add = $("#value_add").val();
                if (value_add == "") {
                    alert("من فضلك ادخل عدد ايام الجزاء");
                    $("#value_add").focus();
                    return false;
                }

                var total_add = $("#total_add").val();
                if (total_add == "") {
                    alert("من فضلك ادخل اجمالي الجزاء");
                    $("#total_add").focus();
                    return false;
                }

                var notes_add = $("#notes_add").val();
                var day_price_add = $("#day_price_add").val();


                var the_finance_cin_periods_id = $('#the_finance_cin_periods_id').val();
                jQuery.ajax({
                    url: '{{ route('MainSalarySanctions.checkExsistsBefor') }}',
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
                                "يوجد سجل جزاءات سابقة مسجلة للموظف من قبل هل تريد الاستمرار"
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
                                url: '{{ route('MainSalarySanctions.store') }}',
                                type: 'post',
                                dataType: 'html',
                                cache: false,
                                data: {
                                    "_token": '{{ csrf_token() }}',
                                    employees_code: employees_code_Add,
                                    finance_cin_periods_id: the_finance_cin_periods_id,
                                    sactions_type: sactions_type_add,
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



        function ajax_search() {
            var employees_code = $("#employees_code_search").val();

            var the_finance_cin_periods_id = $('#the_finance_cin_periods_id').val();

            jQuery.ajax({
                url: '{{ route('AttendanceDeparture.ajaxSearch') }}',
                type: 'post',
                dataType: 'html',
                cache: false,
                data: {
                    "_token": '{{ csrf_token() }}',
                    employees_code: employees_code,

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
                url: '{{ route('MainSalarySanctions.delete_row') }}',
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
                url: '{{ route('MainSalarySanctions.load_edit_row') }}',
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

            var sactions_type_edit = $("#sactions_type_edit").val();
            if (sactions_type_edit == "") {
                alert("من فضلك اختر نوع الجزاء");
                $("#sactions_type_edit").focus();
                return false;
            }

            var value_edit = $("#value_edit").val();
            if (value_edit == "") {
                alert("من فضلك ادخل عدد ايام الجزاء");
                $("#value_edit").focus();
                return false;
            }

            var total_edit = $("#total_edit").val();
            if (total_edit == "") {
                alert("من فضلك ادخل اجمالي الجزاء");
                $("#total_edit").focus();
                return false;
            }

            var notes_edit = $("#notes_edit").val();
            var day_price_edit = $("#day_price_edit").val();


            var the_finance_cin_periods_id = $('#the_finance_cin_periods_id').val();
            $('#backup_freeze_modal').modal('show');

            jQuery.ajax({
                url: '{{ route('MainSalarySanctions.do_edit_row') }}',
                type: 'post',
                dataType: 'html',
                cache: false,
                data: {
                    "_token": '{{ csrf_token() }}',
                    employees_code: employees_code_edit,
                    the_finance_cin_periods_id: the_finance_cin_periods_id,
                    sactions_type: sactions_type_edit,
                    value: value_edit,
                    total: total_edit,
                    notes: notes_edit,
                    day_price: day_price_edit,
                    main_salary_employee_id: main_salary_employee_id,
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
