@extends('layouts.admin')

@section('title')
    البيانات الشخصية
@endsection

@section('contentheader')
    البروفايل
@endsection

@section('contentheaderactivelink')
    <a href="{{ route('userProfile.index') }}">الصفحة الشخصية</a>
@endsection

@section('contentheaderactive')
    تعديل
@endsection

@section('content')
    <div class="col-12">
        <div class="card">

            <div class="card-header">
                <h3 class="card-title card_title_center">تعديل بيانات الشخصية</h3>
            </div>

            <div class="card-body">

                @if (@isset($data) && !@empty($data))
                    <form action="{{ route('userProfile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>اسم المستخدم كاملا</label>
                                <input readonly type="text" name="name" id="name" class="form-control"
                                    value="{{ old('name', $data['name']) }}">
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>البريد الالكتروني</label>
                                <input type="text" name="email" id="email" class="form-control"
                                    value="{{ old('email', $data['email']) }}">
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>اسم المستخدم للدخول به للنظام</label>
                                <input type="text" name="username" id="username" class="form-control"
                                    value="{{ old('username', $data['username']) }}">
                                @error('username')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>الصورة الشخصية</label>
                                @if (!@empty($data['image']))
                                    <img src="{{ asset('assets/admin/uploads') . '/' . $data['image'] }}"
                                        style="border-radius: 50%; width: 80px; height: 80px;" class="rounded-circle"
                                        alt="صورة الموظف">
                                    
                                @endif
                                <input autofocus type="file" name="image_edit" id="image_edit" class="form-control">

                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>هل تريد تحديث كلمة المرور</label>
                                <select name="checkForUpdatePassword" id="checkForUpdatePassword" class="form-control">
                                    <option
                                        {{ old('checkForUpdatePassword', $data['checkForUpdatePassword']) == 1 ? 'selected' : '' }}
                                        value="0">لا</option>
                                    <option
                                        {{ old('checkForUpdatePassword', $data['checkForUpdatePassword']) == 0 ? 'selected' : '' }}
                                        value="1">نعم</option>
                                </select>
                                @error('checkForUpdatePassword')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>


                        <div class="col-md-12">
                            <div class="form-group" id="PasswordDIV"
                                @if (old('checkForUpdatePassword') == 0) style="display: none" @endif>
                                <label>كلمة المرور للدخول به للنظام</label>
                                <input type="password" name="password" id="password" class="form-control" value=""
                                    oninvalid="setCustomeValidity('يرجى ادخال هذا الحقل')">
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>





                        <div class="col-md-12">
                            <div class="form-group text-center">
                                <button class="btn btn-sm btn-primary" type="submit" name="submit">حفظ التغيرات</button>
                                <a href="{{ route('userProfile.index') }}" class="btn btn-sm btn-danger">الغاء</a>
                            </div>
                        </div>

                    </form>
                @endif


            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).on('change', '#checkForUpdatePassword', function(e) {
            if ($(this).val() == 1) {
                $("#PasswordDIV").show();
            } else {
                $("#PasswordDIV").hide();
            }
        });
    </script>
@endsection
