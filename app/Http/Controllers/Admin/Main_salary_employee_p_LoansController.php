<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin_panel_setting;
use App\Models\Employee;
use App\Models\Main_salary_employee_p_loans;
use App\Models\Main_salary_employee_p_loans_aksat;
use Illuminate\Support\Facades\DB;

class Main_salary_employee_p_LoansController extends Controller
{
    
    public function index()
    {
        $com_code = auth()->user()->com_code;
        $data = get_cols_where_paginate(new Main_salary_employee_p_loans(), array('*'), array('com_code' => $com_code), 'id', 'DESC', PAGINATION_COUNTER);

        if (!empty($data)) {
            foreach ($data as $info) {
                $info->emp_name = get_field_value(new Employee(), 'emp_name', array('com_code' => $com_code, 'employees_code' => $info->employees_code));
            }
        }
        $other['employees'] = get_cols_where(new Employee(), array('emp_name', 'employees_code', 'emp_salary', 'day_price'), array('com_code' => $com_code, 'function_status' => 1));
        return view('admin.Main_salary_employee_p_loans.index', ['data' => $data, 'other' => $other]);
    }

    public function checkExsistsBefor(Request $request)
    {
        $com_code = auth()->user()->com_code;

        if ($request->ajax()) {
            $checkExsistsBeforCounter = get_count_where(new Main_salary_employee_p_loans(), array('com_code' => $com_code, 'employees_code' => $request->employees_code, 'is_archived' => 0));
            if ($checkExsistsBeforCounter > 0) {
                return json_encode("exsists_befor");
            } else {
                return json_encode("no_exsists_befor");
            }
        }
    }


    public function store(Request $request)
    {
        $com_code = auth()->user()->com_code;

        if ($request->ajax()) {

            $employeeData = get_cols_where_row(new Employee(), array("id"), array('com_code' => $com_code, 'employees_code' => $request->employees_code, 'function_status' => 1));
            if (!empty($employeeData)) {

                DB::beginTransaction();

                $dataToInsert = [
                    'employees_code' => $request->employees_code,
                    'emp_salary' => $request->emp_salary,
                    'total' => $request->total,
                    'month_number' => $request->month_number,
                    'month_kast_value' => $request->month_kast_value,
                    'year_and_month_start_date' => $request->year_and_month_start_date,
                    'year_and_month_start' => date('Y-m', strtotime($request->year_and_month_start_date)),
                    'notes' => $request->notes,
                    'added_by' => auth()->user()->id,
                    'com_code' => $com_code
                ];


                $flagParent = insert(new Main_salary_employee_p_loans(), $dataToInsert, true);
                if ($flagParent) {
                    //تقسم الاقساط الشهرية تلقائيا
                    $i = 1;
                    $effectiveDate = $dataToInsert['year_and_month_start'];
                    while ($i <= $dataToInsert['month_number']) {
                        $dataToInsertkast = [
                            'main_salary_p_loans_id' => $flagParent['id'],
                            'month_kast_value' => $dataToInsert['month_kast_value'],
                            'year_and_month' => $effectiveDate,
                            'state' => 0,
                            'added_by' => auth()->user()->id,
                            'com_code' => $com_code,

                        ];

                        insert(new Main_salary_employee_p_loans_aksat(), $dataToInsertkast);
                        $i++;

                        $effectiveDate = date('Y-m', strtotime("+1 months", strtotime($effectiveDate)));
                    }
                }
                DB::commit();

                return json_encode("done");
            }
        }
    }

    public function load_akast_details(Request $request)
    {
        $com_code = auth()->user()->com_code;
        if ($request->ajax()) {
            $dataParentLoan = get_cols_where_row(new Main_salary_employee_p_loans(), array("*"), array('com_code' => $com_code, 'id' => $request->id));
            if (!empty($dataParentLoan)) {
                $dataParentLoan['aksatDetails'] = get_cols_where(new Main_salary_employee_p_loans_aksat(), array("*"), array("com_code" => $com_code, "main_salary_p_loans_id" => $request->id), 'id', 'ASC');

            }

            return view('admin.Main_salary_employee_p_loans.load_akast_details', ['dataParentLoan' => $dataParentLoan]);
        }
    }

