<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AttendanceDepartureUploadExcelRequest;
use App\Imports\Attendance_departureImport;
use App\Models\Admin;
use App\Models\Attendance_departure_actions_excel;
use App\Models\Employee;
use App\Models\Finance_calender;
use App\Models\Finance_cin_periods;
use App\Models\Weekday;
use DateTime;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class Attendance_departureController extends Controller
{
    public function index()
    {
        $com_code = auth()->user()->com_code;
        $Finance_cin_periods = get_cols_where_paginate_order2(new Finance_cin_periods(), array('*'), array('com_code' => $com_code), 'finance_yr', 'DESC','month_id','ASC', 12);
        
        if(!empty($Finance_cin_periods)){
            foreach($Finance_cin_periods as $info){
                //chech status to open month
                $info->currentYear=get_cols_where_row(new Finance_calender(),array("open_yr_flag"),array('com_code'=>$com_code,'finance_yr'=>$info->finance_yr));
                $info->counterOpenMonth=get_count_where(new Finance_cin_periods(),array('com_code'=>$com_code,'is_open'=>1));
                $info->counterPreviousMonthWatingOpen=Finance_cin_periods::where('com_code',$com_code)->where('finance_yr',$info->finance_yr)->where('month_id','<',$info->month_id)->where('is_open','=',0)->count();

            }
        }

        return view('admin.Attendance_departure.index', compact('Finance_cin_periods'));
    }
    
    
    public function show($finance_cin_periods_id){
        $com_code = auth()->user()->com_code;
        $finance_cin_periods_data=get_cols_where_row(new Finance_cin_periods(),array("*"),array('com_code'=>$com_code,'id'=>$finance_cin_periods_id));
        if(empty($finance_cin_periods_data)){
            return redirect()->route('Attendance_departure.index')->with('error','عفواً غير قادر للوصول الى البينات المطلوبة');
        }

        $data = get_cols_where_paginate(new Employee(), array('*'), array('com_code' => $com_code), 'id', 'DESC', PAGINATION_COUNTER);
        $employees_search=get_cols_where(new Employee(),array("employees_code","emp_name"),array('com_code'=>$com_code),'employees_code','ASC');

        $last_attendance_departure_actions_excel_data=get_cols_where_row_orderby(new Attendance_departure_actions_excel(),array('datetimeAction','created_at','added_by'),array('com_code'=>$com_code,'finance_cin_periods_id'=>$finance_cin_periods_id),'datetimeAction','DESC');

        if(!empty($last_attendance_departure_actions_excel_data)){
            $last_attendance_departure_actions_excel_data['added_by_name']=get_field_value(new Admin(),'name',array('com_code'=>$com_code,'id'=>$last_attendance_departure_actions_excel_data['added_by']));
        }

        return view('admin.Attendance_departure.show', ['data'=>$data,'finance_cin_periods_data'=>$finance_cin_periods_data,'employees_search'=>$employees_search,'last_attendance_departure_actions_excel_data'=>$last_attendance_departure_actions_excel_data]);


    }

    public function uploadExcelFile($finance_cin_periods_id){
        $com_code = auth()->user()->com_code;
        $finance_cin_periods_data=get_cols_where_row(new Finance_cin_periods(),array("*"),array('com_code'=>$com_code,'id'=>$finance_cin_periods_id,'is_open'=>1));
        if(empty($finance_cin_periods_data)){
            return redirect()->route('Attendance_departure.index')->with('error','عفواً غير قادر للوصول الى البينات المطلوبة');
        }



        return view('admin.Attendance_departure.uploadExcelFile', ['finance_cin_periods_data'=>$finance_cin_periods_data]);


    }

    public function do_UploadExcelFile(AttendanceDepartureUploadExcelRequest $request, $finance_cin_periods_id){
        $com_code = auth()->user()->com_code;
        $finance_cin_periods_data=get_cols_where_row(new Finance_cin_periods(),array("*"),array('com_code'=>$com_code,'id'=>$finance_cin_periods_id,'is_open'=>1));
        if(empty($finance_cin_periods_data)){
            return redirect()->route('Attendance_departure.index')->with('error','عفواً غير قادر للوصول الى البينات المطلوبة');
        }

        Excel::import(new Attendance_departureImport($finance_cin_periods_data), $request->excel_file);
        return redirect()->route('AttendanceDeparture.show',$finance_cin_periods_id)->with('success','تم سحب البصمة بنجاح');





    }

    public function showPasmaDetails($employees_code,$finance_cin_periods_id){
        $com_code = auth()->user()->com_code;
        $Employee_data = get_cols_where_row(new Employee(), array('*'), array('com_code' => $com_code,'employees_code'=>$employees_code));

        if(empty($Employee_data)){
            return redirect()->route('Attendance_departure.show',$finance_cin_periods_id)->with('error','عفواً غير قادر للوصول الى البينات الموظف');
        }

        $finance_cin_periods_data=get_cols_where_row(new Finance_cin_periods(),array("*"),array('com_code'=>$com_code,'id'=>$finance_cin_periods_id));
        if(empty($finance_cin_periods_data)){
            return redirect()->route('Attendance_departure.index')->with('error','عفواً غير قادر للوصول الى البينات المطلوبة');
        }

      

        return view('admin.Attendance_departure.showPasmaDetails', ['Employee_data'=>$Employee_data,'finance_cin_periods_data'=>$finance_cin_periods_data]);


    }

    
 function load_PasmasaArchive(Request $request) {
    if($request->ajax()){

        $com_code = auth()->user()->com_code;
        $attendance_departure_actions_excel = get_cols_where(new Attendance_departure_actions_excel(), array('*'), array('com_code' => $com_code,'employees_code'=>$request->employees_code,'finance_cin_periods_id'=>$request->finance_cin_periods_id),'datetimeAction','ASC');
        
        if(!empty($attendance_departure_actions_excel)){
            foreach($attendance_departure_actions_excel as $info){
                $dt=new DateTime($info->datetimeAction);
                $date=$dt->format('Y-m-d');
                $nameOfDay = date('l', strtotime($date));
                $info->week_day_name_arabic=get_field_value(new Weekday(),'name',array('name_en'=>$nameOfDay));

            }
        }
              
        return view('admin.Attendance_departure.load_PasmasaArchive',['attendance_departure_actions_excel'=>$attendance_departure_actions_excel]);
    }
}


}
