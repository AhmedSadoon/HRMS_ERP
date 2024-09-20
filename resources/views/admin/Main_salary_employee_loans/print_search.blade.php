<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> بحث بالسلف الشهرية</title>
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
                    بحث بالسلف الشهرية للشهر المالي ({{ $finance_cin_periods_data['month']->name }} لسنة
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

    @if (@isset($data) && !@empty($data) && count($data) > 0)
        <table dir="rtl" id="example2" class="table table-bordered table-hover"
            style="width: 99%;margin: 0 auto;">
            <thead style="background-color: yellow">

                <th style="width: 5%">تسلسل</th>
                <th style="width: 5%">كود</th>
                <th style="width: 25%">اسم الموظف</th>
                <th style="width: 20%">اجمالي السلف الشهرية</th>        
                <th style="width: 20%">بواسطة</th>        
                <th style="width: 15%">الحالة</th>
              

            </thead>


            </thead>
            <tbody>
               @php
                  $i=1;
               @endphp
                @foreach ($data as $info)
                    <tr>

                        <td>
                            {{ $i }}
                        </td>

                        <td>
                           {{ $info->employees_code }}
                       </td>

                       <td>
                        {{ $info->emp_name }}
                    </td>

                        <td>
                            {{ $info->total * 1 }}
                        </td>

                        <td>
                           {{ $info->added->name }}
                       </td>

                        <td>
                            @if ($info->is_archived == 1)
                                مؤرشف
                            @else
                                مفتوح
                            @endif
                        </td>




                    </tr>
                    @php
                    $i++;
                 @endphp
                @endforeach
                <tr>
                    <td style="background-color:lightsalmon;" colspan="3"> الاجمالي

                    </td>
                    

                    <td style="background-color: lightgreen;text-align: right; " colspan="4">
                        {{ $other['total_sum'] * 1 }} دينار
                    </td>
                   
                </tr>
            </tbody>
        </table>
        <br>
    @else
        <div class="clearfix"></div>
        <p class="" style="text-align: center; font-size: 16px;font-weight: bold; color: brown">
            عفوا لاتوجد بيانات لعرضها !!
        </p>

    @endif


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
