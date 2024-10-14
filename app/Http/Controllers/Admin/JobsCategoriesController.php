<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobCategoriesRequest;
use App\Models\Employee;
use App\Models\jobs_category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JobsCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $com_code=auth()->user()->com_code;
        $JobsCategories=get_cols_where_paginate(new jobs_category(),array('*'),array('com_code'=>$com_code),'id','DESC',PAGINATION_COUNTER);
        if(!empty($JobsCategories)){
            foreach($JobsCategories as $info){
                $info->CounterUse=get_count_where(new Employee(),array("com_code"=>$com_code,"emp_jobs_id"=>$info->id));
            } 
        }
        return view('admin.JobsCategories.index',compact('JobsCategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.JobsCategories.create');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(JobCategoriesRequest $request)
    {
        try{

            $com_code = auth()->user()->com_code;
            $checkExsits = get_cols_where_row(new jobs_category(), array('id'),array('com_code'=>$com_code,'name'=>$request->name));
            if (!empty($checkExsits)) {
                return redirect()->back()->with(['error' => 'عفواً  اسم الوظيفة مسجل مسبقاً'])->withInput();
            }

            DB::beginTransaction();

            $dataToInsert=[
                'name'=>$request->name,
                'active'=>$request->active,
                'added_by'=>auth()->user()->id,
                'com_code'=>$com_code
            ];

            insert(new jobs_category(),$dataToInsert);
            DB::commit();
            return redirect()->route('JobsCategories.index')->with('success','تم اضافة الوظيفة بنجاح');


        } catch (\Exception $ex) {
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
        $data=get_cols_where_row(new jobs_category(),array('*'),array('com_code'=>$com_code,'id'=>$id));
        if(empty($data)){
            return redirect()->route('JobsCategories.index')->with('error','عفواً غير قادر على الوصول الى البيانات المطلوبة')->withInput();
        }

        return view('admin.JobsCategories.edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(JobCategoriesRequest $request,$id)
    {
        try{
            $com_code=auth()->user()->com_code;
            $data=get_cols_where_row(new jobs_category(),array('*'),array('com_code'=>$com_code,'id'=>$id));
            if(empty($data)){
                return redirect()->route('JobsCategories.index')->with('error','عفواً غير قادر على الوصول الى البيانات المطلوبة')->withInput();
            }

            $checkExsits = jobs_category::select('id')->where('com_code',$com_code)->where('name',$request->name)->where('id','!=',$id)->first();
            if (!empty($checkExsits)) {
                return redirect()->back()->with(['error' => 'عفواً  اسم الوظيفة مسجل مسبقاً'])->withInput();
            }

            DB::beginTransaction();
    
            $dataToUpdate=[
                'name'=>$request->name,
                'active'=>$request->active,
                'updated_by'=>auth()->user()->id
            ];
    
            update(new jobs_category(),$dataToUpdate,array('com_code'=>$com_code,'id'=>$id));
            DB::commit();
            return redirect()->route('JobsCategories.index')->with('success','تم تعديل الوظيفة بنجاح');

        }catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error'=>'عفواً حدث خطأ'.$ex->getMessage()])->withInput();
        }
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try{
            $com_code = auth()->user()->com_code;
            $data=get_cols_where_row(new jobs_category(),array("*"),array("com_code"=>$com_code,'id'=>$id));
            if (empty($data)) {
                return redirect()->route('JobsCategories.index')->with('error','عفواً غير قادر على الوصول الى البيانات المطلوبة');
            }    
            $CounterUse=get_count_where(new Employee(),array("com_code"=>$com_code,"emp_jobs_id"=>$id));
            if($CounterUse>0){
                return redirect()->route('JobsCategories.index')->with(['error'=>'عفواً لا يمكن حذف البيانات لانه تم استخدامها سابقاً']); 
            }
            DB::beginTransaction();
            
            destroy(new jobs_category(),array("com_code"=>$com_code,'id'=>$id));
    
           DB::commit();
           return redirect()->route('JobsCategories.index')->with('success','تم حذف الوظيفة بنجاح');
    
           } catch (\Exception $ex) {
           DB::rollBack();
           return redirect()->route('JobsCategories.index')->with(['error'=>'عفواً حدث خطأ'.$ex->getMessage()]);
       }
    }
}
