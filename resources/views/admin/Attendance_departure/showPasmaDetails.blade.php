@extends('layouts.admin')

@section('title')
    البصمة
@endsection

@section('contentheader')
    قائمة جهاز البصمة
@endsection

@section('contentheaderactivelink')
    <a href="{{ route('AttendanceDeparture.index') }}">بصمة الموظف</a>
@endsection

@section('contentheaderactive')
    عرض
@endsection

@section('content')
    <style>
        .modal-xl {
            max-width: 100%;
            margin: 0 auto;
            padding: 0px !important;
        }
    </style>
    <div class="col-12">
        <div class="card">

            <div class="card-header">
                <h3 class="card-title card_title_center">

                    <button class="btn btn-sm btn-info" style="float: right;"
                        data-empcode="{{ $Employee_data['employees_code'] }}"
                        data-finclnid="{{ $finance_cin_periods_data['id'] }}" id="showArchivePassmaBtn">عرض سجل ارشيف البصمة</button>

                    بيانات بصمة الموظف ({{ $Employee_data['emp_name'] }}) بالشهر المالي
                    ({{ $finance_cin_periods_data['month']->name }} لسنة {{ $finance_cin_periods_data['finance_yr'] }})

                </h3>

            </div>

            <div class="card-body" id="ajax_ersponce_searchdiv" style="padding: 0px 5px">

                @if (@isset($data) and !@empty($data) and count($data) > 0)
                    <table id="example2" class="table table-bordered table-hover">

                        <thead class="custom_thead">

                            <th>كود</th>
                            <th>اسم الموظف</th>
                            <th>الفرع</th>
                            <th>الادارة</th>
                            <th>الوظيفة</th>
                            <th>صورة الموظف</th>
                            <th>العمليات</th>

                        </thead>
                        <tbody>
                            @foreach ($data as $info)
                                <tr>

                                    <td>{{ $info->employees_code }} </td>
                                    <td>{{ $info->emp_name }} </td>
                                    <td> {{ $info->Branch->name }} </td>
                                    <td>
                                        @if (!@empty($info->Department->name))
                                            {{ $info->Department->name }}
                                        @endif

                                    </td>

                                    <td>
                                        @if (!@empty($info->Job->name))
                                            {{ $info->Job->name }}
                                        @endif

                                    </td>



                                    <td>
                                        @if (!@empty($info->emp_photo))
                                            <img src="{{ asset('assets/admin/uploads') . '/' . $info->emp_photo }}"
                                                style="border-radius: 50%; width: 80px; height: 80px;"
                                                class="rounded-circle" alt="صورة الموظف">
                                        @else
                                            @if ($info->emp_gender == 1)
                                                <img src="{{ asset('assets/admin/images/boy.png') }}"
                                                    style="border-radius: 50%; width: 80px; height: 80px;"
                                                    class="rounded-circle" alt="صورة الموظف">
                                            @else
                                                <img src="{{ asset('assets/admin/images/woman.png') }}"
                                                    style="border-radius: 50%; width: 80px; height: 80px;"
                                                    class="rounded-circle" alt="صورة الموظف">
                                            @endif
                                        @endif
                                    </td>


                                    <td>


                                        <a href="{{ route('AttendanceDeparture.showPasmaDetails', ['employees_code' => $info->employees_code, 'finance_cin_periods_id' => $finance_cin_periods_data['id']]) }}"
                                            class="btn btn-sm btn-success">التفاصيل</a>
                                        <a target="_blank"
                                            href="{{ route('AttendanceDeparture.print_onePasmasearch', $info->id) }}"
                                            class="btn btn-sm btn-primary" style="color: white">طباعة </a>

                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <br>
                    <div class="col-md-12">
                        {{ $data->links('pagination::bootstrap-5') }}
                    </div>
                @else
                    <p class="bg-danger text-center">عفواً لا توجد بيانات لعرضها</p>
                @endif


            </div>
        </div>
    </div>
    {{-- مودل العرض --}}
    <div class="modal fade" id="attendance_departure_actions_excel_Modal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content bg-info">
                <div class="modal-header">
                    <h4 class="modal-title">عرض ارشيف سجلات بصمة الموظف كما هي بدون اي تعديلات</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body" style="background-color: white !important; color:black" id="attendance_departure_actions_excel_ModalBady">

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->


@endsection

@section('script')
    <script src="{{ asset('assets/admin/plugins/select2/js/select2.full.min.js') }}"></script>

    <script>
        $(".select2").select2({
            theme: 'bootstrap4'
        });

        $(document).ready(function() {

            $(document).on('click', '#showArchivePassmaBtn', function() {

                var employees_code = $(this).data('empcode');
                var finance_cin_periods_id= $(this).data('finclnid');

                $.ajax({
                    url: '{{ route('AttendanceDeparture.load_PasmasaArchive') }}',
                    type: 'POST',
                    datatype: 'html',
                    cache: false,
                    data: {
                        "_token": '{{ csrf_token() }}',
                        'employees_code': employees_code,
                        'finance_cin_periods_id': finance_cin_periods_id,
                    },
                    success: function(data) {
                        $("#attendance_departure_actions_excel_ModalBady").html(data);
                        $("#attendance_departure_actions_excel_Modal").modal('show');

                    },
                    error: function() {

                    }

                });
            });









        });
    </script>
@endsection
