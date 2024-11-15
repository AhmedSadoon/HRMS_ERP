<?php

use App\Http\Controllers\Admin\additionalTypesController;
use App\Http\Controllers\Admin\Admin_panel_settingsController;
use App\Http\Controllers\Admin\AllowanceController;
use App\Http\Controllers\Admin\Attendance_departureController;
use App\Http\Controllers\Admin\BranchesController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DepartementController;
use App\Http\Controllers\Admin\DiscountTypesController;
use App\Http\Controllers\Admin\EmployeesController;
use App\Http\Controllers\Admin\Finance_calenderController;
use App\Http\Controllers\Admin\JobsCategoriesController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\Main_salary_employee_AbsenceController;
use App\Http\Controllers\Admin\Main_salary_employee_AdditionController;
use App\Http\Controllers\Admin\Main_salary_employee_AllowancesController;
use App\Http\Controllers\Admin\Main_salary_employee_DiscountController;
use App\Http\Controllers\Admin\Main_salary_employee_LoansController;
use App\Http\Controllers\Admin\Main_salary_employee_p_LoansController;
use App\Http\Controllers\Admin\Main_salary_employee_RewardsController;
use App\Http\Controllers\Admin\Main_salary_employee_sanctionsController;
use App\Http\Controllers\Admin\Main_salary_employeeController;
use App\Http\Controllers\Admin\MainSalaryRecordController;
use App\Http\Controllers\Admin\MainVacationsBalanceController;
use App\Http\Controllers\Admin\NationalitiesController;
use App\Http\Controllers\Admin\OccasionController;
use App\Http\Controllers\Admin\QualificationController;
use App\Http\Controllers\Admin\ReligionsController;
use App\Http\Controllers\Admin\ResignationsController;
use App\Http\Controllers\Admin\ShiftsTypeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

define('PAGINATION_COUNTER', 10);

