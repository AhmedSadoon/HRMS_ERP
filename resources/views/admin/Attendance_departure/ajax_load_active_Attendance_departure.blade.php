<br>
<p style="padding: 5px;border: 1px solid black; text-align: right; border-radius: 10px; background-color: #d9edf7;"
    class="text-center">ملاحظة:اكبر تاريخ بصمة مسجلة بالنظام هو ({{ $max_attend_date }})</p>

<a target="_blank" class="btn btn-sm btn-primary"
    href="{{ route('AttendanceDeparture.print_one_passma_details', ['employees_code' => $other['Employee_data']['employees_code'], 'finance_cin_periods_id' => $other['finance_cin_periods_data']['id']]) }}">طباعة</a>

@if (@isset($other['data']) and !@empty($other['data']) and count($other['data']) > 0)
    <table id="example2" class="table table-bordered table-hover">

        <thead class="custom_thead">

            <th>التاريخ</th>
            <th>الحضور</th>
            <th>الانصراف</th>
            <th>البصمات</th>
            <th>متغيرات</th>
            <th>خصم ايام</th>
            <th>هل اجازة</th>
            <th>حضور متأخر</th>
            <th>انصراف مبكر</th>
            <th>اذون ساعات</th>
            <th>ساعات عمل</th>
            <th>غياب ساعات</th>
            <th>اضافي ساعات</th>
            <th>اجراء</th>


        </thead>
        <tbody>
            @foreach ($other['data'] as $info)
                <tr @if ($info->datetime_in == null and $info->datetime_out == null) style="background-color:#f2dede;" @endif>

                    <td id="the_day_date{{ $info->id }}">
                        {{ $info->the_day_date }}
                        {{ $info->week_day_name_arabic }}
                    </td>
                    <td>

                        @if ($info->time_in != null)
                            @php
                                echo date('H:i', strtotime($info->time_in));
                            @endphp
                        @endif

                    </td>
                    <td>
                        @if ($info->time_out != null)
                            @php
                                echo date('H:i', strtotime($info->time_out));
                            @endphp
                        @endif
                    </td>

                    <td>
                        <div style="width: 6vw">
                            <button data-id="{{ $info->id }}" class="btn btn-sm btn-danger load_my_action">عرض
                                ({{ $info->attendance_departure_actions_Counter * 1 }})</button>
                    </td>
                    </div>
                    <td>
                        <div style="width: 14vw">
                            <input type="text" class="form-control variables" @if($info->is_archived==1) readonly @endif name="variables{{ $info->id }}"
                                id="variables{{ $info->id }}" value="{{ $info->variables }}">
                        </div>
                    </td>
                    <td>
                        <div style="width: 7vw">
                            <input type="text" class="form-control cut" @if($info->is_archived==1) disabled @endif name="cut{{ $info->id }}"
                                id="cut{{ $info->id }}" value="{{ $info->cut * 1 }}"
                                oninput="this.value=this.value.replace(/[^0-9.]/g,'');">
                        </div>
                    </td>
                    <td>

                        <div style="width: 10vw; text-align: center">
                            <select name="vacation_types_id{{ $info->id }}" @if($info->is_archived==1) disabled @endif
                                id="vacation_types_id{{ $info->id }}" class="form-control vacation_types_id">
                                @if (@isset($other['vacation_types']) && !@empty($other['vacation_types']))
                                    @foreach ($other['vacation_types'] as $vac)
                                        <option @if ($info->vacations_type_id == $vac->id) selected="selected" @endif
                                            value="{{ $vac->id }}" @if (($other['Employee_data']['is_active_for_vaccation']==0 or $other['Employee_data']['is_done_vaccation_formula']==0) and $vac->id==3 and $setting['is_pull_manull_days_from_passma']==0) disabled @endif > {{ $vac->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            <a class="move_to" data-address="variables{{ $info->id }}" href="#"><i
                                    class="fas fa-hand-point-right"></i></a>
                            <a class="move_to" data-address="additional_hours{{ $info->id }}" href="#"><i
                                    class="fas fa-hand-point-left"></i></a>
                        </div>

                    </td>
                    <td>
                        <div style="width: 7vw">
                            <input type="text" class="form-control attedance_dely" @if($info->is_archived==1) disabled @endif
                                name="attedance_dely{{ $info->id }}" id="attedance_dely{{ $info->id }}"
                                value="{{ $info->attedance_dely * 1 }}"
                                oninput="this.value=this.value.replace(/[^0-9.]/g,'');">
                        </div>
                    </td>
                    <td>

                        <div style="width: 7vw">
                            <input type="text" class="form-control early_departure" @if($info->is_archived==1) disabled @endif
                                name="early_departure{{ $info->id }}" id="early_departure{{ $info->id }}"
                                value="{{ $info->early_departure * 1 }}"
                                oninput="this.value=this.value.replace(/[^0-9.]/g,'');">
                        </div>

                    </td>
                    <td>
                        <div style="width: 14vw">
                            <input type="text" @if($info->is_archived==1) disabled @endif class="form-control azn_hours" name="azn_hours{{ $info->id }}"
                                id="azn_hours{{ $info->id }}" value="{{ $info->azn_hours }}">
                        </div>
                    </td>
                    <td>
                        <div style="width: 7vw">
                            <input disabled type="text" class="form-control total_hours" @if($info->is_archived==1) disabled @endif
                                name="total_hours{{ $info->id }}" id="total_hours{{ $info->id }}"
                                value="{{ $info->total_hours * 1 }}"
                                oninput="this.value=this.value.replace(/[^0-9.]/g,'');">
                        </div>
                    </td>
                    <td>
                        <div style="width: 7vw">
                            <input type="text" class="form-control absen_hours" @if($info->is_archived==1) disabled @endif
                                name="absen_hours{{ $info->id }}" id="absen_hours{{ $info->id }}"
                                value="{{ $info->absen_hours * 1 }}"
                                oninput="this.value=this.value.replace(/[^0-9.]/g,'');">
                        </div>
                    </td>
                    <td>
                        <div style="width: 8vw">
                            <input type="text" class="form-control additional_hours" @if($info->is_archived==1) readonly @endif
                                name="additional_hours{{ $info->id }}" id="additional_hours{{ $info->id }}"
                                value="{{ $info->additional_hours * 1 }}"
                                oninput="this.value=this.value.replace(/[^0-9.]/g,'');">
                        </div>
                    </td>
                    <td id="make_save_changes_row{{ $info->id }}">
                        <div style="width: 4vw">
                            @if($info->is_archived==0) 
                            <button class="btn btn-sm btn-danger make_save_changes_row" data-id="{{ $info->id }}">حفظ</button>
                            @else
                            مؤرشف
                             @endif
                            
                        </div>
                    </td>


                </tr>
            @endforeach

            <tr style="background-color: lightblue; text-align: center">
                <td colspan="5">الاجماليات</td>
                <td>{{ $other['total_cut'] * 1 }} يوم</td>
                <td>{{ $other['total_vacations_type_id'] * 1 }} يوم <br>
                    @if (@isset($other['vacations_type_distinct']) and !@empty($other['vacations_type_distinct']))

                        @foreach ($other['vacations_type_distinct'] as $vac)
                            {{ $vac->counter * 1 }} {{ $vac->name }} <br>
                        @endforeach


                    @endif


                </td>
                <td>{{ $other['total_attedance_dely'] * 1 }} دقيقة</td>
                <td>{{ $other['total_early_departure'] * 1 }} دقيقة</td>
                <td></td>
                <td>{{ $other['total_hours'] * 1 }} ساعة</td>
                <td>{{ $other['total_absen_hours'] * 1 }} ساعة</td>
                <td colspan="2">{{ $other['total_additional_hours'] * 1 }} ساعة</td>
            </tr>
        </tbody>
    </table>
@else
    <p class="bg-danger text-center">عفواً لا توجد بيانات لعرضها</p>
@endif
