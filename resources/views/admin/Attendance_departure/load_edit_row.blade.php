@if (!@empty($finance_cin_periods_data) && !@empty($main_salary_employee_data) && !@empty($data_row))
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label>بيانات الموظفين</label>
                <select name="employees_code_edit" id="employees_code_edit" class="form-control select2">
                    <option disabled selected value="">اختر الموظف</option>
                    @if (@isset($employees) && !@empty($employees))
                        @foreach ($employees as $info)
                            <option @if ($info->employees_code==$data_row['employees_code']) selected @endif value="{{ $info->employees_code }}" data-s="{{ $info->EmployeeData['emp_salary'] }}"
                                data-dp="{{ $info->EmployeeData['day_price'] }}"> {{ $info->EmployeeData['emp_name'] }}
                                ({{ $info->employees_code }})
                            </option>
                        @endforeach
                    @endif
                </select>

            </div>
        </div>

        <div class="col-md-3 related_employees_edit" style="display: none">
            <div class="form-group">
                <label>راتب الموظف الشهري</label>
                <input readonly type="text" name="emp_salary_edit" id="emp_salary_edit" class="form-control"
                    value="0">
            </div>
        </div>

        <div class="col-md-3 related_employees_edit" >
            <div class="form-group">
                <label>اجر اليوم الواحد</label>
                <input readonly type="text" name="day_price_edit" id="day_price_edit" class="form-control"
                    value="{{$data_row['day_price']*1}}">
            </div>
        </div>


        <div class="col-md-3 ">
            <div class="form-group">
                <label>نوع الجزاء</label>
                <select name="sactions_type_edit" id="sactions_type_edit" class="form-control">
                    <option disabled selected value="">اختر النوع</option>
                    <option @if ($data_row['sactions_type']==1) selected @endif value="1">جزاء ايام</option>
                    <option @if ($data_row['sactions_type']==2) selected @endif value="2">جزاء بصمة</option>
                    <option @if ($data_row['sactions_type']==3) selected @endif value="3">جزاء تحقيق</option>
                </select>
            </div>
        </div>

        <div class="col-md-3 ">
            <div class="form-group">
                <label>عدد اليوم الجزاء</label>
                <input type="text" name="value_edit" id="value_edit" class="form-control"
                    oninput="this.value=this.value.replace(/[^0-9.]/g,'');" value="{{$data_row['value']*1}}">
            </div>
        </div>

        <div class="col-md-3 ">
            <div class="form-group">
                <label>اجمالي قيمة الجزاء</label>
                <input type="text" readonly name="total_edit" id="total_edit" class="form-control" value="{{$data_row['total']*1}}">
            </div>
        </div>

        <div class="col-md-3 ">
            <div class="form-group">
                <label>ملاحظات</label>
                <input type="text" name="notes_edit" id="notes_edit" class="form-control" value="{{$data_row['notes']}}">
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group text-left">
                <button style="margin-top: 33px" id="do_edit_now" class="btn btn-sm btn-danger" type="submit" data-id="{{ $data_row['id'] }}"
                                            data-main_sal_id="{{ $data_row['main_salary_employee_id'] }}"
                    name="submit">تعديل الجزاء</button>
            </div>
        </div>


    </div>
@else
    <p class="bg-danger text-center">عفواً لا توجد بيانات لعرضها</p>
@endif
