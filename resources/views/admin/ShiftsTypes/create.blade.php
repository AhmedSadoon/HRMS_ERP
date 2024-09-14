@extends('layouts.admin')

@section('title')
الشفتات
@endsection

@section('contentheader')
    قائمة الضبط
@endsection

@section('contentheaderactivelink')
    <a href="{{ route('shiftsTypes.index') }}">الشفتات</a>
@endsection

@section('contentheaderactive')
    اضافة
@endsection

@section('content')
    <div class="col-12">
        <div class="card">

            <div class="card-header">
                <h3 class="card-title card_title_center">اضافة شفت جديد</h3>
            </div>

            <div class="card-body">

             <form action="{{route('shiftsTypes.store')}}" method="POST">
                @csrf

                <div class="col-md-12">
                    <div class="form-group">
                        <label>نوع الشفت</label>
                        <select name="type" id="type" class="form-control">
                            <option selected value="">اختر النوع</option>
                            <option @if (old('type')==1) selected @endif value="1">صباحي</option>
                            <option @if (old('type')==2) selected @endif value="2">مسائي</option>
                            <option @if (old('type')==3) selected @endif value="3">يوم كامل</option>
                        </select>
                        @error('type')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
             
                <div class="col-md-12">
                    <div class="form-group">
                        <label>يبدأ من الساعة</label>
                        <input type="time" name="form_time" id="form_time" class="form-control"
                            value="{{ old('form_time') }}">
                        @error('form_time')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label>ينتهي الساعة</label>
                        <input type="time" name="to_time" id="to_time" class="form-control"
                            value="{{ old('to_time') }}">
                        @error('to_time')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label>عدد الساعات</label>
                        <input type="text" name="total_huor" id="total_huor" class="form-control"
                            value="{{ old('total_huor') }}" oninput="this.value=this.value.replace(/[^0-9.]/g,'');">
                        @error('total_huor')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

               

                <div class="col-md-12">
                    <div class="form-group">
                        <label>حالة التفعيل</label>
                        <select name="active" id="active" class="form-control">
                            <option @if (old('active')==1) selected @endif value="1">مفعل</option>
                            <option @if (old('active')==0 and old('active')!='') selected @endif value="0">مغلق</option>
                        </select>
                        @error('active')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group text-center">
                       <button class="btn btn-sm btn-success" type="submit" name="submit">اضف الشفت</button>
                       <a href="{{route('shiftsTypes.index')}}" class="btn btn-sm btn-danger">الغاء</a>
                    </div>
                </div>

             </form>


            </div>
        </div>
    </div>

@endsection
