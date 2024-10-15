@if (@isset($dataParentLoan) and !@empty($dataParentLoan))
    @if (@isset($dataParentLoan['aksatDetails']) and !@empty($dataParentLoan['aksatDetails']))

        <table id="example2" class="table table-bordered table-hover">
            <tr>
                <td class="width30">تاريخ الاضافة</td>

                <td>
                    @php
                        $dt = new DateTime($dataParentLoan['created_at']);
                        $date = $dt->format('Y-m-d');
                        $time = $dt->format('h:i');
                        $newDateTime = date('a', strtotime($dataParentLoan['created_at']));
                        $newDateTimeType = $newDateTime == 'AM' ? 'صباحاً' : 'مساءً';
                    @endphp
                    ({{ $date }})
                    ({{ $time }})
                    ({{ $newDateTimeType }} )
                    ({{ $dataParentLoan->added->name }})

                </td>

                @if ($dataParentLoan['updatedBy'] > '0')
                    <td class="width30">تاريخ التحديث</td>
                @endif
                @if ($dataParentLoan['updatedBy'] > '0')
                    <td>
                        @php
                            $dt = new DateTime($dataParentLoan['updated_at']);
                            $date = $dt->format('Y-m-d');
                            $time = $dt->format('h:i');
                            $newDateTime = date('a', strtotime($dataParentLoan['updated_at']));
                            $newDateTimeType = $newDateTime == 'AM' ? 'صباحاً' : 'مساءً';
                        @endphp
                        ({{ $date }})
                        ({{ $time }})
                        ({{ $newDateTimeType }} )
                        ({{ $dataParentLoan->updatedBy->name }})

                    </td>
                @endif


            </tr>


        </table>

        <table id="example2" class="table table-bordered table-hover">

            <thead class="custom_thead">
                <th>شهر الاستحقاق</th>
                <th>قيمة القسط</th>
                <th>حالة الدفع</th>
                <th>حالة الارشفة</th>
                <th>ملاحظة</th>


            </thead>
            <tbody>
                @foreach ($dataParentLoan['aksatDetails'] as $info)
                    <tr>
                        <td>
                            {{ $info->year_and_month }}
                        </td>

                        <td>
                            {{ $info->month_kast_value * 1 }}
                        </td>

                        <td>
                            @if ($info->state == 1)
                                تم الدفع على الراتب
                            @elseif ($info->state == 2)
                                تم الدفع كاش
                            @else
                                بأنتظار الدفع
                            @endif

                            @if (
                                $info->state == 0 and
                                    $info->counterBeforNotPaid == 0 and
                                    $info->is_archived == 0 and
                                    $dataParentLoan['is_dismissail_done'] == 1)
                                <button class="btn btn-sm btn-danger doSingleCachPayNow" data-id="{{ $info->id }}"
                                    data-idparent="{{ $dataParentLoan['id'] }}">دفع كاش مفرد</button>
                            @endif
                        </td>

                        <td>
                            @if ($info->is_archived == 1)
                                نعم
                            @else
                                لا
                            @endif
                        </td>


                        <td>
                            {{ $info->notes }}
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

