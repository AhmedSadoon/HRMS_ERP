<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\admin_panel_setting;
use App\Models\Branche;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Finance_calender;
use App\Models\Finance_cin_periods;
use App\Models\jobs_category;
use App\Models\Main_salary_employee;
use App\Models\Main_salary_employee_Absence;
use App\Models\Main_salary_employee_addition;
use App\Models\Main_salary_employee_allowances;
use App\Models\Main_salary_employee_discount;
use App\Models\Main_salary_employee_loans;
use App\Models\Main_salary_employee_p_loans_aksat;
use App\Models\Main_salary_employee_rewards;
use App\Models\Main_salary_employee_sanctions;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class Main_salary_employeeController extends Controller
{
    use GeneralTrait;
    public function index()
    {
        $com_code = auth()->user()->com_code;
        $Finance_cin_periods = get_cols_where_paginate_order2(new Finance_cin_periods(), array('*'), array('com_code' => $com_code), 'finance_yr', 'DESC', 'month_id', 'ASC', 12);

        if (!empty($Finance_cin_periods)) {
            foreach ($Finance_cin_periods as $info) {
                //chech status to open month
                $info->currentYear = get_cols_where_row(new Finance_calender(), array("open_yr_flag"), array('com_code' => $com_code, 'finance_yr' => $info->finance_yr));
                $info->counterOpenMonth = get_count_where(new Finance_cin_periods(), array('com_code' => $com_code, 'is_open' => 1));
                $info->counterPreviousMonthWatingOpen = Finance_cin_periods::where('com_code', $com_code)->where('finance_yr', $info->finance_yr)->where('month_id', '<', $info->month_id)->where('is_open', '=', 0)->count();
            }
        }

        return view('admin.Main_salary_employee.index', compact('Finance_cin_periods'));
    }

    public function show($finance_cin_periods_id)
    {
        $com_code = auth()->user()->com_code;
        $finance_cin_periods_data = get_cols_where_row(new Finance_cin_periods(), array("*"), array('com_code' => $com_code, 'id' => $finance_cin_periods_id));
        if (empty($finance_cin_periods_data)) {
            return redirect()->route('MainSalaryEmployee.index')->with('error', 'عفواً غير قادر للوصول الى البينات المطلوبة');
        }

        $data = get_cols_where_paginate(new Main_salary_employee(), array('id', 'employees_code', 'total_benefits', 'total_deductions', 'final_the_net', 'is_take_action_diss_collec', 'is_stoped', 'branch_id', 'emp_department_id', 'emp_jobs_id','is_archived'), array('com_code' => $com_code, 'finance_cin_periods_id' => $finance_cin_periods_id), 'id', 'DESC', PAGINATION_COUNTER);
        if (!empty($data)) {
            foreach ($data as $info) {
                $info->emp_name = get_field_value(new Employee(), 'emp_name', array('com_code' => $com_code, 'employees_code' => $info->employees_code));
                $info->emp_photo = get_field_value(new Employee(), 'emp_photo', array('com_code' => $com_code, 'employees_code' => $info->employees_code));
                $info->emp_gender = get_field_value(new Employee(), 'emp_gender', array('com_code' => $com_code, 'employees_code' => $info->employees_code));
                $info->branch_name = get_field_value(new Branche(), 'name', array('com_code' => $com_code, 'id' => $info->branch_id));
                $info->department_name = get_field_value(new Department(), 'name', array('com_code' => $com_code, 'id' => $info->emp_department_id));
                $info->jobs_name = get_field_value(new jobs_category(), 'name', array('com_code' => $com_code, 'id' => $info->emp_jobs_id));
            }
        }

        $other['branches'] = get_cols_where(new Branche(), array("id", "name"), array('com_code' => $com_code, 'active' => 1));
        $other['departments'] = get_cols_where(new Department(), array("id", "name"), array('com_code' => $com_code, 'active' => 1));
        $other['jobs'] = get_cols_where(new jobs_category(), array("id", "name"), array('com_code' => $com_code, 'active' => 1));
        $other['employees'] = get_cols_where(new Employee(), array("employees_code", "emp_name", "emp_salary", "day_price"), array('com_code' => $com_code), 'employees_code', 'ASC');
        $other['nothave'] = 0;

        if ($finance_cin_periods_data['is_open'] == 1) {
            if (!empty($other['employees'])) {
                foreach ($other['employees'] as $info) {
                    $info->counter = get_count_where(new Main_salary_employee(), array("com_code" => $com_code, "employees_code" => $info->employees_code, 'finance_cin_periods_id' => $finance_cin_periods_id));
                    if ($info->counter == 0) {
                        $other['nothave']++;
                    }
                }
            }
        }

        $other['counterSalaries']=get_count_where(new Main_salary_employee(),array('com_code' => $com_code, 'finance_cin_periods_id' => $finance_cin_periods_id));
        $other['counterSalariesWatingArchive']=get_count_where(new Main_salary_employee(),array('com_code' => $com_code, 'finance_cin_periods_id' => $finance_cin_periods_id,'is_archived'=>0));
        $other['counterSalariesDoneArchive']=get_count_where(new Main_salary_employee(),array('com_code' => $com_code, 'finance_cin_periods_id' => $finance_cin_periods_id,'is_archived'=>1));
        $other['counterSalariesStopped']=get_count_where(new Main_salary_employee(),array('com_code' => $com_code, 'finance_cin_periods_id' => $finance_cin_periods_id,'is_stoped'=>1));
        return view('admin.Main_salary_employee.show', ['data' => $data, 'finance_cin_periods_data' => $finance_cin_periods_data, 'other' => $other]);
    }


    public function ajax_search(Request $request)
    {
        if ($request->ajax()) {
            $employees_code = $request->employees_code;
            $branch_id = $request->branch_id;
            $emp_department_id = $request->emp_department_id;
            $emp_jobs_id = $request->emp_jobs_id;
            $function_status = $request->function_status;
            $sal_cach_or_visa = $request->sal_cach_or_visa;
            $is_stoped = $request->is_stoped;
            $is_archived = $request->is_archived;
            $the_finance_cin_periods_id = $request->the_finance_cin_periods_id;

            if ($employees_code == 'all') {
                $field1 = "id";
                $operator1 = ">";
                $value1 = 0;
            } else {
                $field1 = "employees_code";
                $operator1 = "=";
                $value1 = $employees_code;
            }

            if ($branch_id == 'all') {
                $field2 = "id";
                $operator2 = ">";
                $value2 = 0;
            } else {
                $field2 = "branch_id";
                $operator2 = "=";
                $value2 = $branch_id;
            }

            if ($emp_department_id == 'all') {
                $field3 = "id";
                $operator3 = ">";
                $value3 = 0;
            } else {
                $field3 = "emp_department_id";
                $operator3 = "=";
                $value3 = $emp_department_id;
            }

            if ($emp_jobs_id == 'all') {
                $field4 = "id";
                $operator4 = ">";
                $value4 = 0;
            } else {
                $field4 = "emp_jobs_id";
                $operator4 = "=";
                $value4 = $emp_jobs_id;
            }

            if ($function_status == 'all') {
                $field5 = "id";
                $operator5 = ">";
                $value5 = 0;
            } else {
                $field5 = "function_status";
                $operator5 = "=";
                $value5 = $function_status;
            }


            if ($sal_cach_or_visa == 'all') {
                $field6 = "id";
                $operator6 = ">";
                $value6 = 0;
            } else {
                $field6 = "sal_cach_or_visa";
                $operator6 = "=";
                $value6 = $sal_cach_or_visa;
            }

            if ($is_stoped == 'all') {
                $field7 = "id";
                $operator7 = ">";
                $value7 = 0;
            } else {
                $field7 = "is_stoped";
                $operator7 = "=";
                $value7 = $is_stoped;
            }



            if ($is_archived == 'all') {
                $field8 = "id";
                $operator8 = ">";
                $value8 = 0;
            } else {
                $field8 = "is_archived";
                $operator8 = "=";
                $value8 = $is_archived;
            }

            $com_code = auth()->user()->com_code;
            $data = Main_salary_employee::select("*")
                ->where($field1, $operator1, $value1)
                ->where($field2, $operator2, $value2)
                ->where($field3, $operator3, $value3)
                ->where($field4, $operator4, $value4)
                ->where($field5, $operator5, $value5)
                ->where($field6, $operator6, $value6)
                ->where($field7, $operator7, $value7)
                ->where($field8, $operator8, $value8)
                ->where('finance_cin_periods_id', '=', $the_finance_cin_periods_id)
                ->where('com_code', '=', $com_code)
                ->orderBy('id', 'DESC')
                ->paginate(PAGINATION_COUNTER);

           

            if (!empty($data)) {
                foreach ($data as $info) {
                    $info->emp_name = get_field_value(new Employee(), 'emp_name', array('com_code' => $com_code, 'employees_code' => $info->employees_code));
                    $info->emp_photo = get_field_value(new Employee(), 'emp_photo', array('com_code' => $com_code, 'employees_code' => $info->employees_code));
                    $info->emp_gender = get_field_value(new Employee(), 'emp_gender', array('com_code' => $com_code, 'employees_code' => $info->employees_code));
                    $info->branch_name = get_field_value(new Branche(), 'name', array('com_code' => $com_code, 'id' => $info->branch_id));
                    $info->department_name = get_field_value(new Department(), 'name', array('com_code' => $com_code, 'id' => $info->emp_department_id));
                    $info->jobs_name = get_field_value(new jobs_category(), 'name', array('com_code' => $com_code, 'id' => $info->emp_jobs_id));
                }
            }

            $other['counterSalaries'] = Main_salary_employee::where($field1, $operator1, $value1)
            ->where($field2, $operator2, $value2)
            ->where($field3, $operator3, $value3)
            ->where($field4, $operator4, $value4)
            ->where($field5, $operator5, $value5)
            ->where($field6, $operator6, $value6)
            ->where($field7, $operator7, $value7)
            ->where($field8, $operator8, $value8)
            ->where('finance_cin_periods_id', '=', $the_finance_cin_periods_id)
            ->where('com_code', '=', $com_code)
            ->count();

            $other['counterSalariesWatingArchive'] = Main_salary_employee::where($field1, $operator1, $value1)
            ->where($field2, $operator2, $value2)
            ->where($field3, $operator3, $value3)
            ->where($field4, $operator4, $value4)
            ->where($field5, $operator5, $value5)
            ->where($field6, $operator6, $value6)
            ->where($field7, $operator7, $value7)
            ->where($field8, $operator8, $value8)
            ->where('finance_cin_periods_id', '=', $the_finance_cin_periods_id)
            ->where('com_code', '=', $com_code)
            ->where('is_archived','=',0)
            ->count();

            $other['counterSalariesDoneArchive'] = Main_salary_employee::where($field1, $operator1, $value1)
            ->where($field2, $operator2, $value2)
            ->where($field3, $operator3, $value3)
            ->where($field4, $operator4, $value4)
            ->where($field5, $operator5, $value5)
            ->where($field6, $operator6, $value6)
            ->where($field7, $operator7, $value7)
            ->where($field8, $operator8, $value8)
            ->where('finance_cin_periods_id', '=', $the_finance_cin_periods_id)
            ->where('com_code', '=', $com_code)
            ->where('is_archived','=',1)
            ->count();

            
            $other['counterSalariesStopped'] = Main_salary_employee::where($field1, $operator1, $value1)
            ->where($field2, $operator2, $value2)
            ->where($field3, $operator3, $value3)
            ->where($field4, $operator4, $value4)
            ->where($field5, $operator5, $value5)
            ->where($field6, $operator6, $value6)
            ->where($field7, $operator7, $value7)
            ->where($field8, $operator8, $value8)
            ->where('finance_cin_periods_id', '=', $the_finance_cin_periods_id)
            ->where('com_code', '=', $com_code)
            ->where('is_stoped','=',1)
            ->count();


            return view('admin.Main_salary_employee.ajax_search', ['data' => $data,'other'=>$other]);
        }
    }


    public function AddManuallySalary(Request $request, $finance_cin_periods_id)
    {
        $com_code = auth()->user()->com_code;
        $finance_cin_periods_data = get_cols_where_row(new Finance_cin_periods(), array("*"), array('com_code' => $com_code, 'id' => $finance_cin_periods_id));
        if (empty($finance_cin_periods_data)) {
            return redirect()->route('MainSalaryEmployee.index')->with('error', 'عفواً غير قادر للوصول الى البينات المطلوبة');
        }

        if ($finance_cin_periods_data['is_open'] != 1) {
            return redirect()->back()->with('error', 'عفواً لايمكن اضافة رواتب للشهر المالي الحالي في هذه المرحلة');
        }

        $employeeData = get_cols_where_row(new Employee(), array('*'), array('com_code' => $com_code, 'employees_code' => $request->employees_code_Add));

        if (empty($employeeData)) {
            return redirect()->back()->with('error', 'عفواً غير قادر للوصول لبيانات الموظف');
        }
        //كود فتح الرواتب للموظفين المستمرين في الخدمة

        $DataSalaryToInsert['finance_cin_periods_id'] = $finance_cin_periods_id;
        $DataSalaryToInsert['employees_code'] = $employeeData['employees_code'];
        $DataSalaryToInsert['com_code'] = $com_code;


        $checkExsistsCounter = get_count_where(new Main_salary_employee(), $DataSalaryToInsert);
        if ($checkExsistsCounter > 0) {
            return redirect()->back()->with('error', 'عفواً هذا الموظف له سجل راتب مضاف مسبقا');
        }

        $DataSalaryToInsert['emp_name'] = $employeeData['emp_name'];
        $DataSalaryToInsert['day_price'] = $employeeData['day_price'];
        $DataSalaryToInsert['is_sensitive_manager_data'] = $employeeData['is_sensitive_manager_data'];
        $DataSalaryToInsert['branch_id'] = $employeeData['branch_id'];
        $DataSalaryToInsert['function_status'] = $employeeData['function_status'];
        $DataSalaryToInsert['emp_department_id'] = $employeeData['emp_department_id'];
        $DataSalaryToInsert['emp_jobs_id'] = $employeeData['emp_jobs_id'];
        $lastSalaryData = get_cols_where_row_orderby(new Main_salary_employee(), array('final_the_net_after_close_for_trahil'), array('com_code' => $com_code, 'employees_code' => $employeeData['employees_code'], 'is_archived' => 1), 'id', 'DESC');
        if (!empty($lastSalaryData)) {
            $DataSalaryToInsert['last_salary_remain_balance'] = $lastSalaryData['final_the_net_after_close_for_trahil'];
        } else {
            $DataSalaryToInsert['last_salary_remain_balance'] = 0;
        }

        $DataSalaryToInsert['emp_sal'] = $employeeData['emp_sal'];
        $DataSalaryToInsert['year_and_month'] = $finance_cin_periods_data['year_and_month'];
        $DataSalaryToInsert['finance_yr'] = $finance_cin_periods_data['finance_yr'];
        $DataSalaryToInsert['sal_cach_or_visa'] = $employeeData['sal_cach_or_visa'];
        $DataSalaryToInsert['added_by'] = auth()->user()->id;



        $flagInsert = insert(new Main_salary_employee(), $DataSalaryToInsert, true);
        if (!empty($flagInsert)) {
            $this->Recalculate_main_salary_employee($flagInsert['id']);
        }



        return redirect()->back()->with('success', 'لقد تم اضافة راتب الموظف بالشهر المالي بنجاح');
    }

    public function delete_salary(Request $request)
    {
        if ($request->ajax()) {

            $com_code = auth()->user()->com_code;
            $finance_cin_periods_data = get_cols_where_row(new Finance_cin_periods(), array("*"), array('com_code' => $com_code, 'id' => $request->the_finance_cin_periods_id, 'is_open' => 1));
            if (!empty($finance_cin_periods_data)) {

                $MainSalaryEmployeeData = get_cols_where_row(new Main_salary_employee(), array("*"), array('com_code' => $com_code, 'id' => $request->id, 'finance_cin_periods_id' => $request->the_finance_cin_periods_id, 'is_archived' => 0));

                if (!empty($MainSalaryEmployeeData)) {
                    destroy(new Main_salary_employee(), array('com_code' => $com_code, 'id' => $request->id, 'finance_cin_periods_id' => $request->the_finance_cin_periods_id, 'is_archived' => 0));
                    return json_encode('done');
                }
            }
        }
    }

    public function showSalaryDetails($Main_salary_employeeID)
    {


        $com_code = auth()->user()->com_code;
        $MainSalaryEmployeeData = get_cols_where_row(new Main_salary_employee(), array("*"), array('com_code' => $com_code, 'id' => $Main_salary_employeeID));

        if (empty($MainSalaryEmployeeData)) {
            return redirect()->back()->with('error', 'عفواً غير قادر للوصول الى البيانات المطلوبة');
        }
         $finance_cin_periods_data = get_cols_where_row(new Finance_cin_periods(), array("*"), array('com_code' => $com_code, 'id' => $MainSalaryEmployeeData['finance_cin_periods_id']));
        if (empty($finance_cin_periods_data)) {
            return redirect()->back()->with('error', 'عفواً غير قادر للوصول الى البيانات المطلوبة');
        }

        if($MainSalaryEmployeeData['is_archived']==0){
            $this->Recalculate_main_salary_employee($Main_salary_employeeID);
            $MainSalaryEmployeeData = get_cols_where_row(new Main_salary_employee(), array("*"), array('com_code' => $com_code, 'id' => $Main_salary_employeeID));

        }
                $MainSalaryEmployeeData['emp_name'] = get_field_value(new Employee(), 'emp_name', array('com_code' => $com_code, 'employees_code' => $MainSalaryEmployeeData['employees_code']));
                $MainSalaryEmployeeData['emp_gender'] = get_field_value(new Employee(), 'emp_gender', array('com_code' => $com_code, 'employees_code' => $MainSalaryEmployeeData['employees_code']));
                $MainSalaryEmployeeData['branch_name'] = get_field_value(new Branche(), 'name', array('com_code' => $com_code, 'id' => $MainSalaryEmployeeData['branch_id']));
                $MainSalaryEmployeeData['department_name'] = get_field_value(new Department(), 'name', array('com_code' => $com_code, 'id' => $MainSalaryEmployeeData['emp_department_id']));
                $MainSalaryEmployeeData['jobs_name'] = get_field_value(new jobs_category(), 'name', array('com_code' => $com_code, 'id' => $MainSalaryEmployeeData['emp_jobs_id']));
    
                return view('admin.Main_salary_employee.showSalaryDetails', ['MainSalaryEmployeeData' => $MainSalaryEmployeeData, 'finance_cin_periods_data' => $finance_cin_periods_data]);

    }

    public function doStopSalary($Main_salary_employeeID)
    {


        $com_code = auth()->user()->com_code;
        $MainSalaryEmployeeData = get_cols_where_row(new Main_salary_employee(), array("*"), array('com_code' => $com_code, 'id' => $Main_salary_employeeID));

        if (empty($MainSalaryEmployeeData)) {
            return redirect()->back()->with('error', 'عفواً غير قادر للوصول الى البيانات المطلوبة');
        }
         $finance_cin_periods_data = get_cols_where_row(new Finance_cin_periods(), array("*"), array('com_code' => $com_code, 'id' => $MainSalaryEmployeeData['finance_cin_periods_id']));
        if (empty($finance_cin_periods_data)) {
            return redirect()->back()->with('error', 'عفواً غير قادر للوصول الى البيانات المطلوبة');
        }

        if ($MainSalaryEmployeeData['is_archived']==1 || $finance_cin_periods_data['is_open']!=1 ) {
            return redirect()->back()->with('error', 'عفواً لا يمكن عمل هذا الاجراء حالياً');
        }

        if ($MainSalaryEmployeeData['is_stoped'] ==1 ) {
            return redirect()->back()->with('error', 'عفواً الراتب بالفعل موقوف مسبقاً');
        }

                $dataToUpdate['is_stoped'] =1;
                $dataToUpdate['updated_by']=auth()->user()->id;

                update(new Main_salary_employee(),$dataToUpdate,array('com_code' => $com_code, 'id' => $Main_salary_employeeID,'is_archived'=>0));
                
                    return redirect()->back()->with('success', 'تم ايقاف الراتب');
               


    }

    public function doCancelStopSalary($Main_salary_employeeID)
    {


        $com_code = auth()->user()->com_code;
        $MainSalaryEmployeeData = get_cols_where_row(new Main_salary_employee(), array("*"), array('com_code' => $com_code, 'id' => $Main_salary_employeeID));

        if (empty($MainSalaryEmployeeData)) {
            return redirect()->back()->with('error', 'عفواً غير قادر للوصول الى البيانات المطلوبة');
        }
         $finance_cin_periods_data = get_cols_where_row(new Finance_cin_periods(), array("*"), array('com_code' => $com_code, 'id' => $MainSalaryEmployeeData['finance_cin_periods_id']));
        if (empty($finance_cin_periods_data)) {
            return redirect()->back()->with('error', 'عفواً غير قادر للوصول الى البيانات المطلوبة');
        }

        if ($MainSalaryEmployeeData['is_archived']==1 || $finance_cin_periods_data['is_open']!=1 ) {
            return redirect()->back()->with('error', 'عفواً لا يمكن عمل هذا الاجراء حالياً');
        }

        if ($MainSalaryEmployeeData['is_stoped'] ==0 ) {
            return redirect()->back()->with('error', 'عفواً الراتب بالفعل غير موقوف مسبقاً');
        }

                $dataToUpdate['is_stoped'] =0;
                $dataToUpdate['updated_by']=auth()->user()->id;

                update(new Main_salary_employee(),$dataToUpdate,array('com_code' => $com_code, 'id' => $Main_salary_employeeID,'is_archived'=>0));
                
                    return redirect()->back()->with('success', 'تم الغاء ايقاف الراتب');
               


    }

    public function doDeleteSalaryIternal($Main_salary_employeeID)
    {


        $com_code = auth()->user()->com_code;
        $MainSalaryEmployeeData = get_cols_where_row(new Main_salary_employee(), array("*"), array('com_code' => $com_code, 'id' => $Main_salary_employeeID));

        if (empty($MainSalaryEmployeeData)) {
            return redirect()->back()->with('error', 'عفواً غير قادر للوصول الى البيانات المطلوبة');
        }
         $finance_cin_periods_data = get_cols_where_row(new Finance_cin_periods(), array("*"), array('com_code' => $com_code, 'id' => $MainSalaryEmployeeData['finance_cin_periods_id']));
        if (empty($finance_cin_periods_data)) {
            return redirect()->back()->with('error', 'عفواً غير قادر للوصول الى البيانات المطلوبة');
        }

        if ($MainSalaryEmployeeData['is_archived']==1 || $finance_cin_periods_data['is_open']!=1 || $MainSalaryEmployeeData['is_stoped'] ==1 ) {
            return redirect()->back()->with('error', 'عفواً لا يمكن عمل هذا الاجراء حالياً');
        }

      

             
                destroy(new Main_salary_employee(),array('com_code' => $com_code, 'id' => $Main_salary_employeeID,'is_archived'=>0));
                
                    return redirect()->route('MainSalaryEmployee.show',$MainSalaryEmployeeData['finance_cin_periods_id'])->with('success', 'تم حذف الراتب');
               


    }

    public function load_archive_salary(Request $request)
    {

        if($request->ajax()){
            $com_code = auth()->user()->com_code;
            $Main_salary_employeeID=$request->id;
            $main_salary_employee_data = get_cols_where_row(new Main_salary_employee(), array("*"), array('com_code' => $com_code, 'id' => $Main_salary_employeeID,'is_archived'=>0,'is_stoped'=>0));
            $finance_cin_periods_data = get_cols_where_row(new Finance_cin_periods(), array("*"), array('com_code' => $com_code, 'id' => $main_salary_employee_data['finance_cin_periods_id'],'is_open'=>1));

            return view('admin.Main_salary_employee.load_archive_salary', ['main_salary_employee_data' => $main_salary_employee_data,'finance_cin_periods_data'=>$finance_cin_periods_data]);

        }

    }

    
    public function do_archive_salary($Main_salary_employeeID)
    {


        $com_code = auth()->user()->com_code;
        $MainSalaryEmployeeData = get_cols_where_row(new Main_salary_employee(), array("*"), array('com_code' => $com_code, 'id' => $Main_salary_employeeID));

        if (empty($MainSalaryEmployeeData)) {
            return redirect()->back()->with('error', 'عفواً غير قادر للوصول الى البيانات المطلوبة');
        }
         $finance_cin_periods_data = get_cols_where_row(new Finance_cin_periods(), array("*"), array('com_code' => $com_code, 'id' => $MainSalaryEmployeeData['finance_cin_periods_id']));
        if (empty($finance_cin_periods_data)) {
            return redirect()->back()->with('error', 'عفواً غير قادر للوصول الى البيانات المطلوبة');
        }

        if ($MainSalaryEmployeeData['is_archived']==1 || $finance_cin_periods_data['is_open']!=1 ||$MainSalaryEmployeeData['is_stoped']==1) {
            return redirect()->back()->with('error', 'عفواً لا يمكن عمل هذا الاجراء حالياً');
        }

     

                $dataToUpdate['is_archived'] =1;
                $dataToUpdate['archived_date'] =date("Y-m-d H:i:s");
                $dataToUpdate['archived_by']=auth()->user()->id;

                //هنا لو راتب الموظف بالموجب يبقى مستحق للموظف وبعد الارشفة بيتم حفظ راتب الموظف داخل ضرف الراتب للشهر
                // اذا كان بالسالب بيتم ترحيل الرصيد السالب لشهر القادم
                // بنسبة 1% يتم تحصيل المستحق او بعضه وترحيل المديونية للشهر القادم 

                if($MainSalaryEmployeeData['final_the_net']<0){

                    //الراتب سالب يرحل الرصيد للشهر القادم
                    $dataToUpdate['final_the_net_after_close_for_trahil']=$MainSalaryEmployeeData['final_the_net'];

                }else{
                    $dataToUpdate['final_the_net_after_close_for_trahil']=0;
                }


                $flag=update(new Main_salary_employee(),$dataToUpdate,array('com_code' => $com_code, 'id' => $Main_salary_employeeID,'is_archived'=>0,'is_stoped'=>0));
                
                $dataToUpdate_variables['is_archived'] = 1;
                $dataToUpdate_variables['archived_at'] = date("Y-m-d H:i:s");
                $dataToUpdate_variables['archived_by'] = auth()->user()->id;
                update(new Main_salary_employee_sanctions(), $dataToUpdate_variables, array('com_code' => $com_code, 'main_salary_employee_id' => $Main_salary_employeeID, 'finance_cin_periods_id'=>$finance_cin_periods_data['id']));
                update(new Main_salary_employee_Absence(), $dataToUpdate_variables, array('com_code' => $com_code, 'main_salary_employee_id' => $Main_salary_employeeID, 'finance_cin_periods_id'=>$finance_cin_periods_data['id']));
                update(new Main_salary_employee_discount(), $dataToUpdate_variables, array('com_code' => $com_code, 'main_salary_employee_id' => $Main_salary_employeeID, 'finance_cin_periods_id'=>$finance_cin_periods_data['id']));
                update(new Main_salary_employee_loans(), $dataToUpdate_variables, array('com_code' => $com_code, 'main_salary_employee_id' => $Main_salary_employeeID, 'finance_cin_periods_id'=>$finance_cin_periods_data['id']));

                Main_salary_employee_p_loans_aksat::where('com_code' ,'=', $com_code)
                ->where("year_and_month",$finance_cin_periods_data['year_and_month'])
                ->where('is_archived','=',0)
                ->where('state','!=',2)
                ->where('employees_code','=',$MainSalaryEmployeeData['employees_code'])
                ->where('main_salary_employee_id','=',$Main_salary_employeeID)
                ->update($dataToUpdate_variables);
                
                update(new Main_salary_employee_addition(), $dataToUpdate_variables, array('com_code' => $com_code, 'main_salary_employee_id' => $Main_salary_employeeID, 'finance_cin_periods_id'=>$finance_cin_periods_data['id']));
                update(new Main_salary_employee_rewards(), $dataToUpdate_variables, array('com_code' => $com_code, 'main_salary_employee_id' => $Main_salary_employeeID, 'finance_cin_periods_id'=>$finance_cin_periods_data['id']));
                update(new Main_salary_employee_allowances(), $dataToUpdate_variables, array('com_code' => $com_code, 'main_salary_employee_id' => $Main_salary_employeeID, 'finance_cin_periods_id'=>$finance_cin_periods_data['id']));

                    return redirect()->back()->with('success', 'تم ارشفة الراتب');
               


    }

    public function printSalary($Main_salary_employeeID)
    {


        $com_code = auth()->user()->com_code;
        $MainSalaryEmployeeData = get_cols_where_row(new Main_salary_employee(), array("*"), array('com_code' => $com_code, 'id' => $Main_salary_employeeID));

        if (empty($MainSalaryEmployeeData)) {
            return redirect()->back()->with('error', 'عفواً غير قادر للوصول الى البيانات المطلوبة');
        }
         $finance_cin_periods_data = get_cols_where_row(new Finance_cin_periods(), array("*"), array('com_code' => $com_code, 'id' => $MainSalaryEmployeeData['finance_cin_periods_id']));
        if (empty($finance_cin_periods_data)) {
            return redirect()->back()->with('error', 'عفواً غير قادر للوصول الى البيانات المطلوبة');
        }

      

     

        $MainSalaryEmployeeData['emp_name'] = get_field_value(new Employee(), 'emp_name', array('com_code' => $com_code, 'employees_code' => $MainSalaryEmployeeData['employees_code']));
        $MainSalaryEmployeeData['emp_gender'] = get_field_value(new Employee(), 'emp_gender', array('com_code' => $com_code, 'employees_code' => $MainSalaryEmployeeData['employees_code']));
        $MainSalaryEmployeeData['branch_name'] = get_field_value(new Branche(), 'name', array('com_code' => $com_code, 'id' => $MainSalaryEmployeeData['branch_id']));
        $MainSalaryEmployeeData['department_name'] = get_field_value(new Department(), 'name', array('com_code' => $com_code, 'id' => $MainSalaryEmployeeData['emp_department_id']));
        $MainSalaryEmployeeData['jobs_name'] = get_field_value(new jobs_category(), 'name', array('com_code' => $com_code, 'id' => $MainSalaryEmployeeData['emp_jobs_id']));
        $systemData=get_cols_where_row(new admin_panel_setting(),array('company_name','image','phone','address'),array('com_code'=>$com_code));
        return view('admin.Main_salary_employee.printSalary', ['MainSalaryEmployeeData'=>$MainSalaryEmployeeData,'finance_cin_periods_data'=>$finance_cin_periods_data,'systemData'=>$systemData]);
            


    }

    public function print_search(Request $request)
    {
            $com_code = auth()->user()->com_code;
            $employees_code = $request->employees_code;
            $branch_id = $request->branch_id;
            $emp_department_id = $request->emp_department_id;
            $emp_jobs_id = $request->emp_jobs_id;
            $function_status = $request->function_status;
            $sal_cach_or_visa = $request->sal_cach_or_visa;
            $is_stoped = $request->is_stoped;
            $is_archived = $request->is_archived;
            $the_finance_cin_periods_id = $request->the_finance_cin_periods_id;
            
            $finance_cin_periods_data = get_cols_where_row(new Finance_cin_periods(), array("*"), array('com_code' => $com_code, 'id' => $the_finance_cin_periods_id));
            if (empty($finance_cin_periods_data)) {
                return redirect()->back()->with('error', 'عفواً غير قادر للوصول الى البيانات المطلوبة');
            }
            if ($employees_code == 'all') {
                $field1 = "id";
                $operator1 = ">";
                $value1 = 0;
            } else {
                $field1 = "employees_code";
                $operator1 = "=";
                $value1 = $employees_code;
            }

            if ($branch_id == 'all') {
                $field2 = "id";
                $operator2 = ">";
                $value2 = 0;
            } else {
                $field2 = "branch_id";
                $operator2 = "=";
                $value2 = $branch_id;
            }

            if ($emp_department_id == 'all') {
                $field3 = "id";
                $operator3 = ">";
                $value3 = 0;
            } else {
                $field3 = "emp_department_id";
                $operator3 = "=";
                $value3 = $emp_department_id;
            }

            if ($emp_jobs_id == 'all') {
                $field4 = "id";
                $operator4 = ">";
                $value4 = 0;
            } else {
                $field4 = "emp_jobs_id";
                $operator4 = "=";
                $value4 = $emp_jobs_id;
            }

            if ($function_status == 'all') {
                $field5 = "id";
                $operator5 = ">";
                $value5 = 0;
            } else {
                $field5 = "function_status";
                $operator5 = "=";
                $value5 = $function_status;
            }


            if ($sal_cach_or_visa == 'all') {
                $field6 = "id";
                $operator6 = ">";
                $value6 = 0;
            } else {
                $field6 = "sal_cach_or_visa";
                $operator6 = "=";
                $value6 = $sal_cach_or_visa;
            }

            if ($is_stoped == 'all') {
                $field7 = "id";
                $operator7 = ">";
                $value7 = 0;
            } else {
                $field7 = "is_stoped";
                $operator7 = "=";
                $value7 = $is_stoped;
            }



            if ($is_archived == 'all') {
                $field8 = "id";
                $operator8 = ">";
                $value8 = 0;
            } else {
                $field8 = "is_archived";
                $operator8 = "=";
                $value8 = $is_archived;
            }

            $data = Main_salary_employee::select("*")
                ->where($field1, $operator1, $value1)
                ->where($field2, $operator2, $value2)
                ->where($field3, $operator3, $value3)
                ->where($field4, $operator4, $value4)
                ->where($field5, $operator5, $value5)
                ->where($field6, $operator6, $value6)
                ->where($field7, $operator7, $value7)
                ->where($field8, $operator8, $value8)
                ->where('finance_cin_periods_id', '=', $the_finance_cin_periods_id)
                ->where('com_code', '=', $com_code)
                ->orderBy('id', 'DESC')
                ->paginate(PAGINATION_COUNTER);
                if($request->submit_button!='indetails' && $request->submit_button!='intotal'){
                    $total['emp_sal']=0;
                    $total['day_price']=0;
                    $total['additions']=0;
                    $total['motivation']=0;
                    $total['additional_days_counter']=0;
                    $total['additional_days']=0;
                    $total['fixed_suits']=0;
                    $total['changable_suits']=0;
                    $total['total_benefits']=0;
                    $total['absence_days_counter']=0;
                    $total['absence_days']=0;
                    $total['sanctions_days_counter']=0;
                    $total['sanctions_days_total']=0;
                    $total['monthly_loan']=0;
                    $total['permanent_loan']=0;
                    $total['discount']=0;
                    $total['medical_nsurance_cutMonthely']=0;
                    $total['social_nsurance_cutMonthely']=0;
                    $total['total_deductions']=0;
                    $total['last_salary_remain_balance']=0;
                    $total['final_the_net']=0;
        
                }

           
            if (!empty($data)) {
                foreach ($data as $info) {

                    $info->emp_name = get_field_value(new Employee(), 'emp_name', array('com_code' => $com_code, 'employees_code' => $info->employees_code));
                    $info->emp_photo = get_field_value(new Employee(), 'emp_photo', array('com_code' => $com_code, 'employees_code' => $info->employees_code));
                    $info->emp_gender = get_field_value(new Employee(), 'emp_gender', array('com_code' => $com_code, 'employees_code' => $info->employees_code));
                    $info->branch_name = get_field_value(new Branche(), 'name', array('com_code' => $com_code, 'id' => $info->branch_id));
                    $info->department_name = get_field_value(new Department(), 'name', array('com_code' => $com_code, 'id' => $info->emp_department_id));
                    $info->jobs_name = get_field_value(new jobs_category(), 'name', array('com_code' => $com_code, 'id' => $info->emp_jobs_id));
                
                    if($request->submit_button!='indetails' && $request->submit_button!='intotal'){
                        $total['emp_sal']+=$info->emp_sal;
                        $total['day_price']+=$info->day_price;
                        $total['additions']+=$info->additions;
                        $total['motivation']+=$info->motivation;
                        $total['additional_days_counter']+=$info->additional_days_counter;
                        $total['additional_days']+=$info->additional_days;
                        $total['fixed_suits']+=$info->fixed_suits;
                        $total['changable_suits']+=$info->changable_suits;
                        $total['total_benefits']+=$info->total_benefits;
                        $total['absence_days_counter']+=$info->absence_days_counter;
                        $total['absence_days']+=$info->absence_days;
                        $total['sanctions_days_counter']+=$info->sanctions_days_counter;
                        $total['sanctions_days_total']+=$info->sanctions_days_total;
                        $total['monthly_loan']+=$info->monthly_loan;
                        $total['permanent_loan']+=$info->permanent_loan;
                        $total['discount']+=$info->discount;
                        $total['medical_nsurance_cutMonthely']+=$info->medical_nsurance_cutMonthely;
                        $total['social_nsurance_cutMonthely']+=$info->social_nsurance_cutMonthely;
                        $total['total_deductions']+=$info->total_deductions;
                        $total['last_salary_remain_balance']+=$info->last_salary_remain_balance;
                        $total['final_the_net']+=$info->final_the_net;
                    }
                    
                
                }
            }
            $systemData=get_cols_where_row(new admin_panel_setting(),array('company_name','image','phone','address'),array('com_code'=>$com_code));
            if($request->submit_button=='indetails'){
                return view('admin.Main_salary_employee.print_search_indetails', ['data' => $data,'systemData'=>$systemData,'finance_cin_periods_data'=>$finance_cin_periods_data]);

            }elseif($request->submit_button=='intotal'){

                $other['emp_sal'] = Main_salary_employee::where($field1, $operator1, $value1)
                ->where($field2, $operator2, $value2)
                ->where($field3, $operator3, $value3)
                ->where($field4, $operator4, $value4)
                ->where($field5, $operator5, $value5)
                ->where($field6, $operator6, $value6)
                ->where($field7, $operator7, $value7)
                ->where($field8, $operator8, $value8)
                ->where('finance_cin_periods_id', '=', $the_finance_cin_periods_id)
                ->where('com_code', '=', $com_code)
                ->sum('emp_sal');

                $other['total_benefits'] = Main_salary_employee::where($field1, $operator1, $value1)
                ->where($field2, $operator2, $value2)
                ->where($field3, $operator3, $value3)
                ->where($field4, $operator4, $value4)
                ->where($field5, $operator5, $value5)
                ->where($field6, $operator6, $value6)
                ->where($field7, $operator7, $value7)
                ->where($field8, $operator8, $value8)
                ->where('finance_cin_periods_id', '=', $the_finance_cin_periods_id)
                ->where('com_code', '=', $com_code)
                ->sum('total_benefits');

                $other['total_deductions'] = Main_salary_employee::where($field1, $operator1, $value1)
                ->where($field2, $operator2, $value2)
                ->where($field3, $operator3, $value3)
                ->where($field4, $operator4, $value4)
                ->where($field5, $operator5, $value5)
                ->where($field6, $operator6, $value6)
                ->where($field7, $operator7, $value7)
                ->where($field8, $operator8, $value8)
                ->where('finance_cin_periods_id', '=', $the_finance_cin_periods_id)
                ->where('com_code', '=', $com_code)
                ->sum('total_deductions');

                $other['final_the_net'] = Main_salary_employee::where($field1, $operator1, $value1)
                ->where($field2, $operator2, $value2)
                ->where($field3, $operator3, $value3)
                ->where($field4, $operator4, $value4)
                ->where($field5, $operator5, $value5)
                ->where($field6, $operator6, $value6)
                ->where($field7, $operator7, $value7)
                ->where($field8, $operator8, $value8)
                ->where('finance_cin_periods_id', '=', $the_finance_cin_periods_id)
                ->where('com_code', '=', $com_code)
                ->sum('final_the_net');

                return view('admin.Main_salary_employee.print_search_intotal', ['data' => $data,'systemData'=>$systemData,'finance_cin_periods_data'=>$finance_cin_periods_data,'other'=>$other]);
            }else{
                
                return view('admin.Main_salary_employee.print_search_intotalDetails', ['data' => $data,'systemData'=>$systemData,'finance_cin_periods_data'=>$finance_cin_periods_data,'total'=>$total]);

            }
        
    }
}
