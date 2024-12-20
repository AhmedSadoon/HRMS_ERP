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


                    بيانات بصمة الموظف ({{ $Employee_data['emp_name'] }}) بالشهر المالي
                    ({{ $finance_cin_periods_data['month']->name }} لسنة {{ $finance_cin_periods_data['finance_yr'] }})

                </h3>
                <input type="hidden" id="the_finance_cin_periods_id" value="{{ $finance_cin_periods_data['id'] }}">
                <input type="hidden" id="the_employees_code" value="{{ $Employee_data['employees_code'] }}">
                <input type="hidden" id="is_active_for_vaccation" value="{{ $Employee_data['is_active_for_vaccation'] }}">
                <button class="btn btn-sm btn-yahoo" style="float: right;"
                    data-empcode="{{ $Employee_data['employees_code'] }}"
                    data-finclnid="{{ $finance_cin_periods_data['id'] }}" id="showArchivePassmaBtn">عرض سجل ارشيف
                    البصمة</button>

                <button class="btn btn-sm btn-success" style="float: right; margin-right: 6px"
                    data-empcode="{{ $Employee_data['employees_code'] }}"
                    data-finclnid="{{ $finance_cin_periods_data['id'] }}" id="load_active_Attendance_departure">تحميل بصمة
                    الشهر</button>


            </div>

            <div class="card-body" id="ajax_ersponce_searchdiv" style="padding: 0px 5px; overflow-x: scroll;">

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
                <div class="modal-body" style="background-color: white !important; color:black"
                    id="attendance_departure_actions_excel_ModalBady">

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


    {{-- مودل العرض --}}
    <div class="modal fade" id="load_my_action_Modal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content bg-info">
                <div class="modal-header">
                    <h4 class="modal-title">عرض سجل حركات البصمة بتاريخ يوم محدد</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body" style="background-color: white !important; color:black"
                    id="load_my_action_ModalBady">

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
                var finance_cin_periods_id = $(this).data('finclnid');

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

            function load_active_Attendance_departure(){
                var finance_cin_periods_id = $("#the_finance_cin_periods_id").val();
                var employees_code = $("#the_employees_code").val();
                $('#backup_freeze_modal').modal('show');
                $.ajax({
                    url: '{{ route('AttendanceDeparture.load_active_Attendance_departure') }}',
                    type: 'POST',
                    datatype: 'html',
                    cache: false,
                    data: {
                        "_token": '{{ csrf_token() }}',
                        employees_code: employees_code,
                        finance_cin_periods_id: finance_cin_periods_id,
                    },
                    success: function(data) {
                        $("#ajax_ersponce_searchdiv").html(data);
                        setTimeout(() => {
                            $('#backup_freeze_modal').modal('hide');
                        }, 1000);
                    },
                    error: function() {
                        setTimeout(() => {
                            $('#backup_freeze_modal').modal(
                                'hide');
                        }, 1000);
                    }

                });
            }

            $(document).on('click', '#load_active_Attendance_departure', function() {

                load_active_Attendance_departure();
            });


            $(document).on('click', '.load_my_action', function() {

                var attendance_departure_id = $(this).data('id');
                var finance_cin_periods_id = $("#the_finance_cin_periods_id").val();
                var employees_code = $("#the_employees_code").val();

                $.ajax({
                    url: '{{ route('AttendanceDeparture.load_my_action') }}',
                    type: 'POST',
                    datatype: 'html',
                    cache: false,
                    data: {
                        "_token": '{{ csrf_token() }}',
                        employees_code: employees_code,
                        finance_cin_periods_id: finance_cin_periods_id,
                        attendance_departure_id: attendance_departure_id,
                    },
                    success: function(data) {
                        $("#load_my_action_ModalBady").html(data);
                        $("#load_my_action_Modal").modal('show');


                    },
                    error: function() {

                    }

                });
            });

            $(document).on('click', '.make_save_changes_row', function() {

                var id = $(this).data('id');
                var variables = $("#variables"+id).val();
                var cut = $("#cut"+id).val();
                var vacation_types_id = $("#vacation_types_id"+id).val();
                var attedance_dely = $("#attedance_dely"+id).val();
                var early_departure = $("#early_departure"+id).val();
                var azn_hours = $("#azn_hours"+id).val();
                var total_hours = $("#total_hours"+id).val();
                var absen_hours = $("#absen_hours"+id).val();
                var additional_hours = $("#additional_hours"+id).val();
                var finance_cin_periods_id = $("#the_finance_cin_periods_id").val();
                var employees_code = $("#the_employees_code").val();
                $('#backup_freeze_modal').modal('show');

                $.ajax({
                    url: '{{ route('AttendanceDeparture.save_active_Attendance_departure') }}',
                    type: 'POST',
                    datatype: 'html',
                    cache: false,
                    data: {
                        "_token": '{{ csrf_token() }}',
                        id:id,
                        variables:variables,
                        cut:cut,
                        vacation_types_id:vacation_types_id,
                        attedance_dely:attedance_dely,
                        early_departure:early_departure,
                        azn_hours:azn_hours,
                        total_hours:total_hours,
                        absen_hours:absen_hours,
                        additional_hours:additional_hours,
                        finance_cin_periods_id:finance_cin_periods_id,
                        employees_code:employees_code,
                    },
                    success: function(data) {
                        load_active_Attendance_departure();
                        setTimeout(() => {
                        $('#backup_freeze_modal').modal(
                            'hide');
                    }, 1000);


                    },
                    error: function() {
                        load_active_Attendance_departure();
                        setTimeout(() => {
                        $('#backup_freeze_modal').modal(
                            'hide');
                    }, 1000);

                    alert('عفواً حدث خطأ');
                    }

                });
            });

            $(document).on('click', '.move_to', function() {
                event.preventDefault();
                var dest=$(this).attr('data-address');
               $("#"+dest).focus();
            });

            $(document).on('click','#zeroresetdatetime_in',function(e){
                e.preventDefault();
                $('#datetime_in_update').val("");
                $('#undoresetdatetime_in').show();

            });

            $(document).on('click','#zeroresetdatetime_out',function(e){
                e.preventDefault();
                $('#datetime_out_update').val("");
                $('#undoresetdatetime_out').show();

            });

            $(document).on('click','#undoresetdatetime_in',function(e){
                e.preventDefault();
                $('#datetime_in_update').val($(this).data('old'));
            });

            $(document).on('click','#undoresetdatetime_out',function(e){
                e.preventDefault();
                $('#datetime_out_update').val($(this).data('old'));
            });

            $(document).on('click','#redo_update',function(e){
                e.preventDefault();
                var datetime_in=$('#datetime_in_update').val();
                var datetime_out_update=$('#datetime_out_update').val();

                if((datetime_out_update !="" && datetime_in !="") && datetime_out_update < datetime_in ){
                    alert("عفواً لايمكن ان يكون توقيت الانصراف اقل من توقيت الحضور");
                    return false;
                }

                var id=$(this).data("id");
                var finance_cin_periods_id = $("#the_finance_cin_periods_id").val();
                var employees_code = $("#the_employees_code").val();
                $('#backup_freeze_modal').modal('show');
                $.ajax({
                    url: '{{ route('AttendanceDeparture.redo_update_actions') }}',
                    type: 'POST',
                    datatype: 'json',
                    cache: false,
                    data: {
                        "_token": '{{ csrf_token() }}',
                        employees_code: employees_code,
                        finance_cin_periods_id: finance_cin_periods_id,
                        id: id,
                        datetime_in:datetime_in,
                        datetime_out:datetime_out_update,
                    },
                    success: function(data) {
                        load_active_Attendance_departure();
                        setTimeout(() => {
                            $('#backup_freeze_modal').modal(
                                'hide');
                        }, 1000);
                        

                    },
                    error: function() {
                        setTimeout(() => {
                            $('#backup_freeze_modal').modal('hide');
                        }, 1000);
                    }

                });


            });


        });

        
    </script>
@endsection
