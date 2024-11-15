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
                <h3 class="card-title card_title_center">بيانات رصيد السنوي لاجازات الموظفين
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
                        <label>بحث بحالة تفعيل الرصيد</label>
                        <select name="is_active_for_vaccation_search" id="is_active_for_vaccation_search" class="form-control">
                            <option value="all">بحث بالكل</option>
                            <option value="1">مفعل له الرصيد</option>
                            <option value="0">غير مفعل له الرصيد</option>
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

                @if (@isset($data) and !@empty($data) and count($data) > 0)
                    <table id="example2" class="table table-bordered table-hover">

                        <thead class="custom_thead">
                            <th>كود</th>
                            <th style="width: 22%">اسم الموظف</th>
                            <th>الفرع</th>
                            <th style="width: 15%">الادارة</th>
                            <th>الوظيفة</th>
                            <th>نزول الرصيد</th>
                            <th>الصورة</th>
                            <th>الرصيد</th>
                        </thead>
                        <tbody>
                            @foreach ($data as $info)
                                <tr>
                                    <td> {{ $info->employees_code }} </td>
                                    <td> {{ $info->emp_name }} 
                                        <br>
                                     <span style="color: brown;">
                                        @if ($info->function_status == 1)
                                        بالخدمة
                                        @else
                                        خارج الخدمة
                                        @endif
                                     </span>
                                    </td>
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
                                        @if ($info->is_active_for_vaccation == 1)
                                            نعم
                                        @else
                                          ليس بعد
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

                                      
                                        <a class="btn btn-sm btn-info"
                                            href="{{ route('EmployeeVacationsBalance.show', $info->id) }}">عرض</a>
                                           
                                        
                                            
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

            $(document).on('change', '#is_active_for_vaccation_search', function(e) {
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
                var is_active_for_vaccation_search = $("#is_active_for_vaccation_search").val();
                var emp_gender_search = $("#emp_gender_search").val();
                var search_btn_radio = $('input[type=radio][name=code_search]:checked').val();
                
                jQuery.ajax({
                    url: '{{ route('EmployeeVacationsBalance.ajaxSearch') }}',
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
                        is_active_for_vaccation_search: is_active_for_vaccation_search,
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
                    var is_active_for_vaccation_search = $("#is_active_for_vaccation_search").val();
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
                            is_active_for_vaccation_search: is_active_for_vaccation_search,
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
