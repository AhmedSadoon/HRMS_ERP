<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Finance_calenderRequest;
use App\Http\Requests\Finance_calenderUpdateRequest;
use App\Models\Finance_calender;
use App\Models\Finance_cin_periods;
use App\Models\Monthes;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class Finance_calenderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $com_code=auth()->user()->com_code;
        //$Finance_calender=Finance_calender::select('*')->orderBy('finance_yr','DESC')->paginate(PAGINATION_COUNTER);
        $Finance_calender=get_cols_where_paginate(new Finance_calender(),array('*'),array('com_code'=>$com_code),'id','DESC',PAGINATION_COUNTER);
        $checkDataOpenCounter=Finance_calender::where('open_yr_flag',1)->count();
        return view('admin.Finance_calender.index',compact('Finance_calender','checkDataOpenCounter'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.Finance_calender.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Finance_calenderRequest $request)
    {
        try {
            
            DB::beginTransaction();
            $dataToInsert['finance_yr']=$request->finance_yr;
            $dataToInsert['finance_yr_desc']=$request->finance_yr_desc;
            $dataToInsert['start_date']=$request->start_date;
            $dataToInsert['end_date']=$request->end_date;            
            $dataToInsert['added_by']=auth()->user()->id;
            $dataToInsert['com_code']=auth()->user()->com_code;
            $finance_calender=Finance_calender::insert($dataToInsert);

            if($finance_calender){
                $dateParent=Finance_calender::select("id")->where($dataToInsert)->first();
                
                $startDate=new DateTime($request->start_date);
                $endDate=new DateTime($request->end_date);
                $dateInterval=new DateInterval('P1M');
                $datePerioud=new DatePeriod($startDate,$dateInterval,$endDate);

                foreach($datePerioud as $date){
                    $dataMonth['finance_calenders_id']=$dateParent['id'];
                    $MonthName_en=$date->format('F');
                    $dateParentMonthes=Monthes::select("id")->where(['name_en'=>$MonthName_en])->first();
                    $dataMonth['month_id']=$dateParentMonthes['id'];
                    $dataMonth['finance_yr']=$dataToInsert['finance_yr'];
                    $dataMonth['start_date_m']=date('Y-m-01',strtotime($date->format('Y-m-d')));
                    $dataMonth['end_date_m']=date('Y-m-t',strtotime($date->format('Y-m-d')));
                    $dataMonth['year_and_month']=date('Y-m',strtotime($date->format('Y-m-d')));
                    $datediff=strtotime( $dataMonth['end_date_m'])-strtotime( $dataMonth['start_date_m']);
                    $dataMonth['number_of_dats']=round($datediff/(60*60*24))+1;
                    $dataMonth['com_code']=auth()->user()->com_code;
                    $dataMonth['created_at']=date('Y-m-d H:i:s');
                    $dataMonth['updated_at']=date('Y-m-d H:i:s');
                    $dataMonth['updated_by']=auth()->user()->id;
                    $dataMonth['added_by']=auth()->user()->id;

                    $dataMonth['start_date_for_pasma']=date('Y-m-01',strtotime($date->format('Y-m-d')));
                    $dataMonth['end_date_for_pasma']=date('Y-m-t',strtotime($date->format('Y-m-d')));

                    Finance_cin_periods::insert($dataMonth);
                    
                }
            }
            DB::commit();
            return redirect()->route('finance_calender.index')->with(['success'=>'تم ادخال البيانات بنجاح']);
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
     
            $data=Finance_calender::select("*")->where('id',$id)->first();
            if(empty($data)){
                return redirect()->back()->with(['error'=>'عفواً حدث خطأ']);
            }

            if($data['open_yr_flag']!=0){
                return redirect()->back()->with(['error'=>'عفواً لا يمكن حذف سنة مالية في هذه الحالية']);
            }
            return view('admin.Finance_calender.update',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id,Finance_calenderUpdateRequest $request)
    {
        try {

            $data=Finance_calender::select("*")->where('id',$id)->first();
            
            
            if(empty($data)){
                return redirect()->back()->with(['error'=>'عفواً حدث خطأ']);
            }

            if($data['open_yr_flag']!=0){

                return redirect()->back()->with(['error'=>'عفواً لا يمكن حذف سنة مالية في هذه الحالية'])->withInput();
            }

            $validator=Validator::make($request->all(),[
                'finance_yr'=>['required',Rule::unique('finance_calenders')->ignore($id)],
            ]);

            if($validator->fails()){
                return redirect()->back()->with(['error'=>'عفواً السنة المالية مسجلة من قبل'])->withInput();

            }

            DB::beginTransaction();
          
            $dataToUpdate['finance_yr']=$request->finance_yr;
            $dataToUpdate['finance_yr_desc']=$request->finance_yr_desc;
            $dataToUpdate['start_date']=$request->start_date;
            $dataToUpdate['end_date']=$request->end_date;            
            $dataToUpdate['updated_by']=auth()->user()->id;
            $finance_calender=Finance_calender::where('id',$id)->update($dataToUpdate);

            if($finance_calender){
                if($data['start_date']!=$request->start_date || $data['end_date']!=$request->end_date){

                    $flagDelete=Finance_cin_periods::where(['finance_calenders_id'=>$id])->delete();
                    if($flagDelete){

                        $startDate=new DateTime($request->start_date);
                        $endDate=new DateTime($request->end_date);
                        $dateInterval=new DateInterval('P1M');
                        $datePerioud=new DatePeriod($startDate,$dateInterval,$endDate);
        
                        foreach($datePerioud as $date){
                            $dataMonth['finance_calenders_id']=$id;
                            $MonthName_en=$date->format('F');
                            $dateParentMonthes=Monthes::select("id")->where(['name_en'=>$MonthName_en])->first();
                            $dataMonth['month_id']=$dateParentMonthes['id'];
                            $dataMonth['finance_yr']=$dataToUpdate['finance_yr'];
                            $dataMonth['start_date_m']=date('Y-m-01',strtotime($date->format('Y-m-d')));
                            $dataMonth['end_date_m']=date('Y-m-t',strtotime($date->format('Y-m-d')));
                            $dataMonth['year_and_month']=date('Y-m',strtotime($date->format('Y-m-d')));
                            $datediff=strtotime( $dataMonth['end_date_m'])-strtotime( $dataMonth['start_date_m']);
                            $dataMonth['number_of_dats']=round($datediff/(60*60*24))+1;
                            $dataMonth['com_code']=auth()->user()->com_code;
                            $dataMonth['created_at']=date('Y-m-d H:i:s');
                            $dataMonth['updated_at']=date('Y-m-d H:i:s');
                            $dataMonth['updated_by']=auth()->user()->id;
                            $dataMonth['added_by']=auth()->user()->id;
        
                            $dataMonth['start_date_for_pasma']=date('Y-m-01',strtotime($date->format('Y-m-d')));
                            $dataMonth['end_date_for_pasma']=date('Y-m-t',strtotime($date->format('Y-m-d')));
        
                            Finance_cin_periods::insert($dataMonth);
                    }
                }
            }
        }
        DB::commit();
        return redirect()->route('finance_calender.index')->with(['success'=>'تم تحديث البيانات بنجاح']);

        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error'=>'عفواً حدث خطأ'.$ex->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $data=Finance_calender::select("*")->where('id',$id)->first();
            if(empty($data)){
                return redirect()->back()->with(['error'=>'عفواً حدث خطأ']);
            }

            if($data['open_yr_flag']!=0){
                return redirect()->back()->with(['error'=>'عفواً لا يمكن حذف سنة مالية في هذه الحالية']);

            }

            Finance_calender::where('id',$id)->delete();

            return redirect()->route('finance_calender.index')->with(['success'=>'تم حذف البيانات بنجاح']);


        } catch (\Exception $ex) {
            return redirect()->back()->with(['error'=>'عفواً حدث خطأ'.$ex->getMessage()]);
        }
    }

 function show_year_monthes(Request $request) {
        if($request->ajax()){
            $finance_cin_periods=Finance_cin_periods::select("*")->where(['finance_calenders_id'=>$request->id])->get();
            return view('admin.Finance_calender.show_year_monthes',compact('finance_cin_periods'));
        }
    }

    public function do_open($id) {
        try {
            $data=Finance_calender::select("*")->where('id',$id)->first();
            if(empty($data)){
                return redirect()->back()->with(['error'=>'عفواً حدث خطأ']);
            }

            if($data['open_yr_flag']!=0){
                return redirect()->back()->with(['error'=>'عفواً لا يمكن فتح السنة مالية في هذه الحالية']);

            }

            $checkDataOpenCounter=Finance_calender::where('open_yr_flag',1)->count();
            if($checkDataOpenCounter){
                return redirect()->back()->with(['error'=>'عفواً هناك سنة مالية مفتوحة']);
            }

            $dataToUpdate['open_yr_flag']=1;
            $dataToUpdate['updated_by']=auth()->user()->id;
            $flag=Finance_calender::where('id',$id)->update($dataToUpdate);

            return redirect()->route('finance_calender.index')->with(['success'=>'تم فتح السنة المالية بنجاح']);


        } catch (\Exception $ex) {
            return redirect()->back()->with(['error'=>'عفواً حدث خطأ'.$ex->getMessage()]);
        }
    }
}
