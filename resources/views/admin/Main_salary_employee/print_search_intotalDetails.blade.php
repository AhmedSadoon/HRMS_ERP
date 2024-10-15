<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <title> طباعة مفردات راتب</title>
      <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
      <link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap_rtl-v4.2.1/bootstrap.min.css')}}">
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
         .custom_td_fisrt{width: 30%;background-color: lightcyan  !important;}
         /*    .tdheader{
         top: 0;
         position: sticky;
         background: yellow;
         color: black;
         }*/
         td,th{text-align:  center;color: black;}
         .underPrag{text-decoration: underline; font-size: 14px; color: black; font-weight: bold; margin: 7px;}
         .maintd{font-size: 14px;}     
         span{padding-top:1px; padding-bottom: 1px; }
         @media print{@page {size: landscape};
         }
         @media print {
         .tdheader  {
         height: 100px;
         } 
         }
      </style>
   </head>
   <body >
      <table   dir="rtl"    cellspacing="1" cellpadding="1" border="1"    style="text-align:right;border-color: black; width: 98%; background-color: white  !important;  margin: 0 auto;  ">
         <tr >
            <td colspan="24" class="text-right maintd "  style="padding-right:10px !important;text-align: center !important;"> 
               بحث  الرواتب بالشهر المالي  ({{ $finance_cin_periods_data['month']->name }} لسنة {{ $finance_cin_periods_data['finance_yr'] }})
            </td>
         </tr >
         <tr style="background-color: lemonchiffon  !important">
            <td class="tdheader" rowspan="2" ><span style="writing-mode: vertical-lr !important;
               -ms-writing-mode: tb-rl !important;;
               transform: rotate(180deg) !important;">الرقم الوظيفي</span> </td>
            <td class="tdheader" style="width: 15%;" rowspan="2"   ><span style="writing-mode: vertical-lr !important;
               -ms-writing-mode: tb-rl !important;;
               transform: rotate(180deg) !important;"> الاسم</span></td>
            <td class="tdheader" rowspan="2"  ><span style="writing-mode: vertical-lr !important;
               -ms-writing-mode: tb-rl !important;;
               transform: rotate(180deg) !important;"> الوظيفة</span></td>
            <td  colspan="8" style="font-size: 14px;font-weight: bold;">الاستحقاقات</td>
            <td class="tdheader" rowspan="2" >     <span style="writing-mode: vertical-lr !important;
               -ms-writing-mode: tb-rl !important;;
               transform: rotate(180deg) !important;">  اجمالي الاستحقاقات </span></td>
            <td colspan="9" style="font-size: 14px;font-weight: bold;">الاستقطاعات</td>
            <td class="tdheader" rowspan="2" >     <span style="writing-mode: vertical-lr !important;
               -ms-writing-mode: tb-rl !important;;
               transform: rotate(180deg) !important;">  اجمالي الاستقطاعات </span></td>
            <td class="tdheader" rowspan="2" >         <span style="writing-mode: vertical-lr !important;
               -ms-writing-mode: tb-rl !important;;
               transform: rotate(180deg) !important;">  رصيد سابق مرحل</span></td>
            <td class="tdheader" rowspan="2" >         <span style="writing-mode: vertical-lr !important;
               -ms-writing-mode: tb-rl !important;;
               transform: rotate(180deg) !important;"> الراتب المستحق</span></td>
         </tr>
         <tr style="background-color: wheat  !important" >
            <td class="tdheader" ><span style="writing-mode: vertical-lr !important;
               -ms-writing-mode: tb-rl !important;;
               transform: rotate(180deg) !important;"> الراتب</span></td>
            <td class="tdheader" ><span style="writing-mode: vertical-lr !important;
               -ms-writing-mode: tb-rl !important;;
               transform: rotate(180deg) !important;">اجر اليوم الواحد </span></td>
            <td class="tdheader" >   <span style="writing-mode: vertical-lr !important;
               -ms-writing-mode: tb-rl !important;;
               transform: rotate(180deg) !important;">مكافئات </span></td>
            <td  class="tdheader">    <span style="writing-mode: vertical-lr !important;
               -ms-writing-mode: tb-rl !important;;
               transform: rotate(180deg) !important;">الحافز قيمة </span></td>
            <td class="tdheader" >     <span style="writing-mode: vertical-lr !important;
               -ms-writing-mode: tb-rl !important;;
               transform: rotate(180deg) !important;">الاضافي ايام </span></td>
            <td class="tdheader" >     <span style="writing-mode: vertical-lr !important;
               -ms-writing-mode: tb-rl !important;;
               transform: rotate(180deg) !important;">قيمة الاضافي </span></td>
            <td class="tdheader" >     <span style="writing-mode: vertical-lr !important;
               -ms-writing-mode: tb-rl !important;;
               transform: rotate(180deg) !important;"> بدلات ثابتة </span></td>
            <td class="tdheader" >     <span style="writing-mode: vertical-lr !important;
               -ms-writing-mode: tb-rl !important;;
               transform: rotate(180deg) !important;"> بدلات متغيرة </span></td>
            <td class="tdheader" >  <span style="writing-mode: vertical-lr !important;
               -ms-writing-mode: tb-rl !important;;
               transform: rotate(180deg) !important;">ايام الغياب </span></td>
            <td class="tdheader" >   <span style="writing-mode: vertical-lr !important;
               -ms-writing-mode: tb-rl !important;;
               transform: rotate(180deg) !important;"> قيمة الغياب</span></td>
            <td class="tdheader" >     <span style="writing-mode: vertical-lr !important;
               -ms-writing-mode: tb-rl !important;;
               transform: rotate(180deg) !important;">الجزاء ايام </span></td>
            <td class="tdheader" >     <span style="writing-mode: vertical-lr !important;
               -ms-writing-mode: tb-rl !important;;
               transform: rotate(180deg) !important;"> قيمة الجزاء</span></td>
            <td class="tdheader" >      <span style="writing-mode: vertical-lr !important;
               -ms-writing-mode: tb-rl !important;;
               transform: rotate(180deg) !important;"> سلف شهرية</span></td>
            <td class="tdheader" >      <span style="writing-mode: vertical-lr !important;
               -ms-writing-mode: tb-rl !important;;
               transform: rotate(180deg) !important;"> سلف مستديمة</span></td>
            <td class="tdheader" >       <span style="writing-mode: vertical-lr !important;
               -ms-writing-mode: tb-rl !important;;
               transform: rotate(180deg) !important;"> خصومات مالية</span></td>
            <td class="tdheader" >        <span style="writing-mode: vertical-lr !important;
               -ms-writing-mode: tb-rl !important;;
               transform: rotate(180deg) !important;"> تأمين طبي</span></td>
            <td class="tdheader" >        <span style="writing-mode: vertical-lr !important;
               -ms-writing-mode: tb-rl !important;;
               transform: rotate(180deg) !important;"> تأمين اجتماعي</span></td>
         </tr>
         @if(@isset($data) and !@empty($data) and count($data)>0 )
         @foreach ( $data as $info )
         <tr>
            <td> {{ $info->employees_code  }} </td>
            <td>{{$info->emp_name}}</td>
            <td>{{$info->jobs_name}}</td>
            <td style="background-color: linen !important">{{$info->emp_sal*1}}</td>
            <td>{{$info->day_price*1}}</td>
            <td>{{$info->additions*1}}</td>
            <td>{{$info->motivation*1}}</td>
            <td>{{$info->additional_days_counter*1}}</td>
            <td>{{$info->additional_days*1}}</td>
            <td>{{$info->fixed_suits*1}}</td>
            <td>{{$info->changable_suits*1}}</td>
            <td style="background-color: lightcyan !important">{{$info->total_benefits*1}}</td>
            <td>{{$info->absence_days_counter*1}}</td>
            <td>{{$info->absence_days*1}}</td>
            <td>{{$info->sanctions_days_counter*1}}</td>
            <td>{{$info->sanctions_days_total*1}}</td>
            <td>{{$info->monthly_loan*1}}</td>
            <td>{{$info->permanent_loan*1}}</td>
            <td>{{$info->discount*1}}</td>
            <td>{{$info->medical_nsurance_cutMonthely*1}}</td>
            <td>{{$info->social_nsurance_cutMonthely*1}}</td>
            <td style="background-color: lightsalmon !important">{{$info->total_deductions*1}}</td>
            <td style="background-color: greenyellow !important">{{$info->last_salary_remain_balance*1}}</td>
            <td style="background-color: lightgreen !important">{{$info->final_the_net*1}}</td>
         </tr>
         @endforeach
         <tr style="background-color: lavender !important">
            <td colspan="3">الاجماليات</td>
            <td>{{ $total['emp_sal']*1}}</td>
            <td>{{ $total['day_price']*1}}</td>
            <td>{{ $total['additions']*1}}</td>
            <td>{{ $total['motivation']*1}}</td>
            <td>{{ $total['additional_days_counter']*1}}</td>
            <td>{{ $total['additional_days']*1}}</td>
            <td>{{ $total['fixed_suits']*1}}</td>
            <td>{{ $total['changable_suits']*1}}</td>
            <td style="background-color: lightsalmon !important">{{ $total['total_benefits']*1}}</td>
            <td>{{ $total['absence_days_counter']*1}}</td>
            <td>{{ $total['absence_days']*1}}</td>
            <td>{{ $total['sanctions_days_counter']*1}}</td>
            <td>{{ $total['sanctions_days_total']*1}}</td>
            <td>{{ $total['monthly_loan']*1}}</td>
            <td>{{ $total['permanent_loan']*1}}</td>
            <td>{{ $total['discount']*1}}</td>
            <td>{{ $total['medical_nsurance_cutMonthely']*1}}</td>
            <td>{{ $total['social_nsurance_cutMonthely']*1}}</td>
            <td  style="background-color: lightsalmon !important">{{ $total['total_deductions']*1}}</td>
            <td  style="background-color: greenyellow !important">{{ $total['last_salary_remain_balance']*1}}</td>
            <td  style="background-color: lightsalmon !important">{{ $total['final_the_net']*1}}</td>
         </tr>
      </table>
      @else
      <div class="clearfix"></div>
      <p class="" style="text-align: center; font-size: 16px;font-weight: bold; color: brown">
         عفوا لاتوجد بيانات لعرضها !!
      </p>
      @endif
      <br>
      <p style="
         padding: 10px 10px 0px 10px;
         bottom: 0;
         width: 100%;
         /* Height of the footer*/ 
         text-align: center;font-size: 16px; font-weight: bold;
         "> {{ $systemData['address'] }} - {{ $systemData['phones'] }} </p>
      <div class="clearfix"></div>
      <br>
      <p class="text-center">
         <button onclick="window.print()" class="btn btn-success btn-sm" id="printButton">طباعة</button>
      </p>
   </body>
</html>