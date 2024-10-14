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
    اضافة
@endsection

@section('content')
    <div class="col-12">
        <div class="card">

            <div class="card-header">
                <h3 class="card-title card_title_center">بيانات الفروع
                    <a class="btn btn-sm btn-warning" href="{{route('branches.create')}}">اضافة جديد</a>
                </h3>
            </div>

            <div class="card-body">

                @if (@isset($branches) and !@empty($branches))
                    <table id="example2" class="table table-bordered table-hover">

                        <thead class="custom_thead">
                            <th>كود الفرع</th>
                            <th>اسم الفرع</th>
                            <th>العنوان</th>
                            <th>الهاتف</th>
                            <th>الايميل</th>
                            <th>حالة التفعيل</th>
                            <th>الاضافة بواسطة</th>
                            <th>التحديث بواسطة</th>
                            <th>العمليات</th>
                        </thead>
                        <tbody>
                            @foreach ($branches as $info)
                                <tr>
                                    <td>
                                        {{ $info->id }}
                                    </td>

                                    <td>
                                        {{ $info->name }}
                                    </td>

                                    <td>
                                        {{ $info->address }}
                                    </td>

                                    <td>
                                        {{ $info->phones }}
                                    </td>

                                    <td>
                                        {{ $info->email }}
                                    </td>

                                    <td   @if ($info->active==1)  class='bg-success' @else class='bg-danger' @endif >
                                        @if ($info->active==1) مفعل @else غير مفعل @endif
                                    </td>

                                    <td>{{$info->added->name}}</td>

                                    <td>
                                        @if ($info->updatedBy > '0')
                                        {{$info->updatedBy->name}}
                                        @else
                                        لايوجد
                                        @endif
                                    </td>

                                    <td>
                                      
                                        <a class="btn btn-sm btn-success" href="{{route('branches.edit',$info->id)}}">تعديل</a>
                                        @if ($info->CounterUse==0)
                                        <a class="btn btn-sm btn-danger are_you_shur" href="{{route('branches.destroy',$info->id)}}">حذف</a>

                                        @endif
                                      
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="bg-danger text-center">عفواً لا توجد بيانات لعرضها</p>
                @endif


            </div>
        </div>
    </div>


@endsection

@section('script')

<script>
    $(document).ready(function(){
        $(document).on('click','.show_year_monthes',function(){

            var id=$(this).data('id');
            $.ajax({
                url: '{{route('finance_calender.show_year_monthes')}}',
                type:'POST',
                datatype:'html',
                cache:false,
                data:{
                    "_token":'{{csrf_token()}}',
                    'id':id
                },
                success:function(data){
                   $("#show_year_monthesBady").html(data);
                   $("#show_year_monthesModal").modal('show');
                    
                },
                error:function(){

                }
                
        });
        });
    });

    
</script>
@endsection
