<?php

namespace App\Traits;

use App\Models\admin_panel_setting;
use App\Models\Employee;
use App\Models\employee_fixed_suits;
use App\Models\Finance_cin_periods;
use App\Models\Main_salary_employee;
use App\Models\Main_salary_employee_Absence;
use App\Models\Main_salary_employee_addition;
use App\Models\Main_salary_employee_allowances;
use App\Models\Main_salary_employee_discount;
use App\Models\Main_salary_employee_loans;
use App\Models\Main_salary_employee_p_loans_aksat;
use App\Models\Main_salary_employee_rewards;
use App\Models\Main_salary_employee_sanctions;
use App\Models\MainVacationsBalance;

trait GeneralTrait
{

    function Recalculate_main_salary_employee($main_salary_employee_id)
    {
        $com_code = auth()->user()->com_code;
        $main_salary_employee_data = get_cols_where_row(new Main_salary_employee(), array("*"), array('com_code' => $com_code, "id" => $main_salary_employee_id, 'is_archived' => 0));

        if (!empty($main_salary_employee_data)) {
            $employee_data = get_cols_where_row(new Employee(), array('motivation', 'social_nsurance_cutMonthely', 'medical_nsurance_cutMonthely', 'emp_salary', 'day_price', 'id'), array("com_code" => $com_code, 'employees_code' => $main_salary_employee_data['employees_code']));
            $finance_cin_periods_data = get_cols_where_row(new Finance_cin_periods(), array('year_and_month'), array("com_code" => $com_code, 'is_open' => 1, 'id' => $main_salary_employee_data['finance_cin_periods_id']));

            if (!empty($employee_data) && !empty($finance_cin_periods_data)) {


                //اولا المستحق للموظف
                $dataToUpdate['day_price'] = $employee_data['day_price'];
                $dataToUpdate['emp_sal'] = $employee_data['emp_salary'];
                $dataToUpdate['motivation'] = $employee_data['motivation'];
                $dataToUpdate['fixed_suits'] = get_sum_where(new employee_fixed_suits(), "value", array('com_code' => $com_code, "employee_id" => $employee_data['id']));;
                //البدلات المتغيرة
                $dataToUpdate['changable_suits'] = get_sum_where(new Main_salary_employee_allowances(), "total", array('com_code' => $com_code, "main_salary_employee_id" => $main_salary_employee_id));
                //المكافئات المالية
                $dataToUpdate['additions'] = get_sum_where(new Main_salary_employee_rewards(), "total", array('com_code' => $com_code, "main_salary_employee_id" => $main_salary_employee_id));
                //اضافي الايام
                $dataToUpdate['additional_days_counter'] = get_sum_where(new Main_salary_employee_addition(), "value", array('com_code' => $com_code, "main_salary_employee_id" => $main_salary_employee_id));
                $dataToUpdate['additional_days'] = get_sum_where(new Main_salary_employee_addition(), "total", array('com_code' => $com_code, "main_salary_employee_id" => $main_salary_employee_id));
                $dataToUpdate['total_benefits'] = $dataToUpdate['emp_sal'] + $dataToUpdate['motivation'] + $dataToUpdate['fixed_suits'] + $dataToUpdate['changable_suits'] + $dataToUpdate['additions'] + $dataToUpdate['additional_days'];
                $dataToUpdate['social_nsurance_cutMonthely'] = $employee_data['social_nsurance_cutMonthely'];
                $dataToUpdate['medical_nsurance_cutMonthely'] = $employee_data['medical_nsurance_cutMonthely'];

                //المستحق على الموظف

                //الجزاءات
                $dataToUpdate['sanctions_days_counter'] = get_sum_where(new Main_salary_employee_sanctions(), "value", array('com_code' => $com_code, "main_salary_employee_id" => $main_salary_employee_id));
                $dataToUpdate['sanctions_days_total'] = get_sum_where(new Main_salary_employee_sanctions(), "total", array('com_code' => $com_code, "main_salary_employee_id" => $main_salary_employee_id));
                //غياب الايام
                $dataToUpdate['absence_days_counter'] = get_sum_where(new Main_salary_employee_Absence(), "value", array('com_code' => $com_code, "main_salary_employee_id" => $main_salary_employee_id));
                $dataToUpdate['absence_days'] = get_sum_where(new Main_salary_employee_Absence(), "total", array('com_code' => $com_code, "main_salary_employee_id" => $main_salary_employee_id));
                //الخصم المالي
                $dataToUpdate['discount'] = get_sum_where(new Main_salary_employee_discount(), "total", array('com_code' => $com_code, "main_salary_employee_id" => $main_salary_employee_id));

                //السلف الشهرية
                $dataToUpdate['monthly_loan'] = get_sum_where(new Main_salary_employee_loans(), "total", array('com_code' => $com_code, "main_salary_employee_id" => $main_salary_employee_id));
                //السلف المستديمة
                $dataToUpdate['permanent_loan'] = Main_salary_employee_p_loans_aksat::where('com_code', '=', $com_code)
                    ->where("year_and_month", "=", $finance_cin_periods_data['year_and_month'])
                    ->where('is_archived', '=', 0)
                    ->where('state', '!=', 2)
                    ->where('employees_code', '=', $main_salary_employee_data['employees_code'])
                    ->where('is_parent_dismissail_done', '=', 1)
                    ->sum('month_kast_value');

                $dataToUpdateAksat['state'] = 1;
                $dataToUpdateAksat['main_salary_employee_id'] = $main_salary_employee_id;
                Main_salary_employee_p_loans_aksat::where('com_code', '=', $com_code)
                    ->where("year_and_month", $finance_cin_periods_data['year_and_month'])
                    ->where('is_archived', '=', 0)
                    ->where('state', '!=', 2)
                    ->where('employees_code', '=', $main_salary_employee_data['employees_code'])
                    ->update($dataToUpdateAksat);


                $dataToUpdate['total_deductions'] = $dataToUpdate['social_nsurance_cutMonthely'] + $dataToUpdate['medical_nsurance_cutMonthely'] + $dataToUpdate['sanctions_days_total'] + $dataToUpdate['absence_days'] + $dataToUpdate['discount'] + $dataToUpdate['monthly_loan'] + $dataToUpdate['permanent_loan'];

                $dataToUpdate['final_the_net'] = $main_salary_employee_data['last_salary_remain_balance'] + ($dataToUpdate['total_benefits'] - $dataToUpdate['total_deductions']);



                update(new  Main_salary_employee(), $dataToUpdate, array('com_code' => $com_code, "id" => $main_salary_employee_id, 'is_archived' => 0));




                //صافي الراتب

            }
        }
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

                                $datainsert['finance_yr'] = $CurrentOpenMonth['finance_yr'];
                                $datainsert['current_month_balance'] = $admin_panel_settingsData['monthly_vaction_balance'];
                                $datainsert['total_available_balance'] = $admin_panel_settingsData['monthly_vaction_balance'];
                                $datainsert['net_balance'] = $admin_panel_settingsData['monthly_vaction_balance'];
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
                                $data_to_update_vacations['net_balance'] = $data_to_update_vacations['total_available_balance'] - $info->spent_balance;
                                
                                update(new MainVacationsBalance(), $data_to_update_vacations, array('com_code' => $com_code, 'employees_code' => $employees_code, 'id' => $info->id));
                            }
                        }
                    }
                }
            }
        }
    }
}
