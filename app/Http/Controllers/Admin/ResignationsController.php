<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResignationsRequest;
use App\Models\Employee;
use App\Models\Resignation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResignationsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $com_code = auth()->user()->com_code;
        $resignations = get_cols_where_paginate(new Resignation(), array('*'), array('com_code' => $com_code), 'id', 'DESC', PAGINATION_COUNTER);
        if (!empty($resignations)) {
            foreach ($resignations as $info) {
                $info->CounterUse = get_count_where(new Employee(), array("com_code" => $com_code, "resignation_id" => $info->id));
            }
        }
        return view('admin.Resignations.index', compact('resignations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.Resignations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ResignationsRequest $request)
    {
        try {
            $com_code = auth()->user()->com_code;
            $checkExsists = get_cols_where_row(new Resignation(), array('id'), array('com_code' => $com_code, 'name' => $request->name));
            if (!empty($checkExsists)) {
                return redirect()->back()->with('error', 'هذا النوع مسجل مسبقاً')->withInput();
            }
            DB::beginTransaction();
            $dataToInset = [
                'name' => $request->name,
                'active' => $request->active,
                'com_code' => $com_code,
                'added_by' => auth()->user()->id
            ];

            insert(new Resignation(), $dataToInset);
            DB::commit();
            return redirect()->route('Resignations.index')->with('success', 'تم اضافة النوع بنجاح');
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('error', 'عفوا حدث خطأ' . $ex->getMessage())->withInput();
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

        try {
            $com_code = auth()->user()->com_code;
            $data = get_cols_where_row(new Resignation(), array('*'), array('com_code' => $com_code, 'id' => $id));
            if (empty($data)) {
                return redirect()->route('Resignations.index')->with('error', 'عفواً غير قادر للوصول الى البيانات المطلوبة');
            }

            return view('admin.Resignations.edit', compact('data'));
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('error', 'عفوا حدث خطأ' . $ex->getMessage())->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ResignationsRequest $request, $id)
    {
        try {
            $com_code = auth()->user()->com_code;

            $checkExsists = Resignation::select('id')->where('com_code', $com_code)->where('name', $request->name)->where('id', '!=', $id)->first();

            if (!empty($checkExsists)) {
                return redirect()->back()->with('error', 'هذا النوع مسجل مسبقاً')->withInput();
            }

            $data = get_cols_where_row(new Resignation(), array('*'), array('com_code' => $com_code, 'id' => $id));
            if (empty($data)) {
                return redirect()->route('Resignations.index')->with('error', 'عفواً غير قادر للوصول الى البيانات المطلوبة');
            }

            DB::beginTransaction();

            $dataToUpdate = [
                'name' => $request->name,
                'active' => $request->active,
                'updated_by' => auth()->user()->id
            ];

            update(new Resignation(), $dataToUpdate, array('com_code' => $com_code, 'id' => $id));

            DB::commit();
            return redirect()->route('Resignations.index')->with('success', 'تم تعديل البيانات بنجاح');
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('error', 'عفوا حدث خطأ' . $ex->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $com_code = auth()->user()->com_code;
            $data = get_cols_where_row(new Resignation(), array('id'), array('com_code' => $com_code, 'id' => $id));
            if (empty($data)) {
                return redirect()->back()->with('error', 'هذا النوع مسجل مسبقاً')->withInput();
            }


            $CounterUse = get_count_where(new Employee(), array("com_code" => $com_code, "resignation_id" => $id));
            if ($CounterUse > 0) {
                return redirect()->route('Resignations.index')->with(['error' => 'عفواً لا يمكن حذف البيانات لانه تم استخدامها سابقاً']);
            }

            DB::beginTransaction();

            destroy(new Resignation(), array('com_code' => $com_code, 'id' => $id));
            DB::commit();
            return redirect()->route('Resignations.index')->with('success', 'تم حذف النوع بنجاح');
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->route('Resignations.index')->with('error', 'عفوا حدث خطأ' . $ex->getMessage());
        }
    }
}
