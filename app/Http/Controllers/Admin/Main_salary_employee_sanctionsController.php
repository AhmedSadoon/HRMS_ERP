<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Finance_calender;
use App\Models\Finance_cin_periods;
use App\Models\Main_salary_employee;
use App\Models\Main_salary_employee_sanctions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Main_salary_employee_sanctionsController extends Controller
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

        return view('admin.Main_salary_employee_sanctions.index', compact('Finance_cin_periods'));
    }

    public function show($finance_cin_periods_id){
        $com_code = auth()->user()->com_code;
        $finance_cin_periods_data=get_cols_where_row(new Finance_cin_periods(),array("*"),array('com_code'=>$com_code,'id'=>$finance_cin_periods_id));
        if(empty($finance_cin_periods_data)){
            return redirect()->route('MainSalaryRecord.index')->with('error','عفواً غير قادر للوصول الى البينات المطلوبة');
        }

        $data = get_cols_where_paginate(new Main_salary_employee_sanctions(), array('*'), array('com_code' => $com_code,'finance_cin_periods_id'=>$finance_cin_periods_id),'id','DESC',PAGINATION_COUNTER);
        if(!empty($data)){
            foreach($data as $info){
                $info->emp_name=get_field_value(new Employee(),'emp_name',array('com_code'=>$com_code,'employees_code'=>$info->employees_code));
            }
        }

        $employees=get_cols_where(new Employee(),array("employees_code","emp_name","emp_salary","day_price"),array('com_code'=>$com_code),'employees_code','ASC');
        return view('admin.Main_salary_employee_sanctions.show', ['data'=>$data,'finance_cin_periods_data'=>$finance_cin_periods_data,'employees'=>$employees]);


    }

    
    public function checkExsistsBefor(Request $request){
        $com_code = auth()->user()->com_code;

        if($request->ajax()){
            $checkExsistsBeforCounter=get_count_where(new Main_salary_employee_sanctions(),array('com_code'=>$com_code,'finance_cin_periods_id'=>$request->the_finance_cin_periods_id,'employees_code'=>$request->employees_code));
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
                    'sactions_type'=>$request->sactions_type ,
                    'value'=>$request->value ,
                    'total'=>$request->total ,
                    'notes'=>$request->notes ,
                    'added_by'=>auth()->user()->id,
                    'com_code'=>$com_code
                ];

                
            insert(new Main_salary_employee_sanctions(),$dataToInsert);
            DB::commit();

            return json_encode("done");
                
            }
            
        }
    }

    public function ajax_search(Request $request) {
        if($request->ajax()){
            $employees_code=$request->employees_code;
            $sactions_type=$request->sactions_type;
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

            if($sactions_type=='all'){
                $field2="id";
                $operator2=">";
                $value2=0;
            }else{
                $field2="sactions_type";
                $operator2="=";
                $value2=$sactions_type;
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
            $data = Main_salary_employee_sanctions::select("*")->where($field1,$operator1,$value1)->where($field2,$operator2,$value2)->where($field3,$operator3,$value3)->where('finance_cin_periods_id','=',$the_finance_cin_periods_id)->where('com_code','=',$com_code)->orderBy('id','DESC')->paginate(PAGINATION_COUNTER);
            
            if(!empty($data)){
                foreach($data as $info){
                    $info->emp_name=get_field_value(new Employee(),'emp_name',array('com_code'=>$com_code,'employees_code'=>$info->employees_code));
                }
            }
        
            
            return view('admin.Main_salary_employee_sanctions.ajax_search', ['data'=>$data]);
        }
    }

    public function delete_row(Request $request) {

        
        if($request->ajax()){
            $com_code = auth()->user()->com_code;
            $finance_cin_periods_data=get_cols_where_row(new Finance_cin_periods(),array("id"),array('com_code'=>$com_code,'id'=>$request->the_finance_cin_periods_id,'is_open'=>1));
            $main_salary_employee_data=get_cols_where_row(new Main_salary_employee(),array("id"),array('com_code'=>$com_code,'finance_cin_periods_id'=>$request->the_finance_cin_periods_id,'id'=>$request->main_salary_employee_id,'is_archived'=>0));
            $data_row=get_cols_where_row(new Main_salary_employee_sanctions(),array("id"),array('com_code'=>$com_code,'id'=>$request->id,'is_archived'=>0,'finance_cin_periods_id'=>$request->the_finance_cin_periods_id,'main_salary_employee_id'=>$request->main_salary_employee_id));
            if(!empty($finance_cin_periods_data) and !empty($data_row) and !empty($main_salary_employee_data)){

                destroy(new Main_salary_employee_sanctions(),array('com_code'=>$com_code,'id'=>$request->id,'is_archived'=>0,'finance_cin_periods_id'=>$request->the_finance_cin_periods_id,'main_salary_employee_id'=>$request->main_salary_employee_id));
            
               return json_encode("done");
            }
        
            
        }
    }

    public function load_edit_row(Request $request) {
        if($request->ajax()){
            $com_code = auth()->user()->com_code;
            $finance_cin_periods_data=get_cols_where_row(new Finance_cin_periods(),array("id"),array('com_code'=>$com_code,'id'=>$request->the_finance_cin_periods_id,'is_open'=>1));
            $main_salary_employee_data=get_cols_where_row(new Main_salary_employee(),array("id"),array('com_code'=>$com_code,'finance_cin_periods_id'=>$request->the_finance_cin_periods_id,'id'=>$request->main_salary_employee_id,'is_archived'=>0));
            $data_row=get_cols_where_row(new Main_salary_employee_sanctions(),array("*"),array('com_code'=>$com_code,'id'=>$request->id,'is_archived'=>0,'finance_cin_periods_id'=>$request->the_finance_cin_periods_id,'main_salary_employee_id'=>$request->main_salary_employee_id));
            $employees=get_cols_where(new Employee(),array("employees_code","emp_name","emp_salary","day_price"),array('com_code'=>$com_code),'employees_code','ASC');


            return view('admin.Main_salary_employee_sanctions.load_edit_row',['finance_cin_periods_data'=>$finance_cin_periods_data,'main_salary_employee_data'=>$main_salary_employee_data,'data_row'=>$data_row,'employees'=>$employees]);
        }

    }

    
    public function do_edit_row(Request $request){
        $com_code = auth()->user()->com_code;

        if($request->ajax()){
            $finance_cin_periods_data=get_cols_where_row(new Finance_cin_periods(),array("*"),array('com_code'=>$com_code,'id'=>$request->the_finance_cin_periods_id,'is_open'=>1));
            $main_salary_employee_data=get_cols_where_row(new Main_salary_employee(),array("id"),array('com_code'=>$com_code,'finance_cin_periods_id'=>$request->the_finance_cin_periods_id,'employees_code'=>$request->employees_code,'is_archived'=>0));
            $data_row=get_cols_where_row(new Main_salary_employee_sanctions(),array("*"),array('com_code'=>$com_code,'id'=>$request->id,'is_archived'=>0,'finance_cin_periods_id'=>$request->the_finance_cin_periods_id,'main_salary_employee_id'=>$request->main_salary_employee_id));

            if(!empty($finance_cin_periods_data) && !empty($main_salary_employee_data) && !empty($data_row)){

                DB::beginTransaction();

                $dataToUdate=[
                   
                    'employees_code'=>$request->employees_code ,
                    'day_price'=>$request->day_price ,
                    'sactions_type'=>$request->sactions_type ,
                    'value'=>$request->value ,
                    'total'=>$request->total ,
                    'notes'=>$request->notes ,
                    'updated_by'=>auth()->user()->id,
                   
                ];

                
            update(new Main_salary_employee_sanctions(),$dataToUdate,array('com_code'=>$com_code,'id'=>$request->id,'is_archived'=>0,'finance_cin_periods_id'=>$request->the_finance_cin_periods_id,'main_salary_employee_id'=>$request->main_salary_employee_id));
            DB::commit();

            return json_encode("done");
                
            }
            
        }
    }

   
}
