@extends('layouts.admin')

@section('title')
الرواتب
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('contentheader')
    قائمة الرواتب
@endsection

@section('contentheaderactivelink')
    <a href="{{ route('MainSalaryEmployee.index') }}">الرواتب</a>
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
                <h3 class="card-title card_title_center">بيانات رواتب الموظفين مفصلة بالشهر المالي
                    ({{ $finance_cin_periods_data['month']->name }} لسنة {{ $finance_cin_periods_data['finance_yr'] }})

                </h3>
                @if ($finance_cin_periods_data['is_open'] == 1)
                    <button data-toggle="modal" data-target="#AddModal" class="btn btn-sm btn-success">اضافة راتب يدوي</button>
                @endif
            </div>

            <form action="{{ route('MainSalaryEmployee.print_search') }}" method="POST" target="_blank">
                @csrf
                <input type="hidden" name="the_finance_cin_periods_id" id="the_finance_cin_periods_id"
                    value="{{ $finance_cin_periods_data['id'] }}">

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

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>بحث بالفروع</label>
                            <select name="branch_id" id="branch_id_search" class="form-control select2">
    
                                <option value="all">بحث بالكل</option>
                                @if (@isset($other['branches']) && !@empty($other['branches']))
                                    @foreach ($other['branches'] as $info)
                                        <option value="{{ $info->id }}">{{ $info->name }}</option>
                                    @endforeach
                                @endif
                            </select>
    
                        </div>
    
                    </div>
    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>بحث بالادارة</label>
                            <select name="emp_department_id" id="emp_department_id_search" class="form-control select2">
    
                                <option value="all">بحث بالكل</option>
                                @if (@isset($other['departments']) && !@empty($other['departments']))
                                    @foreach ($other['departments'] as $info)
                                        <option value="{{ $info->id }}">{{ $info->name }}</option>
                                    @endforeach
                                @endif
                            </select>
    
                        </div>
                    </div>
    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>بحث بالوظائف</label>
                            <select name="emp_jobs_id" id="emp_jobs_id_search" class="form-control select2 ">
                                <option value="all">بحث بالكل</option>
                                @if (@isset($other['jobs']) && !@empty($other['jobs']))
                                    @foreach ($other['jobs'] as $info)
                                        <option value="{{ $info->id }}"> {{ $info->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>بحث بالحالة الوظيفية</label>
                            <select name="function_status" id="function_status_search" class="form-control">
                                <option value="all">بحث بالكل</option>
                                <option value="1">يعمل</option>
                                <option value="0">خارج العمل</option>
    
                            </select>
    
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>بحث بنوع صرف الراتب</label>
                            <select name="sal_cach_or_visa" id="sal_cach_or_visa_search" class="form-control">
                                <option value="all">بحث بالكل</option>
                                <option value="1">كاش</option>
                                <option value="2">فيزا</option>
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
                            <label> بحث بحالة الايقاف</label>
                            <select name="is_stoped" id="is_stoped_search" class="form-control select2">
                                <option value="all">بحث بالكل</option>
                                <option value="0">مفعل</option>
                                <option value="1">موقوف</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12 text-center">
                        <div class="form-group">
                            <button type="post" name="submit_button" class="btn btn-sm btn-primary" value="indetails">طباعة البحث تفصيلي</button>
                            <button type="post" name="submit_button" class="btn btn-sm btn-info" value="intotal">طباعة البحث اجمالي</button>
                            <hr>
                        </div>
                    </div>




                </div>
            </form>
            
            <div class="card-body" id="ajax_ersponce_searchdiv" style="padding: 0px 5px">


                <h3 style="text-align: center; font-size: 14px; font-weight: bold;color: brown;">مرأة البحث</h3>
            <table id="example2" class="table table-bordered table-hover" style="width: 80%; margin: 0 auto;">

                
                    <tr style="background-color: lightblue">
                    <th>عدد الرواتب</th>
                    <th>بأنتضار الارشفة</th>
                    <th>عدد المؤرشف</th>
                    <th>عدد الموقوف</th>
                </tr>
                <tr>
                    <td>{{$other['counterSalaries']*1}}</td>
                    <td>{{$other['counterSalariesWatingArchive']*1}}</td>
                    <td>{{$other['counterSalariesDoneArchive']*1}}</td>
                    <td>{{$other['counterSalariesStopped']*1}}</td>
                </tr>
            </table>


                @if (@isset($data) and !@empty($data) and count($data) > 0)
                    <table id="example2" class="table table-bordered table-hover">

                        <thead class="custom_thead">

                            <th>كود</th>
                            <th>اسم الموظف</th>
                            <th>الفرع</th>
                            <th>الادارة</th>
                            <th>الوظيفة</th>
                            <th>حالة الارشفة</th>
                            <th>صورة الموظف</th>
                            <th>العمليات</th>

                        </thead>
                        <tbody>
                            @foreach ($data as $info)
                                <tr>

                                    <td>{{ $info->employees_code }} </td>
                                    <td>{{ $info->emp_name }} </td>
                                    <td>{{ $info->branch_name }}</td>
                                    <td>{{ $info->department_name }}</td>
                                    <td>{{ $info->jobs_name }}</td>
                                    
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
                                        @if ($info->is_archived == 0)
        
                                            <button data-id="{{ $info->id }}"
                                                data-id="{{ $info->id }}"
                                                class="btn btn-sm btn-danger are_you_shur delete_this_row">حذف</button>
                                        @endif

                                        <a href="{{route('MainSalaryEmployee.showSalaryDetails',$info->id)}}" class="btn btn-sm btn-success">التفاصيل</a>
                                        <a target="_blank" href="{{route('MainSalaryEmployee.printSalary',$info->id)}}" class="btn btn-sm btn-primary" style="color: white">طباعة </a>

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
                    <h4 class="modal-title">اضافة راتب جديد للشهر المالي</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body" id="AddModalBady" style="background-color: white; color: black;">
                    <form action="{{route('MainSalaryEmployee.AddManuallySalary',$finance_cin_periods_data['id'])}}" method="POST">
                        @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>بيانات الموظفين الذين ليس مضاف لهم رواتب الشهر الحالي ( {{$other['nothave']*1}} )</label>
                                <select name="employees_code_Add" id="employees_code_Add" class="form-control select2">
                                    <option disabled selected value="">اختر الموظف</option>
                                    @if (@isset($other['employees']) && !@empty($other['employees']))
                                        @foreach ($other['employees'] as $info)     
                                        @if($info->counter>0) @continue @endif
                                            <option value="{{ $info->employees_code }}" data-s="{{ $info->emp_salary }}">
                                                {{ $info->emp_name }}
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

                        <div class="col-md-3">
                            <div class="form-group text-left">
                                <button style="margin-top: 33px" id="do_add_now" class="btn btn-sm btn-danger"
                                    type="submit" name="submit">فتح سجل راتب بهذا الشهر</button>
                            </div>
                        </div>


                    </div>
                    </form>
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

            

            });

    

        });

    

       

        $(document).on('change', '#employees_code_search', function(e) {
            ajax_search();

        });

        $(document).on('change', '#branch_id_search', function(e) {
            ajax_search();

        });

        $(document).on('change', '#emp_department_id_search', function(e) {
            ajax_search();

        });

        $(document).on('change', '#emp_jobs_id_search', function(e) {
            ajax_search();

        });

        $(document).on('change', '#function_status_search', function(e) {
            ajax_search();

        });

        $(document).on('change', '#sal_cach_or_visa_search', function(e) {
            ajax_search();

        });

        $(document).on('change', '#is_archived_search', function(e) {
            ajax_search();

        });

        $(document).on('change', '#is_stoped_search', function(e) {
            ajax_search();

        });

        function ajax_search() {
            var employees_code = $("#employees_code_search").val();
            var branch_id = $("#branch_id_search").val();
            var emp_department_id = $("#emp_department_id_search").val();
            var emp_jobs_id = $("#emp_jobs_id_search").val();
            var function_status = $("#function_status_search").val();
            var sal_cach_or_visa = $("#sal_cach_or_visa_search").val();
            var is_archived = $("#is_archived_search").val();
            var is_stoped = $("#is_stoped_search").val();
            var the_finance_cin_periods_id = $('#the_finance_cin_periods_id').val();

            jQuery.ajax({
                url: '{{ route('MainSalaryEmployee.ajaxSearch') }}',
                type: 'post',
                dataType: 'html',
                cache: false,
                data: {
                    "_token": '{{ csrf_token() }}',
                    employees_code: employees_code,
                    branch_id: branch_id,
                    emp_department_id:emp_department_id,
                    emp_jobs_id:emp_jobs_id,
                    function_status:function_status,
                    sal_cach_or_visa:sal_cach_or_visa,
                    is_stoped:is_stoped,
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
            var branch_id = $("#branch_id_search").val();
            var emp_department_id = $("#emp_department_id_search").val();
            var emp_jobs_id = $("#emp_jobs_id_search").val();
            var function_status = $("#function_status_search").val();
            var sal_cach_or_visa = $("#sal_cach_or_visa_search").val();
            var is_archived = $("#is_archived_search").val();
            var is_stoped = $("#is_stoped_search").val();
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
                    branch_id: branch_id,
                    emp_department_id:emp_department_id,
                    emp_jobs_id:emp_jobs_id,
                    function_status:function_status,
                    sal_cach_or_visa:sal_cach_or_visa,
                    is_stoped:is_stoped,
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
            $("#backup_freeze_modal").modal("show");
            jQuery.ajax({
                url: '{{ route('MainSalaryEmployee.delete_salary') }}',
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
                    alert("عفواً حدث خطأ ربما يكون سجل الراتب غير موجود او مؤرشف ");
                }
            });
        });

    </script>
@endsection
