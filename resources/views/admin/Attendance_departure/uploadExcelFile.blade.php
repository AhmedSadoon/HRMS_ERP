@extends('layouts.admin')

@section('title')
    البصمة
@endsection

@section('contentheader')
    قائمة جهاز البصمة
@endsection

@section('contentheaderactivelink')
    <a href="{{ route('MainSalarySanctions.index') }}">بصمة الموظفين</a>
@endsection

@section('contentheaderactive')
    اضافة
@endsection

@section('content')
    <div class="col-12">
        <div class="card">

            <div class="card-header">
                ارفاق ملف بصمات الموظفين بالشهر المالي
                ({{ $finance_cin_periods_data['month']->name }} لسنة {{ $finance_cin_periods_data['finance_yr'] }})
                بفترة بصمة (من {{ $finance_cin_periods_data['start_date_for_pasma'] }} الى {{ $finance_cin_periods_data['end_date_for_pasma'] }})
            </div>

            <div class="card-body">

             <form action="{{route('AttendanceDeparture.do_UploadExcelFile',$finance_cin_periods_data['id'])}}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="col-md-12">
                    <div class="form-group">
                        <label>اختر ملف البصمة
                            <span style="color:brown;">(ملاحظة: سيتم اهمال اي بصمة خارج نطاق فترة الشهر المالي)</span>
                        </label>
                        <input autofocus type="file" name="excel_file" id="excel_file"
                            class="form-control" >
                            @error('excel_file')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group text-center">
                       <button class="btn btn-sm btn-success" type="submit" name="submit">ارفاق الملف</button>
                       <a href="{{route('AttendanceDeparture.show',$finance_cin_periods_data['id'])}}" class="btn btn-sm btn-danger">الغاء</a>
                    </div>
                </div>

             </form>


            </div>
        </div>
    </div>

@endsection
