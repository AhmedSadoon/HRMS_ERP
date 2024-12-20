<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AttendanceDepartureUploadExcelRequest;
use App\Imports\Attendance_departureImport;
use App\Models\Admin;
use App\Models\admin_panel_setting;
use App\Models\Attendance_departure;
use App\Models\Attendance_departure_actions;
use App\Models\Attendance_departure_actions_excel;
use App\Models\Branche;
use App\Models\Employee;
use App\Models\Finance_calender;
use App\Models\Finance_cin_periods;
use App\Models\jobs_category;
use App\Models\Main_salary_employee;
use App\Models\MainVacationsBalance;
use App\Models\Vacation_type;
use App\Models\Weekday;
use App\Traits\GeneralTrait;
use DateTime;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class Attendance_departureController extends Controller
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

        return view('admin.Attendance_departure.index', compact('Finance_cin_periods'));
    }


    public function show($finance_cin_periods_id)
    {
        $com_code = auth()->user()->com_code;
        $finance_cin_periods_data = get_cols_where_row(new Finance_cin_periods(), array("*"), array('com_code' => $com_code, 'id' => $finance_cin_periods_id));
        if (empty($finance_cin_periods_data)) {
            return redirect()->route('Attendance_departure.index')->with('error', 'عفواً غير قادر للوصول الى البينات المطلوبة');
        }

        $data = get_cols_where_paginate(new Employee(), array('*'), array('com_code' => $com_code), 'id', 'DESC', PAGINATION_COUNTER);
        $employees_search = get_cols_where(new Employee(), array("employees_code", "emp_name"), array('com_code' => $com_code), 'employees_code', 'ASC');

        $last_attendance_departure_actions_excel_data = get_cols_where_row_orderby(new Attendance_departure_actions_excel(), array('datetimeAction', 'created_at', 'added_by'), array('com_code' => $com_code, 'finance_cin_periods_id' => $finance_cin_periods_id), 'datetimeAction', 'DESC');

        if (!empty($last_attendance_departure_actions_excel_data)) {
            $last_attendance_departure_actions_excel_data['added_by_name'] = get_field_value(new Admin(), 'name', array('com_code' => $com_code, 'id' => $last_attendance_departure_actions_excel_data['added_by']));
        }

        return view('admin.Attendance_departure.show', ['data' => $data, 'finance_cin_periods_data' => $finance_cin_periods_data, 'employees_search' => $employees_search, 'last_attendance_departure_actions_excel_data' => $last_attendance_departure_actions_excel_data]);
    }

    public function uploadExcelFile($finance_cin_periods_id)
    {
        $com_code = auth()->user()->com_code;
        $finance_cin_periods_data = get_cols_where_row(new Finance_cin_periods(), array("*"), array('com_code' => $com_code, 'id' => $finance_cin_periods_id, 'is_open' => 1));
        if (empty($finance_cin_periods_data)) {
            return redirect()->route('Attendance_departure.index')->with('error', 'عفواً غير قادر للوصول الى البينات المطلوبة');
        }



        return view('admin.Attendance_departure.uploadExcelFile', ['finance_cin_periods_data' => $finance_cin_periods_data]);
    }

    public function do_UploadExcelFile(AttendanceDepartureUploadExcelRequest $request, $finance_cin_periods_id)
    {
        $com_code = auth()->user()->com_code;
        $finance_cin_periods_data = get_cols_where_row(new Finance_cin_periods(), array("*"), array('com_code' => $com_code, 'id' => $finance_cin_periods_id, 'is_open' => 1));
        if (empty($finance_cin_periods_data)) {
            return redirect()->route('Attendance_departure.index')->with('error', 'عفواً غير قادر للوصول الى البينات المطلوبة');
        }

        Excel::import(new Attendance_departureImport($finance_cin_periods_data), $request->excel_file);
        return redirect()->route('AttendanceDeparture.show', $finance_cin_periods_id)->with('success', 'تم سحب البصمة بنجاح');
    }

    public function showPasmaDetails($employees_code, $finance_cin_periods_id)
    {
        $com_code = auth()->user()->com_code;
        $Employee_data = get_cols_where_row(new Employee(), array('*'), array('com_code' => $com_code, 'employees_code' => $employees_code));

        if (empty($Employee_data)) {
            return redirect()->route('Attendance_departure.show', $finance_cin_periods_id)->with('error', 'عفواً غير قادر للوصول الى البينات الموظف');
        }

        $finance_cin_periods_data = get_cols_where_row(new Finance_cin_periods(), array("*"), array('com_code' => $com_code, 'id' => $finance_cin_periods_id));
        if (empty($finance_cin_periods_data)) {
            return redirect()->route('Attendance_departure.index')->with('error', 'عفواً غير قادر للوصول الى البينات المطلوبة');
        }
        $this->calculate_employees_vacations_balance($employees_code);
        $this->calculate_employees_vacations_balance($employees_code);



        return view('admin.Attendance_departure.showPasmaDetails', ['Employee_data' => $Employee_data, 'finance_cin_periods_data' => $finance_cin_periods_data]);
    }


    function load_PasmasaArchive(Request $request)
    {
        if ($request->ajax()) {

            $com_code = auth()->user()->com_code;
            $attendance_departure_actions_excel = get_cols_where(new Attendance_departure_actions_excel(), array('*'), array('com_code' => $com_code, 'employees_code' => $request->employees_code, 'finance_cin_periods_id' => $request->finance_cin_periods_id), 'datetimeAction', 'ASC');

            if (!empty($attendance_departure_actions_excel)) {
                foreach ($attendance_departure_actions_excel as $info) {
                    $dt = new DateTime($info->datetimeAction);
                    $date = $dt->format('Y-m-d');
                    $nameOfDay = date('l', strtotime($date));
                    $info->week_day_name_arabic = get_field_value(new Weekday(), 'name', array('name_en' => $nameOfDay));
                }
            }

            return view('admin.Attendance_departure.load_PasmasaArchive', ['attendance_departure_actions_excel' => $attendance_departure_actions_excel]);
        }
    }

    //check_dayies_interval 
    public function load_active_Attendance_departure(Request $request)
    {
        if ($request->ajax()) {
            $com_code = auth()->user()->com_code;
            $other['Employee_data'] = get_cols_where_row(new Employee(), array('*'), array('com_code' => $com_code, 'employees_code' => $request->employees_code));
            $max_attend_date = Attendance_departure::where('com_code', $com_code)
                ->where('finance_cin_periods_id', $request->finance_cin_periods_id)
                ->max('datein');
            if (!empty($other['Employee_data'])) {
                $setting = get_cols_where_row(new admin_panel_setting(), array('is_pull_manull_days_from_passma'), array('com_code' => $com_code));
                $other['finance_cin_periods_data'] = get_cols_where_row(new Finance_cin_periods(), array("*"), array('com_code' => $com_code, 'id' => $request->finance_cin_periods_id));
                if (!empty($other['finance_cin_periods_data'])) {
                    //لو الشهر المالي مازال مفتوح ولم يؤرشف
                    if ($other['finance_cin_periods_data']['is_open'] == 1) {
                        //هنا نجيب اكبر تاريخ واخر تاريخ تم سحب البصمة اليه


                        $to_date = $other['finance_cin_periods_data']['end_date_for_pasma'];
                        $from_date = $other['finance_cin_periods_data']['start_date_for_pasma'];

                        while ($from_date <= $to_date && $from_date <= $max_attend_date) {

                            //هنا راح نشوف الايام الفاضية الموظف لم يفعل بها بصمة وهنا يتم اجراء التعبئة التلقائية وتطبيق الضبط العام ورصيد الاجازات
                            $is_exists = get_cols_where_row(new Attendance_departure(), array('id'), array('com_code' => $com_code, 'finance_cin_periods_id' => $request->finance_cin_periods_id, 'employees_code' => $request->employees_code, 'the_day_date' => $from_date));
                            //هنا لو اليوم مموجود لازم النظام ينزل له سجل ويطبق عليه المتغيرات ان وجدت
                            if (empty($is_exists)) {
                                $datainsert['finance_cin_periods_id'] = $request->finance_cin_periods_id;
                                $datainsert['shift_hour_contract'] = $other['Employee_data']['daily_work_hour'];
                                $datainsert['employees_code'] = $request->employees_code;
                                $datainsert['com_code'] = $com_code;
                                $datainsert['year_and_month'] = $other['finance_cin_periods_data']['year_and_month'];
                                $datainsert['branch_id'] = $other['Employee_data']['branch_id'];
                                $datainsert['function_status'] = $other['Employee_data']['function_status'];
                                $main_salary_employee = get_cols_where_row(new Main_salary_employee(), array('id'), array('com_code' => $com_code, 'employees_code' => $request->employees_code, 'is_archived' => 0));
                                if (!empty($main_salary_employee)) {
                                    $datainsert['main_salary_employee_id'] = $main_salary_employee['id'];
                                }
                                $datainsert['added_by'] = auth()->user()->id;
                                $datainsert['com_code'] = $com_code;
                                $datainsert['the_day_date'] = $from_date;

                                insert(new Attendance_departure(), $datainsert);


                                /////////////////////////////////////
                            }



                            $from_date = date('Y-m-d', strtotime($from_date . '+1 day'));
                        }
                    }
                    ////////////////////////////////////////////////////////////
                    $other['data'] = get_cols_where(new Attendance_departure(), array('*'), array('com_code' => $com_code, 'finance_cin_periods_id' => $request->finance_cin_periods_id, 'employees_code' => $request->employees_code), 'the_day_date', 'ASC');
                    $other['total_cut'] = 0;
                    $other['total_attedance_dely'] = 0;
                    $other['total_early_departure'] = 0;
                    $other['total_hours'] = 0;
                    $other['total_absen_hours'] = 0;
                    $other['total_additional_hours'] = 0;
                    $other['total_vacations_type_id'] = 0;
                    if (!empty($other['data'])) {
                        foreach ($other['data'] as $info) {

                            $nameOfDay = date('l', strtotime($info->the_day_date));
                            $info->week_day_name_arabic = get_field_value(new Weekday(), 'name', array('name_en' => $nameOfDay));
                            $info->attendance_departure_actions_Counter = get_count_where(new Attendance_departure_actions(), array('com_code' => $com_code, 'attendance_departure_id' => $info->id, 'finance_cin_periods_id' => $request->finance_cin_periods_id, 'employees_code' => $request->employees_code));
                            if ($info->cut != null) {
                                $other['total_cut'] += $info->cut;
                            }

                            if ($info->attedance_dely != null) {
                                $other['total_attedance_dely'] += $info->attedance_dely;
                            }

                            if ($info->early_departure != null) {
                                $other['total_early_departure'] += $info->early_departure;
                            }



                            if ($info->total_hours != null) {
                                $other['total_hours'] += $info->total_hours;
                            }

                            if ($info->absen_hours != null) {
                                $other['total_absen_hours'] += $info->absen_hours;
                            }

                            if ($info->additional_hours != null) {
                                $other['total_additional_hours'] += $info->additional_hours;
                            }

                            if ($info->vacations_type_id != null) {
                                $other['total_vacations_type_id'] += 1;
                            }
                        }
                    }

                    $other['vacation_types'] = Vacation_type::all();

                    $other['vacations_type_distinct'] = Attendance_departure::where('com_code', '=', $com_code)
                        ->where('finance_cin_periods_id', '=', $request->finance_cin_periods_id)
                        ->where('employees_code', '=', $request->employees_code)
                        ->where('vacations_type_id', '>', 0)
                        ->orderBy('vacations_type_id', 'ASC')
                        ->distinct()->get('vacations_type_id');
                    if (!empty($other['vacations_type_distinct'])) {
                        foreach ($other['vacations_type_distinct'] as $info) {
                            $info->name = get_field_value(new Vacation_type(), 'name', array('id' => $info->vacations_type_id));
                            $info->counter = get_count_where(new Attendance_departure(), array('com_code' => $com_code, 'finance_cin_periods_id' => $request->finance_cin_periods_id, 'employees_code' => $request->employees_code, 'vacations_type_id' => $info->vacations_type_id));
                        }
                    }

                    return view('admin.Attendance_departure.ajax_load_active_Attendance_departure', ['other' => $other, 'max_attend_date' => $max_attend_date, 'setting' => $setting]);
                }
            }
        }
    }

    function load_my_action(Request $request)
    {
        if ($request->ajax()) {

            $com_code = auth()->user()->com_code;
            $parent = get_cols_where_row(new Attendance_departure(), array('id', 'datetime_in', 'datetime_out', 'is_archived', 'is_updated_active_action', 'is_updated_active_action_date', 'is_updated_active_action_by'), array('com_code' => $com_code, 'employees_code' => $request->employees_code, 'finance_cin_periods_id' => $request->finance_cin_periods_id, 'id' => $request->attendance_departure_id));
            $Attendance_departure_actions = get_cols_where(new Attendance_departure_actions(), array('*'), array('com_code' => $com_code, 'employees_code' => $request->employees_code, 'finance_cin_periods_id' => $request->finance_cin_periods_id, 'attendance_departure_id' => $request->attendance_departure_id), 'datetimeAction', 'ASC');

            if (!empty($Attendance_departure_actions)) {
                foreach ($Attendance_departure_actions as $info) {
                    $dt = new DateTime($info->datetimeAction);
                    $date = $dt->format('Y-m-d');
                    $nameOfDay = date('l', strtotime($date));
                    $info->week_day_name_arabic = get_field_value(new Weekday(), 'name', array('name_en' => $nameOfDay));
                }
            }

            return view('admin.Attendance_departure.load_my_action', ['Attendance_departure_actions' => $Attendance_departure_actions, 'parent' => $parent]);
        }
    }

    public function save_active_Attendance_departure(Request $request)
    {
        $com_code = auth()->user()->com_code;
        if ($request->ajax()) {

            $attendance_departure = get_cols_where_row(new Attendance_departure(), array('the_day_date','vacations_type_id', 'year_and_month'), array('com_code' => $com_code, 'id' => $request->id, 'is_archived' => 0, 'finance_cin_periods_id' => $request->finance_cin_periods_id, 'employees_code' => $request->employees_code));

            if (!empty($attendance_departure)) {
                $dataToUpdate['variables'] = $request->variables;
                $dataToUpdate['cut'] = $request->cut;
                $dataToUpdate['vacations_type_id'] = $request->vacation_types_id;
                $dataToUpdate['attedance_dely'] = $request->attedance_dely;
                $dataToUpdate['early_departure'] = $request->early_departure;
                $dataToUpdate['azn_hours'] = $request->azn_hours;
                $dataToUpdate['total_hours'] = $request->total_hours;
                $dataToUpdate['absen_hours'] = $request->absen_hours;
                $dataToUpdate['additional_hours'] = $request->additional_hours;

                $flag = update(new Attendance_departure(), $dataToUpdate, array('com_code' => $com_code, 'id' => $request->id, 'is_archived' => 0, 'finance_cin_periods_id' => $request->finance_cin_periods_id, 'employees_code' => $request->employees_code));
               

                if ($flag) {
                    if ($dataToUpdate['vacations_type_id'] == 3 or $attendance_departure['vacations_type_id'] == 3) {
                        
                        $this->calculate_employees_vacations_balance($request->employees_code);
                        $this->calculate_employees_vacations_balance($request->employees_code);


                        $setting = get_cols_where_row(new admin_panel_setting(), array('is_pull_manull_days_from_passma'), array('com_code' => $com_code));
                        $employees_data = get_cols_where_row(new Employee(), array("is_active_for_vaccation", "is_done_vaccation_formula"), array('com_code' => $com_code, 'employees_code' => $request->employees_code));
                        
                        if (!empty($employees_data) and $setting['is_pull_manull_days_from_passma'] == 1) {
                            
                            if ($employees_data['is_active_for_vaccation'] == 1 and $employees_data['is_done_vaccation_formula'] == 1) {
                               
                                $main_employees_vacations_balance = get_cols_where_row(new MainVacationsBalance(), array('spent_balance', 'id'), array('com_code' => $com_code, 'year_and_month' => $attendance_departure['year_and_month']));
                                
                                if (!empty($main_employees_vacations_balance)) {
                                     $dataToUpdateVacation['spent_balance'] =get_count_where(new Attendance_departure(), array('com_code' => $com_code, 'vacations_type_id' => 3, 'finance_cin_periods_id' => $request->finance_cin_periods_id, 'employees_code' => $request->employees_code));
                                    
                                     $dataToUpdateVacation['updated_by']=auth()->user()->id;
                                     
                                     
                                
                                    $result = update(new MainVacationsBalance(), $dataToUpdateVacation, array('id' => $main_employees_vacations_balance['id']));
                                    
                                    if (!empty($result)) {
                                        // اكو مشكلة بعرض الاجازات
                                        $this->reupdate_vacations($request->employees_code);

                                       
                                    }
                                }
                            }
                        }
                    }
                    return json_encode('done');
                }
            }
        }
    }

    public function redo_update_actions(Request $request)
    {
        $com_code = auth()->user()->com_code;
        if ($request->ajax()) {

            $attendance_departure = get_cols_where_row(new Attendance_departure(), array('*'), array('com_code' => $com_code, 'id' => $request->id, 'is_archived' => 0, 'finance_cin_periods_id' => $request->finance_cin_periods_id, 'employees_code' => $request->employees_code));
            if (!empty($attendance_departure)) {


                $datetime_in = $request->datetime_in;
                $datetime_out = $request->datetime_out;
                if ($datetime_out != "") {

                    $seconds = strtotime($datetime_out) - strtotime($datetime_in);
                    $hourdiff = $seconds / 60 / 60;
                    $hourdiff = number_format((float)$hourdiff, 2, '.' . '');
                    $minutesiff = $seconds / 60;
                    $minutesiff = number_format((float)$minutesiff, 2, '.' . '');
                    if ($hourdiff < 0) $hourdiff - $hourdiff * (-1);
                    if ($minutesiff < 0) $minutesiff - $minutesiff * (-1);
                    //اشتغل على متغيرات اقفال البصمة الحالية
                    $dataUpdate['datetime_in'] = date('Y-m-d H:i:s', strtotime($datetime_in));
                    $dataUpdate['datetime_out'] = date('Y-m-d H:i:s', strtotime($datetime_out));
                    $dataUpdate['dateOut'] = date('Y-m-d', strtotime($datetime_out));
                    $dataUpdate['time_out'] = date('H:i:s', strtotime($datetime_out));
                    $dataUpdate['datein'] = date('Y-m-d', strtotime($datetime_in));
                    $dataUpdate['time_in'] = date('H:i:s', strtotime($datetime_in));
                    $dataUpdate['total_hours'] = $hourdiff;

                    if ($hourdiff < $attendance_departure['shift_hour_contract']) {
                        $dataUpdate['additional_hours'] = 0;
                        $dataUpdate['absen_hours'] = $attendance_departure['shift_hour_contract'] - $hourdiff;
                    }

                    if ($hourdiff > $attendance_departure['shift_hour_contract']) {
                        $dataUpdate['additional_hours'] = $hourdiff - $attendance_departure['shift_hour_contract'];
                        $dataUpdate['absen_hours'] = 0;
                    }

                    $dataUpdate['is_updated_active_action'] = 1;
                    $dataUpdate['is_updated_active_action_date'] = date('Y-m-d H:i:s');
                    $dataUpdate['is_updated_active_action_by'] = auth()->user()->id;
                    $flagUpdateParent = update(new Attendance_departure(), $dataUpdate, array('com_code' => $com_code, 'id' => $request->id, 'is_archived' => 0, 'finance_cin_periods_id' => $request->finance_cin_periods_id, 'employees_code' => $request->employees_code));

                    if ($flagUpdateParent) {
                        return json_encode('done');
                    }
                } else {
                    $dataToUpdate['total_hours'] = 0;
                    $dataToUpdate['additional_hours'] = 0;
                    $dataToUpdate['absen_hours'] = 0;
                    $dataToUpdate['is_updated_active_action'] = 1;
                    $dataToUpdate['is_updated_active_action_date'] = date('Y-m-d H:i:s');
                    $dataToUpdate['is_updated_active_action_by'] = auth()->user()->id;
                    $flagUpdateParent = update(new Attendance_departure(), $dataToUpdate, array('com_code' => $com_code, 'id' => $request->id, 'is_archived' => 0, 'finance_cin_periods_id' => $request->finance_cin_periods_id, 'employees_code' => $request->employees_code));

                    if ($flagUpdateParent) {
                        return json_encode('done');
                    }
                }
            }
        }
    }

    public function print_one_passma_details($employees_code, $finance_cin_periods_id)
    {
        $com_code = auth()->user()->com_code;
        $other['Employee_data'] = get_cols_where_row(new Employee(), array('*'), array('com_code' => $com_code, 'employees_code' => $employees_code));
        if (empty($other['Employee_data'])) {
            return redirect()->route('Attendance_departure.index')->with('error', 'عفواً غير قادر للوصول الى البينات المطلوبة');
        }

        $other['Employee_data']['branch_name'] = get_field_value(new Branche(), 'name', array('com_code' => $com_code, 'id' => $other['Employee_data']['branch_id']));
        $other['Employee_data']['job_name'] = get_field_value(new jobs_category(), 'name', array('com_code' => $com_code, 'id' => $other['Employee_data']['emp_jobs_id']));


        $finance_cin_periods_data = get_cols_where_row(new Finance_cin_periods(), array("*"), array('com_code' => $com_code, 'id' => $finance_cin_periods_id));
        if (empty($finance_cin_periods_data)) {
            return redirect()->route('Attendance_departure.index')->with('error', 'عفواً غير قادر للوصول الى البينات المطلوبة');
        }

        $other['data'] = get_cols_where(new Attendance_departure(), array('*'), array('com_code' => $com_code, 'finance_cin_periods_id' => $finance_cin_periods_id, 'employees_code' => $employees_code), 'the_day_date', 'ASC');
        $other['total_cut'] = 0;
        $other['total_attedance_dely'] = 0;
        $other['total_early_departure'] = 0;
        $other['total_hours'] = 0;
        $other['total_absen_hours'] = 0;
        $other['total_additional_hours'] = 0;
        $other['total_vacations_type_id'] = 0;
        if (!empty($other['data'])) {
            foreach ($other['data'] as $info) {

                $nameOfDay = date('l', strtotime($info->the_day_date));
                $info->week_day_name_arabic = get_field_value(new Weekday(), 'name', array('name_en' => $nameOfDay));
                $info->attendance_departure_actions_Counter = get_count_where(new Attendance_departure_actions(), array('com_code' => $com_code, 'attendance_departure_id' => $info->id, 'finance_cin_periods_id' => $finance_cin_periods_id, 'employees_code' => $employees_code));
                if ($info->cut != null) {
                    $other['total_cut'] += $info->cut;
                }

                if ($info->attedance_dely != null) {
                    $other['total_attedance_dely'] += $info->attedance_dely;
                }

                if ($info->early_departure != null) {
                    $other['total_early_departure'] += $info->early_departure;
                }



                if ($info->total_hours != null) {
                    $other['total_hours'] += $info->total_hours;
                }

                if ($info->absen_hours != null) {
                    $other['total_absen_hours'] += $info->absen_hours;
                }

                if ($info->additional_hours != null) {
                    $other['total_additional_hours'] += $info->additional_hours;
                }

                if ($info->vacations_type_id != null) {
                    $other['total_vacations_type_id'] += 1;
                }
            }
        }

        return view('admin.Attendance_departure.print_one_passma_details', ['other' => $other, 'finance_cin_periods_data' => $finance_cin_periods_data]);
    }
}
