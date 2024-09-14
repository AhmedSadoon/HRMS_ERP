@extends('layouts.admin')

@section('title')
    السنوات المالية
@endsection

@section('contentheader')
    قائمة الضبط
@endsection

@section('contentheaderactivelink')
    <a href="{{ route('finance_calender.index') }}">السنوات المالية</a>
@endsection

@section('contentheaderactive')
    عرض
@endsection

@section('content')
    <div class="col-12">
        <div class="card">

            <div class="card-header">
                <h3 class="card-title card_title_center">بيانات السنوات المالية
                    <a class="btn btn-sm btn-success" href="{{route('finance_calender.create')}}">اضافة جديد</a>
                </h3>
            </div>

            <div class="card-body">

                @if (@isset($Finance_calender) and !@empty($Finance_calender) and count($Finance_calender) >0)
                    <table id="example2" class="table table-bordered table-hover">

                        <thead class="custom_thead">
                            <th>كود السنة</th>
                            <th>وصف السنة</th>
                            <th>تاريخ البداية</th>
                            <th>تاريخ النهاية</th>
                            <th>الاضافة بواسطة</th>
                            <th>التحديث بواسطة</th>
                            <th>العمليات</th>
                        </thead>
                        <tbody>
                            @foreach ($Finance_calender as $info)
                                <tr>
                                    <td>
                                        {{ $info->finance_yr }}
                                    </td>

                                    <td>
                                        {{ $info->finance_yr_desc }}
                                    </td>

                                    <td>
                                        {{ $info->start_date }}
                                    </td>

                                    <td>
                                        {{ $info->end_date }}
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
                                        @if ($info->open_yr_flag==0)
                                        @if ($checkDataOpenCounter==0)
                                        <a class="btn btn-sm btn-primary" href="{{route('finance_calender.do_open',$info->id)}}">فتح</a>
                                        @endif
                                        <a class="btn btn-sm btn-success" href="{{route('finance_calender.edit',$info->id)}}">تعديل</a>
                                        <a class="btn btn-sm btn-danger are_you_shur" href="{{route('finance_calender.delete',$info->id)}}">حذف</a>
                                        @else
                                            سنة مالية مفتوحة
                                        @endif
                                        <button class="btn btn-sm btn-info show_year_monthes" data-id="{{$info->id}}">عرض الشهور</button>

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

    <div class="modal fade" id="show_year_monthesModal">
        <div class="modal-dialog modal-xl">
          <div class="modal-content bg-info">
            <div class="modal-header">
              <h4 class="modal-title">عرض الشهور للسنة المالية</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" id="show_year_monthesBady">

            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-outline-light">Save changes</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->

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
