<?php

namespace App\Imports;

use App\Models\admin_panel_setting;
use App\Models\Attendance_departure;
use App\Models\Attendance_departure_actions;
use App\Models\Attendance_departure_actions_excel;
use App\Models\Employee;
use App\Models\Main_salary_employee;
use App\Models\Shifts_type;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class Attendance_departureImport implements ToCollection
{
    private $finance_cin_periods_data;

    public function __construct($finance_cin_periods_data)
    {
        $this->finance_cin_periods_data = $finance_cin_periods_data;
    }
    public function collection(Collection $rows)
    {
        $com_code = auth()->user()->com_code;
        $dataSetting = get_cols_where_row(new admin_panel_setting(), array("*"), array('com_code' => $com_code));
        foreach ($rows as $row) {
            //نختبر لو التاريخ داخل فترة الشهر المالي او لا 
            $the_date_check = date('Y-m-d', strtotime($row[3]));
            if ($the_date_check < $this->finance_cin_periods_data['start_date_for_pasma'] || $the_date_check > $this->finance_cin_periods_data['end_date_for_pasma']) {
                continue;
            }
            if ($row[4] == 'C/In') {
                $action_type = 1;
            } else {
                $action_type = 2;
            }
            $EmployeeData = get_cols_where_row(new Employee(), array('employees_code', 'is_has_fixced_shift', 'shift_type_id', 'daily_work_hour', 'branch_id', 'function_status'), array('com_code' => $com_code, 'zketo_code' => $row[2]));
            if (!empty($EmployeeData)) {

                $checkExsistsBefor = get_cols_where_row(new Attendance_departure_actions_excel(), array('id'), array('com_code' => $com_code, 'finance_cin_periods_id' => $this->finance_cin_periods_data['id'], 'employees_code' => $EmployeeData['employees_code'], 'datetimeAction' => date('Y-m-d H:i:s', strtotime($row[3])), 'action_type' => $action_type));
                if (empty($checkExsistsBefor)) {
                    $checkExsistsSalary = get_cols_where_row(new Main_salary_employee(), array('id'), array('com_code' => $com_code, 'finance_cin_periods_id' => $this->finance_cin_periods_data['id'], 'employees_code' => $EmployeeData['employees_code']));

                    if (!empty($checkExsistsSalary)) {
                        $dataToInsert['main_salary_employee_id'] = $checkExsistsSalary['id'];
                    }
                    $dataToInsert['finance_cin_periods_id'] = $this->finance_cin_periods_data['id'];
                    $dataToInsert['employees_code'] = $EmployeeData['employees_code'];
                    $dataToInsert['action_type'] = $action_type;
                    $dataToInsert['datetimeAction'] = date('Y-m-d H:i:s', strtotime($row[3]));
                    $dataToInsert['added_by'] = auth()->user()->id;
                    $dataToInsert['created_at'] = date('Y-m-d H:i:s');
                    $dataToInsert['com_code'] = $com_code;
                    $Attendance_departure_actions_excel_data = insert(new Attendance_departure_actions_excel(), $dataToInsert, true);

                    //بشكل مؤقت حنسوي الكود هنا 
                    //التأكد من حالة الشفت المحدد للموظف
                    if ($EmployeeData['is_has_fixced_shift'] == 1) {
                        $shift_data = get_cols_where_row(new Shifts_type(), array('form_time', 'to_time', 'total_huor'), array('com_code' => $com_code, 'id' => $EmployeeData['shift_type_id']));
                        if (empty($shift_data)) {
                            continue;
                        } else {
                            $shift_huor = $shift_data['total_huor'];
                        }
                    } else {
                        if ($EmployeeData['daily_work_hour'] == 0 || $EmployeeData['daily_work_hour'] == NULL) {
                            continue;
                        } else {
                            $shift_huor = $EmployeeData['daily_work_hour'];
                        }
                    }

                    //ثانيا هل يوجد يوم فارغ مسجل مطابق لهذا التاريخ
                    $checkfor_empty_record = get_cols_where_row(new Attendance_departure(), array('id'), array('com_code' => $com_code, 'employees_code' => $EmployeeData['employees_code'], 'finance_cin_periods_id' => $this->finance_cin_periods_data['id'], 'the_day_date' => date('Y-m-d', strtotime($row[3])), 'datetime_in' => null));
                    if (!empty($checkfor_empty_record)) {
                        //بصمة الحالية كانت حضور نحذف البصمة الفارغة
                        if ($action_type == 1) {
                            destroy(new Attendance_departure(), array('com_code' => $com_code, 'id' => $checkfor_empty_record['id']));
                        }
                    }


                    //ثالثاً جلب اخر سجل بصمة مسجل
                    //check for last record
                    $last = Attendance_departure::select("*")
                        ->where('employees_code', '=', $EmployeeData['employees_code'])
                        ->where('finance_cin_periods_id', '=', $this->finance_cin_periods_data['id'])
                        ->where('com_code', '=', $com_code)
                        ->where('datein', '!=', null)
                        ->where('datein', '<=', date('Y-m-d', strtotime($row[3])))
                        ->orderBy('datein', 'DESC')
                        ->first();

                    if (!empty($last)) {
                        //نشوف فرق الدقائق ما بين اخر بصمة مسحلة والبصمة الحالية
                        $lastAttendance = $last['datetime_in'];
                        $seconds = strtotime($row[3]) - strtotime($last['datetime_in']);
                        $hourdiff = $seconds / 60 / 60;
                        $hourdiff = number_format((float)$hourdiff, 2, '.' . '');
                        $minutesiff = $seconds / 60;
                        $minutesiff = number_format((float)$minutesiff, 2, '.' . '');
                        if ($hourdiff < 0) $hourdiff - $hourdiff * (-1);
                        if ($minutesiff < 0) $minutesiff - $minutesiff * (-1);
                        //اذا كان تاريخ اخر بصمة مسجلة هو نفس تاريخ البصمة الحالية يعني نفس اليوم
                        if ($last['datein'] == date('Y-m-d', strtotime($row[3]))) {
                            // هل البصمة تأديدية او بصمة فعلية للانصراف او الحضور
                            if ($minutesiff > $dataSetting['less_than_miniute_neglecting_passma']) {
                                //اشتغل على متغيرات اقفال البصمة الحالية
                                $dataUpdate['datetime_out'] = date('Y-m-d H:i:s', strtotime($row[3]));
                                $dataUpdate['dateOut'] = date('Y-m-d', strtotime($row[3]));
                                $dataUpdate['time_out'] = date('H:i:s', strtotime($row[3]));
                                $dataUpdate['total_hours'] = $hourdiff;

                                if ($hourdiff < $shift_huor) {
                                    $dataUpdate['additional_hours'] = 0;
                                    $dataUpdate['absen_hours'] = $shift_huor - $hourdiff;
                                }

                                if ($hourdiff > $shift_huor) {
                                    $dataUpdate['additional_hours'] = $hourdiff - $shift_huor;
                                    $dataUpdate['absen_hours'] = 0;
                                }

                                //لو الموظف له شفت ثابت نشوف موضوع الانصراف المبكر والجزاء عليه
                                $timeenter = date('H:i:s', strtotime($row[3])); //time out وقت خروجه
                                if ($EmployeeData['is_has_fixced_shift'] == 1) {
                                    if ($shift_data['to_time'] > $timeenter) {
                                        $secondNow =  strtotime($shift_data['to_time']) - strtotime($timeenter);
                                        $minutesiffNow = $secondNow / 60;
                                        $minutesiffNow = number_format((float)$minutesiffNow, 2, '.', '');
                                        //هل الموظف اجتازة عدد الدقائق الانصراف المبكر المسموح بها
                                        if ($minutesiffNow >= $dataSetting['after_miniute_calculate_delay']) {

                                            $dataUpdate['early_departure'] = $minutesiffNow;
                                            $countercutQuarterday = get_count_where(new Attendance_departure(), array('com_code' => $com_code, 'employees_code' => $EmployeeData['employees_code'], 'finance_cin_periods_id' => $this->finance_cin_periods_data['id'], 'cut' => .25));
                                            $countercutHalfday = get_count_where(new Attendance_departure(), array('com_code' => $com_code, 'employees_code' => $EmployeeData['employees_code'], 'finance_cin_periods_id' => $this->finance_cin_periods_data['id'], 'cut' => .5));
                                            $countercutOneday = get_count_where(new Attendance_departure(), array('com_code' => $com_code, 'employees_code' => $EmployeeData['employees_code'], 'finance_cin_periods_id' => $this->finance_cin_periods_data['id'], 'cut' => 1));
                                            if ($countercutOneday >= $dataSetting['after_time_allday_daycut']) {
                                                $dataUpdate['cut'] = 1 + $countercutOneday;
                                            } elseif ($countercutHalfday >= $dataSetting['after_time_half_dayCut']) {
                                                $dataUpdate['cut'] = .5 + ($countercutHalfday * .5);
                                            } elseif ($countercutQuarterday >= $dataSetting['after_miniute_quarterday']) {
                                                $dataUpdate['cut'] = .25 + ($countercutQuarterday * .25);
                                            } else {
                                                //$dataUpdate['cut'] = 0;
                                            }
                                        }
                                    }
                                }
                                $dataUpdate['vacations_type_id'] = 0;
                                $flagUpdateParent = update(new Attendance_departure(), $dataUpdate, array('com_code' => $com_code, 'id' => $last['id']));
                                if ($flagUpdateParent) {
                                    //تسجل البصمات الفعلية للموظف 
                                    $dataToInsertAction['attendance_departure_id'] = $last['id'];
                                    $dataToInsertAction['finance_cin_periods_id'] = $this->finance_cin_periods_data['id'];
                                    $dataToInsertAction['employees_code'] = $EmployeeData['employees_code'];
                                    $dataToInsertAction['datetimeAction'] = date('Y-m-d H:i:s', strtotime($row[3]));
                                    $dataToInsertAction['action_type'] = $action_type;
                                   
                                        $dataToInsertAction['it_is_active_with_parent'] = 0;
                                        $dataToInsertAction['added_method'] = 1;
                                    

                                    $dataToInsertAction['added_method'] = 1;
                                    $dataToInsertAction['is_made_action_on_emp'] = 0;
                                    $dataToInsertAction['added_by'] = auth()->user()->id;
                                    $dataToInsertAction['com_code'] = $com_code;
                                    $dataToInsertAction['AttendanceDepartureActionsExcelId'] = $Attendance_departure_actions_excel_data['id'];
                                    $dataToUpdateAction['it_is_active_with_parent']=1;
                                    insert(new Attendance_departure_actions(), $dataToInsertAction);
                                    update(new Attendance_departure_actions(),$dataToUpdateAction,  array('com_code' => $com_code,  'action_type' => $action_type, 'attendance_departure_id' => $last['id'],'datetimeAction'=>$dataUpdate['datetime_out']));
                                   

                                }
                            }
                        } else {
                            //تواريخ مختلفة
                            //بصمة الحضور والانصراف للموظف في تواريخ مختلفة
                            //عدد صاعات فرق بين بصمة الحضور والانصراف تساوي او اقل من عدد ساعات شفت الموظف
                            if ($hourdiff <= $shift_huor) {

                                //تقفيل بصمة اليوم

                                // هل البصمة تأديدية او بصمة فعلية للانصراف او الحضور
                                if ($minutesiff > $dataSetting['less_than_miniute_neglecting_passma']) {
                                    //اشتغل على متغيرات اقفال البصمة الحالية
                                    $dataUpdate['datetime_out'] = date('Y-m-d H:i:s', strtotime($row[3]));
                                    $dataUpdate['dateOut'] = date('Y-m-d', strtotime($row[3]));
                                    $dataUpdate['time_out'] = date('H:i:s', strtotime($row[3]));
                                    $dataUpdate['total_hours'] = $hourdiff;

                                    if ($hourdiff < $shift_huor) {
                                        $dataUpdate['additional_hours'] = 0;
                                        $dataUpdate['absen_hours'] = $shift_huor - $hourdiff;
                                    }

                                    if ($hourdiff > $shift_huor) {
                                        $dataUpdate['additional_hours'] = $hourdiff - $shift_huor;
                                        $dataUpdate['absen_hours'] = 0;
                                    }

                                    //لو الموظف له شفت ثابت نشوف موضوع الانصراف المبكر والجزاء عليه
                                    $timeenter = date('H:i:s', strtotime($row[3])); //time out وقت خروجه
                                    if ($EmployeeData['is_has_fixced_shift'] == 1) {

                                    if ($shift_data['to_time'] > $timeenter) {
                                        $secondNow =  strtotime($shift_data['to_time']) - strtotime($timeenter);
                                        $minutesiffNow = $secondNow / 60;
                                        $minutesiffNow = number_format((float)$minutesiffNow, 2, '.', '');
                                        //هل الموظف اجتازة عدد الدقائق الانصراف المبكر المسموح بها
                                        if ($minutesiffNow >= $dataSetting['after_miniute_calculate_delay']) {

                                            $dataUpdate['early_departure'] = $minutesiffNow;
                                            $countercutQuarterday = get_count_where(new Attendance_departure(), array('com_code' => $com_code, 'employees_code' => $EmployeeData['employees_code'], 'finance_cin_periods_id' => $this->finance_cin_periods_data['id'], 'cut' => .25));
                                            $countercutHalfday = get_count_where(new Attendance_departure(), array('com_code' => $com_code, 'employees_code' => $EmployeeData['employees_code'], 'finance_cin_periods_id' => $this->finance_cin_periods_data['id'], 'cut' => .5));
                                            $countercutOneday = get_count_where(new Attendance_departure(), array('com_code' => $com_code, 'employees_code' => $EmployeeData['employees_code'], 'finance_cin_periods_id' => $this->finance_cin_periods_data['id'], 'cut' => 1));
                                            if ($countercutOneday >= $dataSetting['after_time_allday_daycut']) {
                                                $dataUpdate['cut'] = 1 + $countercutOneday;
                                            } elseif ($countercutHalfday >= $dataSetting['after_time_half_dayCut']) {
                                                $dataUpdate['cut'] = .5 + ($countercutHalfday * .5);
                                            } elseif ($countercutQuarterday >= $dataSetting['after_miniute_quarterday']) {
                                                $dataUpdate['cut'] = .25 + ($countercutQuarterday * .25);
                                            } else {
                                                //$dataUpdate['cut'] = 0;
                                            }
                                        }
                                    }
                                }
                                    $dataUpdate['vacations_type_id'] = 0;
                                    $flagUpdateParent = update(new Attendance_departure(), $dataUpdate, array('com_code' => $com_code, 'id' => $last['id']));
                                    if ($flagUpdateParent) {
                                        //تسجل البصمات الفعلية للموظف 
                                        $dataToInsertAction['attendance_departure_id'] = $last['id'];
                                        $dataToInsertAction['finance_cin_periods_id'] = $this->finance_cin_periods_data['id'];
                                        $dataToInsertAction['employees_code'] = $EmployeeData['employees_code'];
                                        $dataToInsertAction['datetimeAction'] = date('Y-m-d H:i:s', strtotime($row[3]));
                                        $dataToInsertAction['action_type'] = $action_type;
                                         $dataToInsertAction['it_is_active_with_parent'] = 0;
                                         $dataToInsertAction['added_method'] = 1;
                                        

                                        $dataToInsertAction['added_method'] = 1;
                                        $dataToInsertAction['is_made_action_on_emp'] = 0;
                                        $dataToInsertAction['added_by'] = auth()->user()->id;
                                        $dataToInsertAction['com_code'] = $com_code;
                                        $dataToInsertAction['AttendanceDepartureActionsExcelId'] = $Attendance_departure_actions_excel_data['id'];
                                        $dataToUpdateAction['it_is_active_with_parent']=1;
                                        insert(new Attendance_departure_actions(), $dataToInsertAction);

                                        update(new Attendance_departure_actions(),$dataToUpdateAction,  array('com_code' => $com_code,  'action_type' => $action_type, 'attendance_departure_id' => $last['id'],'datetimeAction'=>$dataUpdate['datetime_out']));
                                            

                                    }
                                }


                                /////////////////////////////////////////////
                            } else {
                                //نشوف الضبط العام والحد الاقص لاحتساب عدد ساعات الاضافي والا سيكون حضور شفت جديد 

                                if (($hourdiff - $shift_huor) <= $dataSetting['max_hours_take_Pssma_as_additional']) {

                                    // هل البصمة تأديدية او بصمة فعلية للانصراف او الحضور
                                    if ($minutesiff > $dataSetting['less_than_miniute_neglecting_passma']) {
                                        //اشتغل على متغيرات اقفال البصمة الحالية
                                        $dataUpdate['datetime_out'] = date('Y-m-d H:i:s', strtotime($row[3]));
                                        $dataUpdate['dateOut'] = date('Y-m-d', strtotime($row[3]));
                                        $dataUpdate['time_out'] = date('H:i:s', strtotime($row[3]));
                                        $dataUpdate['total_hours'] = $hourdiff;

                                        if ($hourdiff < $shift_huor) {
                                            $dataUpdate['additional_hours'] = 0;
                                            $dataUpdate['absen_hours'] = $shift_huor - $hourdiff;
                                        }

                                        if ($hourdiff > $shift_huor) {
                                            $dataUpdate['additional_hours'] = $hourdiff - $shift_huor;
                                            $dataUpdate['absen_hours'] = 0;
                                        }

                                        //لو الموظف له شفت ثابت نشوف موضوع الانصراف المبكر والجزاء عليه
                                        $timeenter = date('H:i:s', strtotime($row[3])); //time out وقت خروجه
                                        if ($EmployeeData['is_has_fixced_shift'] == 1) {

                                        if ($shift_data['to_time'] > $timeenter) {
                                            $secondNow =  strtotime($shift_data['to_time']) - strtotime($timeenter);
                                            $minutesiffNow = $secondNow / 60;
                                            $minutesiffNow = number_format((float)$minutesiffNow, 2, '.', '');
                                            //هل الموظف اجتازة عدد الدقائق الانصراف المبكر المسموح بها
                                            //هنا هذه الحالة لن تطبق
                                            if ($minutesiffNow >= $dataSetting['after_miniute_calculate_delay']) {

                                                $dataUpdate['early_departure'] = $minutesiffNow;
                                                $countercutQuarterday = get_count_where(new Attendance_departure(), array('com_code' => $com_code, 'employees_code' => $EmployeeData['employees_code'], 'finance_cin_periods_id' => $this->finance_cin_periods_data['id'], 'cut' => .25));
                                                $countercutHalfday = get_count_where(new Attendance_departure(), array('com_code' => $com_code, 'employees_code' => $EmployeeData['employees_code'], 'finance_cin_periods_id' => $this->finance_cin_periods_data['id'], 'cut' => .5));
                                                $countercutOneday = get_count_where(new Attendance_departure(), array('com_code' => $com_code, 'employees_code' => $EmployeeData['employees_code'], 'finance_cin_periods_id' => $this->finance_cin_periods_data['id'], 'cut' => 1));
                                                if ($countercutOneday >= $dataSetting['after_time_allday_daycut']) {
                                                    $dataUpdate['cut'] = 1 + $countercutOneday;
                                                } elseif ($countercutHalfday >= $dataSetting['after_time_half_dayCut']) {
                                                    $dataUpdate['cut'] = .5 + ($countercutHalfday * .5);
                                                } elseif ($countercutQuarterday >= $dataSetting['after_miniute_quarterday']) {
                                                    $dataUpdate['cut'] = .25 + ($countercutQuarterday * .25);
                                                } else {
                                                    //$dataUpdate['cut'] = 0;
                                                }
                                            }
                                        }
                                    }
                                        $dataUpdate['vacations_type_id'] = 0;
                                        $flagUpdateParent = update(new Attendance_departure(), $dataUpdate, array('com_code' => $com_code, 'id' => $last['id']));
                                        if ($flagUpdateParent) {
                                            //تسجل البصمات الفعلية للموظف 
                                            $dataToInsertAction['attendance_departure_id'] = $last['id'];
                                            $dataToInsertAction['finance_cin_periods_id'] = $this->finance_cin_periods_data['id'];
                                            $dataToInsertAction['employees_code'] = $EmployeeData['employees_code'];
                                            $dataToInsertAction['datetimeAction'] = date('Y-m-d H:i:s', strtotime($row[3]));
                                            $dataToInsertAction['action_type'] = $action_type;
                                          
                                                $dataToInsertAction['it_is_active_with_parent'] = 0;
                                                $dataToInsertAction['added_method'] = 1;
                                            

                                            $dataToInsertAction['added_method'] = 1;
                                            $dataToInsertAction['is_made_action_on_emp'] = 0;
                                            $dataToInsertAction['added_by'] = auth()->user()->id;
                                            $dataToInsertAction['com_code'] = $com_code;
                                            $dataToInsertAction['AttendanceDepartureActionsExcelId'] = $Attendance_departure_actions_excel_data['id'];
                                            $dataToUpdateAction['it_is_active_with_parent']=1;
                                            insert(new Attendance_departure_actions(), $dataToInsertAction);

                                            update(new Attendance_departure_actions(),$dataToUpdateAction,  array('com_code' => $com_code,  'action_type' => $action_type, 'attendance_departure_id' => $last['id'],'datetimeAction'=>$dataUpdate['datetime_out']));
                                           

                                        }
                                    }

                                    /////////////////////////////////
                                } else {
                                    //هنا يبقى عندي تسكين شفت جديد

                                    // راح نجهز الادخال ونعتبر البصمة حضور بغض النظر عن نوع البصمة
                                    //after check empty last

                                    $datainsert['finance_cin_periods_id'] = $this->finance_cin_periods_data['id'];
                                    $datainsert['shift_hour_contract'] = $shift_huor;
                                    $datainsert['status_move'] = 1;
                                    $datainsert['employees_code'] = $EmployeeData['employees_code'];
                                    $datainsert['datein'] = date('Y-m-d', strtotime($row[3]));
                                    $timeenter = $datainsert['datein'];
                                    $datainsert['time_in'] = date('H:i:s', strtotime($row[3]));
                                    $datainsert['datetime_in'] = date('Y-m-d H:i:s', strtotime($row[3]));
                                    $datainsert['com_code'] = $com_code;
                                    $dataToInsert['added_by'] = auth()->user()->id;
                                    //لو الموظف له شفت ثابت نطبق عليه بعض متغيرات الضبط العام

                                    if ($EmployeeData['is_has_fixced_shift'] == 1) {

                                        if ($shift_data['form_time'] < $timeenter) {
                                            $secondNow = strtotime($timeenter) - strtotime($shift_data['form_time']);
                                            $minutesiffNow = $secondNow / 60;
                                            $minutesiffNow = number_format((float)$minutesiffNow, 2, '.', '');
                                            //هل الموظف اجتازة عدد الدقائق التأخير المسموح بها
                                            if ($minutesiffNow >= $dataSetting['after_miniute_calculate_delay']) {

                                                $datainsert['attedance_dely'] = $minutesiffNow;
                                                $countercutQuarterday = get_count_where(new Attendance_departure(), array('com_code' => $com_code, 'employees_code' => $EmployeeData['employees_code'], 'finance_cin_periods_id' => $this->finance_cin_periods_data['id'], 'cut' => .25));
                                                $countercutHalfday = get_count_where(new Attendance_departure(), array('com_code' => $com_code, 'employees_code' => $EmployeeData['employees_code'], 'finance_cin_periods_id' => $this->finance_cin_periods_data['id'], 'cut' => .5));
                                                $countercutOneday = get_count_where(new Attendance_departure(), array('com_code' => $com_code, 'employees_code' => $EmployeeData['employees_code'], 'finance_cin_periods_id' => $this->finance_cin_periods_data['id'], 'cut' => 1));
                                                if ($countercutOneday >= $dataSetting['after_time_allday_daycut']) {
                                                    $datainsert['cut'] = 1;
                                                } elseif ($countercutHalfday >= $dataSetting['after_time_half_dayCut']) {
                                                    $datainsert['cut'] = .5;
                                                } elseif ($countercutQuarterday >= $dataSetting['after_miniute_quarterday']) {
                                                    $datainsert['cut'] = .25;
                                                } else {
                                                    $datainsert['cut'] = 0;
                                                }
                                            }
                                        }
                                    }

                                    $datainsert['year_and_month'] = $this->finance_cin_periods_data['year_and_month'];
                                    $datainsert['branch_id'] = $EmployeeData['branch_id'];
                                    $datainsert['function_status'] = $EmployeeData['function_status'];
                                    $main_salary_employee = get_cols_where_row(new Main_salary_employee(), array('id'), array('com_code' => $com_code, 'employees_code' => $EmployeeData['employees_code'], 'is_archived' => 0));
                                    if (!empty($main_salary_employee)) {
                                        $datainsert['main_salary_employee_id'] = $main_salary_employee['id'];
                                    }
                                    $datainsert['added_by'] = auth()->user()->id;
                                    $datainsert['com_code'] = $com_code;
                                    $datainsert['the_day_date'] = $datainsert['datein'];

                                    $flaginsertParent = insert(new Attendance_departure(), $datainsert, true);
                                    if ($flaginsertParent) {
                                        //تسجل البصمات الفعلية للموظف 
                                        $dataToInsertAction['attendance_departure_id'] = $flaginsertParent['id'];
                                        $dataToInsertAction['finance_cin_periods_id'] = $this->finance_cin_periods_data['id'];
                                        $dataToInsertAction['employees_code'] = $EmployeeData['employees_code'];
                                        $dataToInsertAction['datetimeAction'] = date('Y-m-d H:i:s', strtotime($row[3]));
                                        $dataToInsertAction['action_type'] = $action_type;
                                        $dataToInsertAction['it_is_active_with_parent'] = 0;
                                        $dataToInsertAction['added_method'] = 1;
                                        $dataToInsertAction['is_made_action_on_emp'] = 0;
                                        $dataToInsertAction['added_by'] = auth()->user()->id;
                                        $dataToInsertAction['com_code'] = $com_code;
                                        $dataToInsertAction['AttendanceDepartureActionsExcelId'] = $Attendance_departure_actions_excel_data['id'];
                                        insert(new Attendance_departure_actions(), $dataToInsertAction);
                                        $dataToUpdateAction['it_is_active_with_parent']=1;
                                        update(new Attendance_departure_actions(),$dataToUpdateAction,  array('com_code' => $com_code,  'action_type' => $action_type, 'attendance_departure_id' => $flaginsertParent['id'],'datetimeAction'=>$datainsert['datetime_in']));

                                    }
                                }
                            }
                        }
                    } else {
                        //تعتبر كأول بصمة للموظف خلال الشهر المالي المفتوح حالياً
                        // راح نجهز الادخال ونعتبر البصمة حضور بغض النظر عن نوع البصمة
                        //after check empty last

                        $datainsert['finance_cin_periods_id'] = $this->finance_cin_periods_data['id'];
                        $datainsert['shift_hour_contract'] = $shift_huor;
                        $datainsert['status_move'] = 1;
                        $datainsert['employees_code'] = $EmployeeData['employees_code'];
                        $datainsert['datein'] = date('Y-m-d', strtotime($row[3]));
                        $timeenter = $datainsert['datein'];
                        $datainsert['time_in'] = date('H:i:s', strtotime($row[3]));
                        $datainsert['datetime_in'] = date('Y-m-d H:i:s', strtotime($row[3]));
                        $datainsert['com_code'] = $com_code;
                        $dataToInsert['added_by'] = auth()->user()->id;
                        //لو الموظف له شفت ثابت نطبق عليه بعض متغيرات الضبط العام

                        if ($EmployeeData['is_has_fixced_shift'] == 1) {

                            if ($shift_data['form_time'] < $timeenter) {
                                $secondNow = strtotime($timeenter) - strtotime($shift_data['form_time']);
                                $minutesiffNow = $secondNow / 60;
                                $minutesiffNow = number_format((float)$minutesiffNow, 2, '.', '');
                                //هل الموظف اجتازة عدد الدقائق التأخير المسموح بها
                                if ($minutesiffNow >= $dataSetting['after_miniute_calculate_delay']) {

                                    $datainsert['attedance_dely'] = $minutesiffNow;
                                    $countercutQuarterday = get_count_where(new Attendance_departure(), array('com_code' => $com_code, 'employees_code' => $EmployeeData['employees_code'], 'finance_cin_periods_id' => $this->finance_cin_periods_data['id'], 'cut' => .25));
                                    $countercutHalfday = get_count_where(new Attendance_departure(), array('com_code' => $com_code, 'employees_code' => $EmployeeData['employees_code'], 'finance_cin_periods_id' => $this->finance_cin_periods_data['id'], 'cut' => .5));
                                    $countercutOneday = get_count_where(new Attendance_departure(), array('com_code' => $com_code, 'employees_code' => $EmployeeData['employees_code'], 'finance_cin_periods_id' => $this->finance_cin_periods_data['id'], 'cut' => 1));
                                    if ($countercutOneday >= $dataSetting['after_time_allday_daycut']) {
                                        $datainsert['cut'] = 1;
                                    } elseif ($countercutHalfday >= $dataSetting['after_time_half_dayCut']) {
                                        $datainsert['cut'] = .5;
                                    } elseif ($countercutQuarterday >= $dataSetting['after_miniute_quarterday']) {
                                        $datainsert['cut'] = .25;
                                    } else {
                                        $datainsert['cut'] = 0;
                                    }
                                }
                            }
                        }

                        $datainsert['year_and_month'] = $this->finance_cin_periods_data['year_and_month'];
                        $datainsert['branch_id'] = $EmployeeData['branch_id'];
                        $datainsert['function_status'] = $EmployeeData['function_status'];
                        $main_salary_employee = get_cols_where_row(new Main_salary_employee(), array('id'), array('com_code' => $com_code, 'employees_code' => $EmployeeData['employees_code'], 'is_archived' => 0));
                        if (!empty($main_salary_employee)) {
                            $datainsert['main_salary_employee_id'] = $main_salary_employee['id'];
                        }
                        $datainsert['added_by'] = auth()->user()->id;
                        $datainsert['com_code'] = $com_code;
                        $datainsert['the_day_date'] = $datainsert['datein'];

                        $flaginsertParent = insert(new Attendance_departure(), $datainsert, true);
                        if ($flaginsertParent) {
                            //تسجل البصمات الفعلية للموظف 
                            $dataToInsertAction['attendance_departure_id'] = $flaginsertParent['id'];
                            $dataToInsertAction['finance_cin_periods_id'] = $this->finance_cin_periods_data['id'];
                            $dataToInsertAction['employees_code'] = $EmployeeData['employees_code'];
                            $dataToInsertAction['datetimeAction'] = date('Y-m-d H:i:s', strtotime($row[3]));
                            $dataToInsertAction['action_type'] = $action_type;
                            $dataToInsertAction['it_is_active_with_parent'] = 0;
                            $dataToInsertAction['added_method'] = 1;
                            $dataToInsertAction['is_made_action_on_emp'] = 0;
                            $dataToInsertAction['added_by'] = auth()->user()->id;
                            $dataToInsertAction['com_code'] = $com_code;
                            $dataToInsertAction['AttendanceDepartureActionsExcelId'] = $Attendance_departure_actions_excel_data['id'];
                            insert(new Attendance_departure_actions(), $dataToInsertAction);
                            $dataToUpdateAction['it_is_active_with_parent']=1;
                            update(new Attendance_departure_actions(),$dataToUpdateAction,  array('com_code' => $com_code,  'action_type' => $action_type, 'attendance_departure_id' => $flaginsertParent['id'],'datetimeAction'=>$datainsert['datetime_in']));

                        }
                    }
                }
            }
        }
    }
}
