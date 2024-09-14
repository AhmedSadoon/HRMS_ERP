@extends('layouts.admin')

@section('title')
المناسبات الرسمية
@endsection

@section('contentheader')
    قائمة الضبط
@endsection

@section('contentheaderactivelink')
    <a href="{{ route('Occasions.index') }}">المناسبات الرسمية</a>
@endsection

@section('contentheaderactive')
    تعديل
@endsection

@section('content')
    <div class="col-12">
        <div class="card">

            <div class="card-header">
                <h3 class="card-title card_title_center">تعديل بيانات المناسبة </h3>
            </div>

            <div class="card-body">

             <form action="{{route('Occasions.update',$data['id'])}}" method="POST">
                @csrf

                <div class="col-md-12">
                    <div class="form-group">
                        <label>اسم المناسبة</label>
                        <input type="text" name="name" id="name" class="form-control"
                            value="{{ old('name',$data['name']) }}">
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                

                <div class="col-md-12">
                    <div class="form-group">
                        <label>تبدأ من تاريخ</label>
                        <input type="date" name="from_date" id="from_date" class="form-control"
                            value="{{ old('from_date',$data['from_date']) }}">
                        @error('from_date')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label>الى تاريخ</label>
                        <input type="date" name="to_date" id="to_date" class="form-control"
                            value="{{ old('to_date',$data['to_date']) }}">
                        @error('to_date')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- اذا ادخل عدد الايام يدوي --}}
                <div class="col-md-12">
                    <div class="form-group">
                        <label>عدد الايام</label>
                        <input type="text" name="days_counter" id="days_counter" class="form-control"
                            value="{{ old('days_counter',$data['days_counter']) }}" oninput="this.value=this.value.replace(/[^0-9.]/g,'');">
                        @error('days_counter')
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
                       <button class="btn btn-sm btn-success" type="submit" name="submit">تعديل المناسبة</button>
                       <a href="{{route('Occasions.index')}}" class="btn btn-sm btn-danger">الغاء</a>
                    </div>
                </div>

             </form>


            </div>
        </div>
    </div>

@endsection
