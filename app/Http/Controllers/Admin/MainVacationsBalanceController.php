<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\admin_panel_setting;
use App\Models\Blood_Group;
use App\Models\Branche;
use App\Models\Centers;
use App\Models\Country;
use App\Models\Department;
use App\Models\driving_license_type;
use App\Models\Employee;
use App\Models\Finance_calender;
use App\Models\Finance_cin_periods;
use App\Models\Governorate;
use App\Models\jobs_category;
use App\Models\Language;
use App\Models\MainVacationsBalance;
use App\Models\Military_status;
use App\Models\Nationalitie;
use App\Models\Qualification;
use App\Models\Religion;
use App\Models\Shifts_type;
use Illuminate\Http\Request;

class MainVacationsBalanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $com_code = auth()->user()->com_code;
        $data = get_cols_where_paginate(new Employee(), array('*'), array('com_code' => $com_code), 'id', 'DESC', PAGINATION_COUNTER);

        $other['branches'] = get_cols_where(new Branche(), array("id", "name"), array('com_code' => $com_code, 'active' => 1));
        $other['departments'] = get_cols_where(new Department(), array("id", "name"), array('com_code' => $com_code, 'active' => 1));
        $other['jobs'] = get_cols_where(new jobs_category(), array("id", "name"), array('com_code' => $com_code, 'active' => 1));
        $other['qualifications'] = get_cols_where(new Qualification(), array("id", "name"), array('com_code' => $com_code, 'active' => 1));
        $other['religions'] = get_cols_where(new Religion(), array("id", "name"), array('com_code' => $com_code, 'active' => 1));
        $other['nationalities'] = get_cols_where(new Nationalitie(), array("id", "name"), array('com_code' => $com_code, 'active' => 1));
        $other['countries'] = get_cols_where(new Country(), array("id", "name"), array('com_code' => $com_code, 'active' => 1));
        $other['governorates'] = get_cols_where(new Governorate(), array("id", "name"), array('com_code' => $com_code, 'active' => 1));
        $other['centers'] = get_cols_where(new Centers(), array("id", "name"), array('com_code' => $com_code, 'active' => 1));
        $other['blood_groups'] = get_cols_where(new Blood_Group(), array("id", "name"), array('com_code' => $com_code, 'active' => 1));
        $other['military_statuses'] = get_cols_where(new Military_status(), array("id", "name"), array('active' => 1), 'id', 'ASC');
        $other['driving_license_types'] = get_cols_where(new driving_license_type(), array("id", "name"), array('com_code' => $com_code, 'active' => 1));
        $other['shift_types'] = get_cols_where(new Shifts_type(), array("id", "type", "form_time", "to_time", "total_huor"), array('com_code' => $com_code, 'active' => 1), 'id', 'ASC');
        $other['languages'] = get_cols_where(new Language(), array("id", "name"), array('com_code' => $com_code, 'active' => 1), 'id', 'ASC');


        return view('admin.MainEmployeeVacationsBalance.index', ['data' => $data, 'other' => $other]);
    }

    public function ajax_search(Request $request)
    {
        if ($request->ajax()) {

            $searchbycode = $request->searchbycode;
            $emp_name_search = $request->emp_name_search;
            $branch_id_search = $request->branch_id_search;
            $emp_department_id_search = $request->emp_department_id_search;
            $emp_jobs_id_search = $request->emp_jobs_id_search;
            $function_status_search = $request->function_status_search;
            $is_active_for_vaccation_search = $request->is_active_for_vaccation_search;
            $emp_gender_search = $request->emp_gender_search;
            $search_btn_radio = $request->search_btn_radio;

            // فحص الحقل searchbycode
            if ($searchbycode == '') {
                $field1 = 'id';
                $operator1 = ">";
                $value1 = '0';
            } else {
                if ($search_btn_radio == 'zketo_code') {
                    $field1 = 'zketo_code';
                    $operator1 = "=";
                    $value1 = $searchbycode; // استخدام $searchbycode وليس $search_btn_radio
                } else {
                    $field1 = 'employees_code';
                    $operator1 = "=";
                    $value1 = $searchbycode; // استخدام $searchbycode وليس $search_btn_radio
                }
            }

            // فحص اسم الموظف
            if ($emp_name_search == '') {
                $field2 = 'id';
                $operator2 = ">";
                $value2 = 0;
            } else {
                $field2 = 'emp_name';
                $operator2 = "like";
                $value2 = "%$emp_name_search%"; // تصحيح صياغة الـ like
            }

            // فحص الفرع
            if ($branch_id_search == 'all') {
                $field3 = 'id';
                $operator3 = ">";
                $value3 = 0;
            } else {
                $field3 = 'branch_id';
                $operator3 = "=";
                $value3 = $branch_id_search;
            }

            // فحص القسم
            if ($emp_department_id_search == 'all') {
                $field4 = 'id';
                $operator4 = ">";
                $value4 = 0;
            } else {
                $field4 = 'emp_department_id';
                $operator4 = "=";
                $value4 = $emp_department_id_search;
            }

            // فحص الوظيفة
            if ($emp_jobs_id_search == 'all') {
                $field5 = 'id';
                $operator5 = ">";
                $value5 = 0;
            } else {
                $field5 = 'emp_jobs_id';
                $operator5 = "=";
                $value5 = $emp_jobs_id_search;
            }

            // فحص حالة الوظيفة
            if ($function_status_search == 'all') {
                $field6 = 'id';
                $operator6 = ">";
                $value6 = 0;
            } else {
                $field6 = 'function_status';
                $operator6 = "=";
                $value6 = $function_status_search;
            }

            // فحص طريقة استلام الراتب
            if ($is_active_for_vaccation_search == 'all') {
                $field7 = 'id';
                $operator7 = ">";
                $value7 = 0;
            } else {
                $field7 = 'is_active_for_vaccation';
                $operator7 = "=";
                $value7 = $is_active_for_vaccation_search;
            }

            // فحص الجنس
            if ($emp_gender_search == 'all') {
                $field8 = 'id';
                $operator8 = ">";
                $value8 = 0;
            } else {
                $field8 = 'emp_gender';
                $operator8 = "=";
                $value8 = $emp_gender_search;
            }
            $com_code = auth()->user()->com_code;
            // البحث باستخدام الشروط المحددة
            $data = Employee::select('*')
                ->where($field1, $operator1, $value1)
                ->where($field2, $operator2, $value2)
                ->where($field3, $operator3, $value3)
                ->where($field4, $operator4, $value4)
                ->where($field5, $operator5, $value5)
                ->where($field6, $operator6, $value6)
                ->where($field7, $operator7, $value7)
                ->where($field8, $operator8, $value8)
                ->where('com_code', '=', $com_code)
                ->orderBy('id', 'DESC')
                ->paginate(PAGINATION_COUNTER);



            return view('admin.MainEmployeeVacationsBalance.ajax_search', compact('data'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $com_code = auth()->user()->com_code;
        $data = get_cols_where_row(new Employee(), array("*"), array('com_code' => $com_code, 'id' => $id));
        if (empty($data)) {
            return redirect()->back()->with('error', 'عفواً غير قادر للوصول الى البيانات المطلوبة')->withInput();
        }
        $other['branches'] = get_cols_where(new Branche(), array("id", "name"), array('com_code' => $com_code, 'id' => $data['branch_id']));
        $other['departments'] = get_cols_where(new Department(), array("id", "name"), array('com_code' => $com_code, 'id' => $data['emp_department_id ']));
        $other['jobs'] = get_cols_where(new jobs_category(), array("id", "name"), array('com_code' => $com_code, 'id' => $data['emp_jobs_id']));

        $this->calculate_employees_vacations_balance($data['employees_code']);
        $this->calculate_employees_vacations_balance($data['employees_code']);

        $other['finance_calender'] = get_cols_where(new Finance_calender(), array('*'), array('com_code' => $com_code), 'id', 'DESC');
        $other['finance_calender_open_year'] = get_cols_where_row(new Finance_calender(), array('*'), array('com_code' => $com_code, 'open_yr_flag' => 1));
        if (!empty($other['finance_calender_open_year'])) {
            $other['main_employees_vacations_balance'] = get_cols_where(new MainVacationsBalance(), array('*'), array('employees_code' => $data['employees_code'], 'finance_yr' => $other['finance_calender_open_year']['finance_yr'], 'com_code' => $com_code), 'id', 'ASC');
        }



        return view('admin.MainEmployeeVacationsBalance.show', ['data' => $data, 'other' => $other, 'employees_vacations_balance' => ""]);
    }

    //بداية دالة احتساب رصيد اجازات السنوي والشهري للموظف

    public function calculate_employees_vacations_balance($employees_code)
    {
        $com_code = auth()->user()->com_code;
        $employeeData = get_cols_where_row(new Employee(), array("*"), array('com_code' => $com_code, 'employees_code' => $employees_code, 'function_status' => 1, 'is_active_for_vaccation' => 1));
        $admin_panel_settingsData = get_cols_where_row(new admin_panel_setting(), array('*'), array('com_code' => $com_code));
        if (!empty($employeeData) && !empty($admin_panel_settingsData)) {
            // التحقق من وجود شهر مالي مفتوح مع سنة مالية مفتوحة
            $CurrentOpenMonth = get_cols_where_row(new Finance_cin_periods(), array('id', 'finance_yr', 'year_and_month'), array('com_code' => $com_code, 'is_open' => 1));
            if (!empty($CurrentOpenMonth)) {
                if ($employeeData['is_done_vaccation_formula'] == 0) {
                    //اول مرة ينزل له رصيد
                    $now = time();
                    $your_date = strtotime($employeeData['emp_start_date']);
                    $datediff = $now - $your_date;
                    $diffrence_days = round($datediff / (60 * 60 * 24));
                    //لو عدد الايام يساوي او اكبر من الضبط العام سوف ينزل له رصيد او المدة
                    if ($diffrence_days >= $admin_panel_settingsData['after_days_begins_vacation']) {

                        $acviteDays = number_format($admin_panel_settingsData['after_days_begins_vacation']) * 1;
                        $current_year = $CurrentOpenMonth['finance_yr'];
                        $workYear = date('Y', strtotime($employeeData['emp_start_date']));
                        $dateofActiveFormlula = date('Y-m-d', strtotime($employeeData['emp_start_date'] . "+ $acviteDays days"));

                        if ($workYear == $current_year) {
                            //ينزل رصيد اذا كان بنفس السنة المالية
                            $datainsert['current_month_balance'] = $admin_panel_settingsData['first_balance_begin_vacation'];
                            $datainsert['total_available_balance'] = $admin_panel_settingsData['first_balance_begin_vacation'];
                            $datainsert['net_balance'] = $admin_panel_settingsData['first_balance_begin_vacation'];
                        } else {
                            // هنا نصفر الرصيد السنة المالية السابقة ونبدأ من بداية السنة المالية الجديدة
                            $datainsert['current_month_balance'] = $admin_panel_settingsData['monthly_vaction_balance'];
                            $datainsert['total_available_balance'] = $admin_panel_settingsData['monthly_vaction_balance'];
                            $datainsert['net_balance'] = $admin_panel_settingsData['monthly_vaction_balance'];
                        }

                        if ($diffrence_days <= 360) {
                            $datainsert['year_and_month'] = date('Y-m', strtotime($dateofActiveFormlula));
                        } else {
                            $datainsert['year_and_month'] = $current_year . '-01';
                        }

                        $datainsert['finance_yr'] = $current_year;
                        $datainsert['employees_code'] = $employees_code;
                        $datainsert['added_by'] = auth()->user()->id;
                        $datainsert['com_code'] = $com_code;
                        $datainsert['created_at'] = date('Y-m-d H:i:s');

                        $checkExsists = get_cols_where_row(new MainVacationsBalance(), array('id'), array('com_code' => $com_code, 'employees_code' => $employees_code, 'finance_yr' => $current_year, 'year_and_month' => $datainsert['year_and_month']));
                        if (empty($checkExsists)) {
                            $flag = insert(new MainVacationsBalance(), $datainsert);
                            if ($flag) {
                                $data_to_update['is_done_vaccation_formula'] = 1;
                                $data_to_update['updated_at'] = date('Y-m-d H:i:s');
                                $data_to_update['updated_by'] = auth()->user()->id;
                                update(new Employee(), $data_to_update, array('com_code' => $com_code, 'employees_code' => $employees_code));
                            }
                        }
                    }
                } else {
                    // نزل له رصيد سابقا
                    $last_added = get_cols_where_row_orderby(new MainVacationsBalance(), array('year_and_month'), array('com_code' => $com_code, 'employees_code' => $employees_code, 'finance_yr' => $CurrentOpenMonth['finance_yr']), 'id', 'DESC');
                    $current_month = intval(date('m', strtotime($CurrentOpenMonth['year_and_month'])));
                    if (!empty($last_added)) {
                        if ($last_added['year_and_month'] != $CurrentOpenMonth['year_and_month']) {
                            $i = intval(date('m', strtotime($last_added['year_and_month'])));
                            $i += 1;
                            while ($i <= $current_month) {

                                if ($i < 10) {
                                    $datainsert['year_and_month'] = $CurrentOpenMonth['finance_yr'] . '-0' . $i;
                                } else {
                                    $datainsert['year_and_month'] = $CurrentOpenMonth['finance_yr'] . '-' . $i;
                                }
                                $datainsert['current_month_balance'] = $admin_panel_settingsData['monthly_vaction_balance'];
                                $datainsert['total_available_balance'] = $admin_panel_settingsData['monthly_vaction_balance'];
                                $datainsert['net_balance'] = $admin_panel_settingsData['monthly_vaction_balance'];
                                $datainsert['finance_yr'] = $CurrentOpenMonth['finance_yr'];
                                $datainsert['employees_code'] = $employees_code;
                                $datainsert['added_by'] = auth()->user()->id;
                                $datainsert['com_code'] = $com_code;
                                $datainsert['created_at'] = date('Y-m-d H:i:s');

                                $checkExsists = get_cols_where_row(new MainVacationsBalance(), array('id'), array('com_code' => $com_code, 'employees_code' => $employees_code, 'finance_yr' => $CurrentOpenMonth['finance_yr'], 'year_and_month' => $datainsert['year_and_month']));
                                if (empty($checkExsists)) {
                                    $flag = insert(new MainVacationsBalance(), $datainsert);
                                }
                                $i++;
                            }
                        }
                    } else {
                        //الموظف كان بالخدمة وخرج من الخدمة ورجع مرة اخرى
                        // الموظف كان بالخدمة والادارة عطلت له الارصدة السنوية ورجعت فعلتها مرة اخرى 
                        // هنا وارد انه يكون صفر الرصيد له عند تعطيله او اخراجه من الخدمة 
                        $current_month = intval(date('m', strtotime($CurrentOpenMonth['year_and_month'])));

                        if ($CurrentOpenMonth['year_and_month']) {
                            $firstMonthinOpenYear = get_cols_where_row_orderby(new Finance_cin_periods(), array('year_and_month'), array('com_code' => $com_code, 'finance_yr' => $CurrentOpenMonth['finance_yr'], 'is_open' => 2), 'id', 'ASC');
                            if (!empty($firstMonthinOpenYear)) {
                                $i = intval(date('m', strtotime($firstMonthinOpenYear['year_and_month'])));
                               
                                while ($i <= $current_month) {

                                    if ($i < 10) {
                                        $datainsert['year_and_month'] = $CurrentOpenMonth['finance_yr'] . '-0' . $i;
                                    } else {
                                        $datainsert['year_and_month'] = $CurrentOpenMonth['finance_yr'] . '-' . $i;
                                    }
                                    $datainsert['current_month_balance'] = $admin_panel_settingsData['monthly_vaction_balance'];
                                    $datainsert['total_available_balance'] = $admin_panel_settingsData['monthly_vaction_balance'];
                                    $datainsert['net_balance'] = $admin_panel_settingsData['monthly_vaction_balance'];
                                    $datainsert['finance_yr'] = $CurrentOpenMonth['finance_yr'];
                                    $datainsert['employees_code'] = $employees_code;
                                    $datainsert['added_by'] = auth()->user()->id;
                                    $datainsert['com_code'] = $com_code;
                                    $datainsert['created_at'] = date('Y-m-d H:i:s');

                                    $checkExsists = get_cols_where_row(new MainVacationsBalance(), array('id'), array('com_code' => $com_code, 'employees_code' => $employees_code, 'finance_yr' => $CurrentOpenMonth['finance_yr'], 'year_and_month' => $datainsert['year_and_month']));
                                    if (empty($checkExsists)) {
                                        $flag = insert(new MainVacationsBalance(), $datainsert);
                                    }
                                    $i++;
                                }
                            }
                        }
                    }
                }
                $this->reupdate_vacations($employees_code);
            }
        }
    }

    //دالة تحديث وترحيل وتجميع الارصدة من شهر الى اخر
    public function reupdate_vacations($employees_code)
    {

        $com_code = auth()->user()->com_code;
        $employeeData = get_cols_where_row(new Employee(), array("*"), array('com_code' => $com_code, 'employees_code' => $employees_code, 'function_status' => 1, 'is_active_for_vaccation' => 1));
        $admin_panel_settingsData = get_cols_where_row(new admin_panel_setting(), array('*'), array('com_code' => $com_code));
        if (!empty($employeeData) && !empty($admin_panel_settingsData)) {
            // التحقق من وجود شهر مالي مفتوح مع سنة مالية مفتوحة

            $CurrentOpenMonth = get_cols_where_row(new Finance_cin_periods(), array('id', 'finance_yr', 'year_and_month'), array('com_code' => $com_code, 'is_open' => 1));
            if (!empty($CurrentOpenMonth)) {
                if ($employeeData['is_done_vaccation_formula'] == 1) {
                    if ($admin_panel_settingsData['is_transfer_vacction'] == 1) {
                        //يرحل كل الشهور وكل السنوات
                        $vacations_balance = get_cols_where(new MainVacationsBalance(), array('net_balance', 'spent_balance', 'id', 'current_month_balance', 'spent_balance'), array('com_code' => $com_code, 'employees_code' => $employees_code), 'id', 'ASC');
                    } else {
                        //لا ترحل فقط يرحل ارصدة الشهور للسنة المالية كلا على حدا
                        $vacations_balance = get_cols_where(new MainVacationsBalance(), array('net_balance', 'spent_balance', 'id', 'current_month_balance', 'spent_balance'), array('com_code' => $com_code, 'employees_code' => $employees_code, 'finance_yr' => $CurrentOpenMonth['finance_yr']), 'id', 'ASC');
                    }

                    if (!empty($vacations_balance)) {
                        foreach ($vacations_balance as $info) {
                            $getPrevious = MainVacationsBalance::select('net_balance')->where('com_code', '=', $com_code)->where('employees_code', '=', $employees_code)->where('id', '<', $info->id)->orderBy('id', 'DESC')->first();
                            if (!empty($getPrevious)) {

                                $data_to_update_vacations['carryover_from_previous_month'] = $getPrevious['net_balance'];
                                $data_to_update_vacations['total_available_balance'] = $data_to_update_vacations['carryover_from_previous_month'] + $info->current_month_balance;
                                $data_to_update_vacations['net_balance'] = $data_to_update_vacations['total_available_balance'] + $info->spent_balance;

                                update(new MainVacationsBalance(), $data_to_update_vacations, array('com_code' => $com_code, 'employees_code' => $employees_code, 'id' => $info->id));
                            }
                        }
                    }
                }
            }
        }
    }
}
