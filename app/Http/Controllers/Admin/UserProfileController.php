<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserProfileRequest;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserProfileController extends Controller
{
    public function index() {
        $com_code=auth()->user()->com_code;
        $data=get_cols_where_row(new Admin(),array("*"),array('com_code'=>$com_code,'id'=>auth()->user()->id));
        return view('admin.user_profile.index',['data'=>$data]);
    }

    public function edit() {
        $com_code=auth()->user()->com_code;
        $data=get_cols_where_row(new Admin(),array("*"),array('com_code'=>$com_code,'id'=>auth()->user()->id));
        return view('admin.user_profile.edit',['data'=>$data]);
    }

    public function update(UserProfileRequest $request) {
        try {
            $com_code=auth()->user()->com_code;
        $data=get_cols_where_row(new Admin(),array("*"),array('com_code'=>$com_code,'id'=>auth()->user()->id));

        if(empty($data)){
            return redirect()->route('userProfile.index')->with(['error'=>'غير قادر للوصول الى البيانات']);
        }

        $checkExists_email=Admin::where(['email'=>$request->email,'com_code'=>$com_code])->where('id','!=',auth()->user()->id)->first();
        if(!empty($checkExists_email)){
            return redirect()->back()->with(['error'=>'عفواً البريد الالكتروني للمستخدم مسجل من قبل'])->withInput();
        }

        $checkExists_username=Admin::where(['username'=>$request->username,'com_code'=>$com_code])->where('id','!=',auth()->user()->id)->first();
        if(!empty($checkExists_username)){
            return redirect()->back()->with(['error'=>'عفواً اسم المستخدم مسجل من قبل'])->withInput();
        }

        DB::beginTransaction();
        $dataToUpdate['username']=$request->username;
        $dataToUpdate['email']=$request->email;
        if($request->checkForUpdatePassword==1){
            $dataToUpdate['password']=bcrypt($request->password);

        }

        $dataToUpdate['updated_by']=auth()->user()->id;
        $dataToUpdate['updated_at']=date('Y-m-d H:i:s');

        if ($request->has('image_edit') and !empty($request->image_edit)) {
            $request->validate([
                'image_edit' => 'required|mimes:png,jpg,jpeg|max:2000'
            ]);

            $the_file_path = uploadImage('assets/admin/uploads', $request->image_edit);
            $dataToUpdate['image'] = $the_file_path;

            if (file_exists('assets/admin/uploads/' . $data['image']) && !empty($data['image'])) {
                unlink('assets/admin/uploads/' . $data['image']);
            }
        }

        DB::commit();
        Admin::where(['id'=>auth()->user()->id,'com_code'=>$com_code])->update($dataToUpdate);
        
        return redirect()->route('userProfile.index')->with(['success'=>'تم تحديث البيانات بنجاح']);
        
    } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['error'=>'عفواً حدث خطأ'. $e->getMessage()])->withInput();

        }
    }


}
