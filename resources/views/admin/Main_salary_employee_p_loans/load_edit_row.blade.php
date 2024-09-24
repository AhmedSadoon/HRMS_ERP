@if (!@empty($data_row))
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label>بيانات الموظفين</label>
                <select name="employees_code_edit" id="employees_code_edit" class="form-control select2">
                    <option disabled selected value="">اختر الموظف</option>
                    @if (@isset($employees) && !@empty($employees))
                        @foreach ($employees as $info)
                            <option @if ($info->employees_code==$data_row['employees_code']) selected @endif value="{{ $info->employees_code }}" data-s="{{ $info->emp_salary }}"
                                data-dp="{{ $info->day_price }}"> {{ $info->emp_name}}
                                ({{ $info->employees_code }})
                            </option>
                        @endforeach
                    @endif
                </select>

            </div>
        </div>

        <div class="col-md-4 related_employees_edit" style="display: none">
            <div class="form-group">
                <label>راتب الموظف الشهري</label>
                <input readonly type="text" name="emp_salary_edit" id="emp_salary_edit" class="form-control"
                    value="{{$data_row['emp_salary']*1}}">
            </div>
        </div>

      

        <div class="col-md-4 ">
            <div class="form-group">
                <label>اجمالي قيمة السلفة المستديمة</label>
                <input type="text" name="total_edit" id="total_edit" class="form-control"
                    value="{{$data_row['total']*1}}">
            </div>
        </div>

        <div class="col-md-4 ">
            <div class="form-group">
                <label>عدد الشهور للاقساط</label>
                <input type="text" name="month_number_edit" id="month_number_edit" class="form-control"
                    value="{{$data_row['month_number']*1}}">
            </div>
        </div>

        <div class="col-md-4 ">
            <div class="form-group">
                <label>قيمة القسط الشهري</label>
                <input readonly type="text" name="month_kast_value_edit" id="month_kast_value_edit"
                    class="form-control" oninput="this.value=this.value.replace(/[^0-9.]/g,'');"
                    value="{{$data_row['month_kast_value']*1}}">
            </div>
        </div>

        <div class="col-md-4 ">
            <div class="form-group">
                <label>يبدأ سداد اول قسط في تاريخ</label>
                <input type="date" name="year_and_month_start_date_edit"
                    id="year_and_month_start_date_edit" class="form-control" value="{{$data_row['year_and_month_start_date']}}">
            </div>
        </div>

        <div class="col-md-8 ">
            <div class="form-group">
                <label>ملاحظات</label>
                <input type="text" name="notes_edit" id="notes_edit" class="form-control"
                    value="{{$data_row['notes']}}">
            </div>
        </div>

        <div class="col-md-2">
            <hr>
            <div class="form-group text-center">
                <button id="do_edit_now" class="btn btn-sm btn-danger" type="submit" data-id="{{ $data_row['id'] }}"
                                          
                    name="submit">تعديل السلفة</button>
            </div>
        </div>


    </div>
@else
    <p class="bg-danger text-center">عفواً لا توجد بيانات لعرضها</p>
@endif
