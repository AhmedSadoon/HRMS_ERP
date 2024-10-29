<?php

namespace App\Imports;

use App\Models\Attendance_departure_actions_excel;
use App\Models\Employee;
use App\Models\Main_salary_employee;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class Attendance_departureImport implements ToCollection
{
    private $finance_cin_periods_id;

    public function __construct($finance_cin_periods_id){
        $this->finance_cin_periods_id=$finance_cin_periods_id;
    }
    public function collection(Collection $rows)
    {
        $com_code=auth()->user()->com_code;
        foreach ($rows as $row) 
        {
            if($row[4]=='C/In'){
                $action_type=1;
            }else{
                $action_type=2;
            }
            $EmployeeData=get_cols_where_row(new Employee(),array('employees_code'),array('com_code'=>$com_code,'zketo_code'=>$row[2]));
            if(!empty($EmployeeData)){

                $checkExsistsBefor=get_cols_where_row(new Attendance_departure_actions_excel(),array('id'),array('com_code'=>$com_code,'finance_cin_periods_id'=>$this->finance_cin_periods_id,'employees_code'=>$EmployeeData['employees_code'],'datetimeAction'=>date('Y-m-d H:i:s',strtotime($row[3])),'action_type'=>$action_type));
            if(empty($checkExsistsBefor)){
                $checkExsistsSalary=get_cols_where_row(new Main_salary_employee(),array('id'),array('com_code'=>$com_code,'finance_cin_periods_id'=>$this->finance_cin_periods_id,'employees_code'=>$EmployeeData['employees_code']));

                if(!empty($checkExsistsSalary)){
                $dataToInsert['main_salary_employee_id']=$checkExsistsSalary['id'];
                }
                $dataToInsert['finance_cin_periods_id']=$this->finance_cin_periods_id;
                $dataToInsert['employees_code']=$EmployeeData['employees_code'];
                $dataToInsert['datetimeAction']=date('Y-m-d H:i:s',strtotime($row[3]));
                $dataToInsert['action_type']=$action_type;
                $dataToInsert['added_by']=auth()->user()->id;
                $dataToInsert['created_at']=date('Y-m-d H:i:s');
                $dataToInsert['com_code']=$com_code;
                insert(new Attendance_departure_actions_excel(),$dataToInsert);

            }
           

            }
        }
    }
}
