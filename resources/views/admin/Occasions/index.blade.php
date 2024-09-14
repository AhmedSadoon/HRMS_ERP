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
    عرض
@endsection

@section('content')
    <div class="col-12">
        <div class="card">

            <div class="card-header">
                <h3 class="card-title card_title_center">بيانات المناسبات الرسمية
                    <a class="btn btn-sm btn-warning" href="{{ route('Occasions.create') }}">اضافة جديد</a>
                </h3>
            </div>


            <div class="card-body">

                @if (@isset($Occasions) and !@empty($Occasions) and count($Occasions) > 0)
                    <table id="example2" class="table table-bordered table-hover">

                        <thead class="custom_thead">
                            <th>اسم المناسبة</th>
                            <th>من تاريخ</th>
                            <th>الى تاريخ</th>
                            <th>عدد الايام</th>
                            <th>حالة التفعيل</th>
                            <th>الاضافة بواسطة</th>
                            <th>التحديث بواسطة</th>
                            <th>العمليات</th>
                        </thead>
                        <tbody>
                            @foreach ($Occasions as $info)
                                <tr>
                                    <td>
                                        {{ $info->name }}
                                    </td>

                                    <td>
                                        {{ $info->from_date }}
                                    </td>

                                    <td>
                                        {{ $info->to_date }}
                                    </td>
                                    <td>
                                        {{ $info->days_counter }}
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
                                            href="{{ route('Occasions.edit', $info->id) }}">تعديل</a>
                                        <a class="btn btn-sm btn-danger are_you_shur"
                                            href="{{ route('Occasions.destroy', $info->id) }}">حذف</a>

                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <br>
                    <div class="col-md-12">
                        {{ $Occasions->links('pagination::bootstrap-5') }}
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
