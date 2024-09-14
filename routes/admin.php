<?php

use App\Http\Controllers\Admin\additionalTypesController;
use App\Http\Controllers\Admin\Admin_panel_settingsController;
use App\Http\Controllers\Admin\AllowanceController;
use App\Http\Controllers\Admin\BranchesController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DepartementController;
use App\Http\Controllers\Admin\DiscountTypesController;
use App\Http\Controllers\Admin\EmployeesController;
use App\Http\Controllers\Admin\Finance_calenderController;
use App\Http\Controllers\Admin\JobsCategoriesController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\Main_salary_employee_sanctionsController;
use App\Http\Controllers\Admin\MainSalaryRecordController;
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

          //----------------نهاية سجلات الراتب---------------------
 
            //----------------بداية جزءات الراتب---------------------
            Route::get('/MainSalarySanctions', [Main_salary_employee_sanctionsController::class, 'index'])->name('MainSalarySanctions.index');
            Route::get('/MainSalarySanctions/show/{id}', [Main_salary_employee_sanctionsController::class, 'show'])->name('MainSalarySanctions.show');
            Route::post('/MainSalarySanctions/checkExsistsBefor', [Main_salary_employee_sanctionsController::class, 'checkExsistsBefor'])->name('MainSalarySanctions.checkExsistsBefor');
            Route::post('/MainSalarySanctions/store', [Main_salary_employee_sanctionsController::class, 'store'])->name('MainSalarySanctions.store');
            Route::post('/MainSalarySanctions/ajaxSearch', [Main_salary_employee_sanctionsController::class, 'ajax_search'])->name('MainSalarySanctions.ajaxSearch');
            Route::post('/MainSalarySanctions/delete_row', [Main_salary_employee_sanctionsController::class, 'delete_row'])->name('MainSalarySanctions.delete_row');
            Route::post('/MainSalarySanctions/load_edit_row', [Main_salary_employee_sanctionsController::class, 'load_edit_row'])->name('MainSalarySanctions.load_edit_row');

            //----------------نهاية جزءات الراتب---------------------
          


});

//-------------نهاية تسجيل دخول الادمن-----------


Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => 'guest:admin'], function () {

    Route::get('login', [LoginController::class, 'index'])->name('admin.showlogin');
    Route::post('login', [LoginController::class, 'login'])->name('admin.login');
});

Route::fallback(function () {
    return redirect()->route('admin.showlogin');
});
