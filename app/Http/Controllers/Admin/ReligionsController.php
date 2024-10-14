<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReligionsRequest;
use App\Models\Employee;
use App\Models\Religion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReligionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $com_code = auth()->user()->com_code;
        $Religions = get_cols_where_paginate(new Religion(), array("*"), array('com_code' => $com_code), 'id', 'DESC', PAGINATION_COUNTER);
        if (!empty($Religions)) {
            foreach ($Religions as $info) {
                $info->CounterUse = get_count_where(new Employee(), array("com_code" => $com_code, "religion_id" => $info->id));
            }
        }
        return view('admin.Religions.index', compact('Religions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.Religions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ReligionsRequest $request)
    {
        try {
            $com_code = auth()->user()->com_code;
            $checkExsists = get_cols_where_row(new Religion(), array('id'), array('com_code' => $com_code, 'name' => $request->name));
            if (!empty($checkExsists)) {
                return redirect()->back()->with('error', 'عفواً هذا الاسم مسجل مسبقاً');
            }
            DB::beginTransaction();
            $dataToInsert = [
                'name' => $request->name,
                'active' => $request->active,
                'added_by' => auth()->user()->id,
                'com_code' => $com_code
            ];

            insert(new Religion(), $dataToInsert);
            DB::commit();
            return redirect()->route('Religions.index')->with('success', 'تم اضافة الديانة بنجاح');
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'عفواً حدث خطأ' . $ex->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $com_code = auth()->user()->com_code;
            $data = get_cols_where_row(new Religion(), array('*'), array('com_code' => $com_code, 'id' => $id));
            if (empty($data)) {
                return redirect()->route('Religions.index')->with('error', 'عفواً غير قادر على الوصول الى البيانات');
            }

            return view('admin.Religions.edit', compact('data'));
        } catch (\Exception $ex) {
            return redirect()->back()->with(['error' => 'عفواً حدث خطأ' . $ex->getMessage()])->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ReligionsRequest $request, $id)
    {
        try {
            $com_code = auth()->user()->com_code;
            $data = get_cols_where_row(new Religion(), array('*'), array('com_code' => $com_code, 'id' => $id));
            if (empty($data)) {
                return redirect()->route('Religions.index')->with('error', 'عفواً غير قادر على الوصول الى البيانات');
            }

            $checkexsists = Religion::select('id')->where('com_code', $com_code)->where('id', '!=', $id)->where('name', $request->name)->first();
            if (!empty($checkexsists)) {
                return redirect()->back()->with('error', 'عفواً هذا الاسم مسجل مسبقاً')->withInput();
            }

            DB::beginTransaction();
            $dataToUpdate = [
                'name' => $request->name,
                'active' => $request->active,
                'updated_by' => auth()->user()->id
            ];

            update(new Religion(), $dataToUpdate, array('com_code' => $com_code, 'id' => $id));

            DB::commit();

            return redirect()->route('Religions.index')->with('success', 'تم تعديل الديانة بنجاح');
        } catch (\Exception $ex) {
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
            $data = get_cols_where_row(new Religion(), array('*'), array('com_code' => $com_code, 'id' => $id));
            if (empty($data)) {
                return redirect()->route('Religions.index')->with('error', 'عفواً غير قادر على الوصول الى البيانات');
            }


            $CounterUse = get_count_where(new Employee(), array("com_code" => $com_code, "religion_id" => $id));
            if ($CounterUse > 0) {
                return redirect()->route('Religions.index')->with(['error' => 'عفواً لا يمكن حذف البيانات لانه تم استخدامها سابقاً']);
            }
            DB::beginTransaction();
            destroy(new Religion(), array('com_code' => $com_code, 'id' => $id));

            DB::commit();

            return redirect()->route('Religions.index')->with('success', 'تم حذف الديانة بنجاح');
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'عفواً حدث خطأ' . $ex->getMessage()])->withInput();
        }
    }
}
