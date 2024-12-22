<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Alert_modules;
use App\Models\Alert_movetype;
use App\Models\Alerts_system_monitoring;
use App\Models\Employee;
use Illuminate\Http\Request;

class Alerts_system_monitoringController extends Controller
{
    public function index()
    {
        $com_code = auth()->user()->com_code;
        $data = get_cols_where_paginate(new Alerts_system_monitoring(), array("*"), array('com_code' => $com_code), 'id', 'DESC', PAGINATION_COUNTER);

        if (!empty($data)) {
            foreach ($data as $info) {
                $info->alert_modules_name = get_field_value(new Alert_modules(), 'name', array('id' => $info->alert_modules_id));
                $info->alert_movetype_name = get_field_value(new Alert_movetype(), 'name', array('id' => $info->alert_movetype_id));
            }
        }

        $other['alert_modules']=get_cols_where(new Alert_modules(),array('*'),array('active'=>1));
        $other['alert_movetype']=get_cols_where(new Alert_movetype(),array('*'),array('active'=>1));
        $other['employees']=get_cols_where(new Employee(),array('employees_code','emp_name'),array('com_code'=>$com_code));
        $other['admins']=get_cols_where(new Admin(),array('id','name'),array('com_code'=>$com_code));
        return view('admin.Alerts_system_monitoring.index', ['data' => $data,'other'=>$other]);
    }

    public function ajaxSearch(Request $request)
    {
        if ($request->ajax()) {

            $alert_modules_id = $request->alert_modules_id;
            $alert_movetype_id = $request->alert_movetype_id;
            $employees_code = $request->employees_code;
            $is_marked = $request->is_marked;
            $form_date = $request->form_date;
            $to_date = $request->to_date;
            $admin_id = $request->admin_id;
           

            // فحص الحقل searchbycode
            if ($alert_modules_id == 'all') {
                $field1 = 'id';
                $operator1 = ">";
                $value1 = '0';
            } else {
              
                    $field1 = 'alert_modules_id';
                    $operator1 = "=";
                    $value1 = $alert_modules_id; // استخدام $searchbycode وليس $search_btn_radio
                
            }

                  // فحص الحقل searchbycode
                  if ($alert_movetype_id == 'all') {
                    $field2 = 'id';
                    $operator2 = ">";
                    $value2 = '0';
                } else {
                  
                        $field2 = 'alert_movetype_id';
                        $operator2 = "==";
                        $value2 = $alert_movetype_id; // استخدام $searchbycode وليس $search_btn_radio
                    
                }

      

            // فحص الفرع
            if ($employees_code == 'all') {
                $field3 = 'id';
                $operator3 = ">";
                $value3 = 0;
            } else {
                $field3 = 'employees_code';
                $operator3 = "=";
                $value3 = $employees_code;
            }

            // فحص القسم
            if ($is_marked == 'all') {
                $field4 = 'id';
                $operator4 = ">";
                $value4 = 0;
            } else {
                $field4 = 'is_marked';
                $operator4 = "=";
                $value4 = $is_marked;
            }

            // فحص الوظيفة
            if ($form_date == '') {
                $field5 = 'id';
                $operator5 = ">";
                $value5 = 0;
            } else {
                $field5 = 'date';
                $operator5 = ">=";
                $value5 = $form_date;
            }

            // فحص حالة الوظيفة
            if ($to_date == '') {
                $field6 = 'id';
                $operator6 = ">";
                $value6 = 0;
            } else {
                $field6 = 'date';
                $operator6 = "<=";
                $value6 = $to_date;
            }

            // فحص طريقة استلام الراتب
            if ($admin_id == 'all') {
                $field7 = 'id';
                $operator7 = ">";
                $value7 = 0;
            } else {
                $field7 = 'added_by';
                $operator7 = "=";
                $value7 = $admin_id;
            }

            
            $com_code = auth()->user()->com_code;
            // البحث باستخدام الشروط المحددة
            $data = Alerts_system_monitoring::select('*')
                ->where($field1, $operator1, $value1)
                ->where($field2, $operator2, $value2)
                ->where($field3, $operator3, $value3)
                ->where($field4, $operator4, $value4)
                ->where($field5, $operator5, $value5)
                ->where($field6, $operator6, $value6)
                ->where($field7, $operator7, $value7)
                ->where('com_code', '=', $com_code)
                ->orderBy('id', 'DESC')
                ->paginate(PAGINATION_COUNTER);

                if (!empty($data)) {
                    foreach ($data as $info) {
                        $info->alert_modules_name = get_field_value(new Alert_modules(), 'name', array('id' => $info->alert_modules_id));
                        $info->alert_movetype_name = get_field_value(new Alert_movetype(), 'name', array('id' => $info->alert_movetype_id));
                    }
                }

            return view('admin.Alerts_system_monitoring.ajax_search', compact('data'));
        }
    }

    public function do_undo_mark(Request $request)
    {
        $com_code = auth()->user()->com_code;

        if ($request->ajax()) {
            $data=get_cols_where_row(new Alerts_system_monitoring(),array('is_marked'),array('com_code'=>$com_code,'id'=>$request->id));
            if(!empty($data)){
                if($data['is_marked']==1){
                    $dataToUpdate['is_marked']=0;
                    $dataToUpdate['updated_by']=auth()->user()->id;
                }else{
                    $dataToUpdate['is_marked']=1;
                    $dataToUpdate['updated_by']=auth()->user()->id;
                }

                update(new Alerts_system_monitoring(),$dataToUpdate,array('com_code'=>$com_code,'id'=>$request->id));
            }
        }
    }

}
