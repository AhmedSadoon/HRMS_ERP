<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\admin_panel_setting;
use App\Models\Employee;
use App\Models\Finance_calender;
use App\Models\Finance_cin_periods;
use App\Models\Main_employee_investigations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MainEmployeesInvestigationsController extends Controller
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

        return view('admin.Main_employees_Investigations.index', compact('Finance_cin_periods'));
    }

    public function show($finance_cin_periods_id){
        $com_code = auth()->user()->com_code;
        $finance_cin_periods_data=get_cols_where_row(new Finance_cin_periods(),array("*"),array('com_code'=>$com_code,'id'=>$finance_cin_periods_id));
        if(empty($finance_cin_periods_data)){
            return redirect()->route('MainEmployeesInvestigations.index')->with('error','عفواً غير قادر للوصول الى البينات المطلوبة');
        }

        if($finance_cin_periods_data['is_open']==0){
            return redirect()->route('MainEmployeesInvestigations.index')->with('error','عفواً لايمكن العمل على شهر مالي لم يفتح بعد');

        }

        $data = get_cols_where_paginate(new Main_employee_investigations(), array('*'), array('com_code' => $com_code,'finance_cin_periods_id'=>$finance_cin_periods_id),'id','DESC',PAGINATION_COUNTER);
        if(!empty($data)){
            foreach($data as $info){
                $info->emp_name=get_field_value(new Employee(),'emp_name',array('com_code'=>$com_code,'employees_code'=>$info->employees_code));
            }
        }

      

        $employees=get_cols_where(new Employee(),array("employees_code","emp_name","emp_salary","day_price"),array('com_code'=>$com_code),'employees_code','ASC');
        return view('admin.Main_employees_Investigations.show', ['data'=>$data,'finance_cin_periods_data'=>$finance_cin_periods_data,'employees'=>$employees]);


    }

    public function checkExsistsBefor(Request $request){
        $com_code = auth()->user()->com_code;

        if($request->ajax()){
            $checkExsistsBeforCounter=get_count_where(new Main_employee_investigations(),array('com_code'=>$com_code,'finance_cin_periods_id'=>$request->the_finance_cin_periods_id,'employees_code'=>$request->employees_code));
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

            if(!empty($finance_cin_periods_data) ){

                DB::beginTransaction();

               
                    $dataToInsert['finance_cin_periods_id']=$request->finance_cin_periods_id ;
                    $dataToInsert['employees_code']=$request->employees_code ;
                    $dataToInsert['is_auto']=0 ;
                    $dataToInsert['content']=$request->content ;
                    $dataToInsert['notes']=$request->notes ;
                    $dataToInsert['added_by']=auth()->user()->id;
                    $dataToInsert['com_code']=$com_code;
               

                
            insert(new Main_employee_investigations(),$dataToInsert);
  
            DB::commit();

            return json_encode("done");
                
            }
            
        }
    }

    public function load_edit_row(Request $request) {
        if($request->ajax()){
            $com_code = auth()->user()->com_code;
            $finance_cin_periods_data=get_cols_where_row(new Finance_cin_periods(),array("id"),array('com_code'=>$com_code,'id'=>$request->the_finance_cin_periods_id,'is_open'=>1));
            $data_row=get_cols_where_row(new Main_employee_investigations(),array("*"),array('com_code'=>$com_code,'id'=>$request->id,'is_archived'=>0,'finance_cin_periods_id'=>$request->the_finance_cin_periods_id));
            
            $employees=get_cols_where(new Employee(),array("employees_code","emp_name","emp_salary","day_price"),array('com_code'=>$com_code),'employees_code','ASC');


            return view('admin.Main_employees_Investigations.load_edit_row',['finance_cin_periods_data'=>$finance_cin_periods_data,'data_row'=>$data_row,'employees'=>$employees]);
        }

    }

    
    public function do_edit_row(Request $request){
        $com_code = auth()->user()->com_code;

        if($request->ajax()){
            $finance_cin_periods_data=get_cols_where_row(new Finance_cin_periods(),array("*"),array('com_code'=>$com_code,'id'=>$request->the_finance_cin_periods_id,'is_open'=>1));
            $data_row=get_cols_where_row(new Main_employee_investigations(),array("*"),array('com_code'=>$com_code,'id'=>$request->id,'is_archived'=>0,'finance_cin_periods_id'=>$request->the_finance_cin_periods_id));

            if(!empty($finance_cin_periods_data) && !empty($data_row)){

                DB::beginTransaction();
                    $dataToUdate['employees_code']=$request->employees_code; 
                    $dataToUdate['content']=$request->content;
                    $dataToUdate['notes']=$request->notes;
                    $dataToUdate['updated_by']=auth()->user()->id;
                  
            update(new Main_employee_investigations(),$dataToUdate,array('com_code'=>$com_code,'id'=>$request->id,'is_archived'=>0,'finance_cin_periods_id'=>$request->the_finance_cin_periods_id));

            DB::commit();

            return json_encode("done");
                
            }
            
        }
    }

    public function delete_row(Request $request) {

        
        if($request->ajax()){
            $com_code = auth()->user()->com_code;
            $finance_cin_periods_data=get_cols_where_row(new Finance_cin_periods(),array("id"),array('com_code'=>$com_code,'id'=>$request->the_finance_cin_periods_id,'is_open'=>1));
            $data_row=get_cols_where_row(new Main_employee_investigations(),array("id"),array('com_code'=>$com_code,'id'=>$request->id,'is_archived'=>0,'finance_cin_periods_id'=>$request->the_finance_cin_periods_id));
            if(!empty($finance_cin_periods_data) and !empty($data_row)){

                destroy(new Main_employee_investigations(),array('com_code'=>$com_code,'id'=>$request->id,'is_archived'=>0,'finance_cin_periods_id'=>$request->the_finance_cin_periods_id));
               
               
               return json_encode("done");
            }
        
            
        }
    }

    public function ajax_search(Request $request) {
        if($request->ajax()){
            $employees_code=$request->employees_code;
            $is_auto=$request->is_auto;
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

            if($is_auto=='all'){
                $field2="id";
                $operator2=">";
                $value2=0;
            }else{
                $field2="is_auto";
                $operator2="=";
                $value2=$is_auto;
            }

            if($is_archived=='all'){
                $field3="id";
                $operator3=">";
                $value3=0;
            }else{
                $field3="is_archived";
                $operator3="=";
                $value3=$is_archived;
            }
    
            $com_code = auth()->user()->com_code;
            $data = Main_employee_investigations::select("*")->where($field1,$operator1,$value1)->where($field2,$operator2,$value2)->where($field3,$operator3,$value3)->where('finance_cin_periods_id','=',$the_finance_cin_periods_id)->where('com_code','=',$com_code)->orderBy('id','DESC')->paginate(PAGINATION_COUNTER);
            
            if(!empty($data)){
                foreach($data as $info){
                    $info->emp_name=get_field_value(new Employee(),'emp_name',array('com_code'=>$com_code,'employees_code'=>$info->employees_code));
                }
            }
        
            
            return view('admin.Main_employees_Investigations.ajax_search', ['data'=>$data]);
        }
    }
  
    public function print_search(Request $request) {

            $employees_code=$request->employees_code_search;
            $is_auto=$request->is_auto;
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

            if($is_auto=='all'){
                $field2="id";
                $operator2=">";
                $value2=0;
            }else{
                $field2="is_auto";
                $operator2="=";
                $value2=$is_auto;
            }

            if($is_archived=='all'){
                $field3="id";
                $operator3=">";
                $value3=0;
            }else{
                $field3="is_archived";
                $operator3="=";
                $value3=$is_archived;
            }
    
            $com_code = auth()->user()->com_code;
            $other['value_sum']=0;
            $other['total_sum']=0;
            $data = Main_employee_investigations::select("*")->where($field1,$operator1,$value1)->where($field2,$operator2,$value2)->where($field3,$operator3,$value3)->where('finance_cin_periods_id','=',$the_finance_cin_periods_id)->where('com_code','=',$com_code)->orderBy('id','DESC')->get();
            $finance_cin_periods_data=get_cols_where_row(new Finance_cin_periods(),array("*"),array('com_code'=>$com_code,'id'=>$the_finance_cin_periods_id));

            if(!empty($data)){
                foreach($data as $info){
                    $info->emp_name=get_field_value(new Employee(),'emp_name',array('com_code'=>$com_code,'employees_code'=>$info->employees_code));
                    $other['value_sum']+=$info->value;
                    $other['total_sum']+=$info->total;
                }
            }
        
            $systemData=get_cols_where_row(new admin_panel_setting(),array('company_name','image','phone','address'),array('com_code'=>$com_code));
            return view('admin.Main_employees_Investigations.print_search', ['data'=>$data,'finance_cin_periods_data'=>$finance_cin_periods_data,'systemData'=>$systemData,'other'=>$other]);
        
    }
}
