<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Finance_calender;
use App\Models\Finance_cin_periods;
use App\Models\Main_salary_employee;
use Illuminate\Http\Request;

class MainSalaryRecordController extends Controller
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

        return view('admin.MainSalaryRecord.index', compact('Finance_cin_periods'));
    }

    public function do_open_month(Request $request, $id) {

        $com_code = auth()->user()->com_code;
        $data=get_cols_where_row(new Finance_cin_periods(),array("*"),array('com_code'=>$com_code,'id'=>$id));

        if(empty($data)){
            return redirect()->route('MainSalaryRecord.index')->with('error','عفواً غير قادر للوصول الى البيانات');
        }

        $currentYear=get_cols_where_row(new Finance_calender(),array("open_yr_flag"),array('com_code'=>$com_code,'finance_yr'=>$data['finance_yr']));
        if(empty($currentYear)){
            return redirect()->route('MainSalaryRecord.index')->with('error','عفواً غير قادر للوصول الى بيانات السنة المالية المطلوبة');
        }

        if($currentYear['open_yr_flag']!=1){
            return redirect()->route('MainSalaryRecord.index')->with('error','عفواً السنة المالية التابع لها هذا الشهر غير مفتوحة حالياً');

        }

        if($data['is_open']==1){
            return redirect()->route('MainSalaryRecord.index')->with('error','عفواً هذا الشهر بالفعل مفتوح');

        }

        if($data['is_open']==2){
            return redirect()->route('MainSalaryRecord.index')->with('error','عفواً هذا الشهر بالفعل مؤرشف من قبل');

        }

        $counterOpenMonth=get_count_where(new Finance_cin_periods(),array('com_code'=>$com_code,'is_open'=>1));
        
        if($counterOpenMonth>0){
            return redirect()->route('MainSalaryRecord.index')->with('error','عفواً لايمكن فتح هذا الشهر لوجود شهر مالي اخر مفتوح حاليا');

        }

        $counterPreviousMonthWatingOpen=Finance_cin_periods::where('com_code',$com_code)->where('finance_yr',$data['finance_yr'])->where('month_id','<',$data['month_id'])->where('is_open','=',0)->count();
        if($counterPreviousMonthWatingOpen>0){
            return redirect()->route('MainSalaryRecord.index')->with('error','عفواً لايمكن فتح هذا الشهر لوجود شهر اخر سابق له مستحق الفتح اولا');

        }

        $dataToUpdate=[
            'start_date_for_pasma'=>$request->start_date_for_pasma,
            'end_date_for_pasma'=>$request->end_date_for_pasma,
            'is_open'=>1,
            'updated_by'=>auth()->user()->id,
        ];

        $flag=update(new Finance_cin_periods(),$dataToUpdate,array('com_code'=>$com_code,'id'=>$id));
        
        if($flag){
            $all_employees=get_cols_where(new Employee(),array('*'),array('com_code'=>$com_code,'function_status'=>1),'employees_code','ASC');
        
            //كود فتح الرواتب للموظفين المستمرين في الخدمة
            if(!empty($all_employees)){
                foreach($all_employees as $info){
                    $DataSalaryToInsert=array();
                    
                        $DataSalaryToInsert['finance_cin_periods_id']=$id;
                        $DataSalaryToInsert['employees_code']=$info->employees_code;
                        $DataSalaryToInsert['com_code']=$com_code;
                        

                        $checkExsistsCounter=get_count_where(new Main_salary_employee(),$DataSalaryToInsert);
                        if($checkExsistsCounter==0){
                            
                                $DataSalaryToInsert['emp_name']=$info->emp_name;
                                $DataSalaryToInsert['day_price']=$info->day_price;
                                $DataSalaryToInsert['is_sensitive_manager_data']=$info->is_sensitive_manager_data;
                                $DataSalaryToInsert['branch_id']=$info->branch_id;
                                $DataSalaryToInsert['function_status']=$info->function_status;
                                $DataSalaryToInsert['emp_department_id']=$info->emp_department_id;
                                $DataSalaryToInsert['emp_jobs_id']=$info->emp_jobs_id;
                                //يعاد شرح رصيد الشهر السابق
                                $DataSalaryToInsert['last_salary_remain_balance']=0;
                                $DataSalaryToInsert['emp_sal']=$info->emp_sal;
                                $DataSalaryToInsert['year_and_month']=$data->year_and_month;
                                $DataSalaryToInsert['finance_yr']=$data->finance_yr;
                                $DataSalaryToInsert['sal_cach_or_visa']=$info->sal_cach_or_visa;
                                $DataSalaryToInsert['added_by']=auth()->user()->id;
                        
                        

                        insert(new Main_salary_employee(),$DataSalaryToInsert);

                        }
                }
            }
        }
        
        
        return redirect()->route('MainSalaryRecord.index')->with('success','تم فتح الشهر المالي');

    }

    public function load_open_month(Request $request){

        if($request->ajax()){
            $id=$request->id;
            $com_code = auth()->user()->com_code;
            $data=get_cols_where_row(new Finance_cin_periods(),array("*"),array('com_code'=>$com_code,'id'=>$id));
            return view('admin.MainSalaryRecord.load_open_monthModal',['data'=>$data]);
        }
    }


}
