@extends('layouts.admin')

@section('title')
    التحقيقات
@endsection

@section('contentheader')
    قائمة التحقيقات
@endsection

@section('contentheaderactivelink')
    <a href="{{ route('MainEmployeesInvestigations.index') }}">التحقيقات الادارية</a>
@endsection

@section('contentheaderactive')
    عرض
@endsection

@section('content')
    <div class="col-12">
        <div class="card">

            <div class="card-header">
                <h3 class="card-title card_title_center">بيانات التحقيقات الادارية
                </h3>
            </div>

        
            <div class="card-body" id="ajax_ersponce_searchdiv" style="padding: 0px 5px">

                @if (@isset($Finance_cin_periods) and !@empty($Finance_cin_periods) and count($Finance_cin_periods) > 0)
                    <table id="example2" class="table table-bordered table-hover">

                        <thead class="custom_thead">
                            <th>اسم الشهر</th>

                            <th>تاريخ البداية</th>
                            <th>تاريخ النهاية</th>
                            <th>تاريخ بداية البصمة</th>
                            <th>تاريخ نهاية البصمة</th>
                            <th>عدد الايام</th>
                            <th>حالة الشهر</th>


                        </thead>
                        <tbody>
                            @foreach ($Finance_cin_periods as $info)
                                <tr>
                                    <td>
                                        {{ $info->Month->name }}
                                    </td>



                                    <td>
                                        {{ $info->start_date_m }}
                                    </td>

                                    <td>
                                        {{ $info->end_date_m }}
                                    </td>

                                    <td>
                                        {{ $info->start_date_for_pasma }}
                                    </td>

                                    <td>
                                        {{ $info->end_date_for_pasma }}
                                    </td>

                                    <td>
                                        {{ $info->number_of_dats }}
                                    </td>



                                    <td>
                                        @if ($info->is_open == 1)
                                            مفتوح
                                            @elseif ($info->is_open == 2)
                                            مغلق ومؤرشف
                                            @else
                                            بأنتظار الفتح
                                        @endif

                                      
                                                @if ($info->is_open != 0 )
                                                    <a href="{{route('MainEmployeesInvestigations.show',$info->id)}}" class="btn btn-sm btn-success">عرض</a>
                                                @endif
                                         





                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <br>
                    <div class="col-md-12">
                        {{ $Finance_cin_periods->links('pagination::bootstrap-5') }}
                    </div>
                    @else
                    <p class="bg-danger text-center">عفواً لا توجد بيانات لعرضها</p>
                @endif


            </div>
        </div>
    </div>

    <div class="modal fade" id="load_open_monthModal" >
        <div class="modal-dialog modal-xl">
          <div class="modal-content bg-info">
            <div class="modal-header">
              <h4 class="modal-title">فتح الشهر المالية</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" id="load_open_monthModalBady" style="background-color: white; color: black;">
    
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
        $(document).ready(function() {

            $(document).on('change', '#type_search', function(e) {
                ajax_search();
            });

            $(document).on('input', '#huor_from_range', function(e) {
                ajax_search();
            });

            $(document).on('input', '#huor_to_range', function(e) {
                ajax_search();
            });

            function ajax_search() {
                var type_search = $("#type_search").val();
                var huor_from_range = $("#huor_from_range").val();
                var huor_to_range = $("#huor_to_range").val();

                jQuery.ajax({
                    url: '{{ route('shiftsTypes.ajaxSearch') }}',
                    type: 'post',
                    dataType: 'html',
                    cache: false,
                    data: {
                        "_token": '{{ csrf_token() }}',
                        type_search: type_search,
                        huor_from_range: huor_from_range,
                        huor_to_range: huor_to_range
                    },

                    success: function(data) {
                        $("#ajax_ersponce_searchdiv").html(data);
                    },
                    error: function() {
                        alert("عفواً حدث خطأ")
                    }

                });

                $(document).on('click', '#ajax_pagination_in_search a', function(e) {

                    e.preventDefault();
                    var type_search = $("#type_search").val();
                    var huor_from_range = $("#huor_from_range").val();
                    var huor_to_range = $("#huor_to_range").val();
                    var linkurl = $(this).attr("href");

                    jQuery.ajax({
                        url: linkurl,
                        type: 'post',
                        dataType: 'html',
                        cache: false,
                        data: {
                            "_token": '{{ csrf_token() }}',
                            type_search: type_search,
                            huor_from_range: huor_from_range,
                            huor_to_range: huor_to_range
                        },

                        success: function(data) {
                            $("#ajax_ersponce_searchdiv").html(data);
                        },
                        error: function() {
                            alert("عفواً حدث خطأ")
                        }

                    });


                });


            }

            $(document).on('click', '.the_load_modal', function(e) {
                var id=$(this).data("id");
                jQuery.ajax({
                    url: '{{ route('MainSalaryRecord.load_open_month') }}',
                    type: 'post',
                    dataType: 'html',
                    cache: false,
                    data: {
                        "_token": '{{ csrf_token() }}',
                        id: id,
                    },

                    success: function(data) {
                        $("#load_open_monthModalBady").html(data);
                        $("#load_open_monthModal").modal("show");
                    },
                    error: function() {
                        alert("عفواً حدث خطأ")
                    }

                });
            });

        });
    </script>


@endsection
