<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> حضور وانصراف الموظف</title>
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




    <p style="text-align: center;padding: 5px;font-weight: bold;">
        سجل بصمة الموظف ({{ $finance_cin_periods_data['month']->name }} لسنة
        {{ $finance_cin_periods_data['finance_yr'] }})

    </p>
    <br>
    <table dir="rtl" cellspacing="1" cellpadding="3" border="1"
    style="text-align: right; width: 95%; margin: 0 auto; ">
    <tr>
        <td style="font-weight: bold">الفرع</td>
        <td style="font-weight: bold">كود الموظف</td>
        <td style="font-weight: bold">اسم الموظف</td>
        <td style="font-weight: bold">اسم الوظيفية</td>
        <td style="font-weight: bold">الحالة الوظيفية</td>
        <td style="font-weight: bold">عدد ساعات العمل</td>
        <td style="font-weight: bold">تاريخ التعيين</td>
       
    </tr>
    <tr>
        <td>{{$other['Employee_data']['branch_name']}}</td>
        <td>{{$other['Employee_data']['employees_code']}}</td>
        <td>{{$other['Employee_data']['emp_name']}}</td>
        <td>{{$other['Employee_data']['job_name']}}</td>
        <td>@if ($other['Employee_data']['function_status']==1) بالخدمة @else خارج الخدمة @endif</td>
        <td>{{$other['Employee_data']['daily_work_hour']*1}}</td>
        <td>{{$other['Employee_data']['emp_start_date']}}</td>
    </tr>
    </table>


    @if (@isset($other['data']) and !@empty($other['data']) and count($other['data']) > 0)
        <table dir="rtl" cellspacing="1" cellpadding="3" border="1"
            style="text-align: right; width: 95%; margin: 0 auto; ">

            <thead class="custom_thead">

                <th>التاريخ</th>
                <th>الحضور</th>
                <th>الانصراف</th>
                <th>متغيرات</th>

                <th>حضور متأخر</th>
                <th>انصراف مبكر</th>
                <th>اذون ساعات</th>
                <th>ساعات عمل</th>
                <th>غياب ساعات</th>
                <th>اضافي ساعات</th>



            </thead>
            <tbody>
                @foreach ($other['data'] as $info)
                    <tr @if ($info->datetime_in == null and $info->datetime_out == null) style="background-color:#f2dede;" @endif>

                        <td id="the_day_date{{ $info->id }}">
                            {{ $info->the_day_date }}
                            {{ $info->week_day_name_arabic }}
                        </td>
                        <td>

                            @if ($info->time_in != null)
                                @php
                                    echo date('H:i', strtotime($info->time_in));
                                @endphp
                            @endif

                        </td>
                        <td>
                            @if ($info->time_out != null)
                                @php
                                    echo date('H:i', strtotime($info->time_out));
                                @endphp
                            @endif
                        </td>


                        <td>
                            {{ $info->variables }}
                        </td>


                        <td>
                            {{ $info->attedance_dely * 1 }}
                        </td>
                        <td>
                            {{ $info->early_departure * 1 }}
                        </td>
                        <td>
                            {{ $info->azn_hours }}
                        </td>
                        <td>
                            {{ $info->total_hours * 1 }}
                        </td>
                        <td>
                            {{ $info->absen_hours * 1 }}
                        </td>
                        <td>
                            {{ $info->additional_hours * 1 }}
                        </td>



                    </tr>
                @endforeach

                <tr style="background-color: lightblue; text-align: center">
                    <td colspan="4">الاجماليات</td>




                    <td>{{ $other['total_attedance_dely'] * 1 }} دقيقة</td>
                    <td>{{ $other['total_early_departure'] * 1 }} دقيقة</td>
                    <td></td>
                    <td>{{ $other['total_hours'] * 1 }} ساعة</td>
                    <td>{{ $other['total_absen_hours'] * 1 }} ساعة</td>
                    <td>{{ $other['total_additional_hours'] * 1 }} ساعة</td>
                </tr>
            </tbody>
        </table>
    @else
        <p class="bg-danger text-center">عفواً لا توجد بيانات لعرضها</p>
    @endif



    <div class="clearfix"></div> <br>
    <p class="text-center">
        <button onclick="window.print()" class="btn btn-success btn-sm" id="printButton">طباعة</button>
    </p>
</body>

</html>
