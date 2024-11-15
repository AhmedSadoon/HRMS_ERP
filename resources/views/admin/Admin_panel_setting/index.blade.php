@extends('layouts.admin')

@section('title')
الضبط العام للنظام
@endsection

@section('contentheader')
قائمة الضبط
@endsection

@section('contentheaderactivelink')
<a href="{{route('admin_panel_settings.index')}}">الضبط العام</a>
@endsection

@section('contentheaderactive')
عرض
@endsection

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center">بيانات الضبط العام للنظام</h3>
        </div>
        <div class="card-body">

            @if (@isset($data)and !@empty($data))
                
                <table id="example2" class="table table-bordered table-hover">
                    <tr>
                        <td class="width30">اسم الشركة</td>
                        <td>{{$data['company_name']}}</td>
                    </tr>

                    <tr>
                        <td class="width30">حالة التفعيل</td>
                        <td>
                            @if ($data['system_status']==1)
                                مفعل
                                @else
                                معطل
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <td class="width30">هاتف الشركة</td>
                        <td>{{$data['phone']}}</td>
                    </tr>

                    <tr>
                        <td class="width30">عنوان الشركة</td>
                        <td>{{$data['address']}}</td>
                    </tr>

                    <tr>
                        <td class="width30">بريد الشركة</td>
                        <td>{{$data['email']}}</td>
                    </tr>

                    <tr>
                        <td class="width30">بعد كم دقيقة يحسب تأخير حضور</td>
                        <td>{{$data['after_miniute_calculate_delay']}}</td>
                    </tr>

                    <tr>
                        <td class="width30">بعد كم دقيقة يحسب انصراف مبكر</td>
                        <td>{{$data['after_miniute_calculate_early_departure']}}</td>
                    </tr>

                    <tr>
                        <td class="width30">بعد كم مرة حضور متأخر او تنصراف مبكر خصم ربع يوم	</td>
                        <td>{{$data['after_miniute_quarterday']}}</td>
                    </tr>

                    <tr>
                        <td class="width30">بعد كم مرة تأخير او انصراف مبكر نخصم نص يوم</td>
                        <td>{{$data['after_time_half_dayCut']}}</td>
                    </tr>
                    
                    <tr>
                        <td class="width30">نخصم بعد كم مرة تأخير او انصراف مبكر يوم كامل</td>
                        <td>{{$data['after_time_allday_daycut']}}</td>
                    </tr>

                    <tr>
                        <td class="width30"> اقل من كم دقيقة فرق بين البصمة الاولى والثانية يتم اهمال البصمة التأكيدية للموظف </td>
                        <td>{{$data['less_than_miniute_neglecting_passma']}}</td>
                    </tr>

                    <tr>
                        <td class="width30"> الحد الاقصى لاحتساب عدد ساعات العمل الاضافية عند انصراف الموظف واحتساب بصمة الانصراف والا ستحتسب على انها بصمة حضور شفت جديد </td>
                        <td>{{$data['max_hours_take_Pssma_as_additional']}}</td>
                    </tr>

                    <tr>
                        <td class="width30">رصيد اجازات الموظف الشهري</td>
                        <td>{{$data['monthly_vaction_balance']}}</td>
                    </tr>

                    <tr>
                        <td class="width30">بعد كم يوم ينزل رصيد الاجازات</td>
                        <td>{{$data['after_days_begins_vacation']}}</td>
                    </tr>

                    <tr>
                        <td class="width30">الرصيد الاولي المرحلة عند تفعيل الاجازات للموظف</td>
                        <td>{{$data['first_balance_begin_vacation']}}</td>
                    </tr>

                    <tr>
                        <td class="width30">الرصيد الاولي المرحلة عند تفعيل الاجازات للموظف</td>
                        <td>{{$data['first_balance_begin_vacation']}}</td>
                    </tr>

                    <tr>
                        <td class="width30">هل الرصيد يرحل من السنة المالية الحالية الى الاخرى</td>
                        <td>@if ($data['is_transfer_vacction']==1) نعم  @else لا @endif</td>
                    </tr>

                    <tr>
                        <td class="width30">قيمة خصم الايام بعد ثاني مرة غياب بدون عذر</td>
                        <td>{{$data['sanctions_value_second_abcence']}}</td>
                    </tr>

                    <tr>
                        <td class="width30">قيمة خصم الايام بعد ثالث مرة غياب بدون عذر</td>
                        <td>{{$data['sanctions_value_thaird_abcence']}}</td>
                    </tr>
                    
                    <tr>
                        <td class="width30">قيمة خصم الايام بعد رابع مرة غياب بدون عذر</td>
                        <td>{{$data['sanctions_value_forth_abcence']}}</td>
                    </tr>

                    <tr>
                        <td class="width30">شعار الشركة</td>
                        <td>  
                            <img src="{{ asset('assets/admin/uploads') . '/' . $data['image'] }}"
                            style="border-radius: 50%; width: 80px; height: 80px;"
                            class="rounded-circle" alt="شعار الشركة"> 
                        </td>
                    </tr>



                    <tr>
                        <td colspan="2" class="text-center">
                            <a href="{{route('admin_panel_settings.edit')}}" class="btn btn-sm btn-danger">تعديل</a>
                        </td>

                    </tr>
                </table>

            @else
                <p class="bg-danger text-center">عفواً لا توجد بيانات لعرضها</p>
            @endif
        </div>
    </div>
</div>


@endsection