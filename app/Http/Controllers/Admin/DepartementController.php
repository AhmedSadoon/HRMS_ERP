<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DepartementsRequest;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepartementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $com_code=auth()->user()->com_code;
        $departements=get_cols_where_paginate(new Department(),array('*'),array('com_code'=>$com_code),'id','DESC',PAGINATION_COUNTER);

        return view('admin.Departements.index',compact('departements'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.Departements.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DepartementsRequest $request)
    {
        try {
            $com_code = auth()->user()->com_code;
            $checkExsits = get_cols_where_row(new Department(), array('id'),array('com_code'=>$com_code,'name'=>$request->name));
            if (!empty($checkExsits)) {
                return redirect()->back()->with(['error' => 'عفواً  اسم الادارة مسجل مسبقاً'])->withInput();
            }


            $dataToinsert = [
                'name' => $request->name,
                'phones' => $request->phones,
                'notes' => $request->notes,
                'active' => $request->active,
                'added_by' => auth()->user()->id,
                'com_code' => $com_code,
            ];

            DB::beginTransaction();

            insert(new Department(),$dataToinsert);
            DB::commit();
            return redirect()->route('departements.index')->with('success','تم اضافة الادارة بنجاح');
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error'=>'عفواً حدث خطأ'.$ex->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $com_code = auth()->user()->com_code;
        $data=get_cols_where_row(new Department(),array("*"),array("com_code"=>$com_code,'id'=>$id));
        if (empty($data)) {
            return redirect()->route('departements.index')->with('error','عفواً غير قادر على الوصول الى البيانات المطلوبة');
        }
        return view('admin.Departements.edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DepartementsRequest $request,$id)
    {
       try{
        $com_code = auth()->user()->com_code;
        $data=get_cols_where_row(new Department(),array("*"),array("com_code"=>$com_code,'id'=>$id));
        if (empty($data)) {
            return redirect()->route('departements.index')->with('error','عفواً غير قادر على الوصول الى البيانات المطلوبة');
        }

        $checkExsits = Department::select('id')->where('com_code',$com_code)->where('name',$request->name)->where('id','!=',$id)->first();
        if (!empty($checkExsits)) {
            return redirect()->back()->with(['error' => 'عفواً  اسم الادارة مسجل مسبقاً'])->withInput();
        }


        DB::beginTransaction();

        $dataToUpdate=[
            'name' => $request->name,
            'phones' => $request->phones,
            'notes' => $request->notes,
            'active' => $request->active,
            'updated_by' => auth()->user()->id,
        ];

       update(new Department(),$dataToUpdate,array("com_code"=>$com_code,'id'=>$id));
       DB::commit();
       return redirect()->route('departements.index')->with('success','تم تعديل الادارة بنجاح');

       } catch (\Exception $ex) {
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
            $data=get_cols_where_row(new Department(),array("*"),array("com_code"=>$com_code,'id'=>$id));
            if (empty($data)) {
                return redirect()->route('departements.index')->with('error','عفواً غير قادر على الوصول الى البيانات المطلوبة');
            }    
    
            DB::beginTransaction();
            
            destroy(new Department(),array("com_code"=>$com_code,'id'=>$id));
    
           DB::commit();
           return redirect()->route('departements.index')->with('success','تم حذف الادارة بنجاح');
    
           } catch (\Exception $ex) {
           DB::rollBack();
           return redirect()->route('departements.index')->with(['error'=>'عفواً حدث خطأ'.$ex->getMessage()]);
       }
    }
}
