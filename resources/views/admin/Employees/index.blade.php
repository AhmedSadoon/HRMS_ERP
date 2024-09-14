@extends('layouts.admin')

@section('title')
    بيانات الموظفين
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('contentheader')
    قائمة شؤون الموظفين
@endsection

@section('contentheaderactivelink')
    <a href="{{ route('Employees.index') }}">الموظفين</a>
@endsection

@section('contentheaderactive')
    عرض
@endsection

@section('content')
    <div class="col-12">
        <div class="card">

            <div class="card-header">
                <h3 class="card-title card_title_center">بيانات الموظفين
                    <a class="btn btn-sm btn-success" href="{{ route('Employees.create') }}">اضافة جديد</a>
                </h3>
            </div>

            <div class="row" style="padding: 5px">

                <div class="col-md-3">
                    <div class="form-group">
                        <label>
                            <input type="radio" name="code_search" checked value="employee_code">كود موظف
                            <input type="radio" name="code_search" value="zketo_code">كود بصمة
                        </label>
                        <input autofocus type="text" name="searchbycode" id="searchbycode" class="form-control"
                            value="">

                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>اسم الموظف</label>
                        <input type="text" name="emp_name_search" id="emp_name_search" class="form-control"
                            value="">

                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>بحث بالفروع</label>
                        <select name="branch_id_search" id="branch_id_search" class="form-control select2">

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
                        <select name="emp_department_id_search" id="emp_department_id_search" class="form-control select2">

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
                        <select name="emp_jobs_id_search" id="emp_jobs_id_search" class="form-control select2 ">
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
                        <select name="function_status_search" id="function_status_search" class="form-control">
                            <option value="all">بحث بالكل</option>
                            <option value="1">يعمل</option>
                            <option value="0">خارج العمل</option>

                        </select>

                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>بحث بنوع صرف الراتب</label>
                        <select name="sal_cach_or_visa_search" id="sal_cach_or_visa_search" class="form-control">
                            <option value="all">بحث بالكل</option>
                            <option value="1">كاش</option>
                            <option value="2">فيزا</option>
                        </select>

                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>بحث بنوع الجنس</label>
                        <select name="emp_gender_search" id="emp_gender_search" class="form-control">
                            <option value="all">بحث بالكل</option>
                            <option value="1">ذكر</option>
                            <option value="2">انثى</option>
                        </select>

                    </div>
                </div>

            </div>


            <div class="card-body" id="ajax_responce_searchDiv">

                @if (@isset($Employees) and !@empty($Employees) and count($Employees) > 0)
                    <table id="example2" class="table table-bordered table-hover">

                        <thead class="custom_thead">
                            <th>كود</th>
                            <th>اسم الموظف</th>
                            <th>الفرع</th>
                            <th>الادارة</th>
                            <th>الوظيفة</th>
                            <th>الحالة الوظيفية</th>
                            <th>الصورة الشخصية</th>
                            <th>العمليات</th>
                        </thead>
                        <tbody>
                            @foreach ($Employees as $info)
                                <tr>
                                    <td> {{ $info->employees_code }} </td>
                                    <td> {{ $info->emp_name }} </td>
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
                                        @if ($info->function_status == 1)
                                            بالخدمة
                                        @else
                                            خارج الخدمة
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

                                        <a class="btn btn-sm btn-success"
                                            href="{{ route('Employees.edit', $info->id) }}">تعديل</a>
                                        <a class="btn btn-sm btn-info"
                                            href="{{ route('Employees.show', $info->id) }}">المزيد</a>
                                        <a class="btn btn-sm btn-danger are_you_shur"
                                            href="{{ route('Employees.destroy', $info->id) }}">حذف</a>

                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <br>
                    <div class="col-md-12">
                        {{ $Employees->links('pagination::bootstrap-5') }}
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

            $(document).on('input', '#searchbycode', function(e) {
                ajax_search();
            });

            $(document).on('input', '#emp_name_search', function(e) {
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

            $(document).on('change', '#emp_gender_search', function(e) {
                ajax_search();
            });

            $('input[type=radio][name=code_search]').change(function(e) {
                ajax_search();
            });

            function ajax_search() {
                var searchbycode = $("#searchbycode").val();
                var emp_name_search = $("#emp_name_search").val();
                var branch_id_search = $("#branch_id_search").val();
                var emp_department_id_search = $("#emp_department_id_search").val();
                var emp_jobs_id_search = $("#emp_jobs_id_search").val();
                var function_status_search = $("#function_status_search").val();
                var sal_cach_or_visa_search = $("#sal_cach_or_visa_search").val();
                var emp_gender_search = $("#emp_gender_search").val();
                var search_btn_radio = $('input[type=radio][name=code_search]:checked').val();
                
                jQuery.ajax({
                    url: '{{ route('Employees.ajaxSearch') }}',
                    type: 'post',
                    dataType: 'html',
                    cache: false,
                    data: {
                        "_token": '{{ csrf_token() }}',
                        searchbycode: searchbycode,
                        emp_name_search: emp_name_search,
                        branch_id_search: branch_id_search,
                        emp_department_id_search: emp_department_id_search,
                        emp_jobs_id_search: emp_jobs_id_search,
                        function_status_search: function_status_search,
                        sal_cach_or_visa_search: sal_cach_or_visa_search,
                        emp_gender_search: emp_gender_search,
                        search_btn_radio:search_btn_radio,

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
                    var searchbycode = $("#searchbycode").val();
                    var emp_name_search = $("#emp_name_search").val();
                    var branch_id_search = $("#branch_id_search").val();
                    var emp_department_id_search = $("#emp_department_id_search").val();
                    var emp_jobs_id_search = $("#emp_jobs_id_search").val();
                    var function_status_search = $("#function_status_search").val();
                    var sal_cach_or_visa_search = $("#sal_cach_or_visa_search").val();
                    var emp_gender_search = $("#emp_gender_search").val();
                    var search_btn_radio = $('input[type=radio][name=code_search]:checked').val();

                    jQuery.ajax({
                        url: linkurl,
                        type: 'post',
                        dataType: 'html',
                        cache: false,
                        data: {
                            "_token": '{{ csrf_token() }}',
                            searchbycode: searchbycode,
                            emp_name_search: emp_name_search,
                            branch_id_search: branch_id_search,
                            emp_department_id_search: emp_department_id_search,
                            emp_jobs_id_search: emp_jobs_id_search,
                            function_status_search: function_status_search,
                            sal_cach_or_visa_search: sal_cach_or_visa_search,
                            emp_gender_search: emp_gender_search,
                            search_btn_radio:search_btn_radio,
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
