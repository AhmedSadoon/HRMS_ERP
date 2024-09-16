@extends('layouts.admin')
@section('title')
    الضبط العام للنظام
@endsection
@section('contentheader')
    قائمة الضبط
@endsection
@section('contentheaderactivelink')
    <a href="{{ route('admin_panel_settings.edit') }}"> تعديل الضبط العام</a>
@endsection
@section('contentheaderactive')
    تعديل
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center"> تحديث بيانات الضبط العام للنظام </h3>
        </div>
        <div class="card-body">
            @if (@isset($data) and !@empty($data))
                <form action="{{ route('admin_panel_settings.update') }}" method="POST" enctype="multipart/form-data">
                    
                    <div class="row">
                        @csrf
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>اسم الشركة</label>
                                <input type="text" name="company_name" id="company_name" class="form-control"
                                    value="{{ old('company_name', $data['company_name']) }}">
                                @error('company_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>هاتف الشركة</label>
                                <input type="text" name="phone" id="phone" class="form-control"
                                    value="{{ old('phone', $data['phone']) }}" placeholder="ادخل اسم الشركة">
                                @error('phone')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label> عنوان الشركة </label>
                                <input type="text" name="address" id="address" class="form-control"
                                    value="{{ old('address', $data['address']) }}" placeholder="ادخل عنوان الشركة">
                                @error('phones')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>بريد الشركة </label>
                                <input type="text" name="email" id="email" class="form-control"
                                    value="{{ old('email', $data['email']) }}" placeholder="ادخل بريد الشركة">
                                @error('phones')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>بعد كم دقيقة نحسب تاخير حضور </label>
                                <input type="text" name="after_miniute_calculate_delay"
                                    id="after_miniute_calculate_delay" class="form-control"
                                    value="{{ old('after_miniute_calculate_delay', $data['after_miniute_calculate_delay']) }}"
                                    oninput="this.value=this.value.replace(/[^0-9.]/g,'');">
                                @error('after_miniute_calculate_delay')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>بعد كم دقيقة نحسب انصراف مبكر </label>
                                <input type="text" name="after_miniute_calculate_early_departure"
                                    id="after_miniute_calculate_early_departure" class="form-control"
                                    value="{{ old('after_miniute_calculate_early_departure', $data['after_miniute_calculate_early_departure']) }}"
                                    oninput="this.value=this.value.replace(/[^0-9.]/g,'');">
                                @error('phones')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>بعد كم دقيقه مجموع الانصراف المبكر او الحضور المتأخر نحصم ربع يوم </label>
                                <input type="text" name="after_miniute_quarterday" id="after_miniute_quarterday"
                                    class="form-control"
                                    value="{{ old('after_miniute_quarterday', $data['after_miniute_quarterday']) }}"
                                    oninput="this.value=this.value.replace(/[^0-9.]/g,'');">
                                @error('after_miniute_quarterday')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label> بعد كم مرة تأخير او انصارف مبكر نخصم نص يوم </label>
                                <input type="text" name="after_time_half_dayCut" id="after_time_half_dayCut"
                                    class="form-control"
                                    value="{{ old('after_time_half_dayCut', $data['after_time_half_dayCut']) }}"
                                    oninput="this.value=this.value.replace(/[^0-9.]/g,'');">
                                @error('after_time_half_dayCut')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label> نخصم بعد كم مره تاخير او انصارف مبكر يوم كامل </label>
                                <input type="text" name="after_time_allday_daycut" id="after_time_allday_daycut"
                                    class="form-control"
                                    value="{{ old('after_time_allday_daycut', $data['after_time_allday_daycut']) }}"
                                    oninput="this.value=this.value.replace(/[^0-9.]/g,'');">
                                @error('after_time_allday_daycut')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label> رصيد اجازات الموظف الشهري </label>
                                <input type="text" name="monthly_vaction_balance" id="monthly_vaction_balance"
                                    class="form-control"
                                    value="{{ old('monthly_vaction_balance', $data['monthly_vaction_balance']) }}"
                                    oninput="this.value=this.value.replace(/[^0-9.]/g,'');">
                                @error('monthly_vaction_balance')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label> بعد كم يوم ينزل للموظف رصيد اجازات </label>
                                <input type="text" name="after_days_begins_vacation" id="after_days_begins_vacation"
                                    class="form-control"
                                    value="{{ old('after_days_begins_vacation', $data['after_days_begins_vacation']) }}"
                                    oninput="this.value=this.value.replace(/[^0-9.]/g,'');">
                                @error('after_days_begins_vacation')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label> الرصيد الاولي المرحل عند تفعيل الاجازات للموظف مثل نزول عشرة ايام ونص بعد سته شهور
                                    للموظف </label>
                                <input type="text" name="first_balance_begin_vacation"
                                    id="first_balance_begin_vacation" class="form-control"
                                    value="{{ old('first_balance_begin_vacation', $data['first_balance_begin_vacation']) }}"
                                    oninput="this.value=this.value.replace(/[^0-9.]/g,'');">
                                @error('first_balance_begin_vacation')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label> الرصيد الاولي المرحل عند تفعيل الاجازات للموظف مثل نزول عشرة ايام ونص بعد سته شهور
                                    للموظف </label>
                                <input type="text" name="sanctions_value_first_abcence"
                                    id="sanctions_value_first_abcence" class="form-control"
                                    value="{{ old('sanctions_value_first_abcence', $data['sanctions_value_first_abcence']) }}"
                                    oninput="this.value=this.value.replace(/[^0-9.]/g,'');">
                                @error('sanctions_value_first_abcence')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label> قيمة خصم الايام بعد ثاني مرة غياب بدون اذن </label>
                                <input type="text" name="sanctions_value_second_abcence"
                                    id="sanctions_value_second_abcence" class="form-control"
                                    value="{{ old('sanctions_value_second_abcence', $data['sanctions_value_second_abcence']) }}"
                                    oninput="this.value=this.value.replace(/[^0-9.]/g,'');">
                                @error('sanctions_value_second_abcence')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label> قيمة خصم الايام بعد ثالث مرة غياب بدون اذن </label>
                                <input type="text" name="sanctions_value_thaird_abcence"
                                    id="sanctions_value_thaird_abcence" class="form-control"
                                    value="{{ old('sanctions_value_thaird_abcence', $data['sanctions_value_thaird_abcence']) }}"
                                    oninput="this.value=this.value.replace(/[^0-9.]/g,'');">
                                @error('sanctions_value_thaird_abcence')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label> قيمة خصم الايام بعد رابع مرة غياب بدون اذن </label>
                                <input type="text" name="sanctions_value_forth_abcence"
                                    id="sanctions_value_forth_abcence" class="form-control"
                                    value="{{ old('sanctions_value_forth_abcence', $data['sanctions_value_forth_abcence']) }}"
                                    oninput="this.value=this.value.replace(/[^0-9.]/g,'');">
                                @error('sanctions_value_forth_abcence')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>شعار الشركة</label>
                                <input autofocus type="file" name="image" id="image"
                                    class="form-control" >

                            </div>
                        </div>

                        <div class="col-md-12 text-center">
                            <div class="form-group">
                                <button type="submit" class="btn btn-success">تحديث</button>
                            </div>
                        </div>
                    </div>
                </form>
            @else
                <p class="bg-danger text-center"> عفوا لاتوجد بيانات لعرضها</p>
            @endif
        </div>
    </div>
@endsection