Route::group(['prefix' => 'admin', 'middleware' => 'auth:admin'], function () {

    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/logout', [LoginController::class, 'logout'])->name('admin.logout');


    //----------------------------بداية الضبط العام------------------------------

    Route::get('/generalSettings', [Admin_panel_settingsController::class, 'index'])->name('admin_panel_settings.index');
    Route::get('/generalSettingsEdit', [Admin_panel_settingsController::class, 'edit'])->name('admin_panel_settings.edit');
    Route::post('/generalSettingsupdate', [Admin_panel_settingsController::class, 'update'])->name('admin_panel_settings.update');


    //----------------------------نهاية الضبط العام---------------------------


    //---------------بداية السنوات المالية---------------------

    Route::resource('/finance_calender', Finance_calenderController::class);
    Route::get('/finance_calender/delete/{id}', [Finance_calenderController::class, 'destroy'])->name('finance_calender.delete');
    Route::post('/finance_calender/show_year_monthes', [Finance_calenderController::class, 'show_year_monthes'])->name('finance_calender.show_year_monthes');
    Route::get('/finance_calender/do_open/{id}', [Finance_calenderController::class, 'do_open'])->name('finance_calender.do_open');

    //---------------نهاية السنوات المالية---------------------

    //-----------------------بداية الفروع-----------------------

    Route::get('/branches', [BranchesController::class, 'index'])->name('branches.index');
    Route::get('/branches/create', [BranchesController::class, 'create'])->name('branches.create');
    Route::post('/branches/store', [BranchesController::class, 'store'])->name('branches.store');
    Route::get('/branches/edit/{id}', [BranchesController::class, 'edit'])->name('branches.edit');
    Route::get('/branches/delete/{id}', [BranchesController::class, 'destroy'])->name('branches.destroy');
    Route::post('/branches/update/{id}', [BranchesController::class, 'update'])->name('branches.update');


    //-----------------------نهاية الفروع-----------------------
    //---------------------- بداية شفتات الدوام--------------------------
    Route::get('/Shiftstypes', [ShiftsTypeController::class, 'index'])->name('shiftsTypes.index');
    Route::get('/Shiftstypes/create', [ShiftsTypeController::class, 'create'])->name('shiftsTypes.create');
    Route::post('/Shiftstypes/store', [ShiftsTypeController::class, 'store'])->name('shiftsTypes.store');
    Route::get('/Shiftstypes/edit/{id}', [ShiftsTypeController::class, 'edit'])->name('shiftsTypes.edit');
    Route::get('/Shiftstypes/delete/{id}', [ShiftsTypeController::class, 'destroy'])->name('shiftsTypes.destroy');
    Route::post('/Shiftstypes/update/{id}', [ShiftsTypeController::class, 'update'])->name('shiftsTypes.update');
    Route::post('/Shiftstypes/ajaxSearch', [ShiftsTypeController::class, 'ajax_search'])->name('shiftsTypes.ajaxSearch');




    //---------------------- نهاية شفتات الدوام--------------------------

    //----------------بداية الادارة---------------------
    Route::get('/departements', [DepartementController::class, 'index'])->name('departements.index');
    Route::get('/departements/create', [DepartementController::class, 'create'])->name('departements.create');
    Route::post('/departements/store', [DepartementController::class, 'store'])->name('departements.store');
    Route::get('/departements/edit/{id}', [DepartementController::class, 'edit'])->name('departements.edit');
    Route::get('/departements/delete/{id}', [DepartementController::class, 'destroy'])->name('departements.destroy');
    Route::post('/departements/update/{id}', [DepartementController::class, 'update'])->name('departements.update');
    //----------------نهاية الادارة---------------------

    //----------------بداية فئات الوظائف---------------------
    Route::get('/JobsCategories', [JobsCategoriesController::class, 'index'])->name('JobsCategories.index');
    Route::get('/JobsCategories/create', [JobsCategoriesController::class, 'create'])->name('JobsCategories.create');
    Route::post('/JobsCategories/store', [JobsCategoriesController::class, 'store'])->name('JobsCategories.store');
    Route::get('/JobsCategories/edit/{id}', [JobsCategoriesController::class, 'edit'])->name('JobsCategories.edit');
    Route::get('/JobsCategories/delete/{id}', [JobsCategoriesController::class, 'destroy'])->name('JobsCategories.destroy');
    Route::post('/JobsCategories/update/{id}', [JobsCategoriesController::class, 'update'])->name('JobsCategories.update');
    //----------------نهاية فئات الوظائف---------------------

    //----------------بداية المؤهلات---------------------
    Route::get('/Qualifications', [QualificationController::class, 'index'])->name('Qualifications.index');
    Route::get('/Qualifications/create', [QualificationController::class, 'create'])->name('Qualifications.create');
    Route::post('/Qualifications/store', [QualificationController::class, 'store'])->name('Qualifications.store');
    Route::get('/Qualifications/edit/{id}', [QualificationController::class, 'edit'])->name('Qualifications.edit');
    Route::get('/Qualifications/delete/{id}', [QualificationController::class, 'destroy'])->name('Qualifications.destroy');
    Route::post('/Qualifications/update/{id}', [QualificationController::class, 'update'])->name('Qualifications.update');
    //----------------نهاية المؤهلات---------------------

    //----------------بداية العطل الرسمية---------------------
    Route::get('/Occasions', [OccasionController::class, 'index'])->name('Occasions.index');
    Route::get('/Occasions/create', [OccasionController::class, 'create'])->name('Occasions.create');
    Route::post('/Occasions/store', [OccasionController::class, 'store'])->name('Occasions.store');
    Route::get('/Occasions/edit/{id}', [OccasionController::class, 'edit'])->name('Occasions.edit');
    Route::get('/Occasions/delete/{id}', [OccasionController::class, 'destroy'])->name('Occasions.destroy');
    Route::post('/Occasions/update/{id}', [OccasionController::class, 'update'])->name('Occasions.update');
    //----------------نهاية العطل الرسمية---------------------

    //----------------بداية انواع ترك العمل---------------------
    Route::get('/Resignations', [ResignationsController::class, 'index'])->name('Resignations.index');
    Route::get('/Resignations/create', [ResignationsController::class, 'create'])->name('Resignations.create');
    Route::post('/Resignations/store', [ResignationsController::class, 'store'])->name('Resignations.store');
    Route::get('/Resignations/edit/{id}', [ResignationsController::class, 'edit'])->name('Resignations.edit');
    Route::get('/Resignations/delete/{id}', [ResignationsController::class, 'destroy'])->name('Resignations.destroy');
    Route::post('/Resignations/update/{id}', [ResignationsController::class, 'update'])->name('Resignations.update');
    //----------------نهاية انواع ترك العمل---------------------

    //----------------بداية الجنسية---------------------
    Route::get('/Nationalities', [NationalitiesController::class, 'index'])->name('Nationalities.index');
    Route::get('/Nationalities/create', [NationalitiesController::class, 'create'])->name('Nationalities.create');
    Route::post('/Nationalities/store', [NationalitiesController::class, 'store'])->name('Nationalities.store');
    Route::get('/Nationalities/edit/{id}', [NationalitiesController::class, 'edit'])->name('Nationalities.edit');
    Route::get('/Nationalities/delete/{id}', [NationalitiesController::class, 'destroy'])->name('Nationalities.destroy');
    Route::post('/Nationalities/update/{id}', [NationalitiesController::class, 'update'])->name('Nationalities.update');
    //----------------نهاية الجنسية---------------------

    //----------------بداية الديانة---------------------
    Route::get('/Religions', [ReligionsController::class, 'index'])->name('Religions.index');
    Route::get('/Religions/create', [ReligionsController::class, 'create'])->name('Religions.create');
    Route::post('/Religions/store', [ReligionsController::class, 'store'])->name('Religions.store');
    Route::get('/Religions/edit/{id}', [ReligionsController::class, 'edit'])->name('Religions.edit');
    Route::get('/Religions/delete/{id}', [ReligionsController::class, 'destroy'])->name('Religions.destroy');
    Route::post('/Religions/update/{id}', [ReligionsController::class, 'update'])->name('Religions.update');
    //----------------نهاية الديانة---------------------

    //----------------بداية شؤون الموظفين---------------------
    Route::get('/Employees', [EmployeesController::class, 'index'])->name('Employees.index');
    Route::get('/Employees/create', [EmployeesController::class, 'create'])->name('Employees.create');
    Route::post('/Employees/store', [EmployeesController::class, 'store'])->name('Employees.store');
    Route::get('/Employees/edit/{id}', [EmployeesController::class, 'edit'])->name('Employees.edit');
    Route::get('/Employees/delete/{id}', [EmployeesController::class, 'destroy'])->name('Employees.destroy');
    Route::post('/Employees/update/{id}', [EmployeesController::class, 'update'])->name('Employees.update');
    Route::post('/Employees/get_governorates', [EmployeesController::class, 'get_governorates'])->name('Employees.get_governorates');
    Route::post('/Employees/get_centers', [EmployeesController::class, 'get_centers'])->name('Employees.get_centers');
    Route::get('/Employees/show/{id}', [EmployeesController::class, 'show'])->name('Employees.show');
    Route::post('/Employees/ajaxSearch', [EmployeesController::class, 'ajax_search'])->name('Employees.ajaxSearch');
    Route::get('/Employees/download/{id}/{field_name}', [EmployeesController::class, 'download'])->name('Employees.download');
    Route::post('/Employees/add_files/{id}', [EmployeesController::class, 'add_files'])->name('Employees.add_files');
    Route::get('/Employees/download_files/{id}', [EmployeesController::class, 'download_files'])->name('Employees.download_files');
    Route::get('/Employees/destroy_file/{id}', [EmployeesController::class, 'destroy_file'])->name('Employees.destroy_file');
    Route::post('/Employees/add_allowances/{id}', [EmployeesController::class, 'add_allowances'])->name('Employees.add_allowances');
    Route::get('/Employees/destroy_allowances/{id}', [EmployeesController::class, 'destroy_allowances'])->name('Employees.destroy_allowances');
    Route::post('/Employees/load_edit_allowances', [EmployeesController::class, 'load_edit_allowances'])->name('Employees.load_edit_allowances');
    Route::post('/Employees/do_edit_allowances/{id}', [EmployeesController::class, 'do_edit_allowances'])->name('Employees.do_edit_allowances');
    Route::post('/Employees/showSalaryArchive', [EmployeesController::class, 'showSalaryArchive'])->name('Employees.showSalaryArchive');


    //----------------نهاية شؤون الموظفين---------------------


    //----------------بداية الاضافي على الراتب---------------------
    Route::get('/additionalTypes', [additionalTypesController::class, 'index'])->name('additionalTypes.index');
    Route::get('/additionalTypes/create', [additionalTypesController::class, 'create'])->name('additionalTypes.create');
    Route::post('/additionalTypes/store', [additionalTypesController::class, 'store'])->name('additionalTypes.store');
    Route::get('/additionalTypes/edit/{id}', [additionalTypesController::class, 'edit'])->name('additionalTypes.edit');
    Route::get('/additionalTypes/delete/{id}', [additionalTypesController::class, 'destroy'])->name('additionalTypes.destroy');
    Route::post('/additionalTypes/update/{id}', [additionalTypesController::class, 'update'])->name('additionalTypes.update');
    //----------------نهاية الاضافي على الراتب---------------------

    //----------------بداية الخصم على الراتب---------------------
    Route::get('/DiscountType', [DiscountTypesController::class, 'index'])->name('DiscountType.index');
    Route::get('/DiscountType/create', [DiscountTypesController::class, 'create'])->name('DiscountType.create');
    Route::post('/DiscountType/store', [DiscountTypesController::class, 'store'])->name('DiscountType.store');
    Route::get('/DiscountType/edit/{id}', [DiscountTypesController::class, 'edit'])->name('DiscountType.edit');
    Route::get('/DiscountType/delete/{id}', [DiscountTypesController::class, 'destroy'])->name('DiscountType.destroy');
    Route::post('/DiscountType/update/{id}', [DiscountTypesController::class, 'update'])->name('DiscountType.update');
    //----------------نهاية الخصم على الراتب---------------------

    //----------------بداية البدلات على الراتب---------------------
    Route::get('/Allowances', [AllowanceController::class, 'index'])->name('Allowances.index');
    Route::get('/Allowances/create', [AllowanceController::class, 'create'])->name('Allowances.create');
    Route::post('/Allowances/store', [AllowanceController::class, 'store'])->name('Allowances.store');
    Route::get('/Allowances/edit/{id}', [AllowanceController::class, 'edit'])->name('Allowances.edit');
    Route::get('/Allowances/delete/{id}', [AllowanceController::class, 'destroy'])->name('Allowances.destroy');
    Route::post('/Allowances/update/{id}', [AllowanceController::class, 'update'])->name('Allowances.update');
    //----------------نهاية البدلات على الراتب---------------------

    //----------------بداية سجلات الراتب---------------------
    Route::get('/MainSalaryRecord', [MainSalaryRecordController::class, 'index'])->name('MainSalaryRecord.index');
    Route::post('/MainSalaryRecord/do_open_month/{id}', [MainSalaryRecordController::class, 'do_open_month'])->name('MainSalaryRecord.do_open_month');
    Route::post('/MainSalaryRecord/store', [MainSalaryRecordController::class, 'store'])->name('MainSalaryRecord.store');
    Route::get('/MainSalaryRecord/edit/{id}', [MainSalaryRecordController::class, 'edit'])->name('MainSalaryRecord.edit');
    Route::get('/MainSalaryRecord/delete/{id}', [MainSalaryRecordController::class, 'destroy'])->name('MainSalaryRecord.destroy');
    Route::post('/MainSalaryRecord/update/{id}', [MainSalaryRecordController::class, 'update'])->name('MainSalaryRecord.update');
    Route::post('/MainSalaryRecord/load_open_month', [MainSalaryRecordController::class, 'load_open_month'])->name('MainSalaryRecord.load_open_month');
    Route::get('/MainSalaryRecord/do_close_month/{id}', [MainSalaryRecordController::class, 'do_close_month'])->name('MainSalaryRecord.do_close_month');

    //----------------نهاية سجلات الراتب---------------------

    //----------------بداية جزءات الراتب---------------------
    Route::get('/MainSalarySanctions', [Main_salary_employee_sanctionsController::class, 'index'])->name('MainSalarySanctions.index');
    Route::get('/MainSalarySanctions/show/{id}', [Main_salary_employee_sanctionsController::class, 'show'])->name('MainSalarySanctions.show');
    Route::post('/MainSalarySanctions/checkExsistsBefor', [Main_salary_employee_sanctionsController::class, 'checkExsistsBefor'])->name('MainSalarySanctions.checkExsistsBefor');
    Route::post('/MainSalarySanctions/store', [Main_salary_employee_sanctionsController::class, 'store'])->name('MainSalarySanctions.store');
    Route::post('/MainSalarySanctions/ajaxSearch', [Main_salary_employee_sanctionsController::class, 'ajax_search'])->name('MainSalarySanctions.ajaxSearch');
    Route::post('/MainSalarySanctions/delete_row', [Main_salary_employee_sanctionsController::class, 'delete_row'])->name('MainSalarySanctions.delete_row');
    Route::post('/MainSalarySanctions/load_edit_row', [Main_salary_employee_sanctionsController::class, 'load_edit_row'])->name('MainSalarySanctions.load_edit_row');
    Route::post('/MainSalarySanctions/do_edit_row', [Main_salary_employee_sanctionsController::class, 'do_edit_row'])->name('MainSalarySanctions.do_edit_row');
    Route::post('/MainSalarySanctions/print_search', [Main_salary_employee_sanctionsController::class, 'print_search'])->name('MainSalarySanctions.print_search');

    //----------------نهاية جزءات الراتب---------------------

    //----------------بداية الغيابات ---------------------
    Route::get('/MainSalaryAbsence', [Main_salary_employee_AbsenceController::class, 'index'])->name('MainSalaryAbsence.index');
    Route::get('/MainSalaryAbsence/show/{id}', [Main_salary_employee_AbsenceController::class, 'show'])->name('MainSalaryAbsence.show');
    Route::post('/MainSalaryAbsence/checkExsistsBefor', [Main_salary_employee_AbsenceController::class, 'checkExsistsBefor'])->name('MainSalaryAbsence.checkExsistsBefor');
    Route::post('/MainSalaryAbsence/store', [Main_salary_employee_AbsenceController::class, 'store'])->name('MainSalaryAbsence.store');
    Route::post('/MainSalaryAbsence/ajaxSearch', [Main_salary_employee_AbsenceController::class, 'ajax_search'])->name('MainSalaryAbsence.ajaxSearch');
    Route::post('/MainSalaryAbsence/delete_row', [Main_salary_employee_AbsenceController::class, 'delete_row'])->name('MainSalaryAbsence.delete_row');
    Route::post('/MainSalaryAbsence/load_edit_row', [Main_salary_employee_AbsenceController::class, 'load_edit_row'])->name('MainSalaryAbsence.load_edit_row');
    Route::post('/MainSalaryAbsence/do_edit_row', [Main_salary_employee_AbsenceController::class, 'do_edit_row'])->name('MainSalaryAbsence.do_edit_row');
    Route::post('/MainSalaryAbsence/print_search', [Main_salary_employee_AbsenceController::class, 'print_search'])->name('MainSalaryAbsence.print_search');

    //----------------نهاية الغيابات---------------------

    //----------------بداية اضافي الراتب---------------------
    Route::get('/MainSalaryAddition', [Main_salary_employee_AdditionController::class, 'index'])->name('MainSalaryAddition.index');
    Route::get('/MainSalaryAddition/show/{id}', [Main_salary_employee_AdditionController::class, 'show'])->name('MainSalaryAddition.show');
    Route::post('/MainSalaryAddition/checkExsistsBefor', [Main_salary_employee_AdditionController::class, 'checkExsistsBefor'])->name('MainSalaryAddition.checkExsistsBefor');
    Route::post('/MainSalaryAddition/store', [Main_salary_employee_AdditionController::class, 'store'])->name('MainSalaryAddition.store');
    Route::post('/MainSalaryAddition/ajaxSearch', [Main_salary_employee_AdditionController::class, 'ajax_search'])->name('MainSalaryAddition.ajaxSearch');
    Route::post('/MainSalaryAddition/delete_row', [Main_salary_employee_AdditionController::class, 'delete_row'])->name('MainSalaryAddition.delete_row');
    Route::post('/MainSalaryAddition/load_edit_row', [Main_salary_employee_AdditionController::class, 'load_edit_row'])->name('MainSalaryAddition.load_edit_row');
    Route::post('/MainSalaryAddition/do_edit_row', [Main_salary_employee_AdditionController::class, 'do_edit_row'])->name('MainSalaryAddition.do_edit_row');
    Route::post('/MainSalaryAddition/print_search', [Main_salary_employee_AdditionController::class, 'print_search'])->name('MainSalaryAddition.print_search');

    //----------------نهاية اضافي الراتب---------------------

    //----------------بداية الخصومات الراتب---------------------
    Route::get('/MainSalaryDiscount', [Main_salary_employee_DiscountController::class, 'index'])->name('MainSalaryDiscount.index');
    Route::get('/MainSalaryDiscount/show/{id}', [Main_salary_employee_DiscountController::class, 'show'])->name('MainSalaryDiscount.show');
    Route::post('/MainSalaryDiscount/checkExsistsBefor', [Main_salary_employee_DiscountController::class, 'checkExsistsBefor'])->name('MainSalaryDiscount.checkExsistsBefor');
    Route::post('/MainSalaryDiscount/store', [Main_salary_employee_DiscountController::class, 'store'])->name('MainSalaryDiscount.store');
    Route::post('/MainSalaryDiscount/ajaxSearch', [Main_salary_employee_DiscountController::class, 'ajax_search'])->name('MainSalaryDiscount.ajaxSearch');
    Route::post('/MainSalaryDiscount/delete_row', [Main_salary_employee_DiscountController::class, 'delete_row'])->name('MainSalaryDiscount.delete_row');
    Route::post('/MainSalaryDiscount/load_edit_row', [Main_salary_employee_DiscountController::class, 'load_edit_row'])->name('MainSalaryDiscount.load_edit_row');
    Route::post('/MainSalaryDiscount/do_edit_row', [Main_salary_employee_DiscountController::class, 'do_edit_row'])->name('MainSalaryDiscount.do_edit_row');
    Route::post('/MainSalaryDiscount/print_search', [Main_salary_employee_DiscountController::class, 'print_search'])->name('MainSalaryDiscount.print_search');

    //----------------نهاية الخصومات الراتب---------------------


    //----------------بداية مكافئات الراتب---------------------
    Route::get('/MainSalaryRewards', [Main_salary_employee_RewardsController::class, 'index'])->name('MainSalaryRewards.index');
    Route::get('/MainSalaryRewards/show/{id}', [Main_salary_employee_RewardsController::class, 'show'])->name('MainSalaryRewards.show');
    Route::post('/MainSalaryRewards/checkExsistsBefor', [Main_salary_employee_RewardsController::class, 'checkExsistsBefor'])->name('MainSalaryRewards.checkExsistsBefor');
    Route::post('/MainSalaryRewards/store', [Main_salary_employee_RewardsController::class, 'store'])->name('MainSalaryRewards.store');
    Route::post('/MainSalaryRewards/ajaxSearch', [Main_salary_employee_RewardsController::class, 'ajax_search'])->name('MainSalaryRewards.ajaxSearch');
    Route::post('/MainSalaryRewards/delete_row', [Main_salary_employee_RewardsController::class, 'delete_row'])->name('MainSalaryRewards.delete_row');
    Route::post('/MainSalaryRewards/load_edit_row', [Main_salary_employee_RewardsController::class, 'load_edit_row'])->name('MainSalaryRewards.load_edit_row');
    Route::post('/MainSalaryRewards/do_edit_row', [Main_salary_employee_RewardsController::class, 'do_edit_row'])->name('MainSalaryRewards.do_edit_row');
    Route::post('/MainSalaryRewards/print_search', [Main_salary_employee_RewardsController::class, 'print_search'])->name('MainSalaryRewards.print_search');

    //----------------نهاية مكافئات الراتب---------------------

    //----------------بداية بدلات الراتب---------------------
    Route::get('/MainSalaryAllowances', [Main_salary_employee_AllowancesController::class, 'index'])->name('MainSalaryAllowances.index');
    Route::get('/MainSalaryAllowances/show/{id}', [Main_salary_employee_AllowancesController::class, 'show'])->name('MainSalaryAllowances.show');
    Route::post('/MainSalaryAllowances/checkExsistsBefor', [Main_salary_employee_AllowancesController::class, 'checkExsistsBefor'])->name('MainSalaryAllowances.checkExsistsBefor');
    Route::post('/MainSalaryAllowances/store', [Main_salary_employee_AllowancesController::class, 'store'])->name('MainSalaryAllowances.store');
    Route::post('/MainSalaryAllowances/ajaxSearch', [Main_salary_employee_AllowancesController::class, 'ajax_search'])->name('MainSalaryAllowances.ajaxSearch');
    Route::post('/MainSalaryAllowances/delete_row', [Main_salary_employee_AllowancesController::class, 'delete_row'])->name('MainSalaryAllowances.delete_row');
    Route::post('/MainSalaryAllowances/load_edit_row', [Main_salary_employee_AllowancesController::class, 'load_edit_row'])->name('MainSalaryAllowances.load_edit_row');
    Route::post('/MainSalaryAllowances/do_edit_row', [Main_salary_employee_AllowancesController::class, 'do_edit_row'])->name('MainSalaryAllowances.do_edit_row');
    Route::post('/MainSalaryAllowances/print_search', [Main_salary_employee_AllowancesController::class, 'print_search'])->name('MainSalaryAllowances.print_search');

    //----------------نهاية بدلات الراتب---------------------


    //----------------بداية السلف الشهرية ---------------------
    Route::get('/MainSalaryLoans', [Main_salary_employee_LoansController::class, 'index'])->name('MainSalaryLoans.index');
    Route::get('/MainSalaryLoans/show/{id}', [Main_salary_employee_LoansController::class, 'show'])->name('MainSalaryLoans.show');
    Route::post('/MainSalaryLoans/checkExsistsBefor', [Main_salary_employee_LoansController::class, 'checkExsistsBefor'])->name('MainSalaryLoans.checkExsistsBefor');
    Route::post('/MainSalaryLoans/store', [Main_salary_employee_LoansController::class, 'store'])->name('MainSalaryLoans.store');
    Route::post('/MainSalaryLoans/ajaxSearch', [Main_salary_employee_LoansController::class, 'ajax_search'])->name('MainSalaryLoans.ajaxSearch');
    Route::post('/MainSalaryLoans/delete_row', [Main_salary_employee_LoansController::class, 'delete_row'])->name('MainSalaryLoans.delete_row');
    Route::post('/MainSalaryLoans/load_edit_row', [Main_salary_employee_LoansController::class, 'load_edit_row'])->name('MainSalaryLoans.load_edit_row');
    Route::post('/MainSalaryLoans/do_edit_row', [Main_salary_employee_LoansController::class, 'do_edit_row'])->name('MainSalaryLoans.do_edit_row');
    Route::post('/MainSalaryLoans/print_search', [Main_salary_employee_LoansController::class, 'print_search'])->name('MainSalaryLoans.print_search');

    //----------------نهاية السلف الشهرية ---------------------


    //----------------بداية السلف المستديمة ---------------------
    Route::get('/MainSalary_p_Loans', [Main_salary_employee_p_LoansController::class, 'index'])->name('MainSalary_p_Loans.index');
    Route::get('/MainSalary_p_Loans/show/{id}', [Main_salary_employee_p_LoansController::class, 'show'])->name('MainSalary_p_Loans.show');
    Route::post('/MainSalary_p_Loans/checkExsistsBefor', [Main_salary_employee_p_LoansController::class, 'checkExsistsBefor'])->name('MainSalary_p_Loans.checkExsistsBefor');
    Route::post('/MainSalary_p_Loans/store', [Main_salary_employee_p_LoansController::class, 'store'])->name('MainSalary_p_Loans.store');
    Route::post('/MainSalary_p_Loans/ajaxSearch', [Main_salary_employee_p_LoansController::class, 'ajax_search'])->name('MainSalary_p_Loans.ajaxSearch');
    Route::get('/MainSalary_p_Loans/delete_parent_loan/{id}', [Main_salary_employee_p_LoansController::class, 'delete_parent_loan'])->name('MainSalary_p_Loans.delete_parent_loan');
    Route::post('/MainSalary_p_Loans/load_akast_details', [Main_salary_employee_p_LoansController::class, 'load_akast_details'])->name('MainSalary_p_Loans.load_akast_details');
    Route::post('/MainSalary_p_Loans/load_edit_row', [Main_salary_employee_p_LoansController::class, 'load_edit_row'])->name('MainSalary_p_Loans.load_edit_row');
    Route::post('/MainSalary_p_Loans/do_edit_row', [Main_salary_employee_p_LoansController::class, 'do_edit_row'])->name('MainSalary_p_Loans.do_edit_row');
    Route::post('/MainSalary_p_Loans/print_search', [Main_salary_employee_p_LoansController::class, 'print_search'])->name('MainSalary_p_Loans.print_search');
    Route::get('/MainSalary_p_Loans/do_is_dismissail_done_now/{id}', [Main_salary_employee_p_LoansController::class, 'do_is_dismissail_done_now'])->name('MainSalary_p_Loans.do_is_dismissail_done_now');
    Route::post('/MainSalary_p_Loans/doSingleCachPayNow', [Main_salary_employee_p_LoansController::class, 'doSingleCachPayNow'])->name('MainSalary_p_Loans.doSingleCachPayNow');

    //----------------نهاية السلف المستديمة ---------------------

    //---------------- بداية الرواتب النهائية مفصلة---------------------
    Route::get('/MainSalaryEmployee', [Main_salary_employeeController::class, 'index'])->name('MainSalaryEmployee.index');
    Route::get('/MainSalaryEmployee/show/{id}', [Main_salary_employeeController::class, 'show'])->name('MainSalaryEmployee.show');
    Route::post('/MainSalaryEmployee/ajaxSearch', [Main_salary_employeeController::class, 'ajax_search'])->name('MainSalaryEmployee.ajaxSearch');
    Route::post('/MainSalaryEmployee/print_search', [Main_salary_employeeController::class, 'print_search'])->name('MainSalaryEmployee.print_search');
    Route::get('/MainSalaryEmployee/showSalaryDetails/{id}', [Main_salary_employeeController::class, 'showSalaryDetails'])->name('MainSalaryEmployee.showSalaryDetails');
    Route::post('/MainSalaryEmployee/AddManuallySalary/{id}', [Main_salary_employeeController::class, 'AddManuallySalary'])->name('MainSalaryEmployee.AddManuallySalary');
    Route::post('/MainSalaryEmployee/delete_salary', [Main_salary_employeeController::class, 'delete_salary'])->name('MainSalaryEmployee.delete_salary');
    Route::get('/MainSalaryEmployee/doStopSalary/{id}', [Main_salary_employeeController::class, 'doStopSalary'])->name('MainSalaryEmployee.doStopSalary');
    Route::get('/MainSalaryEmployee/doCancelStopSalary/{id}', [Main_salary_employeeController::class, 'doCancelStopSalary'])->name('MainSalaryEmployee.doCancelStopSalary');
    Route::get('/MainSalaryEmployee/doDeleteSalaryIternal/{id}', [Main_salary_employeeController::class, 'doDeleteSalaryIternal'])->name('MainSalaryEmployee.doDeleteSalaryIternal');
    Route::post('/MainSalaryEmployee/load_archive_salary', [Main_salary_employeeController::class, 'load_archive_salary'])->name('MainSalaryEmployee.load_archive_salary');
    Route::post('/MainSalaryEmployee/do_archive_salary/{id}', [Main_salary_employeeController::class, 'do_archive_salary'])->name('MainSalaryEmployee.do_archive_salary');
    Route::get('/MainSalaryEmployee/printSalary/{id}', [Main_salary_employeeController::class, 'printSalary'])->name('MainSalaryEmployee.printSalary');

    //----------------نهاية الرواتب النهائية مفصلة---------------------

    //----------------بداية البصمة---------------------
    Route::get('/AttendanceDeparture', [Attendance_departureController::class, 'index'])->name('AttendanceDeparture.index');
    Route::get('/AttendanceDeparture/show/{id}', [Attendance_departureController::class, 'show'])->name('AttendanceDeparture.show');
    Route::get('/AttendanceDeparture/showPasmaDetails/{employees_code}/{finance_cin_periods_id}', [Attendance_departureController::class, 'showPasmaDetails'])->name('AttendanceDeparture.showPasmaDetails');
    Route::post('/AttendanceDeparture/ajaxSearch', [Attendance_departureController::class, 'ajax_search'])->name('AttendanceDeparture.ajaxSearch');
    Route::post('/AttendanceDeparture/print_onePasmasearch', [Attendance_departureController::class, 'print_onePasmasearch'])->name('AttendanceDeparture.print_onePasmasearch');
    Route::get('/AttendanceDeparture/uploadExcelFile/{id}', [Attendance_departureController::class, 'uploadExcelFile'])->name('AttendanceDeparture.uploadExcelFile');
    Route::post('/AttendanceDeparture/do_UploadExcelFile/{id}', [Attendance_departureController::class, 'do_UploadExcelFile'])->name('AttendanceDeparture.do_UploadExcelFile');
    Route::post('/AttendanceDeparture/load_PasmasaArchive', [Attendance_departureController::class, 'load_PasmasaArchive'])->name('AttendanceDeparture.load_PasmasaArchive');
    Route::post('/AttendanceDeparture/load_active_Attendance_departure', [Attendance_departureController::class, 'load_active_Attendance_departure'])->name('AttendanceDeparture.load_active_Attendance_departure');
    Route::post('/AttendanceDeparture/load_my_action', [Attendance_departureController::class, 'load_my_action'])->name('AttendanceDeparture.load_my_action');
    Route::post('/AttendanceDeparture/save_active_Attendance_departure', [Attendance_departureController::class, 'save_active_Attendance_departure'])->name('AttendanceDeparture.save_active_Attendance_departure');
    Route::post('/AttendanceDeparture/redo_update_actions', [Attendance_departureController::class, 'redo_update_actions'])->name('AttendanceDeparture.redo_update_actions');
    Route::get('/AttendanceDeparture/print_one_passma_details/{employees_code}/{finance_cin_periods_id}', [Attendance_departureController::class, 'print_one_passma_details'])->name('AttendanceDeparture.print_one_passma_details');

    //----------------نهاية البصمة ---------------------

    //----------------بداية الرصيد---------------------
    Route::get('/EmployeeVacationsBalance', [MainVacationsBalanceController::class, 'index'])->name('EmployeeVacationsBalance.index');
    Route::post('/EmployeeVacationsBalance/ajaxSearch', [MainVacationsBalanceController::class, 'ajax_search'])->name('EmployeeVacationsBalance.ajaxSearch');
    Route::get('/EmployeeVacationsBalance/show/{id}', [MainVacationsBalanceController::class, 'show'])->name('EmployeeVacationsBalance.show');
    Route::post('/EmployeeVacationsBalance/load_edit_balance', [MainVacationsBalanceController::class, 'load_edit_balance'])->name('EmployeeVacationsBalance.load_edit_balance');


    //----------------نهاية الرصيد ---------------------


});

//-------------نهاية تسجيل دخول الادمن-----------


Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => 'guest:admin'], function () {

    Route::get('login', [LoginController::class, 'index'])->name('admin.showlogin');
    Route::post('login', [LoginController::class, 'login'])->name('admin.login');
});

Route::fallback(function () {
    return redirect()->route('admin.showlogin');
});
