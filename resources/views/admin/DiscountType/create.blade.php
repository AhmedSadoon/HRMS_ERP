@extends('layouts.admin')

@section('title')
انواع الخصم
@endsection

@section('contentheader')
    قائمة الموظفين
@endsection

@section('contentheaderactivelink')
    <a href="{{ route('DiscountType.index') }}">انواع الخصم الراتب</a>
@endsection

@section('contentheaderactive')
    اضافة
@endsection

@section('content')
    <div class="col-12">
        <div class="card">

            <div class="card-header">
                <h3 class="card-title card_title_center">اضافة انواع الخصم الراتب</h3>
            </div>

            <div class="card-body">

             <form action="{{route('DiscountType.store')}}" method="POST">
                @csrf
                
                <div class="col-md-12">
                    <div class="form-group">
                        <label>اسم النوع</label>
                        <input type="text" name="name" id="name" class="form-control"
                            value="{{ old('name') }}">
                        @error('name')
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
                       <button class="btn btn-sm btn-success" type="submit" name="submit">اضف الخصم</button>
                       <a href="{{route('DiscountType.index')}}" class="btn btn-sm btn-danger">الغاء</a>
                    </div>
                </div>

             </form>


            </div>
        </div>
    </div>

@endsection
