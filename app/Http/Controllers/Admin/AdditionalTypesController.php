<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdditionalTypeRequest;
use App\Models\Additional_type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class additionalTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $com_code = auth()->user()->com_code;
        $Additional_types = get_cols_where_paginate(new Additional_type(), array('*'), array('com_code' => $com_code), 'id', 'DESC', PAGINATION_COUNTER);
        return view('admin.Additional_Types.index', compact('Additional_types'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.Additional_Types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdditionalTypeRequest $request)
    {
        try {
            $com_code = auth()->user()->com_code;
            $checkExsists = get_cols_where_row(new Additional_type(), array('id'), array('com_code' => $com_code, 'name' => $request->name));
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

            insert(new Additional_type(), $dataToInsert);
            DB::commit();
            return redirect()->route('additionalTypes.index')->with('success', 'تم اضافة النوع بنجاح');
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
        $data = get_cols_where_row(new Additional_type(), array('*'), array('com_code' => $com_code, 'id' => $id));
        if (empty($data)) {
            return redirect()->route('Additional_Types.index')->with('error', 'عفواً غير قادر الوصول الى البيانات');
        }
        return view('admin.Additional_Types.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AdditionalTypeRequest $request, $id)
    {
        try {
            $com_code = auth()->user()->com_code;
            $checkExsists = Additional_type::select('id')->where('com_code', $com_code)->where('id', '!=', $id)->where('name', $request->name)->first();
            if (!empty($checkExsists)) {
                return redirect()->back()->with('error', 'عفواً هذه النوع مسجل من قبل')->withInput();
            }

            $data = get_cols_where_row(new Additional_type(), array('*'), array('com_code' => $com_code, 'id' => $id));
            if (empty($data)) {
                return redirect()->route('additionalTypes.index')->with('error', 'عفواً غير قادر الوصول الى البيانات');
            }

            DB::beginTransaction();

            $dataToUpdate = [
                'name' => $request->name,
                'active' => $request->active,
                'updated_by' => auth()->user()->id,
            ];

            update(new Additional_type(), $dataToUpdate, array('com_code' => $com_code, 'id' => $id));
            DB::commit();
            return redirect()->route('additionalTypes.index')->with('success', 'تم تعديل النوع بنجاح');
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
            $data = get_cols_where_row(new Additional_type(), array('*'), array('com_code' => $com_code, 'id' => $id));
            if (empty($data)) {
                return redirect()->route('additionalTypes.index')->with('error', 'عفواً غير قادر الوصول الى البيانات');
            }

            DB::beginTransaction();

            destroy(new Additional_type(), array('com_code' => $com_code, 'id' => $id));
            DB::commit();
            return redirect()->route('additionalTypes.index')->with('success', 'تم حذف النوع بنجاح');
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('error', 'عفواً حدث خطأ')->withInput();
        }
    }
}
