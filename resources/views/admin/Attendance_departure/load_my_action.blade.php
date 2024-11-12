@if (@isset($parent) and !@empty($parent))

    @if ($parent['is_archived']==0)
        <div class="row">

            <div class="col-md-4">
                <div class="form-group">
                    <label>
                        <button style="margin-top: 33px;" class="btn btn-xs btn-danger pull-left" data-id="{{$parent['id']}}" id="zeroresetdatetime_in">reset</button>
                        <button data-old="{{$parent['datetime_in']}}" style="display: none; margin-right: 2px" class="btn btn-xs btn-info pull-left" id="undoresetdatetime_in">undo</button>

                        بصمة الحضور المفعلة
                    </label>
                    <input data-old="{{$parent['datetime_in']}}" type="datetime-local" class="form-control" id="datetime_in_update" value="{{$parent['datetime_in']}}">
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label>
                        <button style="margin-top: 33px;" class="btn btn-xs btn-danger pull-left" id="zeroresetdatetime_out">reset</button>
                        <button data-old="{{$parent['datetime_out']}}" style="display: none; margin-right: 2px" class="btn btn-xs btn-info pull-left" id="undoresetdatetime_out">undo</button>

                        بصمة الانصراف المفعلة</label>
                    <input data-old="{{$parent['datetime_out']}}" type="datetime-local" class="form-control" id="datetime_out_update" value="{{$parent['datetime_out']}}">
          
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    <button style="margin-top: 40px;" class="btn btn-sm btn-success" data-id="{{$parent['id']}}" id="redo_update">تحديث</button>
                </div>
            </div>

        </div>

    @if ($parent['is_updated_active_action']==1)
        
        <div class="col-md-12">
            <p style="text-align: center; color: brown;">
                ملاحظة : تم التعديل على البصمات (
                    @php
                        $dt=new DateTime($parent['is_updated_active_action_date']);
                        $date=$dt->format("Y-m-d");
                        $time=$dt->format("h:i");
                        //$newDateTime=strtolower(date("a",strtotime($parent['is_updated_active_action_date'])));
                        $newDateTime=strtolower($dt->format("a"));
                        $newDateTimeType=(($newDateTime=="am")?'صباحاً':'مساءً');
                    @endphp
                    {{$date}} 
                    {{$time}}
                    {{$newDateTimeType}} 
        
                    بواسطة ({{$parent->updatedByforAction->name}})
                    ) 
            </p>
        </div>
   
        
    @endif


    @endif

    @if (@isset($Attendance_departure_actions) and !@empty($Attendance_departure_actions))
        <table id="example2" class="table table-bordered table-hover">

            <thead class="custom_thead">
                <th>التاريخ</th>
                <th>نوع البصمة</th>
                <th>التوقيت بنظام 24 ساعة</th>
                <th>التوقيت بنظام 12 ساعة</th>
                <th>هل البصمة مفعلة</th>
                <th>طريقة الاضافة</th>
                <th>السحب بواسطة</th>



            </thead>
            <tbody>
                @foreach ($Attendance_departure_actions as $info)
                    <tr>

                        <td>
                            @php
                                $dt = new DateTime($info->datetimeAction);
                                $date = $dt->format('Y-m-d');
                                $time = $dt->format('h:i');
                                $newDateTime = date('A', strtotime($info->datetimeAction));
                                $newDateTimeType = $newDateTime == 'AM' ? 'صباحا' : 'مساءً';
                            @endphp
                            {{ $info->week_day_name_arabic }}
                            {{ $date }}
                        </td>

                        <td>
                            @if ($info->action_type == 1)
                                حضور
                            @else
                                انصراف
                            @endif
                        </td>



                        <td>
                            @php
                                echo date('H:i', strtotime($info->datetimeAction));
                            @endphp
                        </td>



                        <td>
                            {{ $time }}
                            {{ $newDateTimeType }}
                        </td>

                        <td>
                            @if ($info->it_is_active_with_parent == 1)
                                نعم
                            @else
                                لا
                            @endif

                            @if ($info->it_is_active_with_parent == 1)
                                @if ($info->datetimeAction == $parent['datetime_in'])
                                    <br> اخذت كحضور
                                @endif

                                @if ($info->datetimeAction == $parent['datetime_out'])
                                    <br> اخذت كأنصراف
                                @endif
                            @endif


                        </td>

                        <td>
                            @if ($info->added_method == 1)
                                الي
                            @else
                                يدوي
                            @endif
                        </td>

                        <td>{{ $info->added->name }} <br>

                            @php
                                $dt = new DateTime($info->created_at);
                                $date = $dt->format('Y-m-d');
                                $time = $dt->format('H:i');
                                $newDateTime = date('A', strtotime($info->created_at));
                                $newDateTimeType = $newDateTime == 'AM' ? 'صباحا' : 'مساءً';
                            @endphp
                            ({{ $date }})
                            ({{ $time }}
                            {{ $newDateTimeType }})
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="bg-danger text-center">عفواً لا توجد بيانات لعرضها</p>
    @endif
@else
    <p class="bg-danger text-center">عفواً لا توجد بيانات لعرضها</p>
@endif
