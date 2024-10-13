<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>طباعة شريط الراتب</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap_rtl-v4.2.1/bootstrap.min.css') }}">
    <style>
        @media print {
            .hidden-print {
                display: none;
            }
        }

        @media print {
            #printButton {
                display: none;
            }
        }

        td {
            font-size: 15px !important;
            text-align: center;
        }

        th {
            text-align: center;
        }
    </style>

<body style="padding-top: 10px;font-family: tahoma;">


    <table style="width: 60%;float: right;  margin-right: 5px;" dir="rtl">

        <tr>
            <td style="text-align: center;padding: 5px;font-weight: bold;"> <span
                    style=" display: inline-block;
               width: 500px;
               height: 30px;
               text-align: center;
               color: red;
               border: 1px solid black; border-radius: 10px !important">
                    طباعة مبردات راتب موظف بالشهر ({{ $finance_cin_periods_data['month']->name }} لسنة
                    {{ $finance_cin_periods_data['finance_yr'] }})
                </span>
            </td>
        </tr>
        <tr>
            <td style="text-align: center;padding: 5px;font-weight: bold;">
                <span
                    style=" display: inline-block;
                  width: 400px;
                  height: 30px;
                  text-align: center;
                  color: blue;
                  border: 1px solid black; border-radius: 10px !important">
                    طبع بتاريخ @php echo date('Y-m-d'); @endphp
                </span>
            </td>
        </tr>
        <tr>
            <td style="text-align: center;padding: 5px;font-weight: bold;">
                <span
                    style=" display: inline-block;
                  width: 400px;
                  height: 30px;
                  text-align: center;
                  color: blue;
                  border: 1px solid black; border-radius: 10px !important ">
                    طبع بواسطة {{ auth()->user()->name }}
                </span>
            </td>
        </tr>
    </table>
    <table style="width: 35%;float: right; margin-left: 5px; " dir="rtl">
        <tr>
            <td style="text-align:left !important;padding: 5px;">
                <img style="width: 150px; height: 110px; border-radius: 10px;"
                    src="{{ asset('assets/admin/uploads') . '/' . $systemData['image'] }}">
                <p>{{ $systemData['company_name'] }}</p>
            </td>
        </tr>
    </table>

    <br>

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
                    <td style="width: 20%">اسم الموظف</td>

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
                        @if($MainSalaryEmployeeData['is_stoped']==1)
                            (هذا الراتب موقوف)
                        @endif
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
                    <td>
                        {{ $MainSalaryEmployeeData['emp_sal'] * 1 }}
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
                    <td>
                        {{ $MainSalaryEmployeeData['changable_suits'] * 1 }}

                    </td>
                </tr>

                <tr>
                    <td>اضافي ايام</td>
                    <td>
                        @if ($MainSalaryEmployeeData['additional_days_counter'] > 0)
                            عدد {{ $MainSalaryEmployeeData['additional_days_counter'] * 1 }} يوم بأجمالي
                            ({{ $MainSalaryEmployeeData['additional_days'] * 1 }})
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
                    <td>
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
                    <td>
                        @if ($MainSalaryEmployeeData['sanctions_days_counter'] > 0)
                            {{ $MainSalaryEmployeeData['sanctions_days_total'] * 1 }} <br>
                            عدد {{ $MainSalaryEmployeeData['sanctions_days_counter'] * 1 }} يوم
                        @else
                            0
                        @endif
                    </td>
                </tr>

                <tr>
                    <td>غياب الايام</td>
                    <td>
                        @if ($MainSalaryEmployeeData['absence_days_counter'] > 0)
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
                    <td>
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
                        {{ $MainSalaryEmployeeData['final_the_net'] * 1 }}
                        @if ($MainSalaryEmployeeData['final_the_net'] > 0)
                            <br>
                            مبلغ مستحق للموظف قيمة {{ $MainSalaryEmployeeData['final_the_net'] * 1 }}
                        @elseif ($MainSalaryEmployeeData['final_the_net'] < 0)
                            <br>
                            مبلغ مستحق على الموظف قيمة {{ $MainSalaryEmployeeData['final_the_net'] * 1 * -1 }}
                        @else
                            <br>
                            متزن
                        @endif

                    </td>
                </tr>


                @if ($MainSalaryEmployeeData['is_archived'] == 1)
                    <td style="width: 20%">تم ارشفة الراتب</td>
                    <td>


                        {{ $MainSalaryEmployeeData['archivedBy']->name }}
                        {{ \Carbon\Carbon::parse($MainSalaryEmployeeData['archived_date'])->format('d-m-Y') }}


                    </td>
                @endif


            </table>
            <!------------------------------------------------------------------->

    </div>







    {{-- نهاية نافذة الطباعة --}}
@else
    <p class="bg-danger text-center">عفواً لا توجد بيانات لعرضها</p>
    @endif


    </div>


    <br>
    <p
        style="
         padding: 10px 10px 0px 10px;
         bottom: 0;
         width: 100%;
         /* Height of the footer*/ 
         text-align: center;font-size: 16px; font-weight: bold;
         ">
        {{ $systemData['address'] }} - {{ $systemData['phone'] }} </p>
    <div class="clearfix"></div> <br>
    <p class="text-center">
        <button onclick="window.print()" class="btn btn-success btn-sm" id="printButton">طباعة</button>
    </p>
</body>

</html>
