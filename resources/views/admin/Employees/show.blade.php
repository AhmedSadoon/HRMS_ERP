@extends('layouts.admin')

@section('title')
    بيانات الموظفين
@endsection

@section('contentheader')
    قائمة شؤون الموظفين
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
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
                <h3 class="card-title card_title_center">عرض تفاصيل بيانات الموظف
                    <a class="btn btn-sm btn-success" href="{{ route('Employees.edit', $data['id']) }}">تعديل</a>
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
                                    aria-controls="custom-content-personal_data" aria-selected="true">بيانات شخصية</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link @if (Session::has('tabfiles')) active @endif" id="jobs_data"
                                    data-toggle="pill" href="#custom-content-jobs_data" role="tab"
                                    aria-controls="custom-content-jobs_data" aria-selected="false">بيانات
                                    وظيفية</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link @if (Session::has('tabfiles')) active @endif" id="additional_data"
                                    data-toggle="pill" href="#custom-content-additional_data" role="tab"
                                    aria-controls="custom-content-additional_data" aria-selected="false">بيانات
                                    اضافية</a>
                            </li>

                        </ul>

                        <div class="tab-content" id="custom-content-below-tabContent">

                            {{-- بداية البيانات الشخصية --}}
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
                                            <label>نوع الجنس </label>
                                            <select disabled name="emp_gender" id="emp_gender" class="form-control">
                                                <option value="">اختر الجنس</option>
                                                <option @if ($data['emp_gender'] == 1) selected @endif value="1">ذكر
                                                </option>
                                                <option @if ($data['emp_gender'] == 2 and $data['emp_gender'] != '') selected @endif value="2">
                                                    انثى</option>
                                            </select>

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
                                            <label>المؤهل الدراسي </label>
                                            <select disabled name="qualifications_id" id="qualifications_id"
                                                class="form-control select2 ">
                                                <option value="">اختر المؤهل</option>
                                                @if (@isset($other['qualifications']) && !@empty($other['qualifications']))
                                                    @foreach ($other['qualifications'] as $info)
                                                        <option @if ($data['qualifications_id'] == $info->id) selected="selected" @endif
                                                            value="{{ $info->id }}"> {{ $info->name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>

                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>سنة التخرج </label>
                                            <input disabled autofocus type="text" name="qualifications_year"
                                                id="qualifications_year" class="form-control"
                                                value="{{ $data['qualifications_year'] }}">

                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>تقدير التخرج </label>
                                            <select disabled name="graduation_estimate" id="graduation_estimate"
                                                class="form-control">
                                                <option @if ($data['graduation_estimate'] == 1) selected @endif value="1">
                                                    مقبول</option>
                                                <option @if ($data['graduation_estimate'] == 2 and $data['graduation_estimate'] != '') selected @endif value="2">
                                                    متوسط</option>
                                                <option @if ($data['graduation_estimate'] == 3 and $data['graduation_estimate'] != '') selected @endif value="3">
                                                    جيد</option>
                                                <option @if ($data['graduation_estimate'] == 4 and $data['graduation_estimate'] != '') selected @endif value="4">
                                                    جيد جدا</option>

                                                <option @if ($data['graduation_estimate'] == 5 and $data['graduation_estimate'] != '') selected @endif value="5">
                                                    امتياز</option>
                                            </select>

                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>تخصص التخرج </label>
                                            <input disabled autofocus type="text" name="graduation_specialization"
                                                id="graduation_specialization" class="form-control"
                                                value="{{ $data['graduation_specialization'] }}">

                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>تاريخ الميلاد </label>
                                            <input disabled autofocus type="date" name="brith_date" id="brith_date"
                                                class="form-control" value="{{ $data['brith_date'] }}">

                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>رقم بطاقة الشخصية للموظف </label>
                                            <input disabled autofocus type="text" name="emp_national_identity"
                                                id="emp_national_identity" class="form-control"
                                                value="{{ $data['emp_national_identity'] }}">

                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>تاريخ نفاذ بطاقة الشخصية للموظف </label>
                                            <input disabled autofocus type="date" name="emp_endDate_identityID"
                                                id="emp_endDate_identityID" class="form-control"
                                                value="{{ $data['emp_endDate_identityID'] }}">

                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>مركز اصدار البطاقة الشخصية </label>
                                            <input disabled autofocus type="text" name="emp_idenity_place"
                                                id="emp_idenity_place" class="form-control"
                                                value="{{ $data['emp_idenity_place'] }}">

                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>فصيلة الدم</label>
                                            <select disabled name="blood_group_id" id="blood_group_id"
                                                class="form-control select2 ">
                                                <option value="">اختر الفصيلة</option>
                                                @if (@isset($other['blood_groups']) && !@empty($other['blood_groups']))
                                                    @foreach ($other['blood_groups'] as $info)
                                                        <option
                                                            @if ($data['blood_groups_id'] == $info->id) selected="selected" @endif
                                                            value="{{ $info->id }}"> {{ $info->name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>

                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>الجنسية </label>
                                            <select disabled name="emp_nationalitie_id" id="emp_nationalitie_id"
                                                class="form-control select2 ">
                                                <option value="">اختر الجنسية</option>
                                                @if (@isset($other['nationalities']) && !@empty($other['nationalities']))
                                                    @foreach ($other['nationalities'] as $info)
                                                        <option
                                                            @if ($data['emp_nationalitie_id'] == $info->id) selected="selected" @endif
                                                            value="{{ $info->id }}"> {{ $info->name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>

                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>اللغة الاساسية للموظف </label>
                                            <select disabled name="emp_lang_id" id="emp_lang_id"
                                                class="form-control select2 ">
                                                <option value="">اختر اللغة</option>
                                                @if (@isset($other['languages']) && !@empty($other['languages']))
                                                    @foreach ($other['languages'] as $info)
                                                        <option
                                                            @if ($data['emp_lang_id'] == $info->id) selected="selected" @endif
                                                            value="{{ $info->id }}"> {{ $info->name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>

                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>الديانة </label>
                                            <select disabled name="religion_id" id="religion_id"
                                                class="form-control select2 ">
                                                <option value="">اختر الديانة</option>
                                                @if (@isset($other['religions']) && !@empty($other['religions']))
                                                    @foreach ($other['religions'] as $info)
                                                        <option
                                                            @if ($data['religion_id'] == $info->id) selected="selected" @endif
                                                            value="{{ $info->id }}"> {{ $info->name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>

                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>البريد الالكتروني</label>
                                            <input disabled autofocus type="text" name="emp_email" id="emp_email"
                                                class="form-control" value="{{ $data['emp_email'] }}">

                                        </div>
                                    </div>



                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>الدول </label>
                                            <select disabled name="country_id" id="country_id"
                                                class="form-control select2 ">
                                                <option value="">اختر الدولة التابع لها الموظف</option>
                                                @if (@isset($other['countries']) && !@empty($other['countries']))
                                                    @foreach ($other['countries'] as $info)
                                                        <option
                                                            @if ($data['country_id'] == $info->id) selected="selected" @endif
                                                            value="{{ $info->id }}"> {{ $info->name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>

                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group" id="governorate_Div">
                                            <label>المحافظات</label>
                                            <select disabled name="governorate_id" id="governorate_id"
                                                class="form-control select2 ">
                                                <option value="">اختر المحافظة التابع لها الموظف</option>
                                                @if (@isset($other['governorates']) && !@empty($other['governorates']))
                                                    @foreach ($other['governorates'] as $info)
                                                        <option
                                                            @if ($data['governorate_id'] == $info->id) selected="selected" @endif
                                                            value="{{ $info->id }}">
                                                            {{ $info->name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>

                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group" id="center_Div">
                                            <label>المدينة</label>
                                            <select disabled name="city_id" id="city_id" class="form-control select2 ">
                                                <option value="">اختر المدينة التابع لها الموظف</option>
                                                @if (@isset($other['centers']) && !@empty($other['centers']))
                                                    @foreach ($other['centers'] as $info)
                                                        <option
                                                            @if ($data['city_id'] == $info->id) selected="selected" @endif
                                                            value="{{ $info->id }}"> {{ $info->name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>

                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>عنوان الاقامة الحالي للموظف </label>
                                            <input disabled autofocus type="text" name="states_address"
                                                id="states_address" class="form-control"
                                                value="{{ $data['states_address'] }}">

                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>هاتف المنزل </label>
                                            <input disabled autofocus type="text" name="emp_home_tel"
                                                id="emp_home_tel" class="form-control"
                                                value="{{ $data['emp_home_tel'] }}">

                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>هاتف العمل </label>
                                            <input disabled autofocus type="text" name="emp_work_tel"
                                                id="emp_work_tel" class="form-control"
                                                value="{{ $data['emp_work_tel'] }}">

                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>الحالة العسكرية </label>
                                            <select disabled name="emp_military_status_id" id="emp_military_status_id"
                                                class="form-control">
                                                <option value="">اختر الحالة</option>
                                                @if (@isset($other['military_statuses']) && !@empty($other['military_statuses']))
                                                    @foreach ($other['military_statuses'] as $info)
                                                        <option
                                                            @if ($data['emp_military_status_id'] == $info->id) selected="selected" @endif
                                                            value="{{ $info->id }}"> {{ $info->name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>

                                        </div>
                                    </div>

                                    <div class="col-md-4 related_military_1"
                                        @if ($data['emp_military_status_id'] != 1) style="display: none;" @endif>
                                        <div class="form-group">
                                            <label>تاريخ بداية الخدمة العسكرية</label>
                                            <input disabled autofocus type="date" name="emp_military_date_from"
                                                id="emp_military_date_from" class="form-control"
                                                value="{{ $data['emp_military_date_from'] }}">

                                        </div>
                                    </div>

                                    <div class="col-md-4 related_military_1"
                                        @if ($data['emp_military_status_id'] != 1) style="display: none;" @endif>
                                        <div class="form-group">
                                            <label>تاريخ نهاية الخدمة العسكرية </label>
                                            <input disabled autofocus type="date" name="emp_military_date_to"
                                                id="emp_military_date_to" class="form-control"
                                                value="{{ $data['emp_military_date_to'] }}">

                                        </div>
                                    </div>

                                    <div class="col-md-4 related_military_1"
                                        @if ($data['emp_military_status_id'] != 1) style="display: none;" @endif>
                                        <div class="form-group">
                                            <label>سلاح الخدمة العسكرية </label>
                                            <input disabled autofocus type="text" name="emp_military_wepon"
                                                id="emp_military_wepon" class="form-control"
                                                value="{{ $data['emp_military_wepon'] }}">

                                        </div>
                                    </div>

                                    <div class="col-md-4 related_military_2"
                                        @if ($data['emp_military_status_id'] != 2) style="display: none;" @endif>
                                        <div class="form-group">
                                            <label>تاريخ الاعفاء من الخدمة العسكرية</label>
                                            <input disabled autofocus type="date" name="exemption_date"
                                                id="exemption_date" class="form-control"
                                                value="{{ $data['exemption_date'] }}">

                                        </div>
                                    </div>

                                    <div class="col-md-4 related_military_2"
                                        @if ($data['emp_military_status_id'] != 2) style="display: none;" @endif>
                                        <div class="form-group">
                                            <label>سبب الاعفاء من الخدمة العسكرية </label>
                                            <input disabled autofocus type="text" name="exemption_reason"
                                                id="exemption_reason" class="form-control"
                                                value="{{ $data['exemption_reason'] }}">

                                        </div>
                                    </div>

                                    <div class="col-md-4 related_military_3"
                                        @if ($data['emp_military_status_id'] != 3) style="display: none;" @endif>
                                        <div class="form-group">
                                            <label>سبب ومدة تأجيل الخدمة العسكرية </label>
                                            <input disabled autofocus type="text" name="postponement_reason"
                                                id="postponement_reason" class="form-control"
                                                value="{{ $data['postponement_reason'] }}">

                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>هل يمتلك رخصة قيادة </label>
                                            <select disabled name="does_has_driving_license" id="does_has_driving_license"
                                                class="form-control">
                                                <option value="">اختر الحالة</option>
                                                <option @if ($data['does_has_driving_license'] == 1) selected @endif value="1">
                                                    نعم</option>
                                                <option @if ($data['does_has_driving_license'] == 0 and $data['does_has_driving_license'] != '') selected @endif value="0">
                                                    لا</option>

                                            </select>

                                        </div>
                                    </div>

                                    <div class="col-md-4 related_does_has_driving_license"
                                        @if ($data['does_has_driving_license'] != 1) style="display: none;" @endif>
                                        <div class="form-group">
                                            <label>رقم رخصة القيادة </label>
                                            <input disabled autofocus type="text" name="driving_license_degree"
                                                id="driving_license_degree" class="form-control"
                                                value="{{ $data['driving_license_degree'] }}">

                                        </div>
                                    </div>

                                    <div class="col-md-4 related_does_has_driving_license"
                                        @if ($data['does_has_driving_license'] != 1) style="display: none;" @endif>
                                        <div class="form-group">
                                            <label>اختر نوع الرخصة </label>
                                            <select disabled name="driving_license_types_id" id="driving_license_types_id"
                                                class="form-control">
                                                <option value="">اختر الحالة</option>
                                                @if (@isset($other['driving_license_types']) && !@empty($other['driving_license_types']))
                                                    @foreach ($other['driving_license_types'] as $info)
                                                        <option
                                                            @if ($data['driving_license_types_id'] == $info->id) selected="selected" @endif
                                                            value="{{ $info->id }}"> {{ $info->name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>

                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>هل يمتلك اقارب في العمل </label>
                                            <select disabled name="has_relatives" id="has_relatives"
                                                class="form-control">
                                                <option value="">اختر الحالة</option>
                                                <option @if ($data['has_relatives'] == 1) selected @endif value="1">
                                                    نعم</option>
                                                <option @if ($data['has_relatives'] == 0 and $data['has_relatives'] != '') selected @endif value="0">
                                                    لا</option>

                                            </select>

                                        </div>
                                    </div>

                                    <div class="col-md-12 related_relatives_details"
                                        @if ($data['has_relatives'] != 1) style="display: none;" @endif>
                                        <div class="form-group">
                                            <label>تفاصيل الاقارب </label>
                                            <textarea disabled autofocus type="text" name="relatives_details" id="relatives_details" class="form-control">{{ $data['relatives_details'] }}</textarea>

                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>هل يمتلك اعاقة / عمليات سابقة </label>
                                            <select disabled name="is_disabilities_processes"
                                                id="is_disabilities_processes" class="form-control">
                                                <option value="">اختر الحالة</option>
                                                <option @if ($data['is_disabilities_processes'] == 1) selected @endif value="1">
                                                    نعم</option>
                                                <option @if ($data['is_disabilities_processes'] == 0 and $data['is_disabilities_processes'] != '') selected @endif value="0">
                                                    لا</option>

                                            </select>

                                        </div>
                                    </div>

                                    <div class="col-md-8 related_is_disabilities_processes"
                                        @if ($data['is_disabilities_processes'] != 1) style="display: none;" @endif>
                                        <div class="form-group">
                                            <label>تفاصيل الاعاقة </label>
                                            <textarea disabled autofocus type="text" name="disabilities_processes" id="disabilities_processes"
                                                class="form-control">{{ $data['disabilities_processes'] }}</textarea>

                                        </div>
                                    </div>

                                    <div class="col-md-12 ">
                                        <div class="form-group">
                                            <label>ملاحظات</label>
                                            <textarea disabled autofocus type="text" name="notes" id="notes" class="form-control">{{ $data['notes'] }}</textarea>

                                        </div>
                                    </div>


                                </div>
                                {{-- نهاية الصف --}}




                            </div>
                            {{-- نهاية البيانات الشخصية --}}


                            {{-- بداية البيانات الوظيفية --}}

                            <div class="tab-pane fade @if (!Session::has('tabfiles')) show active @endif"
                                id="custom-content-jobs_data" role="tabpanel" aria-labelledby="jobs_data">
                                {{-- بداية الصف --}}
                                <div class="row">
                                    <br>
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
                                                        <option
                                                            @if ($data['emp_department_id'] == $info->id) selected="selected" @endif
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
                                                        <option
                                                            @if ($data['emp_jobs_id'] == $info->id) selected="selected" @endif
                                                            value="{{ $info->id }}"> {{ $info->name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>

                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>هل ملزم بتسجيل حضور وانصراف </label>
                                            <select disabled name="does_has_ateendance" id="does_has_ateendance"
                                                class="form-control">
                                                <option @if ($data['does_has_ateendance'] == 1) selected @endif value="1">
                                                    نعم</option>
                                                <option @if ($data['does_has_ateendance'] == 0 and $data['does_has_ateendance'] != '') selected @endif value="0">
                                                    لا</option>

                                            </select>

                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>هل له شفت ثابت </label>
                                            <select disabled name="is_has_fixced_shift" id="is_has_fixced_shift"
                                                class="form-control">
                                                <option value="">اختر الشفت</option>
                                                <option @if ($data['is_has_fixced_shift'] == 1) selected @endif value="1">
                                                    نعم</option>
                                                <option @if ($data['is_has_fixced_shift'] == 0 and $data['is_has_fixced_shift'] != '') selected @endif value="0">
                                                    لا</option>

                                            </select>

                                        </div>
                                    </div>

                                    <div class="col-md-4 relatedfixced_shift"
                                        @if (old('is_has_fixced_shift', $data['is_has_fixced_shift']) == 0 ||
                                                old('is_has_fixced_shift', $data['is_has_fixced_shift']) == '') style="display: none;" @endif>
                                        <div class="form-group">
                                            <label>أنواع الشفتات </label>
                                            <select disabled name="shift_type_id" id="shift_type_id"
                                                class="form-control select2 ">
                                                <option value="">اختر الشفت</option>
                                                @if (@isset($other['shift_types']) && !@empty($other['shift_types']))
                                                    @foreach ($other['shift_types'] as $info)
                                                        <option
                                                            @if (old('shift_type_id', $data['shift_type_id']) == $info->id) selected="selected" @endif
                                                            value="{{ $info->id }}">

                                                            @if ($info->type == 1)
                                                                صباحي
                                                            @elseif ($info->type == 2)
                                                                مسائي
                                                            @else
                                                                يوم كامل
                                                            @endif
                                                            من
                                                            @php
                                                                $dt = new DateTime($info->from_time);
                                                                $time = $dt->format('h:i');
                                                                $newDateTime = date('A', strtotime($info->from_time));
                                                                $newDateTimeType =
                                                                    $newDateTime == 'AM' ? 'صباحا ' : 'مساء';
                                                            @endphp

                                                            {{ $time }}
                                                            {{ $newDateTimeType }}
                                                            الي
                                                            @php
                                                                $dt = new DateTime($info->to_time);
                                                                $time = $dt->format('h:i');
                                                                $newDateTime = date('A', strtotime($info->to_time));
                                                                $newDateTimeType =
                                                                    $newDateTime == 'AM' ? 'صباحا ' : 'مساء';
                                                            @endphp

                                                            {{ $time }}
                                                            {{ $newDateTimeType }}
                                                            عدد
                                                            {{ $info->total_huor * 1 }} ساعات




                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>

                                        </div>
                                    </div>

                                    <div class="col-md-4 daily_work_hourDIV"
                                        @if ($data['is_has_fixced_shift'] == 1 || $data['is_has_fixced_shift'] == '') style="display: none;" @endif>
                                        <div class="form-group">
                                            <label>عدد ساعات العمل اليومي </label>
                                            <input disabled autofocus type="text" name="daily_work_hour"
                                                id="daily_work_hour"
                                                oninput="this.value=this.value.replace(/[^0-9.]/g,'');"
                                                class="form-control" value="{{ $data['daily_work_hour'] }}">

                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>راتب الموظف الشهري </label>
                                            <input disabled autofocus type="text" name="emp_salary" id="emp_salary"
                                                oninput="this.value=this.value.replace(/[^0-9.]/g,'');"
                                                class="form-control"
                                                value="{{ old('emp_salary', $data['emp_salary'] * 1) }}">

                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>هل له حافز </label>
                                            <select disabled name="motivation_type" id="motivation_type"
                                                class="form-control">
                                                <option value="">اختر الحافز</option>
                                                <option @if ($data['motivation_type'] == 1) selected @endif value="1">
                                                    ثابت</option>
                                                <option @if ($data['motivation_type'] == 2) selected @endif value="2">
                                                    متغير</option>
                                                <option @if ($data['motivation_type'] == 0 and $data['motivation_type'] != '') selected @endif value="0">
                                                    لا يوجد</option>

                                            </select>

                                        </div>
                                    </div>

                                    <div class="col-md-4 motivationDiv"
                                        @if ($data['motivation_type'] != 1) style="display: none;" @endif>
                                        <div class="form-group">
                                            <label>قيمت الحافز الشهري الثابت </label>
                                            <input disabled autofocus type="text" name="motivation" id="motivation"
                                                oninput="this.value=this.value.replace(/[^0-9.]/g,'');"
                                                class="form-control" value="{{ $data['motivation'] }}">

                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>هل له تأمين اجتماعي </label>
                                            <select disabled name="is_social_nsurance" id="is_social_nsurance"
                                                class="form-control">
                                                <option value="">اختر الحالة</option>
                                                <option @if ($data['is_social_nsurance'] == 1) selected @endif value="1">
                                                    نعم</option>
                                                <option @if ($data['is_social_nsurance'] == 0 and $data['is_social_nsurance'] != '') selected @endif value="0">
                                                    لا</option>

                                            </select>

                                        </div>
                                    </div>

                                    <div class="col-md-4 relatedsocial_nsurance"
                                        @if ($data['is_social_nsurance'] != 1) style="display: none;" @endif>
                                        <div class="form-group">
                                            <label>قيمت التأمين الاجتماعي </label>
                                            <input disabled autofocus type="text" name="social_nsurance_cutMonthely"
                                                id="social_nsurance_cutMonthely"
                                                oninput="this.value=this.value.replace(/[^0-9.]/g,'');"
                                                class="form-control" value="{{ $data['social_nsurance_cutMonthely'] }}">

                                        </div>
                                    </div>

                                    <div class="col-md-4 relatedsocial_nsurance"
                                        @if ($data['is_social_nsurance'] != 1) style="display: none;" @endif>
                                        <div class="form-group">
                                            <label>رقم التأمين الاجتماعي للموظف </label>
                                            <input disabled autofocus type="text" name="social_nsurance_number"
                                                id="social_nsurance_number" class="form-control"
                                                value="{{ $data['social_nsurance_number'] }}">

                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>هل له تأمين طبي </label>
                                            <select disabled name="is_medical_nsurance" id="is_medical_nsurance"
                                                class="form-control">
                                                <option value="">اختر الحالة</option>
                                                <option @if ($data['is_medical_nsurance'] == 1) selected @endif value="1">
                                                    نعم</option>
                                                <option @if ($data['is_medical_nsurance'] == 0 and $data['is_medical_nsurance'] != '') selected @endif value="0">
                                                    لا</option>

                                            </select>

                                        </div>
                                    </div>

                                    <div class="col-md-4 relatedmedical_nsurance"
                                        @if ($data['is_medical_nsurance'] != 1) style="display: none;" @endif>
                                        <div class="form-group">
                                            <label>قيمت التأمين طبي </label>
                                            <input disabled autofocus type="text" name="medical_nsurance_cutMonthely"
                                                id="medical_nsurance_cutMonthely"
                                                oninput="this.value=this.value.replace(/[^0-9.]/g,'');"
                                                class="form-control" value="{{ $data['medical_nsurance_cutMonthely'] }}">

                                        </div>
                                    </div>

                                    <div class="col-md-4 relatedmedical_nsurance"
                                        @if ($data['is_medical_nsurance'] != 1) style="display: none;" @endif>
                                        <div class="form-group">
                                            <label>رقم التأمين طبي للموظف </label>
                                            <input disabled autofocus type="text" name="medical_nsurance_number"
                                                id="medical_nsurance_number" class="form-control"
                                                value="{{ $data['medical_nsurance_number'] }}">

                                        </div>
                                    </div>



                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>نوع صرف راتب الموظف </label>
                                            <select disabled name="sal_cach_or_visa" id="sal_cach_or_visa"
                                                class="form-control">
                                                <option value="">اختر الحالة</option>
                                                <option @if ($data['sal_cach_or_visa'] == 1) selected @endif value="1">
                                                    كاش</option>
                                                <option @if ($data['sal_cach_or_visa'] == 2) selected @endif value="2">
                                                    فيزا</option>


                                            </select>

                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>هل له رصيد اجازات سنوي </label>
                                            <select disabled name="is_active_for_vaccation" id="is_active_for_vaccation"
                                                class="form-control">
                                                <option value="">اختر الحالة</option>
                                                <option @if ($data['is_active_for_vaccation'] == 1) selected @endif value="1">
                                                    نعم</option>
                                                <option @if ($data['is_active_for_vaccation'] == 0 and $data['is_active_for_vaccation'] != '') selected @endif value="0">
                                                    لا</option>


                                            </select>

                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>شخص يمكن الرجوع اليه للضرورة
                                            </label>
                                            <input disabled autofocus type="text" name="urgent_person_details"
                                                id="urgent_person_details" class="form-control"
                                                value="{{ $data['urgent_person_details'] }}">

                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>هل له بدلات ثابتة </label>
                                            <select disabled name="does_have_fixed_allowances"
                                                id="does_have_fixed_allowances" class="form-control">
                                                <option value="">اختر الحالة</option>
                                                <option @if ($data['does_have_fixed_allowances'] == 1) selected @endif value="1">
                                                    نعم</option>
                                                <option @if ($data['does_have_fixed_allowances'] == 0 and $data['does_have_fixed_allowances'] != '') selected @endif value="0">
                                                    لا</option>

                                            </select>

                                        </div>
                                    </div>
                                    @if ($data['does_have_fixed_allowances'] == 1)
                                        <div class="col-md-12">
                                            <hr>
                                            <h3
                                                style="width: 100%; font-size:17px; font-weight: bold; text-align:center; !important;">
                                                البدلات الثابتة المضافة للموظف

                                            </h3>

                                            <button style="margin: 4px" id="load_add_allowances_modal"
                                                data-toggle="modal" data-target="#add_allowances_modal"
                                                class="btn btn-sm btn-success">اضافة بدل للموظف<i
                                                    class="fa fa-arrow-up"></i></button>

                                            {{-- جدول عرض المرفقات --}}
                                            @if (
                                                @isset($other['employee_fixed_suits']) and
                                                    !@empty($other['employee_fixed_suits']) and
                                                    count($other['employee_fixed_suits']) > 0)
                                                <table id="example2" class="table table-bordered table-hover">

                                                    <thead class="custom_thead">
                                                        <th>اسم البدل</th>
                                                        <th>قيمة البدل</th>
                                                        <th>تاريخ الاضافة</th>
                                                        <th>تاريخ التحديث</th>
                                                        <th>العمليات</th>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($other['employee_fixed_suits'] as $info)
                                                            <tr>
                                                                <td>{{$info->allowances->name}} </td>
                                                                <td> {{$info->value*1}}</td>
                                                                
                                                                <td>{{$info->added->name}}</td>

                                                                <td>
                                                                    @if ($info->updatedBy > '0')
                                                                    {{$info->updatedBy->name}}
                                                                    @else
                                                                    لايوجد
                                                                    @endif
                                                                </td>


                                                                <td>
                                                                    <a class="btn btn-sm btn-danger are_you_shur"
                                                                        href="{{ route('Employees.destroy_allowances', $info->id) }}">حذف</a>
                                                                    <button data-id="{{$info->id}}" class="btn btn-sm btn-success load_edit_allowances">تعديل </button>


                                                                </td>

                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            @else
                                                <p class="bg-danger text-center">عفواً لا توجد بيانات لعرضها</p>
                                            @endif
                                            {{-- نهاية الجدول --}}

                                        </div>
                                    @endif

                                </div>
                                {{-- نهاية الصف --}}

                            </div>
                            {{-- نهاية البيانات الوظيفية --}}

                            {{-- بداية البيانات الاضافية --}}
                            <div class="tab-pane fade @if (Session::has('tabfiles')) show active @endif"
                                id="custom-content-additional_data" role="tabpanel" aria-labelledby="additional_data">
                                {{-- بداية الصف --}}
                                <div class="row">

                                    <br>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>اسم الكفيل</label>
                                            <input disabled autofocus type="text" name="emp_cafel" id="emp_cafel"
                                                class="form-control" value="{{ $data['emp_cafel'] }}">

                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>رقم جواز السفر</label>
                                            <input disabled autofocus type="text" name="emp_pasport_no"
                                                id="emp_pasport_no" class="form-control"
                                                value="{{ $data['emp_pasport_no'] }}">

                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>جهة اصدار جواز السفر</label>
                                            <input disabled autofocus type="text" name="emp_pasport_from"
                                                id="emp_pasport_from" class="form-control"
                                                value="{{ $data['emp_pasport_from'] }}">

                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>تاريخ انتهاء جواز السفر</label>
                                            <input disabled autofocus type="date" name="emp_pasport_exp"
                                                id="emp_pasport_exp" class="form-control"
                                                value="{{ $data['emp_pasport_exp'] }}">

                                        </div>
                                    </div>

                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label>عنوان اقامة الموظف قي بلده الام</label>
                                            <input disabled autofocus type="text" name="emp_Basic_stay_com"
                                                id="emp_Basic_stay_com" class="form-control"
                                                value="{{ $data['emp_Basic_stay_com'] }}">

                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>الصورة الشخصية للموظف</label>

                                            @if (!@empty($data['emp_photo']))
                                                <img src="{{ asset('assets/admin/uploads') . '/' . $data['emp_photo'] }}"
                                                    style="border-radius: 50%; width: 80px; height: 80px;"
                                                    class="rounded-circle" alt="صورة الموظف">
                                                <br>
                                                <a class="btn btn-sm btn-success"
                                                    href="{{ route('Employees.download', ['id' => $data['id'], 'field_name' => 'emp_photo']) }}">تحميل
                                                    <span class="fa fa-download"></span></a>
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

                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>السيرة الذاتية للموظف</label>

                                            @if (!@empty($data['emp_cv']))
                                                <br>
                                                <a class="btn btn-sm btn-success"
                                                    href="{{ route('Employees.download', ['id' => $data['id'], 'field_name' => 'emp_cv']) }}">تحميل
                                                    <span class="fa fa-download"></span></a>
                                            @else
                                                لم يتم الارفاق
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <hr>
                                        <h3 class="card-title"
                                            style="width: 100%; font-size:17px; font-weight: bold; text-align:center; !important;">
                                            الملفات المرفقة للموظف
                                        </h3>

                                        {{-- جدول عرض المرفقات --}}
                                        @if (@isset($other['employees_files']) and !@empty($other['employees_files']) and count($other['employees_files']) > 0)
                                            <table id="example2" class="table table-bordered table-hover">

                                                <thead class="custom_thead">
                                                    <th>الاسم</th>
                                                    <th>الملف</th>
                                                    <th>العمليات</th>
                                                </thead>
                                                <tbody>
                                                    @foreach ($other['employees_files'] as $info)
                                                        <tr>
                                                            <td> {{ $info->name }} </td>


                                                            <td>
                                                                @if (!@empty($info->file_path))
                                                                    <img src="{{ asset('assets/admin/uploads') . '/' . $info->file_path }}"
                                                                        style="border-radius: 50%; width: 80px; height: 80px;"
                                                                        class="rounded-circle" alt="الملف">
                                                                @else
                                                                    لايوجد
                                                                @endif
                                                            </td>



                                                            <td>


                                                                <a class="btn btn-sm btn-danger"
                                                                    href="{{ route('Employees.destroy_file', $info->id) }}">حذف</a>
                                                                <a class="btn btn-sm btn-success"
                                                                    href="{{ route('Employees.download_files', ['id' => $info->id]) }}">تحميل
                                                                    <span class="fa fa-download"></span></a>


                                                            </td>

                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @else
                                            <p class="bg-danger text-center">عفواً لا توجد بيانات لعرضها</p>
                                        @endif
                                        {{-- نهاية الجدول --}}
                                        <button id="load_add_file_modal" data-toggle="modal"
                                            data-target="#add_file_modal" class="btn btn-sm btn-success">ارفاق ملف جديد <i
                                                class="fa fa-arrow-up"></i></button>
                                    </div>

                                </div>
                                {{-- نهاية الصف --}}


                            </div>

                            {{-- نهاية البيانات الاضافية --}}

                        </div>

                    </div>
                    <!-- /.card -->
                </div>


            </div>
        </div>
    </div>


    <div class="modal fade" id="add_file_modal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content bg-info">
                <div class="modal-header">
                    <h4 class="modal-title">اضافة بدلات الثابتة للموظف</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body custom_body_modal">
                    <form action="{{ route('Employees.add_files', $data['id']) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>اسم الملف <span style="color: red">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        value="" required oninvalid="setCustomValidity('من فضلك ادخل هذا الحقل')"
                                        onchange="try{setCustomValidity('')}catch(e){}">

                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>اختر الملف</label>
                                    <input autofocus type="file" name="the_file" id="the_file" class="form-control"
                                        value="" required oninvalid="setCustomValidity('من فضلك ادخل هذا الحقل')"
                                        onchange="try{setCustomValidity('')}catch(e){}">

                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group text-center">
                                    <button style="margin-top: 33px" class="btn btn-sm btn-success" type="submit"
                                        name="submit">اضف الملف</button>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-outline-light" data-dismiss="modal">الغاء</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    @if ($data['does_have_fixed_allowances'] == 1)
        <div class="modal fade" id="add_allowances_modal">
            <div class="modal-dialog modal-xl">
                <div class="modal-content bg-info">
                    <div class="modal-header">
                        <h4 class="modal-title">اضافة بدلات ثابتة للموظف</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body custom_body_modal">
                        <form action="{{ route('Employees.add_allowances', $data['id']) }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>بيانات البدلات <span style="color: red">*</span></label>
                                            <select name="allowance_id" id="allowance_id" class="form-control select2">

                                                <option value="">اختر البدل</option>
                                                @if (@isset($other['allowances']) && !@empty($other['allowances']))
                                                    @foreach ($other['allowances'] as $info)
                                                        <option value="{{ $info->id }}">{{ $info->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>قيمة البدل</label>
                                        <input autofocus type="text" name="allowances_value" id="allowances_value"
                                            oninput="this.value=this.value.replace(/[^0-9.]/g,'');" class="form-control"
                                            value="" required
                                            oninvalid="setCustomValidity('من فضلك ادخل هذا الحقل')"
                                            onchange="try{setCustomValidity('')}catch(e){}">

                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group text-center">
                                        <button id="do_add_allowances" style="margin-top: 33px" class="btn btn-sm btn-success" type="submit"
                                            name="submit">اضف البدل الثابت</button>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-outline-light" data-dismiss="modal">الغاء</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

          {{-- مودل التعديل --}}

    <div class="modal fade" id="FixedSuitModalUpdate">
        <div class="modal-dialog modal-xl">
            <div class="modal-content bg-info">
                <div class="modal-header">
                    <h4 class="modal-title">تعديل بدلات ثابتة للموظف</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body" id="FixedSuitModalUpdateBady" style="background-color: white; color: black;">


                </div>

            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    @endif
@endsection

@section('script')
    <script src="{{ asset('assets/admin/plugins/select2/js/select2.full.min.js') }}"></script>
    {{-- <script src="{{ asset('assets/admin/js/employees.js') }}"></script> --}}
    <script>
        $(".select2").select2({
            theme: 'bootstrap4'
        });


        $(document).on('click', '#do_add_allowances', function(e) {
           var allowance_id=$('#allowance_id').val();
           if(allowance_id==""){
            alert("من فضلك اختر البدل الثابت");
            $("#allowance_id").focus();
            return false;
           }

           var allowances_value=$('#allowances_value').val();
           if(allowances_value==""|| allowances_value==0){
            alert("من فضلك ادخل قيمة البدل الثابت");
            $("#allowances_value").focus();
            return false;
           }

        });

        $(document).on('click', '.load_edit_allowances', function(e) {
            var id = $(this).data('id');
            jQuery.ajax({
                url: '{{ route('Employees.load_edit_allowances') }}',
                type: 'post',
                dataType: 'html',
                cache: false,
                data: {
                    "_token": '{{ csrf_token() }}',
                    id: id
                   
                },

                success: function(data) {
                    $("#FixedSuitModalUpdateBady").html(data);
                    $("#FixedSuitModalUpdate").modal("show");
                    $('.select2').select2();
                },
                error: function() {
                    alert("عفواً حدث خطأ")
                }

            });
        });
        $(document).on('click', '#do_allowances_value_edit', function(e) {
            
          var  allowances_value_edit=$("#allowances_value_edit").val();
          if(allowances_value_edit==""||allowances_value_edit==0){
            alert("من فضلك ادخل قيمة البدل الثابت");
            e.preventDefault();
            $("#allowances_value_edit").focus();
            return false;
          }
        });
    </script>
    
@endsection
