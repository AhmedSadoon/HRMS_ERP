<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AllowanceRequest;
use App\Models\Allowance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AllowanceController extends Controller
{
    public function index()
    {
        $com_code = auth()->user()->com_code;
        $Allowances = get_cols_where_paginate(new Allowance(), array('*'), array('com_code' => $com_code), 'id', 'DESC', PAGINATION_COUNTER);
        return view('admin.Allowances.index', compact('Allowances'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.Allowances.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AllowanceRequest $request)
    {
        try {
            $com_code = auth()->user()->com_code;
            $checkExsists = get_cols_where_row(new Allowance(), array('id'), array('com_code' => $com_code, 'name' => $request->name));
            if (!empty($checkExsists)) {
                return redirect()->back()->with('error', 'عفواً هذه النوع مسجل من قبل')->withInput();
            }

            DB::beginTransaction();

            $dataToInsert = [
                'name' => $request->name,
                'active' => $request->active,
                'added_by' => auth()->user()->id,
                'com_code' => $com_code
            ];

            insert(new Allowance(), $dataToInsert);
            DB::commit();
            return redirect()->route('Allowances.index')->with('success', 'تم اضافة النوع بنجاح');
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('error', 'عفواً حدث خطأ')->withInput();
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
        $data = get_cols_where_row(new Allowance(), array('*'), array('com_code' => $com_code, 'id' => $id));
        if (empty($data)) {
            return redirect()->route('Allowances.index')->with('error', 'عفواً غير قادر الوصول الى البيانات');
        }
        return view('admin.Allowances.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AllowanceRequest $request, $id)
    {
        try {
            $com_code = auth()->user()->com_code;
            $checkExsists = Allowance::select('id')->where('com_code', $com_code)->where('id', '!=', $id)->where('name', $request->name)->first();
            if (!empty($checkExsists)) {
                return redirect()->back()->with('error', 'عفواً هذه النوع مسجل من قبل')->withInput();
            }

            $data = get_cols_where_row(new Allowance(), array('*'), array('com_code' => $com_code, 'id' => $id));
            if (empty($data)) {
                return redirect()->route('Allowances.index')->with('error', 'عفواً غير قادر الوصول الى البيانات');
            }

            DB::beginTransaction();

            $dataToUpdate = [
                'name' => $request->name,
                'active' => $request->active,
                'updated_by' => auth()->user()->id,
            ];

            update(new Allowance(), $dataToUpdate, array('com_code' => $com_code, 'id' => $id));
            DB::commit();
            return redirect()->route('Allowances.index')->with('success', 'تم تعديل النوع بنجاح');
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('error', 'عفواً حدث خطأ')->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $com_code = auth()->user()->com_code;
            $data = get_cols_where_row(new Allowance(), array('*'), array('com_code' => $com_code, 'id' => $id));
            if (empty($data)) {
                return redirect()->route('Allowances.index')->with('error', 'عفواً غير قادر الوصول الى البيانات');
            }

            DB::beginTransaction();

            destroy(new Allowance(), array('com_code' => $com_code, 'id' => $id));
            DB::commit();
            return redirect()->route('Allowances.index')->with('success', 'تم حذف النوع بنجاح');
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('error', 'عفواً حدث خطأ')->withInput();
        }
    }
}