    public function delete_parent_loan($id)
    {
        $com_code = auth()->user()->com_code;
        $dataParentLoan = get_cols_where_row(new Main_salary_employee_p_loans(), array("*"), array('com_code' => $com_code, 'id' => $id));
        try {
            if (empty($dataParentLoan)) {
                return redirect()->route('MainSalary_p_Loans.index')->with('error', 'عفوا غير قادر للوصول الى البيانات');
            }

            if ($dataParentLoan['is_dismissail_done'] == 1) {
                return redirect()->route('MainSalary_p_Loans.index')->with('error', 'عفوا لايمكن حذف سلفة تم صرفها بالفعل');
            }

            if ($dataParentLoan['is_archived'] == 1) {
                return redirect()->route('MainSalary_p_Loans.index')->with('error', 'عفوا لايمكن حذف سلفة تم ارشفتها بالفعل');
            }

            DB::beginTransaction();
            $flagParent = destroy(new Main_salary_employee_p_loans(), array('com_code' => $com_code, 'id' => $id, 'is_dismissail_done' => 0, 'is_archived' => 0));

            if ($flagParent) {
                destroy(new Main_salary_employee_p_loans_aksat(), array('com_code' => $com_code, 'main_salary_p_loans_id' => $id, 'state' => 0));
            }
            DB::commit();
            return redirect()->route('MainSalary_p_Loans.index')->with('success', 'تم الحذف بنجاح');
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->route('MainSalary_p_Loans.index')->with(['error' => 'عفواً حدث خطأ' . $ex->getMessage()])->withInput();
        }
    }

    public function ajax_search(Request $request)
    {
        if ($request->ajax()) {
            $employees_code = $request->employees_code;
            $is_archived = $request->is_archived;
            $is_dismissail_done = $request->is_dismissail_done;

            if ($employees_code == 'all') {
                $field1 = "id";
                $operator1 = ">";
                $value1 = 0;
            } else {
                $field1 = "employees_code";
                $operator1 = "=";
                $value1 = $employees_code;
            }



            if ($is_archived == 'all') {
                $field2 = "id";
                $operator2 = ">";
                $value2 = 0;
            } else {
                $field2 = "is_archived";
                $operator2 = "=";
                $value2 = $is_archived;
            }

            if ($is_dismissail_done == 'all') {
                $field3 = "id";
                $operator3 = ">";
                $value3 = 0;
            } else {
                $field3 = "is_dismissail_done";
                $operator3 = "=";
                $value3 = $is_dismissail_done;
            }

            $com_code = auth()->user()->com_code;
            $data = Main_salary_employee_p_loans::select("*")->where($field1, $operator1, $value1)->where($field2, $operator2, $value2)->where($field3, $operator3, $value3)->where('com_code', '=', $com_code)->orderBy('id', 'DESC')->paginate(PAGINATION_COUNTER);

            if (!empty($data)) {
                foreach ($data as $info) {
                    $info->emp_name = get_field_value(new Employee(), 'emp_name', array('com_code' => $com_code, 'employees_code' => $info->employees_code));
                }
            }


            return view('admin.Main_salary_employee_p_loans.ajax_search', ['data' => $data]);
        }
    }

    public function load_edit_row(Request $request)
    {
        if ($request->ajax()) {
            $com_code = auth()->user()->com_code;
            $data_row = get_cols_where_row(new Main_salary_employee_p_loans(), array("*"), array('com_code' => $com_code, 'id' => $request->id, 'is_archived' => 0, 'is_dismissail_done' => 0));

            $employees = get_cols_where(new Employee(), array("employees_code", "emp_name", "emp_salary", "day_price"), array('com_code' => $com_code, 'function_status' => 1));



            return view('admin.Main_salary_employee_p_loans.load_edit_row', ['data_row' => $data_row, 'employees' => $employees]);
        }
    }

    public function do_edit_row(Request $request)
    {
        if ($request->ajax()) {
            $com_code = auth()->user()->com_code;
            $employeeData = get_cols_where_row(new Employee(), array("id"), array('com_code' => $com_code, 'employees_code' => $request->employees_code, 'function_status' => 1));

            $data_row = get_cols_where_row(new Main_salary_employee_p_loans(), array("*"), array('com_code' => $com_code, 'id' => $request->id, 'is_archived' => 0, 'is_dismissail_done' => 0));

            if (!empty($data_row)) {
                if (!empty($employeeData)) {
                    DB::beginTransaction();

                    $dataToUpdate = [
                        'employees_code' => $request->employees_code,
                        'emp_salary' => $request->emp_salary,
                        'total' => $request->total,
                        'month_number' => $request->month_number,
                        'month_kast_value' => $request->month_kast_value,
                        'year_and_month_start_date' => $request->year_and_month_start_date,
                        'year_and_month_start' => date('Y-m', strtotime($request->year_and_month_start_date)),
                        'notes' => $request->notes,
                        'updated_by' => auth()->user()->id,
                        'com_code' => $com_code
                    ];

                    $flagParent = update(new Main_salary_employee_p_loans(), $dataToUpdate, array('com_code' => $com_code, 'id' => $request->id, 'is_archived' => 0, 'is_dismissail_done' => 0));

                    if ($flagParent) {
                        if ($data_row['total'] != $dataToUpdate['total'] || $data_row['month_number'] != $dataToUpdate['month_number'] || $data_row['month_kast_value'] != $dataToUpdate['month_kast_value'] || $data_row['year_and_month_start_date'] != $dataToUpdate['year_and_month_start_date']) {
                            $flagDelete = destroy(new Main_salary_employee_p_loans_aksat(), array('com_code' => $com_code, 'main_salary_p_loans_id' => $request->id));

                            if ($flagDelete) {
                                $i = 1;
                                $effectiveDate = $dataToUpdate['year_and_month_start'];
                                while ($i <= $dataToUpdate['month_number']) {
                                    $dataToInsertkast = [
                                        'main_salary_p_loans_id' => $request->id,
                                        'month_kast_value' => $dataToUpdate['month_kast_value'],
                                        'year_and_month' => $effectiveDate,
                                        'state' => 0,
                                        'added_by' => auth()->user()->id,
                                        'com_code' => $com_code,

                                    ];

                                    insert(new Main_salary_employee_p_loans_aksat(), $dataToInsertkast);
                                    $i++;

                                    $effectiveDate = date('Y-m', strtotime("+1 months", strtotime($effectiveDate)));
                                }
                            }
                        }
                    }

                    DB::commit();

                    return json_encode("done");
                }
            }
        }
    }

