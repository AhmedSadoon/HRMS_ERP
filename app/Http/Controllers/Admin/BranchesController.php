<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BranchesRequest;
use App\Models\admin_panel_setting;
use App\Models\Alerts_system_monitoring;
use App\Models\Branche;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BranchesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $com_code=auth()->user()->com_code;
        $branches=get_cols_where_paginate(new Branche(),array('*'),array('com_code'=>$com_code),'id','DESC',PAGINATION_COUNTER);
        
        if(!empty($branches)){
            foreach($branches as $info){
                $info->CounterUse=get_count_where(new Employee(),array("com_code"=>$com_code,"branch_id"=>$info->id));
            } 
        }
        return view('admin.Branches.index',compact('branches'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.Branches.create');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BranchesRequest $request)
    {
        try {
            
            $com_code=auth()->user()->com_code;
            $checkExsists=get_cols_where_row(new  Branche(),array('id'),array("com_code"=>$com_code,'name'=>$request->name));
            if(!empty($checkExsists)){
                return redirect()->back()->with(['error'=>'عفواً اسم الفرع مسجل مسبقاًُ'])->withInput(); 
            }
            DB::beginTransaction();
            $dataToInsert['name']=$request->name;
            $dataToInsert['address']=$request->address;
            $dataToInsert['phones']=$request->phones;
            $dataToInsert['email']=$request->email;
            $dataToInsert['active']=$request->active;
            $dataToInsert['added_by']=auth()->user()->id;
            $dataToInsert['com_code']=$com_code;
            
            $flag=insert(new Branche(),$dataToInsert,true);
            if($flag){
                $is_active_alerts_system_monitorig=get_field_value(new admin_panel_setting(),"is_active_alerts_system_monitorig",array('com_code'=>$com_code,));
                if($is_active_alerts_system_monitorig==1){
                    $data_monitoring_insert['alert_modules_id']=1;
                    $data_monitoring_insert['alert_movetype_id']=6;
                    $data_monitoring_insert['content']="اضافة فرع جديد بأسم ".$request->name;
                    $data_monitoring_insert['foreign_key_table_id']=$flag['id'];
                    $data_monitoring_insert['added_by']=auth()->user()->id;
                    $data_monitoring_insert['com_code']=$com_code;
                    $data_monitoring_insert['date']=date('Y-m-d');
                    insert(new Alerts_system_monitoring(),$data_monitoring_insert,array('com_code'=>$com_code));
                  
                }
            }
            DB::commit();
            return redirect()->route('branches.index')->with(['success'=>'تم اضافة الفرع بنجاح']);


        }  catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error'=>'عفواً حدث خطأ'.$ex->getMessage()])->withInput();
        }
    }

 

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $com_code=auth()->user()->com_code;
        $data=get_cols_where_row(new Branche(),array('*'),array('id'=>$id,'com_code'=>$com_code));

        if(empty($data)){
            return redirect()->back()->with(['error'=>'عفواً غير قادر على الوصول الى البيانات المطلوبة'])->withInput(); 
        }

        return view('admin.Branches.edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BranchesRequest $request, $id)
    {
        try {
            $com_code=auth()->user()->com_code;
            $data=get_cols_where_row(new Branche(),array("*"),array('id'=>$id,'com_code'=>$com_code));
    
            if(empty($data)){
                return redirect()->route('branches.index')->with(['error'=>'عفواً غير قادر على الوصول الى البيانات المطلوبة'])->withInput(); 
            }

            DB::beginTransaction();
            $dataToUpdate['name']=$request->name;
            $dataToUpdate['address']=$request->address;
            $dataToUpdate['phones']=$request->phones;
            $dataToUpdate['email']=$request->email;
            $dataToUpdate['active']=$request->active;
            $dataToUpdate['updated_by']=auth()->user()->id;

            $flag=update(new Branche(),$dataToUpdate,array('id'=>$id,'com_code'=>$com_code));
            if($flag){
                $is_active_alerts_system_monitorig=get_field_value(new admin_panel_setting(),"is_active_alerts_system_monitorig",array('com_code'=>$com_code,));
                if($is_active_alerts_system_monitorig==1){
                    $data_monitoring_insert['alert_modules_id']=1;
                    $data_monitoring_insert['alert_movetype_id']=7;
                    if($data['name']!=$dataToUpdate['name']){
                        $updateLable='تم تغير الاسم من '.$data['name'].'الى '.' '.$request->name;
                    }
                    $data_monitoring_insert['content']="تعديل فرع بأسم ".$data['name']."" .$updateLable;
                    
                    $data_monitoring_insert['foreign_key_table_id']=$id;
                    $data_monitoring_insert['added_by']=auth()->user()->id;
                    $data_monitoring_insert['com_code']=$com_code;
                    $data_monitoring_insert['date']=date('Y-m-d');
                    insert(new Alerts_system_monitoring(),$data_monitoring_insert,array('com_code'=>$com_code));
                  
                }
            }
            DB::commit();
            return redirect()->route('branches.index')->with(['success'=>'تم تعديل الفرع بنجاح']);


        }  catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error'=>'عفواً حدث خطأ'.$ex->getMessage()])->withInput();
        }

       

      

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $com_code=auth()->user()->com_code;
            $data=get_cols_where_row(new Branche(),array("*"),array('id'=>$id,'com_code'=>$com_code));
    
            if(empty($data)){
                return redirect()->route('branches.index')->with(['error'=>'عفواً غير قادر على الوصول الى البيانات المطلوبة'])->withInput(); 
            }
            $CounterUse=get_count_where(new Employee(),array("com_code"=>$com_code,"branch_id"=>$id));
            if($CounterUse>0){
                return redirect()->route('branches.index')->with(['error'=>'عفواً لا يمكن حذف البيانات لانه تم استخدامها سابقاً']); 
            }
            DB::beginTransaction();
            $flag=destroy(new Branche(),array('id'=>$id,'com_code'=>$com_code));
            if($flag){
                $is_active_alerts_system_monitorig=get_field_value(new admin_panel_setting(),"is_active_alerts_system_monitorig",array('com_code'=>$com_code,));
                if($is_active_alerts_system_monitorig==1){
                    $data_monitoring_insert['alert_modules_id']=1;
                    $data_monitoring_insert['alert_movetype_id']=8;
                    $data_monitoring_insert['content']="حذف فرع بأسم ".$data['name'];
                    $data_monitoring_insert['foreign_key_table_id']=$id;
                    $data_monitoring_insert['added_by']=auth()->user()->id;
                    $data_monitoring_insert['com_code']=$com_code;
                    $data_monitoring_insert['date']=date('Y-m-d');
                    insert(new Alerts_system_monitoring(),$data_monitoring_insert,array('com_code'=>$com_code));
                  
                }
            }
            DB::commit();
            return redirect()->route('branches.index')->with(['success'=>'تم حذف الفرع بنجاح']);


        }  catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->route('branches.index')->with(['error'=>'عفواً حدث خطأ'.$ex->getMessage()])->withInput();
        }

    }
}
