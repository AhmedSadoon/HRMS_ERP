@extends('layouts.admin')

@section('title')
انواع الديانات 
@endsection

@section('contentheader')
    قائمة الضبط
@endsection

@section('contentheaderactivelink')
    <a href="{{ route('Religions.index') }}">الديانات</a>
@endsection

@section('contentheaderactive')
    عرض
@endsection

@section('content')
    <div class="col-12">
        <div class="card">

            <div class="card-header">
                <h3 class="card-title card_title_center">بيانات انواع ديانات الموظفين 
                    <a class="btn btn-sm btn-warning" href="{{ route('Religions.create') }}">اضافة جديد</a>
                </h3>
            </div>


            <div class="card-body">

                @if (@isset($Religions) and !@empty($Religions) and count($Religions) > 0)
                    <table id="example2" class="table table-bordered table-hover">

                        <thead class="custom_thead">
                            <th>الديانة</th>
                            <th>حالة التفعيل</th>
                            <th>الاضافة بواسطة</th>
                            <th>التحديث بواسطة</th>
                            <th>العمليات</th>
                        </thead>
                        <tbody>
                            @foreach ($Religions as $info)
                                <tr>
                                    <td>
                                        {{ $info->name }}
                                    </td>
                                    <td @if ($info->active == 1) class='bg-success' @else class='bg-danger' @endif>
                                        @if ($info->active == 1)
                                            مفعل
                                        @else
                                            غير مفعل
                                        @endif
                                    </td>

                                    <td>{{ $info->added->name }}</td>

                                    <td>
                                        @if ($info->updatedBy > '0')
                                            {{ $info->updatedBy->name }}
                                        @else
                                            لايوجد
                                        @endif
                                    </td>

                                    <td>

                                        <a class="btn btn-sm btn-success"
                                            href="{{ route('Religions.edit', $info->id) }}">تعديل</a>
                                            @if ($info->CounterUse==0)
                                            <a class="btn btn-sm btn-danger are_you_shur"
                                            href="{{ route('Religions.destroy', $info->id) }}">حذف</a>
                                            @endif
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <br>
                    <div class="col-md-12">
                        {{ $Religions->links('pagination::bootstrap-5') }}
                    </div>
                @else
                    <p class="bg-danger text-center">عفواً لا توجد بيانات لعرضها</p>
                @endif


            </div>
        </div>
    </div>


@endsection

@section('script')
    <script></script>
@endsection
