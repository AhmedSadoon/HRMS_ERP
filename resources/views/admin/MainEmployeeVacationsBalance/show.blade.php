@extends('layouts.admin')

@section('title')
    بيانات الرصيد السنوي
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('contentheader')
    قائمة الرصيد السنوي
@endsection

@section('contentheaderactivelink')
    <a href="{{ route('EmployeeVacationsBalance.index') }}">الرصيد السنوي</a>
@endsection

@section('contentheaderactive')
    عرض
@endsection

@section('content')
    <div class="col-12">
        <div class="card">

            <div class="card-header">
                <h3 class="card-title card_title_center">عرض تفاصيل الرصيد السنوي للموظف
                    <a target="_blank" class="btn btn-sm btn-primary"
                        href="{{ route('Employees.show', $data['id']) }}">عرض</a>
                    <a target="_blank" class="btn btn-sm btn-success"
                        href="{{ route('Employees.edit', $data['id']) }}">تعديل</a>
                    <a class="btn btn-sm btn-info"
                        href="{{ route('EmployeeVacationsBalance.index', $data['id']) }}">عودة</a>
                </h3>
            </div>

            <div class="card-body">

                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title" style="width: 100%; text-align:right; !important">
                            <i class="fas fa-edit"></i>
                            البيانات المطلوبة للموظف
                        </h3>
                    </div>
                    <div class="card-body">

                        <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link @if (!Session::has('tabfiles')) active @endif" id="personal_data"
                                    data-toggle="pill" href="#custom-content-personal_data" role="tab"
                                    aria-controls="custom-content-personal_data" aria-selected="true">بيانات الرصيد</a>
                            </li>


                        </ul>

                        <div class="tab-content" id="custom-content-below-tabContent">


                            <div class="tab-pane fade @if (!Session::has('tabfiles')) show active @endif"
                                id="custom-content-personal_data" role="tabpanel" aria-labelledby="personal_data">
                                <br>
                                {{-- بداية الصف --}}
                                <div class="row">

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>كود بصمة الموظف </label>
                                            <input disabled type="text" name="zketo_code" id="zketo_code"
                                                class="form-control" value="{{ $data['zketo_code'] }}">

                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>اسم الموظف </label>
                                            <input disabled type="text" name="emp_name" id="emp_name"
                                                class="form-control" value="{{ $data['emp_name'] }}">

                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>تاريخ التعيين </label>
                                            <input disabled autofocus type="date" name="emp_start_date"
                                                id="emp_start_date" class="form-control"
                                                value="{{ $data['emp_start_date'] }}">

                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>الفرع التابع له الموظف </label>
                                            <select disabled name="branch_id" id="branch_id" class="form-control select2">

                                                <option value="">اختر الفرع</option>
                                                @if (@isset($other['branches']) && !@empty($other['branches']))
                                                    @foreach ($other['branches'] as $info)
                                                        <option @if ($data['branch_id'] == $info->id) selected="selected" @endif
                                                            value="{{ $info->id }}">{{ $info->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>

                                        </div>

                                    </div>



                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>الحالة الوظيفية </label>
                                            <select disabled name="function_status" id="function_status"
                                                class="form-control">
                                                <option value="">اختر الحالة</option>
                                                <option @if ($data['function_status'] == 1) selected @endif value="1">
                                                    يعمل</option>
                                                <option @if ($data['function_status'] == 0 and $data['function_status'] != '') selected @endif value="0">
                                                    خارج العمل</option>

                                            </select>

                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>الادارة التابع له الموظف </label>
                                            <select disabled name="emp_department_id" id="emp_department_id"
                                                class="form-control select2">

                                                <option value="">اختر الادارة</option>
                                                @if (@isset($other['departments']) && !@empty($other['departments']))
                                                    @foreach ($other['departments'] as $info)
                                                        <option @if ($data['emp_department_id'] == $info->id) selected="selected" @endif
                                                            value="{{ $info->id }}">{{ $info->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>



                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label> وظيفة الموظف </label>
                                            <select disabled name="emp_jobs_id" id="emp_jobs_id"
                                                class="form-control select2 ">
                                                <option value="">اختر الوظيفة</option>
                                                @if (@isset($other['jobs']) && !@empty($other['jobs']))
                                                    @foreach ($other['jobs'] as $info)
                                                        <option @if ($data['emp_jobs_id'] == $info->id) selected="selected" @endif
                                                            value="{{ $info->id }}"> {{ $info->name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>

                                        </div>
                                    </div>

                                    <div class="clearfix">

                                    </div>


                                    <div class="col-md-3">

                                        <label>بحث بالسنوات المالية</label>
                                        <select name="finance_calender_search" id="finance_calender_search"
                                            class="form-control select2">

                                            <option value="all">اختر السنة المالية</option>
                                            @if (@isset($other['finance_calender']) && !@empty($other['finance_calender']))
                                                @foreach ($other['finance_calender'] as $info)
                                                    <option value="{{ $info->finance_yr }}"
                                                        @if (
                                                            !empty($other['finance_calender_open_year']) and
                                                                $info->finance_yr == $other['finance_calender_open_year']['finance_yr']
                                                        ) selected @endif>
                                                        {{ $info->finance_yr }}</option>
                                                @endforeach
                                            @endif
                                        </select>


                                    </div>



                                </div>




                                <div class="card-body col-md-12" id="ajax_responce_searchDiv">

                                    @if (@isset($other['finance_calender_open_year']) and !@empty($other['finance_calender_open_year']))
                                        @if (
                                            @isset($other['main_employees_vacations_balance']) and
                                                !@empty($other['main_employees_vacations_balance']) and
                                                count($other['main_employees_vacations_balance']) > 0)
                                            <table id="example2" class="table table-bordered table-hover">

                                                <thead class="custom_thead">
                                                    <th>الشهر</th>
                                                    <th>الرصيد المرحل</th>
                                                    <th>رصيد الشهر</th>
                                                    <th>الرصيد المتاح</th>
                                                    <th>الرصيد المستهلك</th>
                                                    <th>صافي الرصيد</th>
                                                    <th>بواسطة</th>
                                                    <th>التحديث</th>

                                                </thead>
                                                <tbody>
                                                    @foreach ($other['main_employees_vacations_balance'] as $info)
                                                        <tr>
                                                            <td> {{ $info->year_and_month }}
                                                                @if ($admin_panel_settingsData['is_pull_manull_days_from_passma'] == 0)
                                                                    <button  data-id="{{ $info->id }}" class="btn btn-sm btn-danger load_edit_this_row"><i class="fas fa-edit"></i></button>
                                                                @endif
                                                            </td>
                                                         
                                                            <td> {{ $info->carryover_from_previous_month * 1 }}</td>
                                                            <td> {{ $info->current_month_balance * 1 }} </td>
                                                            <td> {{ $info->total_available_balance * 1 }} </td>
                                                            <td> {{ $info->spent_balance * 1 }} </td>
                                                            <td> {{ $info->net_balance * 1 }} </td>
                                                            <td>{{ $info->added->name }}</td>

                                                            <td>
                                                                @if ($info->updatedBy > '0')
                                                                    {{ $info->updatedBy->name }}
                                                                @else
                                                                    لايوجد
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @else
                                            <p class="bg-danger text-center">عفواً لا توجد بيانات لعرضها</p>
                                        @endif
                                    @else
                                        <p class="bg-danger text-center">عفواً لا توجد سنة مالية مفتوحة </p>
                                    @endif




                                </div>
                                {{-- نهاية الصف --}}




                            </div>


                        </div>

                    </div>
                    <!-- /.card -->
                </div>


            </div>
        </div>
    </div>

    <div class="modal fade" id="EditModal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content bg-info">
                <div class="modal-header">
                    <h4 class="modal-title">تحديث الرصيد السنوي المستهلك للموظف بهذا الشهر يدويا </h4>
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

            $(document).on('click', '.load_edit_this_row', function(e) {
                var id = $(this).data('id');
                jQuery.ajax({
                    url: '{{ route('EmployeeVacationsBalance.load_edit_row') }}',
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
                    },
                    error: function() {
                        alert("عفواً حدث خطأ")
                    }

                });
            });

            $(document).on('click', '#do_edit_now', function(e) {
                var spent_balance_edit = $("#spent_balance_edit").val();
                var id = $(this).data("id");

                if (spent_balance_edit == "") {
                    alert("من فضلك ادخل رصيد الاجازات المستهلك لهذا الشهر");
                    $("#spent_balance_edit").focus();
                    return false;
                }



             


                $('#backup_freeze_modal').modal('show');

                jQuery.ajax({
                    url: '{{ route('EmployeeVacationsBalance.do_edit_row') }}',
                    type: 'post',
                    dataType: 'html',
                    cache: false,
                    data: {
                        "_token": '{{ csrf_token() }}',
                        spent_balance: spent_balance_edit,
                       
                        id: id

                    },

                    success: function(data) {
                        location.reload();
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

        });
    </script>
@endsection
