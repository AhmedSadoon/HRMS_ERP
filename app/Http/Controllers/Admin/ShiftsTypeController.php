<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShiftTypeRequest;
use App\Models\Employee;
use App\Models\Shifts_type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShiftsTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $com_code = auth()->user()->com_code;
        $Shifts_type = get_cols_where_paginate(new Shifts_type(), array('*'), array('com_code' => $com_code), 'id', 'DESC', PAGINATION_COUNTER);
        if(!empty($Shifts_type)){
            foreach($Shifts_type as $info){
                $info->CounterUse=get_count_where(new Employee(),array("com_code"=>$com_code,"shift_type_id"=>$info->id));
            } 
        }
        return view('admin.ShiftsTypes.index', compact('Shifts_type'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.ShiftsTypes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ShiftTypeRequest $request)
    {
        try {
            $com_code = auth()->user()->com_code;
            $dataToInsert['com_code'] = $com_code;
            $dataToInsert['type'] = $request->type;
            $dataToInsert['form_time'] = $request->form_time;
            $dataToInsert['to_time'] = $request->to_time;
            $dataToInsert['total_huor'] = $request->total_huor;


            $checkExsitsData = get_cols_where_row(new Shifts_type(), array('id'), $dataToInsert);
            if (!empty($checkExsitsData)) {
                return redirect()->back()->with(['error' => 'عفواً هذه البيانات مسجلة مسبقاً'])->withInput();
            }
            $dataToInsert['active'] = $request->active;
            $dataToInsert['added_by'] = auth()->user()->id;

            DB::beginTransaction();
            insert(new Shifts_type(), $dataToInsert);
            DB::commit();
            return redirect()->route('shiftsTypes.index')->with('success','تم اضافة الشفت بنجاح');

        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'حدث خطأ ما' . $ex->getMessage()])->withInput();
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
        $data=get_cols_where_row(new Shifts_type(),array("*"),array('id'=>$id,'com_code'=>auth()->user()->com_code));

        if(empty($data)){
            return redirect()->route('shiftsTypes.index')->with('error','عفواً غير قادر الى الوصول للبيانات المطلوبة'); 
        }

        return view('admin.ShiftsTypes.edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ShiftTypeRequest $request, $id)
    {

     

        try {

            $data=get_cols_where_row(new Shifts_type(),array("*"),array('id'=>$id,'com_code'=>auth()->user()->com_code));

            if(empty($data)){
                return redirect()->route('shiftsTypes.index')->with('error','عفواً غير قادر الى الوصول للبيانات المطلوبة'); 
            }


            $checkExsitsData = Shifts_type::select("id")->where('type',$request->type)
            ->where('form_time',$request->form_time)
            ->where('to_time',$request->to_time)
            ->where('total_huor',$request->total_huor)->where('id','!=',$id)->first();

            if (!empty($checkExsitsData)) {
                return redirect()->back()->with(['error' => 'عفواً هذه البيانات مسجلة مسبقاً'])->withInput();
            }

            DB::beginTransaction();
            $dataToUpdate['type'] = $request->type;
            $dataToUpdate['form_time'] = $request->form_time;
            $dataToUpdate['to_time'] = $request->to_time;
            $dataToUpdate['total_huor'] = $request->total_huor;
            $dataToUpdate['active'] = $request->active;
            $dataToUpdate['updated_by'] = auth()->user()->id;

            update(new Shifts_type(), $dataToUpdate,array('id'=>$id,'com_code'=>auth()->user()->com_code));
            DB::commit();
            return redirect()->route('shiftsTypes.index')->with('success','تم تعديل الشفت بنجاح');

        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'حدث خطأ ما' . $ex->getMessage()])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $com_code=auth()->user()->com_code;
            $data=get_cols_where_row(new Shifts_type(),array("id"),array('id'=>$id,'com_code'=>auth()->user()->com_code));

            if(empty($data)){
                return redirect()->route('shiftsTypes.index')->with('error','عفواً غير قادر الى الوصول للبيانات المطلوبة'); 
            }

            $CounterUse=get_count_where(new Employee(),array("com_code"=>$com_code,"shift_type_id"=>$id));
            if($CounterUse>0){
                return redirect()->route('shiftsTypes.index')->with(['error'=>'عفواً لا يمكن حذف البيانات لانه تم استخدامها سابقاً']); 
            }

            DB::beginTransaction();
            destroy(new Shifts_type(),array('id'=>$id,'com_code'=>auth()->user()->com_code));
           

            DB::commit();

            return redirect()->route('shiftsTypes.index')->with('success','تم حذف الشفت بنجاح');

        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'حدث خطأ ما' . $ex->getMessage()])->withInput();
        }
    }


    public function ajax_search(Request $request) {
        $com_code=auth()->user()->com_code;
        if($request->ajax()){
            $query = Shifts_type::query();
    
            if($request->type_search !== 'all') {
                $query->where('type', '=', $request->type_search);
            }
    
            if(!empty($request->huor_from_range)) {
                $query->where('total_huor', '>=', $request->huor_from_range);
            }
    
            if(!empty($request->huor_to_range)) {
                $query->where('total_huor', '<=', $request->huor_to_range);
            }
    
            $data = $query->orderBy('id', 'DESC')->paginate(PAGINATION_COUNTER);
            if(!empty($data)){
                foreach($data as $info){
                    $info->CounterUse=get_count_where(new Employee(),array("com_code"=>$com_code,"shift_type_id"=>$info->id));
                } 
            }
            return view('admin.ShiftsTypes.ajax_search', compact('data'));
        }
    }
    
}
