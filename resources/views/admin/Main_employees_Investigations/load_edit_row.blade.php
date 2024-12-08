@if (!@empty($finance_cin_periods_data)  && !@empty($data_row))
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label>بيانات الموظفين</label>
                <select name="employees_code_edit" id="employees_code_edit" class="form-control select2">
                    <option disabled selected value="">اختر الموظف</option>
                    @if (@isset($employees) && !@empty($employees))
                        @foreach ($employees as $info)
                            <option @if ($info->employees_code==$data_row['employees_code']) selected @endif value="{{ $info->employees_code }}"> {{ $info->emp_name }}
                                ({{ $info->employees_code }})
                            </option>
                        @endforeach
                    @endif
                </select>

            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>محتوى التحقيق</label>
                <textarea rows="6" type="text" name="the_content_edit" id="the_content_edit" class="form-control">{{ $data_row['content'] }}</textarea>
            </div>
        </div>

        <div class="col-md-12 ">
            <div class="form-group">
                <label>ملاحظات</label>
                <input type="text" name="notes_edit" id="notes_edit" class="form-control" value="{{ $data_row['notes'] }}">
            </div>
        </div>


        <div class="col-md-12">
            <div class="form-group text-center">
                <button id="do_edit_now" class="btn btn-sm btn-danger" type="submit" data-id="{{ $data_row['id'] }}" name="submit">تعديل التحقيق</button>
            </div>
        </div>


    </div>
@else
    <p class="bg-danger text-center">عفواً لا توجد بيانات لعرضها</p>
@endif
