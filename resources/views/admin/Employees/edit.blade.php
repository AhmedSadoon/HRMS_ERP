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
    تعديل
@endsection

@section('content')
    <div class="col-12">
        <div class="card">

            <div class="card-header">
                <h3 class="card-title card_title_center">تعديل بيانات الموظف </h3>
            </div>

            <div class="card-body">

                <form action="{{ route('Employees.update',$data['id']) }}" method="POST" enctype="multipart/form-data">
                    @csrf

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
                                    <a class="nav-link active" id="personal_data" data-toggle="pill"
                                        href="#custom-content-personal_data" role="tab"
                                        aria-controls="custom-content-personal_data" aria-selected="true">بيانات شخصية</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="jobs_data" data-toggle="pill" href="#custom-content-jobs_data"
                                        role="tab" aria-controls="custom-content-jobs_data" aria-selected="false">بيانات
                                        وظيفية</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="additional_data" data-toggle="pill"
                                        href="#custom-content-additional_data" role="tab"
                                        aria-controls="custom-content-additional_data" aria-selected="false">بيانات
                                        اضافية</a>
                                </li>

                            </ul>

                            <div class="tab-content" id="custom-content-below-tabContent">

                                {{-- بداية البيانات الشخصية --}}
                                <div class="tab-pane fade show active" id="custom-content-personal_data" role="tabpanel"
                                    aria-labelledby="personal_data">
                                    <br>
                                    {{-- بداية الصف --}}
                                    <div class="row">

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>كود بصمة الموظف </label>
                                                <input autofocus type="text" name="zketo_code" id="zketo_code"
                                                    class="form-control" value="{{ old('zketo_code',$data['zketo_code']) }}">
                                                @error('zketo_code')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>اسم الموظف <span style="color: red">*</span></label>
                                                <input type="text" name="emp_name" id="emp_name" class="form-control"
                                                    value="{{ old('emp_name',$data['emp_name']) }}">
                                                @error('emp_name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>نوع الجنس <span style="color: red">*</span></label>
                                                <select name="emp_gender" id="emp_gender" class="form-control">
                                                    <option value="">اختر الجنس</option>
                                                    <option @if (old('emp_gender',$data['emp_gender']) == 1) selected @endif
                                                        value="1">ذكر</option>
                                                    <option @if (old('emp_gender',$data['emp_gender']) == 2 and old('emp_gender',$data['emp_gender']) != '') selected @endif
                                                        value="2">انثى</option>
                                                </select>
                                                @error('emp_gender')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>الفرع التابع له الموظف <span style="color: red">*</span></label>
                                                <select name="branch_id" id="branch_id" class="form-control select2">

                                                    <option value="">اختر الفرع</option>
                                                    @if (@isset($other['branches']) && !@empty($other['branches']))
                                                        @foreach ($other['branches'] as $info)
                                                            <option
                                                                @if (old('branch_id',$data['branch_id']) == $info->id) selected="selected" @endif
                                                                value="{{ $info->id }}">{{ $info->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @error('branch_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                        </div>



                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>المؤهل الدراسي <span style="color: red">*</span></label>
                                                <select name="qualifications_id" id="qualifications_id"
                                                    class="form-control select2 ">
                                                    <option value="">اختر المؤهل</option>
                                                    @if (@isset($other['qualifications']) && !@empty($other['qualifications']))
                                                        @foreach ($other['qualifications'] as $info)
                                                            <option
                                                                @if (old('qualifications_id',$data['qualifications_id']) == $info->id) selected="selected" @endif
                                                                value="{{ $info->id }}"> {{ $info->name }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @error('qualifications_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>سنة التخرج <span style="color: red">*</span></label>
                                                <input autofocus type="text" name="qualifications_year"
                                                    id="qualifications_year" class="form-control"
                                                    value="{{ old('qualifications_year',$data['qualifications_year']) }}">
                                                @error('qualifications_year')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>تقدير التخرج <span style="color: red">*</span></label>
                                                <select name="graduation_estimate" id="graduation_estimate"
                                                    class="form-control">
                                                    <option @if (old('graduation_estimate',$data['graduation_estimate']) == 1) selected @endif
                                                        value="1">مقبول</option>
                                                    <option @if (old('graduation_estimate',$data['graduation_estimate']) == 2 and old('graduation_estimate',$data['graduation_estimate']) != '') selected @endif
                                                        value="2">متوسط</option>
                                                    <option @if (old('graduation_estimate',$data['graduation_estimate']) == 3 and old('graduation_estimate',$data['graduation_estimate']) != '') selected @endif
                                                        value="3">جيد</option>
                                                    <option @if (old('graduation_estimate',$data['graduation_estimate']) == 4 and old('graduation_estimate',$data['graduation_estimate']) != '') selected @endif
                                                        value="4">جيد جدا</option>

                                                    <option @if (old('graduation_estimate',$data['graduation_estimate']) == 5 and old('graduation_estimate',$data['graduation_estimate']) != '') selected @endif
                                                        value="5">امتياز</option>
                                                </select>
                                                @error('graduation_estimate')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>تخصص التخرج <span style="color: red">*</span></label>
                                                <input autofocus type="text" name="graduation_specialization"
                                                    id="graduation_specialization" class="form-control"
                                                    value="{{ old('graduation_specialization',$data['graduation_specialization']) }}">
                                                @error('graduation_specialization')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>تاريخ الميلاد <span style="color: red">*</span></label>
                                                <input autofocus type="date" name="brith_date" id="brith_date"
                                                    class="form-control" value="{{ old('brith_date',$data['brith_date']) }}">
                                                @error('brith_date')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>رقم بطاقة الشخصية للموظف <span style="color: red">*</span></label>
                                                <input autofocus type="text" name="emp_national_identity"
                                                    id="emp_national_identity" class="form-control"
                                                    value="{{ old('emp_national_identity',$data['emp_national_identity']) }}">
                                                @error('emp_national_identity')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>تاريخ نفاذ بطاقة الشخصية للموظف <span style="color: red">*</span></label>
                                                <input autofocus type="date" name="emp_endDate_identityID"
                                                    id="emp_endDate_identityID" class="form-control"
                                                    value="{{ old('emp_endDate_identityID',$data['emp_endDate_identityID']) }}">
                                                @error('emp_endDate_identityID')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>مركز اصدار البطاقة الشخصية <span style="color: red">*</span></label>
                                                <input autofocus type="text" name="emp_idenity_place"
                                                    id="emp_idenity_place" class="form-control"
                                                    value="{{ old('emp_idenity_place' ,$data['emp_idenity_place']) }}">
                                                @error('emp_idenity_place')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>فصيلة الدم</label>
                                                <select name="blood_group_id" id="blood_group_id"
                                                    class="form-control select2 ">
                                                    <option value="">اختر الفصيلة</option>
                                                    @if (@isset($other['blood_groups']) && !@empty($other['blood_groups']))
                                                        @foreach ($other['blood_groups'] as $info)
                                                            <option
                                                                @if (old('blood_groups_id',$data['blood_groups_id']) == $info->id) selected="selected" @endif
                                                                value="{{ $info->id }}"> {{ $info->name }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @error('blood_group_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>الجنسية <span style="color: red">*</span></label>
                                                <select name="emp_nationalitie_id" id="emp_nationalitie_id"
                                                    class="form-control select2 ">
                                                    <option value="">اختر الجنسية</option>
                                                    @if (@isset($other['nationalities']) && !@empty($other['nationalities']))
                                                        @foreach ($other['nationalities'] as $info)
                                                            <option
                                                                @if (old('emp_nationalitie_id',$data['emp_nationalitie_id']) == $info->id) selected="selected" @endif
                                                                value="{{ $info->id }}"> {{ $info->name }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @error('emp_nationalitie_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>اللغة الاساسية للموظف <span style="color: red">*</span></label>
                                                <select name="emp_lang_id" id="emp_lang_id"
                                                    class="form-control select2 ">
                                                    <option value="">اختر اللغة</option>
                                                    @if (@isset($other['languages']) && !@empty($other['languages']))
                                                        @foreach ($other['languages'] as $info)
                                                            <option
                                                                @if (old('emp_lang_id',$data['emp_lang_id']) == $info->id) selected="selected" @endif
                                                                value="{{ $info->id }}"> {{ $info->name }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @error('emp_lang_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>الديانة <span style="color: red">*</span></label>
                                                <select name="religion_id" id="religion_id"
                                                    class="form-control select2 ">
                                                    <option value="">اختر الديانة</option>
                                                    @if (@isset($other['religions']) && !@empty($other['religions']))
                                                        @foreach ($other['religions'] as $info)
                                                            <option
                                                                @if (old('religion_id',$data['religion_id']) == $info->id) selected="selected" @endif
                                                                value="{{ $info->id }}"> {{ $info->name }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @error('religion_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>البريد الالكتروني</label>
                                                <input autofocus type="text" name="emp_email" id="emp_email"
                                                    class="form-control" value="{{ old('emp_email',$data['emp_email']) }}">
                                                @error('emp_email')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>



                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>الدول <span style="color: red">*</span></label>
                                                <select name="country_id" id="country_id" class="form-control select2 ">
                                                    <option value="">اختر الدولة التابع لها الموظف</option>
                                                    @if (@isset($other['countries']) && !@empty($other['countries']))
                                                        @foreach ($other['countries'] as $info)
                                                            <option
                                                                @if (old('country_id',$data['country_id']) == $info->id) selected="selected" @endif
                                                                value="{{ $info->id }}"> {{ $info->name }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @error('country_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group" id="governorate_Div">
                                                <label>المحافظات</label>
                                                <select name="governorate_id" id="governorate_id"
                                                    class="form-control select2 ">
                                                    <option value="">اختر المحافظة التابع لها الموظف</option>
                                                    @if (@isset($other['governorates']) && !@empty($other['governorates']))
                                                        @foreach ($other['governorates'] as $info)
                                                            <option
                                                                @if (old('governorate_id',$data['governorate_id']) == $info->id) selected="selected" @endif
                                                                value="{{ $info->id }}">
                                                                {{ $info->name }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @error('governorate_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group" id="center_Div">
                                                <label>المدينة</label>
                                                <select name="city_id" id="city_id" class="form-control select2 ">
                                                    <option value="">اختر المدينة التابع لها الموظف</option>
                                                    @if (@isset($other['centers']) && !@empty($other['centers']))
                                                        @foreach ($other['centers'] as $info)
                                                            <option
                                                                @if (old('city_id',$data['city_id']) == $info->id) selected="selected" @endif
                                                                value="{{ $info->id }}"> {{ $info->name }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @error('city_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>عنوان الاقامة الحالي للموظف <span style="color: red">*</span></label>
                                                <input autofocus type="text" name="states_address" id="states_address"
                                                    class="form-control" value="{{ old('states_address',$data['states_address']) }}">
                                                @error('states_address')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>هاتف المنزل <span style="color: red">*</span></label>
                                                <input autofocus type="text" name="emp_home_tel" id="emp_home_tel"
                                                    class="form-control" value="{{ old('emp_home_tel',$data['emp_home_tel']) }}">
                                                @error('emp_home_tel')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>هاتف العمل <span style="color: red">*</span></label>
                                                <input autofocus type="text" name="emp_work_tel" id="emp_work_tel"
                                                    class="form-control" value="{{ old('emp_work_tel',$data['emp_work_tel']) }}">
                                                @error('emp_work_tel')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>الحالة العسكرية <span style="color: red">*</span></label>
                                                <select name="emp_military_status_id" id="emp_military_status_id"
                                                    class="form-control">
                                                    <option value="">اختر الحالة</option>
                                                    @if (@isset($other['military_statuses']) && !@empty($other['military_statuses']))
                                                        @foreach ($other['military_statuses'] as $info)
                                                            <option
                                                                @if (old('emp_military_status_id',$data['emp_military_status_id']) == $info->id) selected="selected" @endif
                                                                value="{{ $info->id }}"> {{ $info->name }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @error('emp_military_status_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4 related_military_1"
                                            @if (old('emp_military_status_id',$data['emp_military_status_id']) != 1) style="display: none;" @endif>
                                            <div class="form-group">
                                                <label>تاريخ بداية الخدمة العسكرية</label>
                                                <input autofocus type="date" name="emp_military_date_from"
                                                    id="emp_military_date_from" class="form-control"
                                                    value="{{ old('emp_military_date_from',$data['emp_military_date_from']) }}">
                                                @error('emp_military_date_from')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4 related_military_1"
                                            @if (old('emp_military_status_id',$data['emp_military_status_id']) != 1) style="display: none;" @endif>
                                            <div class="form-group">
                                                <label>تاريخ نهاية الخدمة العسكرية <span style="color: red">*</span></label>
                                                <input autofocus type="date" name="emp_military_date_to"
                                                    id="emp_military_date_to" class="form-control"
                                                    value="{{ old('emp_military_date_to',$data['emp_military_date_to']) }}">
                                                @error('emp_military_date_to')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4 related_military_1"
                                            @if (old('emp_military_status_id',$data['emp_military_status_id']) != 1) style="display: none;" @endif>
                                            <div class="form-group">
                                                <label>سلاح الخدمة العسكرية <span style="color: red">*</span></label>
                                                <input autofocus type="text" name="emp_military_wepon"
                                                    id="emp_military_wepon" class="form-control"
                                                    value="{{ old('emp_military_wepon',$data['emp_military_wepon']) }}">
                                                @error('emp_military_wepon')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4 related_military_2"
                                            @if (old('emp_military_status_id',$data['emp_military_status_id']) != 2) style="display: none;" @endif>
                                            <div class="form-group">
                                                <label>تاريخ الاعفاء من الخدمة العسكرية <span style="color: red">*</span></label>
                                                <input autofocus type="date" name="exemption_date" id="exemption_date"
                                                    class="form-control" value="{{ old('exemption_date',$data['exemption_date']) }}">
                                                @error('exemption_date')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4 related_military_2"
                                            @if (old('emp_military_status_id',$data['emp_military_status_id']) != 2) style="display: none;" @endif>
                                            <div class="form-group">
                                                <label>سبب الاعفاء من الخدمة العسكرية <span style="color: red">*</span></label>
                                                <input autofocus type="text" name="exemption_reason"
                                                    id="exemption_reason" class="form-control"
                                                    value="{{ old('exemption_reason',$data['exemption_reason']) }}">
                                                @error('exemption_reason')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4 related_military_3"
                                            @if (old('emp_military_status_id',$data['emp_military_status_id']) != 3) style="display: none;" @endif>
                                            <div class="form-group">
                                                <label>سبب ومدة تأجيل الخدمة العسكرية <span style="color: red">*</span></label>
                                                <input autofocus type="text" name="postponement_reason"
                                                    id="postponement_reason" class="form-control"
                                                    value="{{ old('postponement_reason',$data['postponement_reason']) }}">
                                                @error('postponement_reason')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>هل يمتلك رخصة قيادة <span style="color: red">*</span></label>
                                                <select name="does_has_driving_license" id="does_has_driving_license"
                                                    class="form-control">
                                                    <option value="">اختر الحالة</option>
                                                    <option @if (old('does_has_driving_license',$data['does_has_driving_license']) == 1) selected @endif
                                                        value="1">نعم</option>
                                                    <option @if (old('does_has_driving_license',$data['does_has_driving_license']) == 0 and old('does_has_driving_license',$data['does_has_driving_license']) != '') selected @endif
                                                        value="0">لا</option>

                                                </select>
                                                @error('does_has_driving_license')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4 related_does_has_driving_license"
                                            @if (old('does_has_driving_license',$data['does_has_driving_license']) != 1) style="display: none;" @endif>
                                            <div class="form-group">
                                                <label>رقم رخصة القيادة <span style="color: red">*</span></label>
                                                <input autofocus type="text" name="driving_license_degree"
                                                    id="driving_license_degree" class="form-control"
                                                    value="{{ old('driving_license_degree',$data['driving_license_degree']) }}">
                                                @error('driving_license_degree')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4 related_does_has_driving_license"
                                            @if (old('does_has_driving_license',$data['does_has_driving_license']) != 1) style="display: none;" @endif>
                                            <div class="form-group">
                                                <label>اختر نوع الرخصة <span style="color: red">*</span></label>
                                                <select name="driving_license_types_id" id="driving_license_types_id"
                                                    class="form-control">
                                                    <option value="">اختر الحالة</option>
                                                    @if (@isset($other['driving_license_types']) && !@empty($other['driving_license_types']))
                                                        @foreach ($other['driving_license_types'] as $info)
                                                            <option
                                                                @if (old('driving_license_types_id',$data['driving_license_types_id']) == $info->id) selected="selected" @endif
                                                                value="{{ $info->id }}"> {{ $info->name }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @error('driving_license_types_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>هل يمتلك اقارب في العمل <span style="color: red">*</span></label>
                                                <select name="has_relatives" id="has_relatives" class="form-control">
                                                    <option value="">اختر الحالة</option>
                                                    <option @if (old('has_relatives',$data['has_relatives']) == 1) selected @endif
                                                        value="1">نعم</option>
                                                    <option @if (old('has_relatives',$data['has_relatives']) == 0 and old('has_relatives',$data['has_relatives']) != '') selected @endif
                                                        value="0">لا</option>

                                                </select>
                                                @error('has_relatives')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-12 related_relatives_details"
                                            @if (old('has_relatives',$data['has_relatives']) != 1) style="display: none;" @endif>
                                            <div class="form-group">
                                                <label>تفاصيل الاقارب <span style="color: red">*</span></label>
                                                <textarea autofocus type="text" name="relatives_details" id="relatives_details" class="form-control">{{ old('relatives_details',$data['relatives_details']) }}</textarea>
                                                @error('relatives_details')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>هل يمتلك اعاقة / عمليات سابقة <span style="color: red">*</span></label>
                                                <select name="is_disabilities_processes" id="is_disabilities_processes"
                                                    class="form-control">
                                                    <option value="">اختر الحالة</option>
                                                    <option @if (old('is_disabilities_processes',$data['is_disabilities_processes']) == 1) selected @endif
                                                        value="1">نعم</option>
                                                    <option @if (old('is_disabilities_processes',$data['is_disabilities_processes']) == 0 and old('is_disabilities_processes',$data['is_disabilities_processes']) != '') selected @endif
                                                        value="0">لا</option>

                                                </select>
                                                @error('is_disabilities_processes')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-8 related_is_disabilities_processes"
                                            @if (old('is_disabilities_processes',$data['is_disabilities_processes']) != 1) style="display: none;" @endif>
                                            <div class="form-group">
                                                <label>تفاصيل الاعاقة <span style="color: red">*</span></label>
                                                <textarea autofocus type="text" name="disabilities_processes" id="disabilities_processes" class="form-control">{{ old('disabilities_processes',$data['disabilities_processes']) }}</textarea>
                                                @error('disabilities_processes')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-12 ">
                                            <div class="form-group">
                                                <label>ملاحظات</label>
                                                <textarea autofocus type="text" name="notes" id="notes" class="form-control">{{ old('notes',$data['notes']) }}</textarea>
                                                @error('notes',$data['notes'])
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>


                                    </div>
                                    {{-- نهاية الصف --}}




                                </div>
                                {{-- نهاية البيانات الشخصية --}}


                                {{-- بداية البيانات الوظيفية --}}

                                <div class="tab-pane fade" id="custom-content-jobs_data" role="tabpanel"
                                    aria-labelledby="jobs_data">
                                    {{-- بداية الصف --}}
                                    <div class="row">
                                        <br>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>تاريخ التعيين <span style="color: red">*</span></label>
                                                <input autofocus type="date" name="emp_start_date" id="emp_start_date"
                                                    class="form-control" value="{{ old('emp_start_date',$data['emp_start_date']) }}">
                                                @error('emp_start_date')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>الحالة الوظيفية <span style="color: red">*</span></label>
                                                <select name="function_status" id="function_status" class="form-control">
                                                    <option value="">اختر الحالة</option>
                                                    <option @if (old('function_status',$data['function_status']) == 1) selected @endif
                                                        value="1">يعمل</option>
                                                    <option @if (old('function_status',$data['function_status']) == 0 and old('function_status',$data['function_status']) != "") selected @endif
                                                        value="0">خارج العمل</option>

                                                </select>
   
                                                @error('function_status')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>الادارة التابع له الموظف <span style="color: red">*</span></label>
                                                <select name="emp_department_id" id="emp_department_id"
                                                    class="form-control select2">

                                                    <option value="">اختر الادارة</option>
                                                    @if (@isset($other['departments']) && !@empty($other['departments']))
                                                        @foreach ($other['departments'] as $info)
                                                            <option
                                                                @if (old('emp_department_id',$data['emp_department_id']) == $info->id) selected="selected" @endif
                                                                value="{{ $info->id }}">{{ $info->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @error('emp_department_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label> وظيفة الموظف <span style="color: red">*</span></label>
                                                <select name="emp_jobs_id" id="emp_jobs_id"
                                                    class="form-control select2 ">
                                                    <option value="">اختر الوظيفة</option>
                                                    @if (@isset($other['jobs']) && !@empty($other['jobs']))
                                                        @foreach ($other['jobs'] as $info)
                                                            <option
                                                                @if (old('emp_jobs_id',$data['emp_jobs_id']) == $info->id) selected="selected" @endif
                                                                value="{{ $info->id }}"> {{ $info->name }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @error('emp_jobs_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>هل ملزم بتسجيل حضور وانصراف <span style="color: red">*</span></label>
                                                <select name="does_has_ateendance" id="does_has_ateendance"
                                                    class="form-control">
                                                    <option @if (old('does_has_ateendance',$data['does_has_ateendance']) == 1) selected @endif
                                                        value="1">نعم</option>
                                                    <option @if (old('does_has_ateendance',$data['does_has_ateendance']) == 0 and old('does_has_ateendance',$data['does_has_ateendance']) != '') selected @endif
                                                        value="0">لا</option>

                                                </select>
                                                @error('does_has_ateendance')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>هل له شفت ثابت <span style="color: red">*</span></label>
                                                <select name="is_has_fixced_shift" id="is_has_fixced_shift"
                                                    class="form-control">
                                                    <option value="">اختر الشفت</option>
                                                    <option @if (old('is_has_fixced_shift',$data['is_has_fixced_shift']) == 1) selected @endif
                                                        value="1">نعم</option>
                                                    <option @if (old('is_has_fixced_shift',$data['is_has_fixced_shift']) == 0 and old('is_has_fixced_shift',$data['is_has_fixced_shift']) != '') selected @endif
                                                        value="0">لا</option>

                                                </select>
                                                @error('is_has_fixced_shift')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4 relatedfixced_shift"
                                            @if (old('is_has_fixced_shift',$data['is_has_fixced_shift']) == 0 || old('is_has_fixced_shift',$data['is_has_fixced_shift']) == '') style="display: none;" @endif>
                                            <div class="form-group">
                                                <label>أنواع الشفتات <span style="color: red">*</span></label>
                                                <select name="shift_type_id" id="shift_type_id"
                                                    class="form-control select2 ">
                                                    <option value="">اختر الشفت</option>
                                                    @if (@isset($other['shift_types']) && !@empty($other['shift_types']))
                                                        @foreach ($other['shift_types'] as $info)
                                                            <option
                                                                @if (old('shift_type_id',$data['shift_type_id']) == $info->id) selected="selected" @endif
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
                                                                    $newDateTime = date(
                                                                        'A',
                                                                        strtotime($info->from_time),
                                                                    );
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
                                                @error('shift_type_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4 daily_work_hourDIV"
                                            @if (old('is_has_fixced_shift',$data['is_has_fixced_shift']) == 1 || old('is_has_fixced_shift',$data['is_has_fixced_shift']) == '') style="display: none;" @endif>
                                            <div class="form-group">
                                                <label>عدد ساعات العمل اليومي <span style="color: red">*</span></label>
                                                <input autofocus type="text" name="daily_work_hour"
                                                    id="daily_work_hour"
                                                    oninput="this.value=this.value.replace(/[^0-9.]/g,'');"
                                                    class="form-control" value="{{ old('daily_work_hour',$data['daily_work_hour']) }}">
                                                @error('daily_work_hour')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>راتب الموظف الشهري <span style="color: red">*
                                                    <button type="button" id="showSalaryArchive" class="btn btn-sm btn-success" data-id="{{$data['id']}}">عرض الارشيف</button>
                                                </span></label>
                                                <input autofocus type="text" name="emp_salary" id="emp_salary"
                                                    oninput="this.value=this.value.replace(/[^0-9.]/g,'');"
                                                    class="form-control" value="{{ old('emp_salary',$data['emp_salary']) }}">
                                                @error('emp_salary')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>هل له حافز <span style="color: red">*</span></label>
                                                <select name="motivation_type" id="motivation_type" class="form-control">
                                                    <option value="">اختر الحافز</option>
                                                    <option @if (old('motivation_type',$data['motivation_type']) == 1) selected @endif
                                                        value="1">ثابت</option>
                                                    <option @if (old('motivation_type',$data['motivation_type']) == 2) selected @endif
                                                        value="2">متغير</option>
                                                    <option @if (old('motivation_type',$data['motivation_type']) == 0 and old('motivation_type',$data['motivation_type']) != '') selected @endif
                                                        value="0">لا يوجد</option>

                                                </select>
                                                @error('motivation_type')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4 motivationDiv"
                                            @if (old('motivation_type',$data['motivation_type']) != 1) style="display: none;" @endif>
                                            <div class="form-group">
                                                <label>قيمت الحافز الشهري الثابت <span style="color: red">*</span></label>
                                                <input autofocus type="text" name="motivation" id="motivation"
                                                    oninput="this.value=this.value.replace(/[^0-9.]/g,'');"
                                                    class="form-control" value="{{ old('motivation',$data['motivation']) }}">
                                                @error('motivation')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>هل له تأمين اجتماعي <span style="color: red">*</span></label>
                                                <select name="is_social_nsurance" id="is_social_nsurance"
                                                    class="form-control">
                                                    <option value="">اختر الحالة</option>
                                                    <option @if (old('is_social_nsurance',$data['is_social_nsurance']) == 1) selected @endif
                                                        value="1">نعم</option>
                                                    <option @if (old('is_social_nsurance',$data['is_social_nsurance']) == 0 and old('is_social_nsurance',$data['is_social_nsurance']) != '') selected @endif
                                                        value="0">لا</option>

                                                </select>
                                                @error('is_social_nsurance')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4 relatedsocial_nsurance"
                                            @if (old('is_social_nsurance',$data['is_social_nsurance']) != 1) style="display: none;" @endif>
                                            <div class="form-group">
                                                <label>قيمت التأمين الاجتماعي <span style="color: red">*</span></label>
                                                <input autofocus type="text" name="social_nsurance_cutMonthely"
                                                    id="social_nsurance_cutMonthely"
                                                    oninput="this.value=this.value.replace(/[^0-9.]/g,'');"
                                                    class="form-control"
                                                    value="{{ old('social_nsurance_cutMonthely',$data['social_nsurance_cutMonthely']) }}">
                                                @error('social_nsurance_cutMonthely')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4 relatedsocial_nsurance"
                                            @if (old('is_social_nsurance',$data['is_social_nsurance']) != 1) style="display: none;" @endif>
                                            <div class="form-group">
                                                <label>رقم التأمين الاجتماعي للموظف <span style="color: red">*</span></label>
                                                <input autofocus type="text" name="social_nsurance_number"
                                                    id="social_nsurance_number" class="form-control"
                                                    value="{{ old('social_nsurance_number',$data['social_nsurance_number']) }}">
                                                @error('social_nsurance_number')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>هل له تأمين طبي <span style="color: red">*</span></label>
                                                <select name="is_medical_nsurance" id="is_medical_nsurance"
                                                    class="form-control">
                                                    <option value="">اختر الحالة</option>
                                                    <option @if (old('is_medical_nsurance',$data['is_medical_nsurance']) == 1) selected @endif
                                                        value="1">نعم</option>
                                                    <option @if (old('is_medical_nsurance',$data['is_medical_nsurance']) == 0 and old('is_medical_nsurance',$data['is_medical_nsurance']) != '') selected @endif
                                                        value="0">لا</option>

                                                </select>
                                                @error('is_medical_nsurance')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4 relatedmedical_nsurance"
                                            @if (old('is_medical_nsurance',$data['is_medical_nsurance']) != 1) style="display: none;" @endif>
                                            <div class="form-group">
                                                <label>قيمت التأمين طبي <span style="color: red">*</span></label>
                                                <input autofocus type="text" name="medical_nsurance_cutMonthely"
                                                    id="medical_nsurance_cutMonthely"
                                                    oninput="this.value=this.value.replace(/[^0-9.]/g,'');"
                                                    class="form-control"
                                                    value="{{ old('medical_nsurance_cutMonthely',$data['medical_nsurance_cutMonthely']) }}">
                                                @error('medical_nsurance_cutMonthely')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4 relatedmedical_nsurance"
                                            @if (old('is_medical_nsurance',$data['is_medical_nsurance']) != 1) style="display: none;" @endif>
                                            <div class="form-group">
                                                <label>رقم التأمين طبي للموظف <span style="color: red">*</span></label>
                                                <input autofocus type="text" name="medical_nsurance_number"
                                                    id="medical_nsurance_number" class="form-control"
                                                    value="{{ old('medical_nsurance_number',$data['medical_nsurance_number']) }}">
                                                @error('medical_nsurance_number')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>نوع صرف راتب الموظف <span style="color: red">*</span></label>
                                                <select name="sal_cach_or_visa" id="sal_cach_or_visa"
                                                    class="form-control">
                                                    <option value="">اختر الحالة</option>
                                                    <option @if (old('sal_cach_or_visa',$data['sal_cach_or_visa']) == 1) selected @endif
                                                        value="1">كاش</option>
                                                    <option @if (old('sal_cach_or_visa',$data['sal_cach_or_visa']) == 2) selected @endif
                                                        value="2">فيزا</option>


                                                </select>
                                                @error('sal_cach_or_visa')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>هل له بدلات ثابتة <span style="color: red">*</span></label>
                                                <select name="does_have_fixed_allowances" id="does_have_fixed_allowances"
                                                    class="form-control">
                                                    <option value="">اختر الحالة</option>
                                                    <option @if (old('does_have_fixed_allowances',$data['does_have_fixed_allowances']) == 1) selected @endif
                                                        value="1">نعم</option>
                                                    <option @if (old('does_have_fixed_allowances',$data['does_have_fixed_allowances']) == 0 and old('does_have_fixed_allowances',$data['does_have_fixed_allowances']) != '') selected @endif
                                                        value="0">لا</option>does_have_fixed_allowances

                                                </select>
                                                @error('does_have_fixed_allowances')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>هل له رصيد اجازات سنوي <span style="color: red">*</span></label>
                                                <select name="is_active_for_vaccation" id="is_active_for_vaccation"
                                                    class="form-control">
                                                    <option value="">اختر الحالة</option>
                                                    <option @if (old('is_active_for_vaccation',$data['is_active_for_vaccation']) == 1) selected @endif
                                                        value="1">نعم</option>
                                                    <option @if (old('is_active_for_vaccation',$data['is_active_for_vaccation']) == 0 and old('is_active_for_vaccation',$data['is_active_for_vaccation']) != '') selected @endif
                                                        value="0">لا</option>


                                                </select>
                                                @error('is_active_for_vaccation')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>شخص يمكن الرجوع اليه للضرورة <span style="color: red">*</span></label>
                                                <input autofocus type="text" name="urgent_person_details"
                                                    id="urgent_person_details" class="form-control"
                                                    value="{{ old('urgent_person_details',$data['urgent_person_details']) }}">
                                                @error('urgent_person_details')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>




                                    </div>
                                    {{-- نهاية الصف --}}

                                </div>
                                {{-- نهاية البيانات الوظيفية --}}

                                {{-- بداية البيانات الاضافية --}}
                                <div class="tab-pane fade" id="custom-content-additional_data" role="tabpanel"
                                    aria-labelledby="additional_data">
                                    {{-- بداية الصف --}}
                                    <div class="row">

                                        <br>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>اسم الكفيل</label>
                                                <input autofocus type="text" name="emp_cafel" id="emp_cafel"
                                                    class="form-control" value="{{ old('emp_cafel',$data['emp_cafel']) }}">
                                                @error('emp_cafel')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>رقم جواز السفر</label>
                                                <input autofocus type="text" name="emp_pasport_no" id="emp_pasport_no"
                                                    class="form-control" value="{{ old('emp_pasport_no',$data['emp_pasport_no']) }}">
                                                @error('emp_pasport_no')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>جهة اصدار جواز السفر</label>
                                                <input autofocus type="text" name="emp_pasport_from"
                                                    id="emp_pasport_from" class="form-control"
                                                    value="{{ old('emp_pasport_from',$data['emp_pasport_from']) }}">
                                                @error('emp_pasport_from')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>تاريخ انتهاء جواز السفر</label>
                                                <input autofocus type="date" name="emp_pasport_exp"
                                                    id="emp_pasport_exp" class="form-control"
                                                    value="{{ old('emp_pasport_exp',$data['emp_pasport_exp']) }}">
                                                @error('emp_pasport_exp')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label>عنوان اقامة الموظف قي بلده الام</label>
                                                <input autofocus type="text" name="emp_Basic_stay_com"
                                                    id="emp_Basic_stay_com" class="form-control"
                                                    value="{{ old('emp_Basic_stay_com',$data['emp_Basic_stay_com']) }}">
                                                @error('emp_Basic_stay_com')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>الصورة الشخصية للموظف</label>
                                                <input autofocus type="file" name="emp_photo" id="emp_photo"
                                                    class="form-control" value="{{ old('emp_photo',$data['emp_photo']) }}">
                                                @error('emp_photo')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>السيرة الذاتية للموظف</label>
                                                <input autofocus type="file" name="emp_cv" id="emp_cv"
                                                    class="form-control" value="{{ old('emp_cv',$data['emp_cv']) }}">
                                                @error('emp_cv')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>






                                    </div>
                                    {{-- نهاية الصف --}}
                                </div>

                                {{-- نهاية البيانات الاضافية --}}

                            </div>

                        </div>
                        <!-- /.card -->
                    </div>

                    <div class="col-md-12">
                        <div class="form-group text-center">
                            <button class="btn btn-sm btn-success" type="submit" name="submit">تعديل الموظف</button>
                            <a href="{{ route('Employees.index') }}" class="btn btn-sm btn-danger">الغاء</a>
                        </div>
                    </div>

                </form>


            </div>
        </div>
    </div>

    <div class="modal fade" id="showSalaryArchiveModal">
        <div class="modal-dialog modal-xl">
          <div class="modal-content bg-info">
            <div class="modal-header">
              <h4 class="modal-title">عرض سجلات ارشيف الرواتب للموظف</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" id="showSalaryArchiveModalBady" style="background-color: white; color:black;">

            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
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
    {{-- <script src="{{ asset('assets/admin/js/employees.js') }}"></script> --}}
    <script>
        $(".select2").select2({
            theme: 'bootstrap4'
        });



        $(document).on('change', '#country_id', function(e) {
            get_governorates();
        });

        function get_governorates() {
            var country_id = $("#country_id").val();

            jQuery.ajax({
                url: '{{ route('Employees.get_governorates') }}',
                type: 'post',
                dataType: 'html',
                cache: false,
                data: {
                    "_token": '{{ csrf_token() }}',
                    country_id: country_id

                },

                success: function(data) {
                    $("#governorate_Div").html(data);
                },
                error: function() {
                    alert("عفواً حدث خطأ")
                }

            });
        }

        $(document).on('change', '#governorate_id', function(e) {
            get_center();
        });

        function get_center() {
            var governorate_id = $("#governorate_id").val();

            jQuery.ajax({
                url: '{{ route('Employees.get_centers') }}',
                type: 'post',
                dataType: 'html',
                cache: false,
                data: {
                    "_token": '{{ csrf_token() }}',
                    governorate_id: governorate_id

                },

                success: function(data) {
                    $("#center_Div").html(data);
                },
                error: function() {
                    alert("عفواً حدث خطأ")
                }

            });
        }

        $(document).on('change', '#emp_military_status_id', function(e) {
            var emp_military_status_id = $(this).val();
            if (emp_military_status_id == 1) {

                $('.related_military_1').show();
                $('.related_military_2').hide();
                $('.related_military_3').hide();

            } else if (emp_military_status_id == 2) {
                $('.related_military_1').hide();
                $('.related_military_2').show();
                $('.related_military_3').hide();

            } else if (emp_military_status_id == 3) {
                $('.related_military_1').hide();
                $('.related_military_2').hide();
                $('.related_military_3').show();
            } else {
                $('.related_military_1').hide();
                $('.related_military_2').hide();
                $('.related_military_3').hide();
            }
        });


        $(document).on('change', '#does_has_driving_license', function(e) {

            if ($(this).val() == 1) {
                $('.related_does_has_driving_license').show();
            } else {
                $('.related_does_has_driving_license').hide();
            }
        });

        $(document).on('change', '#has_relatives', function(e) {

            if ($(this).val() == 1) {
                $('.related_relatives_details').show();
            } else {
                $('.related_relatives_details').hide();
            }
        });

        $(document).on('change', '#is_disabilities_processes', function(e) {

            if ($(this).val() == 1) {
                $('.related_is_disabilities_processes').show();
            } else {
                $('.related_is_disabilities_processes').hide();
            }
        });

        $(document).on('change', '#is_has_fixced_shift', function(e) {

            if ($(this).val() == '') {
                $('.relatedfixced_shift').hide();
                $('.daily_work_hourDIV').hide();
            } else if ($(this).val() == 0) {
                $('.relatedfixced_shift').hide();
                $('.daily_work_hourDIV').show();

            } else {

                $('.relatedfixced_shift').show();
                $('.daily_work_hourDIV').hide();
            }
        });

        $(document).on('change', '#motivation_type', function(e) {

            if ($(this).val() != 1) {
                $('.motivationDiv').hide();
            } else {

                $('.motivationDiv').show();
            }
        });

        $(document).on('change', '#is_social_nsurance', function(e) {

            if ($(this).val() != 1) {
                $('.relatedsocial_nsurance').hide();
            } else {

                $('.relatedsocial_nsurance').show();
            }
        });

        $(document).on('change', '#is_medical_nsurance', function(e) {

            if ($(this).val() != 1) {
                $('.relatedmedical_nsurance').hide();
            } else {

                $('.relatedmedical_nsurance').show();
            }
        });

        $(document).on('click', '#showSalaryArchive', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            jQuery.ajax({
                url: '{{ route('Employees.showSalaryArchive') }}',
                type: 'post',
                dataType: 'html',
                cache: false,
                data: {
                    "_token": '{{ csrf_token() }}',
                    id: id
                   
                },

                success: function(data) {
                    $("#showSalaryArchiveModalBady").html(data);
                    $("#showSalaryArchiveModal").modal("show");
                    $('.select2').select2();
                },
                error: function() {
                    alert("عفواً حدث خطأ")
                }

            });
        });



        
    </script>
@endsection
