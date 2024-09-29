<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\admin_panel_setting;
use App\Models\Employee;
use App\Models\Finance_calender;
use App\Models\Finance_cin_periods;
use App\Models\Main_salary_employee;
use App\Models\Main_salary_employee_Absence;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Main_salary_employee_AbsenceController extends Controller
{
    use GeneralTrait;
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

        return view('admin.Main_salary_employee_absence.index', compact('Finance_cin_periods'));
    }

    public function show($finance_cin_periods_id){
        $com_code = auth()->user()->com_code;
        $finance_cin_periods_data=get_cols_where_row(new Finance_cin_periods(),array("*"),array('com_code'=>$com_code,'id'=>$finance_cin_periods_id));
        if(empty($finance_cin_periods_data)){
            return redirect()->route('MainSalaryAbsence.index')->with('error','عفواً غير قادر للوصول الى البينات المطلوبة');
        }

        $data = get_cols_where_paginate(new Main_salary_employee_Absence, array('*'), array('com_code' => $com_code,'finance_cin_periods_id'=>$finance_cin_periods_id),'id','DESC',PAGINATION_COUNTER);
        if(!empty($data)){
            foreach($data as $info){
                $info->emp_name=get_field_value(new Employee(),'emp_name',array('com_code'=>$com_code,'employees_code'=>$info->employees_code));
            }
        }

        $employees=Main_salary_employee::where('com_code','=',$com_code)->where('finance_cin_periods_id','=',$finance_cin_periods_id)->distinct()->get('employees_code');
        
        if(!empty($employees)){
            foreach($employees as $info){
                $info->EmployeeData=get_cols_where_row(new Employee(),array("employees_code","emp_name","emp_salary","day_price"),array('com_code'=>$com_code,'employees_code'=>$info->employees_code));
            }
        }

        $employees_search=get_cols_where(new Employee(),array("employees_code","emp_name","emp_salary","day_price"),array('com_code'=>$com_code),'employees_code','ASC');
        return view('admin.Main_salary_employee_absence.show', ['data'=>$data,'finance_cin_periods_data'=>$finance_cin_periods_data,'employees'=>$employees,'employees_search'=>$employees_search]);


    }

    
    public function checkExsistsBefor(Request $request){
        $com_code = auth()->user()->com_code;

        if($request->ajax()){
            $checkExsistsBeforCounter=get_count_where(new Main_salary_employee_Absence(),array('com_code'=>$com_code,'finance_cin_periods_id'=>$request->the_finance_cin_periods_id,'employees_code'=>$request->employees_code));
            if($checkExsistsBeforCounter>0){
                return json_encode("exsists_befor");
            }else{
                return json_encode("no_exsists_befor");
            }
        }
    }

    public function store(Request $request){
        $com_code = auth()->user()->com_code;

        if($request->ajax()){
            $finance_cin_periods_data=get_cols_where_row(new Finance_cin_periods(),array("*"),array('com_code'=>$com_code,'id'=>$request->finance_cin_periods_id,'is_open'=>1));

            $main_salary_employee_data=get_cols_where_row(new Main_salary_employee(),array("id"),array('com_code'=>$com_code,'finance_cin_periods_id'=>$request->finance_cin_periods_id,'employees_code'=>$request->employees_code,'is_archived'=>0));
            if(!empty($finance_cin_periods_data) && !empty($main_salary_employee_data)){

                DB::beginTransaction();

                $dataToInsert=[
                    'main_salary_employee_id'=>$main_salary_employee_data['id'],
                    'finance_cin_periods_id'=>$request->finance_cin_periods_id ,
                    'employees_code'=>$request->employees_code ,
                    'is_auto'=>1 ,
                    'day_price'=>$request->day_price ,
                    'value'=>$request->value ,
                    'total'=>$request->total ,
                    'notes'=>$request->notes ,
                    'added_by'=>auth()->user()->id,
                    'com_code'=>$com_code
                ];

                
            $flag=insert(new Main_salary_employee_Absence(),$dataToInsert);
            if(!empty($flag)){
                $this->Recalculate_main_salary_employee($main_salary_employee_data['id']);
            }  
            DB::commit();

            return json_encode("done");
                
            }
            
        }
    }

    public function ajax_search(Request $request) {
        if($request->ajax()){
            $employees_code=$request->employees_code;
            $is_archived=$request->is_archived;
            $the_finance_cin_periods_id=$request->the_finance_cin_periods_id;
    
            if($employees_code=='all'){
                $field1="id";
                $operator1=">";
                $value1=0;
            }else{
                $field1="employees_code";
                $operator1="=";
                $value1=$employees_code;
            }

         

            if($is_archived=='all'){
                $field2="id";
                $operator2=">";
                $value2=0;
            }else{
                $field2="is_archived";
                $operator2="=";
                $value2=$is_archived;
            }
    
            $com_code = auth()->user()->com_code;
            $data = Main_salary_employee_Absence::select("*")->where($field1,$operator1,$value1)->where($field2,$operator2,$value2)->where('finance_cin_periods_id','=',$the_finance_cin_periods_id)->where('com_code','=',$com_code)->orderBy('id','DESC')->paginate(PAGINATION_COUNTER);
            
            if(!empty($data)){
                foreach($data as $info){
                    $info->emp_name=get_field_value(new Employee(),'emp_name',array('com_code'=>$com_code,'employees_code'=>$info->employees_code));
                }
            }
        
            
            return view('admin.Main_salary_employee_absence.ajax_search', ['data'=>$data]);
        }
    }

    public function delete_row(Request $request) {

        
        if($request->ajax()){
            $com_code = auth()->user()->com_code;
            $finance_cin_periods_data=get_cols_where_row(new Finance_cin_periods(),array("id"),array('com_code'=>$com_code,'id'=>$request->the_finance_cin_periods_id,'is_open'=>1));
            $main_salary_employee_data=get_cols_where_row(new Main_salary_employee(),array("id"),array('com_code'=>$com_code,'finance_cin_periods_id'=>$request->the_finance_cin_periods_id,'id'=>$request->main_salary_employee_id,'is_archived'=>0));
            $data_row=get_cols_where_row(new Main_salary_employee_Absence(),array("id"),array('com_code'=>$com_code,'id'=>$request->id,'is_archived'=>0,'finance_cin_periods_id'=>$request->the_finance_cin_periods_id,'main_salary_employee_id'=>$request->main_salary_employee_id));
            if(!empty($finance_cin_periods_data) and !empty($data_row) and !empty($main_salary_employee_data)){

                $flag=destroy(new Main_salary_employee_Absence(),array('com_code'=>$com_code,'id'=>$request->id,'is_archived'=>0,'finance_cin_periods_id'=>$request->the_finance_cin_periods_id,'main_salary_employee_id'=>$request->main_salary_employee_id));
                if(!empty($flag)){
                    $this->Recalculate_main_salary_employee($request->main_salary_employee_id);
                } 
               return json_encode("done");
            }
        
            
        }
    }

    public function load_edit_row(Request $request) {
        if($request->ajax()){
            $com_code = auth()->user()->com_code;
            $finance_cin_periods_data=get_cols_where_row(new Finance_cin_periods(),array("id"),array('com_code'=>$com_code,'id'=>$request->the_finance_cin_periods_id,'is_open'=>1));
            $main_salary_employee_data=get_cols_where_row(new Main_salary_employee(),array("id"),array('com_code'=>$com_code,'finance_cin_periods_id'=>$request->the_finance_cin_periods_id,'id'=>$request->main_salary_employee_id,'is_archived'=>0));
            $data_row=get_cols_where_row(new Main_salary_employee_Absence(),array("*"),array('com_code'=>$com_code,'id'=>$request->id,'is_archived'=>0,'finance_cin_periods_id'=>$request->the_finance_cin_periods_id,'main_salary_employee_id'=>$request->main_salary_employee_id));
            
            $employees=Main_salary_employee::where('com_code','=',$com_code)->where('finance_cin_periods_id','=',$request->the_finance_cin_periods_id)->distinct()->get('employees_code');
        
            if(!empty($employees)){
                foreach($employees as $info){
                    $info->EmployeeData=get_cols_where_row(new Employee(),array("employees_code","emp_name","emp_salary","day_price"),array('com_code'=>$com_code,'employees_code'=>$info->employees_code));
                }
            }

            return view('admin.Main_salary_employee_absence.load_edit_row',['finance_cin_periods_data'=>$finance_cin_periods_data,'main_salary_employee_data'=>$main_salary_employee_data,'data_row'=>$data_row,'employees'=>$employees]);
        }

    }

    
    public function do_edit_row(Request $request){
        $com_code = auth()->user()->com_code;

        if($request->ajax()){
            $finance_cin_periods_data=get_cols_where_row(new Finance_cin_periods(),array("*"),array('com_code'=>$com_code,'id'=>$request->the_finance_cin_periods_id,'is_open'=>1));
            $main_salary_employee_data=get_cols_where_row(new Main_salary_employee(),array("id"),array('com_code'=>$com_code,'finance_cin_periods_id'=>$request->the_finance_cin_periods_id,'id'=>$request->main_salary_employee_id,'employees_code'=>$request->employees_code,'is_archived'=>0));
            $data_row=get_cols_where_row(new Main_salary_employee_Absence(),array("*"),array('com_code'=>$com_code,'id'=>$request->id,'is_archived'=>0,'finance_cin_periods_id'=>$request->the_finance_cin_periods_id,'main_salary_employee_id'=>$request->main_salary_employee_id));

            if(!empty($finance_cin_periods_data) && !empty($main_salary_employee_data) && !empty($data_row)){

                DB::beginTransaction();

                $dataToUdate=[
                   
                    'employees_code'=>$request->employees_code ,
                    'day_price'=>$request->day_price ,
                    'value'=>$request->value ,
                    'total'=>$request->total ,
                    'notes'=>$request->notes ,
                    'updated_by'=>auth()->user()->id,
                   
                ];

                
            $flag=update(new Main_salary_employee_Absence(),$dataToUdate,array('com_code'=>$com_code,'id'=>$request->id,'is_archived'=>0,'finance_cin_periods_id'=>$request->the_finance_cin_periods_id,'main_salary_employee_id'=>$request->main_salary_employee_id));
            if(!empty($flag)){
                $this->Recalculate_main_salary_employee($request->main_salary_employee_id);
            } 
            DB::commit();

            return json_encode("done");
                
            }
            
        }
    }

    public function print_search(Request $request) {

            $employees_code=$request->employees_code_search;
            $is_archived=$request->is_archived_search;
            $the_finance_cin_periods_id=$request->the_finance_cin_periods_id;

            if($employees_code=='all'){
                $field1="id";
                $operator1=">";
                $value1=0;
            }else{
                $field1="employees_code";
                $operator1="=";
                $value1=$employees_code;
            }

           

            if($is_archived=='all'){
                $field2="id";
                $operator2=">";
                $value2=0;
            }else{
                $field2="is_archived";
                $operator2="=";
                $value2=$is_archived;
            }
    
            $com_code = auth()->user()->com_code;
            $other['value_sum']=0;
            $other['total_sum']=0;
            $data = Main_salary_employee_Absence::select("*")->where($field1,$operator1,$value1)->where($field2,$operator2,$value2)->where('finance_cin_periods_id','=',$the_finance_cin_periods_id)->where('com_code','=',$com_code)->orderBy('id','DESC')->get();
            $finance_cin_periods_data=get_cols_where_row(new Finance_cin_periods(),array("*"),array('com_code'=>$com_code,'id'=>$the_finance_cin_periods_id));

            if(!empty($data)){
                foreach($data as $info){
                    $info->emp_name=get_field_value(new Employee(),'emp_name',array('com_code'=>$com_code,'employees_code'=>$info->employees_code));
                    $other['value_sum']+=$info->value;
                    $other['total_sum']+=$info->total;
                }
            }
        
            $systemData=get_cols_where_row(new admin_panel_setting(),array('company_name','image','phone','address'),array('com_code'=>$com_code));
            return view('admin.Main_salary_employee_absence.print_search', ['data'=>$data,'finance_cin_periods_data'=>$finance_cin_periods_data,'systemData'=>$systemData,'other'=>$other]);
        
    }
}
