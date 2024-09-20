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
                <label>اجمالي قيمة السلفة</label>
                <input type="text" name="total_edit" id="total_edit" class="form-control" value="{{$data_row['total']*1}}">
            </div>
        </div>

        <div class="col-md-3 ">
            <div class="form-group">
                <label>ملاحظات</label>
                <input type="text" name="notes_edit" id="notes_edit" class="form-control" value="{{$data_row['notes']}}">
            </div>
        </div>

        <div class="col-md-12">
            <hr>
            <div class="form-group text-center">
                <button id="do_edit_now" class="btn btn-sm btn-danger" type="submit" data-id="{{ $data_row['id'] }}"
                                            data-main_sal_id="{{ $data_row['main_salary_employee_id'] }}"
                    name="submit">تعديل السلفة</button>
            </div>
        </div>


    </div>
@else
    <p class="bg-danger text-center">عفواً لا توجد بيانات لعرضها</p>
@endif