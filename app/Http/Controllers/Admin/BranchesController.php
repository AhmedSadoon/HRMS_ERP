<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BranchesRequest;
use App\Models\Branche;
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
            
            insert(new Branche(),$dataToInsert);
            DB::commit();
            return redirect()->route('branches.index')->with(['success'=>'تم اضافة الفرع بنجاح']);


        }  catch (\Exception $ex) {
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

            update(new Branche(),$dataToUpdate,array('id'=>$id,'com_code'=>$com_code));
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

            DB::beginTransaction();
            destroy(new Branche(),array('id'=>$id,'com_code'=>$com_code));
            DB::commit();
            return redirect()->route('branches.index')->with(['success'=>'تم حذف الفرع بنجاح']);


        }  catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->route('branches.index')->with(['error'=>'عفواً حدث خطأ'.$ex->getMessage()])->withInput();
        }

    }
}
