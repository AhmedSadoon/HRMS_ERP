<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeRequest;
use App\Http\Requests\EmployeeUpdateRequest;
use App\Models\Allowance;
use App\Models\Blood_Group;
use App\Models\Branche;
use App\Models\Centers;
use App\Models\Country;
use App\Models\Department;
use App\Models\driving_license_type;
use App\Models\Employee;
use App\Models\Employee_file;
use App\Models\employee_fixed_suits;
use App\Models\Employee_salary_achive;
use App\Models\Governorate;
use App\Models\jobs_category;
use App\Models\Language;
use App\Models\Main_salary_employee;
use App\Models\Military_status;
use App\Models\Nationalitie;
use App\Models\Qualification;
use App\Models\Religion;
use App\Models\Shifts_type;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeesController extends Controller
{
    use GeneralTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $com_code = auth()->user()->com_code;
        $Employees = get_cols_where_paginate(new Employee(), array('*'), array('com_code' => $com_code), 'id', 'DESC', PAGINATION_COUNTER);

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

        if(!empty($Employees)){
            foreach($Employees as $info){
                $info->CounterUserBefor=get_count_where(new Main_salary_employee(),array('com_code'=>$com_code,'employees_code'=>$info->employees_code));
            }
        }

        return view('admin.Employees.index', ['Employees' => $Employees, 'other' => $other]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $com_code = auth()->user()->com_code;
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

        return view('admin.Employees.create', (['other' => $other]));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmployeeRequest $request)
    {


        try {
            $com_code = auth()->user()->com_code;



            $checkExsits = get_cols_where_row(new Employee(), array('id'), array('com_code' => $com_code, 'emp_name' => $request->emp_name));
            if (!empty($checkExsits)) {
                return redirect()->back()->with(['error' => 'عفواً  اسم الموظف مسجل مسبقاً'])->withInput();
            }

            $checkExsits_zketo_code = get_cols_where_row(new Employee(), array('id'), array('com_code' => $com_code, 'zketo_code' => $request->zketo_code));
            if (!empty($checkExsits_zketo_code)) {
                return redirect()->back()->with(['error' => 'عفواً كود بسمة الموظف مسجل مسبقاً'])->withInput();
            }

            $last_employee = Employee::select('employees_code')->where('com_code','=', $com_code)->orderBy('id', 'desc')->first();
            if (!empty($last_employee)) {
                $employees_code = $last_employee['employees_code'] + 1;
            } else {
                $employees_code = 1;
            }
            


            $dataToInsert = [
                'employees_code' => $employees_code,
                'zketo_code' => $request->zketo_code,
                'emp_name' => $request->emp_name,
                'emp_gender' => $request->emp_gender,
                'branch_id' => $request->branch_id,
                'qualifications_id' => $request->qualifications_id,
                'qualifications_year' => $request->qualifications_year,
                'graduation_estimate' => $request->graduation_estimate,
                'graduation_specialization' => $request->graduation_specialization,
                'brith_date' => $request->brith_date,
                'emp_national_identity' => $request->emp_national_identity,
                'emp_endDate_identityID' => $request->emp_endDate_identityID,
                'emp_idenity_place' => $request->emp_idenity_place,
                'blood_group_id' => $request->blood_group_id,
                'religion_id' => $request->religion_id,
                'emp_lang_id' => $request->emp_lang_id,
                'emp_email' => $request->emp_email,
                'country_id' => $request->country_id,
                'governorate_id' => $request->governorate_id,
                'city_id' => $request->city_id,
                'emp_home_tel' => $request->emp_home_tel,
                'emp_work_tel' => $request->emp_work_tel,
                'emp_military_status_id' => $request->emp_military_status_id,
                'emp_military_date_from' => $request->emp_military_date_from,
                'emp_military_date_to' => $request->emp_military_date_to,
                'emp_military_wepon' => $request->emp_military_wepon,
                'exemption_date' => $request->exemption_date,
                'exemption_reason' => $request->exemption_reason,
                'postponement_reason' => $request->postponement_reason,
                'date_resignation' => $request->date_resignation,
                'resignation_reason' => $request->resignation_reason,
                'does_has_driving_license' => $request->does_has_driving_license,
                'driving_license_degree' => $request->driving_license_degree,
                'driving_license_types_id' => $request->driving_license_types_id,
                'has_relatives' => $request->has_relatives,
                'relatives_details' => $request->relatives_details,
                'notes' => $request->notes,
                'emp_start_date' => $request->emp_start_date,
                'function_status' => $request->function_status,
                'emp_department_id' => $request->emp_department_id,
                'emp_jobs_id' => $request->emp_jobs_id,
                'does_has_ateendance' => $request->does_has_ateendance,
                'is_has_fixced_shift' => $request->is_has_fixced_shift,
                'shift_type_id' => $request->shift_type_id,
                'daily_work_hour' => $request->daily_work_hour,
                'emp_salary' => $request->emp_salary,
                'day_price'=>($request->emp_salary/30),
                'motivation_type' => $request->motivation_type,
                'motivation' => $request->motivation,
                'is_social_nsurance' => $request->is_social_nsurance,
                'social_nsurance_cutMonthely' => $request->social_nsurance_cutMonthely,
                'social_nsurance_number' => $request->social_nsurance_number,
                'is_medical_nsurance' => $request->is_medical_nsurance,
                'medical_nsurance_cutMonthely' => $request->medical_nsurance_cutMonthely,
                'medical_nsurance_number' => $request->medical_nsurance_number,
                'sal_cach_or_visa' => $request->sal_cach_or_visa,
                'is_active_for_vaccation' => $request->is_active_for_vaccation,
                'urgent_person_details' => $request->urgent_person_details,
                'states_address' => $request->states_address,
                'childern_number' => $request->childern_number,
                'emp_social_status_id' => $request->emp_social_status_id,
                'resignation_id' => $request->resignation_id,
                'bank_number_account' => $request->bank_number_account,
                'is_disabilities_processes' => $request->is_disabilities_processes,
                'disabilities_processes' => $request->disabilities_processes,
                'emp_nationalitie_id' => $request->emp_nationalitie_id,
                'emp_cafel' => $request->emp_cafel,
                'emp_pasport_no' => $request->emp_pasport_no,
                'emp_pasport_from' => $request->emp_pasport_from,
                'emp_pasport_exp' => $request->emp_pasport_exp,
                'does_have_fixed_allowances' => $request->does_have_fixed_allowances,
                'is_done_vaccation_formula' => $request->is_done_vaccation_formula,
                'emp_Basic_stay_com' => $request->emp_Basic_stay_com,
                'date' => $request->date,
                'is_sensitive_manager_data' => $request->is_sensitive_manager_data,
                'added_by' => auth()->user()->id,
                'com_code' => $com_code


            ];

           

            if ($request->has('emp_photo')) {
                $request->validate([
                    'emp_photo' => 'required|mimes:png,jpg,jpeg|max:2000'
                ]);

                $the_file_path = uploadImage('assets/admin/uploads', $request->emp_photo);
                $dataToInsert['emp_photo'] = $the_file_path;
            }

            if ($request->has('emp_cv')) {
                $request->validate([
                    'emp_cv' => 'required|mimes:png,jpg,jpeg,doc,docx,pdf|max:2000'
                ]);

                $the_file_path = uploadImage('assets/admin/uploads', $request->emp_cv);
                $dataToInsert['emp_cv'] = $the_file_path;
            }




            DB::beginTransaction();

           $flag= insert(new Employee(), $dataToInsert,true);
           if($flag){
            if( $dataToInsert['emp_salary']>0){
                $dataToInsertSalaryArchive['employee_id']=$flag['id'];
                $dataToInsertSalaryArchive['value']=$dataToInsert['emp_salary'];
                $dataToInsertSalaryArchive['added_by'] = auth()->user()->id;
                $dataToInsertSalaryArchive['com_code'] = $com_code;
            
                insert(new Employee_salary_achive(), $dataToInsertSalaryArchive);

            }
           }

            DB::commit();
            return redirect()->route('Employees.index')->with('success', 'تم اضافة الموظف بنجاح');
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'عفواً حدث خطأ' . $ex->getMessage()])->withInput();
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
        $other['qualifications'] = get_cols_where(new Qualification(), array("id", "name"), array('com_code' => $com_code, 'id' => $data['qualifications_id']));
        $other['religions'] = get_cols_where(new Religion(), array("id", "name"), array('com_code' => $com_code, 'id' => $data['religion_id']));
        $other['nationalities'] = get_cols_where(new Nationalitie(), array("id", "name"), array('com_code' => $com_code, 'id' => $data['emp_nationalitie_id']));
        $other['countries'] = get_cols_where(new Country(), array("id", "name"), array('com_code' => $com_code, 'id' => $data['country_id']));
        $other['governorates'] = get_cols_where(new Governorate(), array("id", "name"), array('com_code' => $com_code, 'id' => $data['governorate_id']));
        $other['centers'] = get_cols_where(new Centers(), array("id", "name"), array('com_code' => $com_code, 'id' => $data['city_id']));
        $other['blood_groups'] = get_cols_where(new Blood_Group(), array("id", "name"), array('com_code' => $com_code, 'id' => $data['blood_group_id']));
        $other['military_statuses'] = get_cols_where(new Military_status(), array("id", "name"), array('active' => 1), 'id', 'ASC');
        $other['driving_license_types'] = get_cols_where(new driving_license_type(), array("id", "name"), array('com_code' => $com_code, 'id' => $data['driving_license_types_id']));
        $other['shift_types'] = get_cols_where(new Shifts_type(), array("id", "type", "form_time", "to_time", "total_huor"), array('com_code' => $com_code, 'id' => $data['shift_type_id']), 'id', 'ASC');
        $other['languages'] = get_cols_where(new Language(), array("id", "name"), array('com_code' => $com_code, 'id' => $data['emp_lang_id']), 'id', 'ASC');
        $other['employees_files'] = get_cols_where(new Employee_file(), array("*"), array('com_code' => $com_code, 'employee_id' => $id));

        if($data['does_have_fixed_allowances']==1){
            $other['employee_fixed_suits'] = get_cols_where(new employee_fixed_suits(), array("*"), array('com_code' => $com_code, 'employee_id' => $id));
            $other['allowances'] = get_cols_where(new Allowance(), array("id","name"), array('com_code' => $com_code, "active"=>1),'id','ASC');

        }


        return view('admin.Employees.show', ['data' => $data, 'other' => $other]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $com_code = auth()->user()->com_code;
        $data = get_cols_where_row(new Employee(), array("*"), array('com_code' => $com_code, 'id' => $id));
        if (empty($data)) {
            return redirect()->back()->with('error', 'عفواً غير قادر للوصول الى البيانات المطلوبة')->withInput();
        }

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



        return view('admin.Employees.edit', ['data' => $data, 'other' => $other]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmployeeUpdateRequest $request, $id)
    {
        try {
            $com_code = auth()->user()->com_code;
            $data = get_cols_where_row(new Employee(), array("*"), array('com_code' => $com_code, 'id' => $id));
            if (empty($data)) {
                return redirect()->back()->with('error', 'عفواً غير قادر للوصول الى البيانات المطلوبة')->withInput();
            }

            $checkExsits = Employee::select('id')->where('com_code', $com_code)->where('emp_name', $request->emp_name)->where('id', '!=', $id)->first();
            if (!empty($checkExsits)) {
                return redirect()->back()->with(['error' => 'عفواً اسم الموظف مسجل مسبقاً'])->withInput();
            }

            $checkExsits_zketo_code = Employee::select('id')->where('com_code', $com_code)->where('zketo_code', $request->zketo_code)->where('id', '!=', $id)->first();
            if (!empty($checkExsits_zketo_code)) {
                return redirect()->back()->with(['error' => 'عفواً كود بصمة الموظف مسجل مسبقاً'])->withInput();
            }
            DB::beginTransaction();

            $dataToUpdate = [
                'zketo_code' => $request->zketo_code,
                'emp_name' => $request->emp_name,
                'emp_gender' => $request->emp_gender,
                'branch_id' => $request->branch_id,
                'qualifications_id' => $request->qualifications_id,
                'qualifications_year' => $request->qualifications_year,
                'graduation_estimate' => $request->graduation_estimate,
                'graduation_specialization' => $request->graduation_specialization,
                'brith_date' => $request->brith_date,
                'emp_national_identity' => $request->emp_national_identity,
                'emp_endDate_identityID' => $request->emp_endDate_identityID,
                'emp_idenity_place' => $request->emp_idenity_place,
                'blood_group_id' => $request->blood_group_id,
                'religion_id' => $request->religion_id,
                'emp_lang_id' => $request->emp_lang_id,
                'emp_email' => $request->emp_email,
                'country_id' => $request->country_id,
                'governorate_id' => $request->governorate_id,
                'city_id' => $request->city_id,
                'emp_home_tel' => $request->emp_home_tel,
                'emp_work_tel' => $request->emp_work_tel,
                'emp_military_status_id' => $request->emp_military_status_id,
                'emp_military_date_from' => $request->emp_military_date_from,
                'emp_military_date_to' => $request->emp_military_date_to,
                'emp_military_wepon' => $request->emp_military_wepon,
                'exemption_date' => $request->exemption_date,
                'exemption_reason' => $request->exemption_reason,
                'postponement_reason' => $request->postponement_reason,
                'date_resignation' => $request->date_resignation,
                'resignation_reason' => $request->resignation_reason,
                'does_has_driving_license' => $request->does_has_driving_license,
                'driving_license_degree' => $request->driving_license_degree,
                'driving_license_types_id' => $request->driving_license_types_id,
                'has_relatives' => $request->has_relatives,
                'relatives_details' => $request->relatives_details,
                'notes' => $request->notes,
                'emp_start_date' => $request->emp_start_date,
                'function_status' => $request->function_status,
                'emp_department_id' => $request->emp_department_id,
                'emp_jobs_id' => $request->emp_jobs_id,
                'does_has_ateendance' => $request->does_has_ateendance,
                'is_has_fixced_shift' => $request->is_has_fixced_shift,
                'shift_type_id' => $request->shift_type_id,
                'daily_work_hour' => $request->daily_work_hour,
                'emp_salary' => $request->emp_salary,
                'day_price'=>($request->emp_salary/30),
                'motivation_type' => $request->motivation_type,
                'motivation' => $request->motivation,
                'is_social_nsurance' => $request->is_social_nsurance,
                'social_nsurance_cutMonthely' => $request->social_nsurance_cutMonthely,
                'social_nsurance_number' => $request->social_nsurance_number,
                'is_medical_nsurance' => $request->is_medical_nsurance,
                'medical_nsurance_cutMonthely' => $request->medical_nsurance_cutMonthely,
                'medical_nsurance_number' => $request->medical_nsurance_number,
                'sal_cach_or_visa' => $request->sal_cach_or_visa,
                'is_active_for_vaccation' => $request->is_active_for_vaccation,
                'urgent_person_details' => $request->urgent_person_details,
                'states_address' => $request->states_address,
                'childern_number' => $request->childern_number,
                'emp_social_status_id' => $request->emp_social_status_id,
                'resignation_id' => $request->resignation_id,
                'bank_number_account' => $request->bank_number_account,
                'is_disabilities_processes' => $request->is_disabilities_processes,
                'disabilities_processes' => $request->disabilities_processes,
                'emp_nationalitie_id' => $request->emp_nationalitie_id,
                'emp_cafel' => $request->emp_cafel,
                'emp_pasport_no' => $request->emp_pasport_no,
                'emp_pasport_from' => $request->emp_pasport_from,
                'emp_pasport_exp' => $request->emp_pasport_exp,
                'does_have_fixed_allowances' => $request->does_have_fixed_allowances,
                'is_done_vaccation_formula' => $request->is_done_vaccation_formula,
                'emp_Basic_stay_com' => $request->emp_Basic_stay_com,
                'date' => $request->date,
                'is_sensitive_manager_data' => $request->is_sensitive_manager_data,
                'updated_by' => auth()->user()->id,


            ];

            if ($request->has('emp_photo')) {
                $request->validate([
                    'emp_photo' => 'required|mimes:png,jpg,jpeg|max:2000'
                ]);

                $the_file_path = uploadImage('assets/admin/uploads', $request->emp_photo);
                $dataToUpdate['emp_photo'] = $the_file_path;

                if (file_exists('assets/admin/uploads/' . $data['emp_photo']) && !empty($data['emp_photo'])) {
                    unlink('assets/admin/uploads/' . $data['emp_photo']);
                }
            }

            if ($request->has('emp_cv')) {
                $request->validate([
                    'emp_cv' => 'required|mimes:png,jpg,jpeg,doc,docx,pdf|max:2000'
                ]);

                $the_file_path = uploadImage('assets/admin/uploads', $request->emp_cv);
                $dataToUpdate['emp_cv'] = $the_file_path;
                if (file_exists('assets/admin/uploads/' . $data['emp_cv']) && !empty($data['emp_cv'])) {
                    unlink('assets/admin/uploads/' . $data['emp_cv']);
                }
            }

            $flag=update(new Employee(), $dataToUpdate, array('com_code' => $com_code, 'id' => $id));
            if($flag){
                //نشوف لو في اختلاف في قيمة الراتب المحدث ينزل له في ارشيف الرواتب
                if( $dataToUpdate['emp_salary']!=$data['emp_salary']){
                    $dataToInsertSalaryArchive['employee_id']=$id;
                    $dataToInsertSalaryArchive['value']=$dataToUpdate['emp_salary'];
                    $dataToInsertSalaryArchive['added_by'] = auth()->user()->id;
                    $dataToInsertSalaryArchive['com_code'] = $com_code;
                
                    insert(new Employee_salary_achive(), $dataToInsertSalaryArchive);
    
                }


                if($dataToUpdate['does_have_fixed_allowances']==0){
                    //يتم حذف البدلات الثابتة وتحديث الراتب الحالي المفتوح 
                    destroy(new employee_fixed_suits(),array("com_code"=>$com_code,"employee_id"=>$id));

                }

                //لو يوجد راتب للموظف مفتوح نعيد احتسابه
                $currentSalaryData=get_cols_where_row(new Main_salary_employee(),array('id'),array("com_code"=>$com_code,"employees_code"=>$data['employees_code'],"is_archived"=>0));
                if(!empty($currentSalaryData)){
                    $this->Recalculate_main_salary_employee($currentSalaryData['id']);
                }

            }
            DB::commit();
            return redirect()->route('Employees.index')->with('success', 'تم تحديث البيانات بنجاح');
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'عفواً حدث خطأ' . $ex->getMessage()])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $com_code = auth()->user()->com_code;
            $data = get_cols_where_row(new Employee(), array("employees_code"), array('com_code' => $com_code, 'id' => $id));
            if (empty($data)) {
                return redirect()->back()->with('error', 'عفواً غير قادر للوصول الى البيانات المطلوبة')->withInput();
            }

            $CounterUserBefor=get_count_where(new Main_salary_employee(),array('com_code'=>$com_code,'employees_code'=>$data['employees_code']));
            if($CounterUserBefor!=0){
                return redirect()->back()->with('error', 'عفواً هذا الموظف له سجلات رواتب من قبل ومتاح فقط تعطيله خارج الخدمة')->withInput();

            }
            destroy(new Employee(), array('com_code' => $com_code, 'id' => $id));

            return redirect()->route('Employees.index')->with('success', 'تم حذف البيانات بنجاح');
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'عفواً حدث خطأ' . $ex->getMessage()])->withInput();
        }
    }

    public function get_governorates(Request $request)
    {
        if ($request->ajax()) {
            $country_id = $request->country_id;
            $other['governorates'] = get_cols_where(new Governorate(), array("id", "name"), array("com_code" => auth()->user()->com_code, "countries_id" => $country_id));
            return view('admin.Employees.get_governorates', ['other' => $other]);
        }
    }

    public function get_centers(Request $request)
    {
        if ($request->ajax()) {
            $governorates_id = $request->governorate_id;
            $other['centers'] = get_cols_where(new Centers(), array("id", "name"), array("com_code" => auth()->user()->com_code, "governorates_id" => $governorates_id));

            return view('admin.Employees.get_centers', ['other' => $other]);
        }
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
            $sal_cach_or_visa_search = $request->sal_cach_or_visa_search;
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
            if ($sal_cach_or_visa_search == 'all') {
                $field7 = 'id';
                $operator7 = ">";
                $value7 = 0;
            } else {
                $field7 = 'sal_cach_or_visa';
                $operator7 = "=";
                $value7 = $sal_cach_or_visa_search;
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
                ->where('com_code','=',$com_code)
                ->orderBy('id', 'DESC')
                ->paginate(PAGINATION_COUNTER);

                if(!empty($data)){
                    foreach($data as $info){
                        $info->CounterUserBefor=get_count_where(new Main_salary_employee(),array('com_code'=>$com_code,'employees_code'=>$info->employees_code));
                    }
                }

            return view( 'admin.Employees.ajax_search', compact('data'));
        }
    }

    public function download($id, $field_name)
    {
        $com_code = auth()->user()->com_code;
        $data = get_cols_where_row(new Employee(), array($field_name), array('com_code' => $com_code, 'id' => $id));
        if (empty($data)) {
            return redirect()->back()->with('error', 'عفواً غير قادر للوصول الى البيانات المطلوبة')->withInput();
        }

        $file_path = "assets/admin/uploads/" . $data[$field_name];
        return response()->download($file_path);
    }

    public function add_files(Request $request, $id)
    {
        try {
            $com_code = auth()->user()->com_code;
            $data = get_cols_where_row(new Employee(), array("id"), array('com_code' => $com_code, 'id' => $id));
            if (empty($data)) {
                return redirect()->back()->with('error', 'عفواً غير قادر للوصول الى البيانات المطلوبة')->withInput();
            }

            $checkExsits = Employee_file::select('id')->where('com_code', $com_code)->where('name', $request->name)->where('employee_id', $id)->first();
            if (!empty($checkExsits)) {
                return redirect()->back()->with(['error' => 'عفواً اسم الملف مسجل مسبقاً']);
            }


            DB::beginTransaction();

            $dataToInsert = [
                'name' => $request->name,
                'employee_id' => $request->id,
                'added_by' => auth()->user()->id,
                'com_code' => $com_code

            ];

            if ($request->has('the_file')) {
                $request->validate([
                    'the_file' => 'required|mimes:png,jpg,jpeg,pdf|max:2000'
                ]);

                $the_file_path = uploadImage('assets/admin/uploads', $request->the_file);
                $dataToInsert['file_path'] = $the_file_path;
            }

            insert(new Employee_file(), $dataToInsert);
            DB::commit();
            return redirect()->back()->with(['success' => 'تم اضافة البيانات بنجاح', 'tabfiles' => 'files']);
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'عفواً حدث خطأ' . $ex->getMessage()]);
        }
    }

    public function destroy_file($id)
    {
        try {
            $com_code = auth()->user()->com_code;
            $data = get_cols_where_row(new Employee_file(), array("id"), array('com_code' => $com_code, 'id' => $id));
            if (empty($data)) {
                return redirect()->back()->with('error', 'عفواً غير قادر للوصول الى البيانات المطلوبة')->withInput();
            }


            destroy(new Employee_file(), array('com_code' => $com_code, 'id' => $id));

            return redirect()->back()->with(['success' => 'تم حذف البيانات بنجاح', 'tabfiles' => 'files']);
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'عفواً حدث خطأ' . $ex->getMessage()])->withInput();
        }
    }

    public function download_files($id)
    {
        $com_code = auth()->user()->com_code;
        $data = get_cols_where_row(new Employee_file(), array('file_path'), array('com_code' => $com_code, 'id' => $id));
        if (empty($data)) {
            return redirect()->back()->with('error', 'عفواً غير قادر للوصول الى البيانات المطلوبة');
        }

        $file_path = "assets/admin/uploads/" . $data['file_path'];
        return response()->download($file_path);
    }
    
    public function add_allowances(Request $request, $id)
    {
        try {
            $com_code = auth()->user()->com_code;
            $data = get_cols_where_row(new Employee(),  array("id",'employees_code'), array('com_code' => $com_code, 'id' => $id));
            if (empty($data)) {
                return redirect()->back()->with('error', 'عفواً غير قادر للوصول الى البيانات المطلوبة')->withInput();
            }

            $checkExsits =employee_fixed_suits::select('id')->where('com_code', $com_code)->where('allowance_id', $request->allowance_id)->where('employee_id', $id)->first();
            if (!empty($checkExsits)) {
                return redirect()->back()->with(['error' => 'عفواً هذا البدل مسجل مسبقاً']);
            }


            DB::beginTransaction();

            $dataToInsert = [
                'employee_id' => $request->id,
                'allowance_id' => $request->allowance_id,
                'value' => $request->allowances_value,
                'added_by' => auth()->user()->id,
                'com_code' => $com_code

            ];


            $flag=insert(new employee_fixed_suits(), $dataToInsert);
            if($flag){
                  //لو يوجد راتب للموظف مفتوح نعيد احتسابه
                  $currentSalaryData=get_cols_where_row(new Main_salary_employee(),array('id'),array("com_code"=>$com_code,"employees_code"=>$data['employees_code'],"is_archived"=>0));
                  if(!empty($currentSalaryData)){
                      $this->Recalculate_main_salary_employee($currentSalaryData['id']);
                  }
            }
            DB::commit();
            return redirect()->back()->with(['success' => 'تم اضافة البيانات بنجاح']);
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'عفواً حدث خطأ' . $ex->getMessage()]);
        }
    }

    public function destroy_allowances($id)
    {
        try {
            $com_code = auth()->user()->com_code;
            $data = get_cols_where_row(new employee_fixed_suits(), array("id",'employee_id'), array('com_code' => $com_code, 'id' => $id));
            if (empty($data)) {
                return redirect()->back()->with('error', 'عفواً غير قادر للوصول الى البيانات المطلوبة')->withInput();
            }

            DB::beginTransaction();

            $dataEmployee = get_cols_where_row(new Employee(),  array('employees_code'), array('com_code' => $com_code, 'id' => $data['employee_id']));
            if (empty($dataEmployee)) {
                return redirect()->back()->with('error', 'عفواً غير قادر للوصول الى البيانات المطلوبة')->withInput();
            }

            $flag=destroy(new employee_fixed_suits(), array('com_code' => $com_code, 'id' => $id));
            if($flag){
                //يتم اعادة احتساب صافي الراتب
                 //لو يوجد راتب للموظف مفتوح نعيد احتسابه
                 $currentSalaryData=get_cols_where_row(new Main_salary_employee(),array('id'),array("com_code"=>$com_code,"employees_code"=>$dataEmployee['employees_code'],"is_archived"=>0));
                 if(!empty($currentSalaryData)){
                     $this->Recalculate_main_salary_employee($currentSalaryData['id']);
                 }

            }
            DB::commit();
            return redirect()->back()->with('success' , 'تم حذف البيانات بنجاح');
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'عفواً حدث خطأ' . $ex->getMessage()])->withInput();
        }
    }

    public function load_edit_allowances(Request $request){
        $com_code = auth()->user()->com_code;

        if($request->ajax()){
            $data = get_cols_where_row(new employee_fixed_suits(), array("*"), array('com_code' => $com_code, 'id' => $request->id));
            
            return view( 'admin.Employees.load_edit_allowances', compact('data'));

        }

    }

    public function do_edit_allowances($id,Request $request)
    {
        try {
            $com_code = auth()->user()->com_code;
            $data = get_cols_where_row(new employee_fixed_suits(), array("id",'employee_id'), array('com_code' => $com_code, 'id' => $id));
            if (empty($data)) {
                return redirect()->back()->with('error', 'عفواً غير قادر للوصول الى البيانات المطلوبة')->withInput();
            }

            $dataEmployee = get_cols_where_row(new Employee(),  array('employees_code'), array('com_code' => $com_code, 'id' => $data['employee_id']));
            if (empty($dataEmployee)) {
                return redirect()->back()->with('error', 'عفواً غير قادر للوصول الى البيانات المطلوبة')->withInput();
            }

            $dataToUpdate=[
                'value'=>$request->allowances_value_edit,
                'updated_by'=>auth()->user()->id,
            ];
            DB::beginTransaction();

            $flag=update(new employee_fixed_suits(), $dataToUpdate,array('com_code' => $com_code, 'id' => $id));
            if($flag){
                //يتم اعادة احتساب صافي الراتب
             
                    //لو يوجد راتب للموظف مفتوح نعيد احتسابه
                    $currentSalaryData=get_cols_where_row(new Main_salary_employee(),array('id'),array("com_code"=>$com_code,"employees_code"=>$dataEmployee['employees_code'],"is_archived"=>0));
                    if(!empty($currentSalaryData)){
                        $this->Recalculate_main_salary_employee($currentSalaryData['id']);
                    }
              

            }
            DB::commit();
            return redirect()->back()->with('success' , 'تم تعديل البيانات بنجاح');
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'عفواً حدث خطأ' . $ex->getMessage()])->withInput();
        }
    }

    public function showSalaryArchive(Request $request){
        $com_code = auth()->user()->com_code;

        if($request->ajax()){
            $data=get_cols_where(new Employee_salary_achive(),array("*"),array('com_code'=>$com_code,'employee_id'=>$request->id),'id','DESC');
            return view('admin.Employees.showSalaryArchive',['data'=>$data]);

        }
    }
}
