@extends('layouts.admin')

@section('title')
    الرواتب
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('contentheader')
    تفاصيل الرواتب
@endsection

@section('contentheaderactivelink')
    <a href="{{ route('MainSalaryEmployee.index') }}">تفاصيل الراتب</a>
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
                <h3 class="card-title card_title_center">تفاصيل راتب موظف بالشهر المالي
                    ({{ $finance_cin_periods_data['month']->name }} لسنة {{ $finance_cin_periods_data['finance_yr'] }})

                </h3>

            </div>


            <div class="card-body">

                @if (@isset($MainSalaryEmployeeData) and !@empty($MainSalaryEmployeeData))


                    {{-- بداية نافذة الطباعة --}}


                    <style>
                        .custom_td_fisrt {
                            width: 30%;
                            background-color: lightcyan;
                        }

                        td,
                        th {
                            text-align: center;
                            color: black;
                        }

                        .underPrag {
                            text-decoration: underline;
                            font-size: 16px;
                            color: black;
                            font-weight: bold;
                            margin: 7px;
                        }

                        @media print {
                            #printButton {
                                display: none;
                            }
                        }
                    </style>

                    
                        <div class="container">
                            <p style="text-align: center;  padding: 3px;">
                                مفردات المرتب
                                <a href="#" id="printButton" class=" btn btn-success btn-xs  hidden-print"
                                    onclick="window.print();">
                                    طباعة
                                </a>
                            </p>
                            

                                <table dir="rtl" cellspacing="1" cellpadding="3" border="2"
                                    style="text-align:right;border-color: black; width: 97%;  margin: 0 auto; background-color: lightgray ">
                                    <tr>
                                        <td style="width: 20%"> الشهر المالي</td>

                                        <td>
                                            ( {{ $MainSalaryEmployeeData['finance_cin_periods_id'] }} ) لسنة
                                            {{ $finance_cin_periods_data['finance_yr'] }}

                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 20%">كود الموظف</td>

                                        <td>
                                            {{ $MainSalaryEmployeeData['emp_name'] }} 
                                            ( كود {{ $MainSalaryEmployeeData['employees_code'] }} )
                                             
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 20%"> الوظيفة</td>
                                        <td> {{ $MainSalaryEmployeeData['jobs_name'] }}</td>
                                    </tr>
                                
                                    <tr>

                                        <td colspan="2" style="background-color: lightpink"> رصيد مرحل من الشهر السابق
                                        
                                            {{ $MainSalaryEmployeeData['last_salary_remain_blance'] * 1 }}
                                        </td>
                                    </tr>
                                
                                </table>

                                <p class="underPrag">أولاً : الاستحقاقات</p>
                                <table dir="rtl" cellspacing="1" cellpadding="3" border="2"
                                    style="text-align:right;border-color: black; width: 97%;  margin: 0 auto;">
                                    <tr>
                                        <td rowspan="8" style="width: 10%;-webkit-transform: rotate(-90deg) !important;">
                                            الاستحقاقات</td>

                                    </tr>
                                  
                                        
                                  
                                    <tr>

                                        <td style="width: 20%;"> الراتب</td>
                                        <td >
                                                {{ $MainSalaryEmployeeData['emp_sal'] * 1  }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td> حافز ثابت</td>
                                        <td>     
                                                {{ $MainSalaryEmployeeData['motivation'] * 1 }}
                                        </td>
                                    </tr>

                                    <tr>
                                        
                                        <td> بدلات ثابتة </td>
                                        <td>{{ $MainSalaryEmployeeData['fixed_suits'] * 1 }}</td>
                                    </tr>

                                    <tr>
                                        <td>بدلات متغيرة</td>
                                        <td >
                                            {{ $MainSalaryEmployeeData['changable_suits'] * 1 }}

                                        </td>
                                    </tr>

                                    <tr>
                                        <td>اضافي ايام</td>
                                        <td >     
                                               @if(  $MainSalaryEmployeeData['additional_days_counter'] >0)
                                               عدد {{ $MainSalaryEmployeeData['additional_days_counter'] * 1 }} يوم بأجمالي ({{$MainSalaryEmployeeData['additional_days']*1}})
                                               @else
                                                    0
                                               @endif
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>مكافئات مالية</td>
                                        <td>     
                                                {{ $MainSalaryEmployeeData['additions'] * 1 }}
                                        </td>
                                    </tr>
                  
                 

                                    <tr style=" background-color: lightblue  !important;">
                                        <td>اجمالي الاستحقاقات</td>
                                        <td >
                                            {{ $MainSalaryEmployeeData['total_benefits'] * 1 }}
                                        </td>
                                    </tr>
                                </table>

                                <p class="underPrag">ثانيا : الاستقطاعات</p>
                                <table dir="rtl" cellspacing="1" cellpadding="3" border="2"
                                    style="text-align:right;border-color: black; width: 97%;  margin: 0 auto;">
                                    <tr>
                                        <td rowspan="9" style="width: 10%;-webkit-transform: rotate(-90deg) !important;">
                                            الاستقطاعات</td>
                                    </tr>

                                    <tr>
                                        <td>تأمين اجتماعي</td>
                                        <td>     
                                                {{ $MainSalaryEmployeeData['social_nsurance_cutMonthely'] * 1 }}
                                        </td>
                                    </tr>

                                    <tr>
                                        
                                        <td>تأمين الطبي</td>
                                        <td>{{ $MainSalaryEmployeeData['medical_nsurance_cutMonthely'] * 1 }}</td>
                                    </tr>

                                    <tr>
                                        <td>جزاء الايام</td>
                                        <td >     
                                               @if(  $MainSalaryEmployeeData['sanctions_days_counter'] >0)
                                                {{ $MainSalaryEmployeeData['sanctions_days_total'] * 1 }} <br>
                                                عدد {{ $MainSalaryEmployeeData['sanctions_days_counter'] * 1 }} يوم
                                               @else
                                                    0
                                               @endif
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>غياب الايام</td>
                                        <td >     
                                               @if(  $MainSalaryEmployeeData['absence_days_counter'] >0)
                                                {{ $MainSalaryEmployeeData['absence_days'] * 1 }} <br>
                                                عدد {{ $MainSalaryEmployeeData['absence_days_counter'] * 1 }} يوم
                                               @else
                                                    0
                                               @endif
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>خصومات مالية</td>
                                        <td>     
                                                {{ $MainSalaryEmployeeData['discount'] * 1 }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>سلف شهرية</td>
                                        <td>     
                                                {{ $MainSalaryEmployeeData['monthly_loan'] * 1 }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>سلف مستديمة</td>
                                        <td>     
                                                {{ $MainSalaryEmployeeData['permanent_loan'] * 1 }}
                                        </td>
                                    </tr>
                  
                 

                                    <tr style=" background-color: lightpink  !important;">
                                        <td>اجمالي الاستقطاعات</td>
                                        <td >
                                            {{ $MainSalaryEmployeeData['total_deductions'] * 1 }}
                                        </td>
                                    </tr>
                                </table>

                                <p class="underPrag">ثالثا : صافي الراتب</p>

                                <table dir="rtl" cellspacing="1" cellpadding="3" border="2"
                                style="text-align:right;border-color: black; width: 97%;  margin: 0 auto; background-color: lightgray ">
                                
                                <tr style="background-color:lightcyan">
                                    <td style="width: 20%">حالة صافي الراتب</td>

                                    <td>
                                        {{ $MainSalaryEmployeeData['final_the_net'] *1}} 
                                        @if($MainSalaryEmployeeData['final_the_net']>0) <br>
                                         مبلغ مستحق للموظف قيمة {{ $MainSalaryEmployeeData['final_the_net'] *1}}
                                         @elseif ($MainSalaryEmployeeData['final_the_net']<0) <br>
                                         مبلغ مستحق على الموظف قيمة {{ $MainSalaryEmployeeData['final_the_net'] *1 *(-1)}}

                                         @else
                                         <br>
                                            متزن
                                         @endif

                                    </td>
                                </tr>
                               
                               
                            
                            </table>
                                <!------------------------------------------------------------------->
                          
                        </div>
                      
                    





                    {{-- نهاية نافذة الطباعة --}}
                @else
                    <p class="bg-danger text-center">عفواً لا توجد بيانات لعرضها</p>
                @endif


            </div>
        </div>
    </div>



@endsection
