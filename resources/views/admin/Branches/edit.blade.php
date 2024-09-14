@extends('layouts.admin')

@section('title')
الفروع
@endsection

@section('contentheader')
    قائمة الضبط
@endsection

@section('contentheaderactivelink')
    <a href="{{ route('branches.index') }}">فروع الشركة</a>
@endsection

@section('contentheaderactive')
تعديل
@endsection

@section('content')
    <div class="col-12">
        <div class="card">

            <div class="card-header">
                <h3 class="card-title card_title_center">تعديل الفرع </h3>
            </div>

            <div class="card-body">

             <form action="{{route('branches.update',$data['id'])}}" method="POST">
                @csrf

             
                <div class="col-md-12">
                    <div class="form-group">
                        <label>اسم الفرع</label>
                        <input type="text" name="name" id="name" class="form-control"
                            value="{{ old('name',$data['name']) }}">
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label>العنوان</label>
                        <input type="text" name="address" id="address" class="form-control"
                            value="{{ old('address',$data['address']) }}">
                        @error('address')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label>الهاتف</label>
                        <input type="text" name="phones" id="phones" class="form-control"
                            value="{{ old('phones',$data['phones']) }}">
                        @error('phones')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label>الايميل</label>
                        <input type="text" name="email" id="email" class="form-control"
                            value="{{ old('email',$data['email']) }}">
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label>حالة التفعيل</label>
                        <select name="active" id="active" class="form-control">
                            <option {{old('active',$data['active'])==1 ? 'selected' : ''}} value="1">مفعل</option>
                            <option {{old('active',$data['active'])==0 ? 'selected' : ''}} value="0">مغلق</option>
                        </select>
                        @error('active')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group text-center">
                       <button class="btn btn-sm btn-success" type="submit" name="submit">تعديل الفرع</button>
                       <a href="{{route('branches.index')}}" class="btn btn-sm btn-danger">الغاء</a>
                    </div>
                </div>

             </form>


            </div>
        </div>
    </div>

@endsection
