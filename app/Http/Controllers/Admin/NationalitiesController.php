<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\NationalitiesRequest;
use App\Models\Nationalitie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NationalitiesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $com_code = auth()->user()->com_code;
        $Nationalities = get_cols_where_paginate(new Nationalitie(), array('*'), array('com_code' => $com_code), 'id', 'DESC', PAGINATION_COUNTER);
        return view('admin.Nationalities.index', compact('Nationalities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.Nationalities.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(NationalitiesRequest $request)
    {
        try {
            $com_code = auth()->user()->com_code;
            $checkExsists = get_cols_where_row(new Nationalitie(), array('id'), array('com_code' => $com_code, 'name' => $request->name));
            if (!empty($checkExsists)) {
                return redirect()->back()->with('error', 'عفواً هذه الجنسية مسجلة من قبل')->withInput();
            }

            DB::beginTransaction();

            $dataToInsert = [
                'name' => $request->name,
                'active' => $request->active,
                'added_by' => auth()->user()->id,
                'com_code' => $com_code
            ];

            insert(new Nationalitie(), $dataToInsert);
            DB::commit();
            return redirect()->route('Nationalities.index')->with('success', 'تم اضافة الجنسية بنجاح');
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
        $data = get_cols_where_row(new Nationalitie(), array('*'), array('com_code' => $com_code, 'id' => $id));
        if (empty($data)) {
            return redirect()->route('Nationalities.index')->with('error', 'عفواً غير قادر الوصول الى البيانات');
        }
        return view('admin.Nationalities.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(NationalitiesRequest $request, $id)
    {
        try {
            $com_code = auth()->user()->com_code;
            $checkExsists = Nationalitie::select('id')->where('com_code', $com_code)->where('id', '!=', $id)->where('name', $request->name)->first();
            if (!empty($checkExsists)) {
                return redirect()->back()->with('error', 'عفواً هذه الجنسية مسجلة من قبل')->withInput();
            }

            $data = get_cols_where_row(new Nationalitie(), array('*'), array('com_code' => $com_code, 'id' => $id));
            if (empty($data)) {
                return redirect()->route('Nationalities.index')->with('error', 'عفواً غير قادر الوصول الى البيانات');
            }

            DB::beginTransaction();

            $dataToUpdate = [
                'name' => $request->name,
                'active' => $request->active,
                'updated_by' => auth()->user()->id,
            ];

            update(new Nationalitie(), $dataToUpdate, array('com_code' => $com_code, 'id' => $id));
            DB::commit();
            return redirect()->route('Nationalities.index')->with('success', 'تم تعديل الجنسية بنجاح');
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
            $data = get_cols_where_row(new Nationalitie(), array('*'), array('com_code' => $com_code, 'id' => $id));
            if (empty($data)) {
                return redirect()->route('Nationalities.index')->with('error', 'عفواً غير قادر الوصول الى البيانات');
            }

            DB::beginTransaction();

            destroy(new Nationalitie(), array('com_code' => $com_code, 'id' => $id));
            DB::commit();
            return redirect()->route('Nationalities.index')->with('success', 'تم حذف الجنسية بنجاح');
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('error', 'عفواً حدث خطأ')->withInput();
        }
    }
}
