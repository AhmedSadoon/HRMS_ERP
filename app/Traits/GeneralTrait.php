<?php

namespace App\Traits;

use App\Models\Employee;
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

trait GeneralTrait
{

    function Recalculate_main_salary_employee($main_salary_employee_id)
    {
        $com_code = auth()->user()->com_code;
        $main_salary_employee_data = get_cols_where_row(new Main_salary_employee(), array("*"), array('com_code' => $com_code, "id" => $main_salary_employee_id, 'is_archived' => 0));

        if (!empty($main_salary_employee_data)) {
            $employee_data = get_cols_where_row(new Employee(), array('motivation', 'social_nsurance_cutMonthely', 'medical_nsurance_cutMonthely', 'emp_salary', 'day_price'), array("com_code" => $com_code, 'employees_code' => $main_salary_employee_data['employees_code']));
            $finance_cin_periods_data = get_cols_where_row(new Finance_cin_periods(), array('year_and_month'), array("com_code" => $com_code,'is_open'=>1, 'id' => $main_salary_employee_data['finance_cin_periods_id']));

            if (!empty($employee_data) && !empty($finance_cin_periods_data)) {

                
                    //اولا المستحق للموظف
                    $dataToUpdate['day_price'] = $employee_data['day_price'];
                    $dataToUpdate['emp_sal'] = $employee_data['emp_salary'];
                    $dataToUpdate['motivation'] = $employee_data['motivation'];
                    $dataToUpdate['fixed_suits'] = 0;
                     //البدلات المتغيرة
                     $dataToUpdate['changable_suits'] = get_sum_where(new Main_salary_employee_allowances(), "total", array('com_code' => $com_code, "main_salary_employee_id" => $main_salary_employee_id));
                      //المكافئات المالية
                    $dataToUpdate['additions'] = get_sum_where(new Main_salary_employee_rewards(), "total", array('com_code' => $com_code, "main_salary_employee_id" => $main_salary_employee_id));
                   //اضافي الايام
                   $dataToUpdate['additional_days_counter'] = get_sum_where(new Main_salary_employee_addition(), "value", array('com_code' => $com_code, "main_salary_employee_id" => $main_salary_employee_id));
                   $dataToUpdate['additional_days'] = get_sum_where(new Main_salary_employee_addition(), "total", array('com_code' => $com_code, "main_salary_employee_id" => $main_salary_employee_id));
                   $dataToUpdate['total_benefits']=$dataToUpdate['emp_sal']+$dataToUpdate['motivation']+$dataToUpdate['fixed_suits']+$dataToUpdate['changable_suits']+$dataToUpdate['additions']+$dataToUpdate['additional_days'];
                    $dataToUpdate['social_nsurance_cutMonthely'] = $employee_data['social_nsurance_cutMonthely'];
                    $dataToUpdate['medical_nsurance_cutMonthely'] = $employee_data['medical_nsurance_cutMonthely'];

                    //المستحق على الموظف

                    //الجزاءات
                    $dataToUpdate['sanctions_days_counter'] = get_sum_where(new Main_salary_employee_sanctions(), "value", array('com_code' => $com_code, "main_salary_employee_id" => $main_salary_employee_id));
                    $dataToUpdate['sanctions_days_total'] = get_sum_where(new Main_salary_employee_sanctions(), "total", array('com_code' => $com_code, "main_salary_employee_id" => $main_salary_employee_id));
                    //غياب الايام
                    $dataToUpdate['absence_days_counter'] = get_sum_where(new Main_salary_employee_Absence(), "value", array('com_code' => $com_code, "main_salary_employee_id" => $main_salary_employee_id));
                    $dataToUpdate['absence_days'] =get_sum_where(new Main_salary_employee_Absence(), "total", array('com_code' => $com_code, "main_salary_employee_id" => $main_salary_employee_id));
                    //الخصم المالي
                    $dataToUpdate['discount'] = get_sum_where(new Main_salary_employee_discount(), "total", array('com_code' => $com_code, "main_salary_employee_id" => $main_salary_employee_id));
                   
                    //السلف الشهرية
                    $dataToUpdate['monthly_loan'] = get_sum_where(new Main_salary_employee_loans(), "total", array('com_code' => $com_code, "main_salary_employee_id" => $main_salary_employee_id));
                    //السلف المستديمة
                    $dataToUpdate['permanent_loan'] = Main_salary_employee_p_loans_aksat::where('com_code' ,'=', $com_code)
                    ->where("year_and_month","=",$finance_cin_periods_data['year_and_month'])
                    ->where('is_archived','=',0)
                    ->where('state','!=',2)
                    ->sum("month_kast_value");

                    
                   
                    $dataToUpdate['total_deductions']= $dataToUpdate['social_nsurance_cutMonthely']+$dataToUpdate['medical_nsurance_cutMonthely']+$dataToUpdate['sanctions_days_total']+$dataToUpdate['absence_days']+$dataToUpdate['discount']+$dataToUpdate['monthly_loan']+$dataToUpdate['monthly_loan'];
                
                    $dataToUpdate['final_the_net']=$main_salary_employee_data['last_salary_remain_balance']+($dataToUpdate['total_benefits']-$dataToUpdate['total_deductions']);
                
                

                update(new  Main_salary_employee(),$dataToUpdate,array('com_code' => $com_code, "id" => $main_salary_employee_id, 'is_archived' => 0));
                
                $dataToUpdateAksat['state']=1;
                $dataToUpdateAksat['main_salary_employee_id']=$main_salary_employee_id;
                Main_salary_employee_p_loans_aksat::where('com_code' ,'=', $com_code)->where("year_and_month",$main_salary_employee_data['year_and_month'])->where('is_archived','=',0)->where('state','!=',2)->update($dataToUpdateAksat);
                
                //صافي الراتب

            }
        }
    }
}
