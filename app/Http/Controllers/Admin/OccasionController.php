<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\OccasionsRequest;
use App\Models\Occasion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OccasionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $com_code = auth()->user()->com_code;
        $Occasions = get_cols_where_paginate(new Occasion(), array("*"), array('com_code' => $com_code), 'id', 'DESC', PAGINATION_COUNTER);
        return view('admin.Occasions.index', compact('Occasions'));
    
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.Occasions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OccasionsRequest $request)
    {
        try {
            $com_code=auth()->user()->com_code;
            $checkexsists=get_cols_where_row(new Occasion(),array('id'),array('com_code'=>$com_code,'name'=>$request->name));
            if(!empty($checkexsists)){
                return redirect()->back()->with(['error'=>'عفواً هذه المناسبة مسجلة من قبل'])->withInput();
            }

            DB::beginTransaction();
            //$timeDiffrence=abs((strtotime($request->to_date)-strtotime($request->from_date))); //اذا كان ادخال عدد ايام العطل تلقائي
            $dataToInsert=[
                'name'=>$request->name,
                'from_date'=>$request->from_date,
                'to_date'=>$request->to_date,
                //'days_counter'=>intval($timeDiffrence/86400)+1, //اذا كان ادخال عدد ايام العطل تلقائي
                'days_counter'=>$request->days_counter, //اذا كان ادخال عدد ايام العطل يدوي
                'active'=>$request->active,
                'added_by'=>auth()->user()->id,
                'com_code'=>$com_code
            ];

            insert(new Occasion(),$dataToInsert);

            DB::commit();

            return redirect()->route('Occasions.index')->with('success','تم اضافة المناسبة بنجاح');

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
        $com_code=auth()->user()->com_code;
        $data=get_cols_where_row(new Occasion(),array('*'),array('com_code'=>$com_code,'id'=>$id));
        if(empty($data)){
            return redirect()->route('Occasions.index')->with(['error'=>'عفواً هذه المناسبة غير موجودة'])->withInput();
        }

        return view('admin.Occasions.edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OccasionsRequest $request,$id)
    {
       try {

        $com_code=auth()->user()->com_code;
        $data=get_cols_where_row(new Occasion(),array('*'),array('com_code'=>$com_code,'id'=>$id));
        if(empty($data)){
            return redirect()->route('Occasions.index')->with(['error'=>'عفواً هذه المناسبة غير موجودة'])->withInput();
        }

        $checkExsists=Occasion::select('id')->where('com_code',$com_code)->where('name',$request->name)->where('id','!=',$id)->first();
        if(!empty($checkExsists)){
            return redirect()->back()->with('error','عفواً هذه المناسبة مسجلة من قبل')->withInput();
        }
        DB::beginTransaction();
        $dataToUpdate=[
                'name'=>$request->name,
                'from_date'=>$request->from_date,
                'to_date'=>$request->to_date,
                //'days_counter'=>intval($timeDiffrence/86400)+1, //اذا كان ادخال عدد ايام العطل تلقائي
                'days_counter'=>$request->days_counter, //اذا كان ادخال عدد ايام العطل يدوي
                'active'=>$request->active,
                'updated_by'=>auth()->user()->id,
                'com_code'=>$com_code
        ];

        update(new Occasion(),$dataToUpdate,array('com_code'=>$com_code,'id'=>$id));
        DB::commit();

        return redirect()->route('Occasions.index')->with('success','تم تعديل المناسبة بنجاح');

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
        try {

            $com_code=auth()->user()->com_code;
            $data=get_cols_where_row(new Occasion(),array('*'),array('com_code'=>$com_code,'id'=>$id));
            if(empty($data)){
                return redirect()->route('Occasions.index')->with(['error'=>'عفواً هذه المناسبة غير موجودة'])->withInput();
            }

            DB::beginTransaction();
           
            destroy(new Occasion(),array('com_code'=>$com_code,'id'=>$id));
            DB::commit();
    
            return redirect()->route('Occasions.index')->with('success','تم حذف المناسبة بنجاح');
    
           } catch (\Exception $ex) {
                DB::rollBack();
                return redirect()->route('Occasions.index')->with(['error'=>'عفواً حدث خطأ'.$ex->getMessage()]);
            }
    }
}