    public function print_search(Request $request)
    {

        $employees_code = $request->employees_code;
        $is_archived = $request->is_archived;
        $is_dismissail_done = $request->is_dismissail_done;

      
            

            if ($employees_code == 'all') {
                $field1 = "id";
                $operator1 = ">";
                $value1 = 0;
            } else {
                $field1 = "employees_code";
                $operator1 = "=";
                $value1 = $employees_code;
            }



            if ($is_archived == 'all') {
                $field2 = "id";
                $operator2 = ">";
                $value2 = 0;
            } else {
                $field2 = "is_archived";
                $operator2 = "=";
                $value2 = $is_archived;
            }

            if ($is_dismissail_done == 'all') {
                $field3 = "id";
                $operator3 = ">";
                $value3 = 0;
            } else {
                $field3 = "is_dismissail_done";
                $operator3 = "=";
                $value3 = $is_dismissail_done;
            }

            $com_code = auth()->user()->com_code;
            $other['total_sum'] = 0;
            $data = Main_salary_employee_p_loans::select("*")->where($field1, $operator1, $value1)->where($field2, $operator2, $value2)->where($field3, $operator3, $value3)->where('com_code', '=', $com_code)->orderBy('id', 'DESC')->get();

            if (!empty($data)) {
                foreach ($data as $info) {
                    $info->emp_name = get_field_value(new Employee(), 'emp_name', array('com_code' => $com_code, 'employees_code' => $info->employees_code));

                    $other['total_sum'] += $info->total;
                }
            }

            $systemData = get_cols_where_row(new admin_panel_setting(), array('company_name', 'image', 'phone', 'address'), array('com_code' => $com_code));
            return view('admin.Main_salary_employee_p_loans.print_search', ['data' => $data, 'systemData' => $systemData, 'other' => $other]);
       
    }

    public function do_is_dismissail_done_now($id)
    {
        $com_code = auth()->user()->com_code;
        $dataParentLoan = get_cols_where_row(new Main_salary_employee_p_loans(), array("*"), array('com_code' => $com_code, 'id' => $id));
        try {
            if (empty($dataParentLoan)) {
                return redirect()->route('MainSalary_p_Loans.index')->with('error', 'عفوا غير قادر للوصول الى البيانات');
            }

            if ($dataParentLoan['is_dismissail_done'] == 1) {
                return redirect()->route('MainSalary_p_Loans.index')->with('error', 'عفوا لقد تم صرف السلفة بالفعل');
            }

            if ($dataParentLoan['is_archived'] == 1) {
                return redirect()->route('MainSalary_p_Loans.index')->with('error', 'عفوا لايمكن صرف سلفة تم ارشفتها بالفعل');
            }

            DB::beginTransaction();
            $dataToUpdate = [
                'is_dismissail_done' => 1,
                'dismissail_by' => auth()->user()->id,
                'dismissail_at' => date('Y-m-d H:i:s'),
              
            ];
            $flagParent = update(new Main_salary_employee_p_loans(), $dataToUpdate, array('com_code' => $com_code, 'id' => $id, 'is_archived' => 0, 'is_dismissail_done' => 0));
            if($flagParent){
                $dataToUpdateAksat['is_parent_dismissail_done']=1;
                update(new Main_salary_employee_p_loans_aksat(),$dataToUpdateAksat,array('com_code'=>$com_code,'main_salary_p_loans_id'=>$id));
            }
            DB::commit();
            return redirect()->route('MainSalary_p_Loans.index')->with('success', 'تم صرف السلفة بنجاح');
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->route('MainSalary_p_Loans.index')->with(['error' => 'عفواً حدث خطأ' . $ex->getMessage()])->withInput();
        }
    }
}
