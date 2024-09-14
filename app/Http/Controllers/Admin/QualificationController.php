<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\QualificationsRequest;
use App\Models\Qualification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QualificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $com_code = auth()->user()->com_code;
        $Qualifications = get_cols_where_paginate(new Qualification(), array("*"), array('com_code' => $com_code), 'id', 'DESC', PAGINATION_COUNTER);
        return view('admin.Qualification.index', compact('Qualifications'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.Qualification.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(QualificationsRequest $request)
    {
        try {
            $com_code = auth()->user()->com_code;
            $checkExsists = get_cols_where_row(new Qualification(), array("*"), array('com_code' => $com_code, 'name' => $request->name));
            if (!empty($checkExsists)) {
                return redirect()->back()->with('error', 'عفواً هذا المؤهل مسجل من قبل')->withInput();
            }
            DB::beginTransaction();
            $dataToInsert = [
                'name' => $request->name,
                'active' => $request->active,
                'added_by' => auth()->user()->id,
                'com_code' => $com_code
            ];

            insert(new Qualification(), $dataToInsert);
            DB::commit();
            return redirect()->route('Qualifications.index')->with('success', 'تم اضافة المؤهل بنجاح');
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'عفواً حدث خطأ' . $ex->getMessage()])->withInput();
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
        $data = get_cols_where_row(new Qualification(), array("*"), array('com_code' => $com_code, 'id' => $id));
        if (empty($data)) {
            return redirect()->back()->with('error', "عفوا غير قادر للوصول الى البيانات")->withInput();
        }

        return view('admin.Qualification.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(QualificationsRequest $request, $id)
    {


        try {
            $com_code = auth()->user()->com_code;
            $data = get_cols_where_row(new Qualification(), array("*"), array('com_code' => $com_code, 'id' => $id));
            if (empty($data)) {
                return redirect()->back()->with('error', "عفوا غير قادر للوصول الى البيانات")->withInput();
            }

            $checkExsists = Qualification::select('id')->where('com_code', $com_code)->where('name', $request->name)->where('id', '!=', $id)->first();
            if (!empty($checkExsists)) {
                return redirect()->back()->with('error', 'عفواً هذا المؤهل مسجل من قبل')->withInput();
            }
            DB::beginTransaction();
            $dataToupdate = [
                'name' => $request->name,
                'active' => $request->active,
                'updated_by' => auth()->user()->id,

            ];

            update(new Qualification(), $dataToupdate, array('com_code' => $com_code, 'id' => $id));
            DB::commit();
            return redirect()->route('Qualifications.index')->with('success', 'تم تعديل المؤهل بنجاح');
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
            $data = get_cols_where_row(new Qualification(), array("*"), array('com_code' => $com_code, 'id' => $id));
            if (empty($data)) {
                return redirect()->back()->with('error', "عفوا غير قادر للوصول الى البيانات")->withInput();
            }

           
            DB::beginTransaction();
           
            destroy(new Qualification(), array('com_code' => $com_code, 'id' => $id));
            
            DB::commit();
            return redirect()->route('Qualifications.index')->with('success', 'تم حذف المؤهل بنجاح');
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'عفواً حدث خطأ' . $ex->getMessage()]);
        }
    }
}
