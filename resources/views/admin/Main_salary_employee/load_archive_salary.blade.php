@if (!@empty($finance_cin_periods_data) && !@empty($main_salary_employee_data))
    <form action="{{ route('MainSalaryEmployee.do_archive_salary', $main_salary_employee_data['id']) }}" method="POST">
        @csrf


        <div class="form-group">
            <label>حالة راتب الموظف الان</label>
            <select name="salaryStatusNowBeforArchive" id="salaryStatusNowBeforArchive" class="form-control">

                @if ($main_salary_employee_data['final_the_net'] > 0)
                    <option value="1">دائن ومستحق له</option>
                @elseif ($main_salary_employee_data['final_the_net'] < 0)
                    <option value="2">مدين ومستحق عليه</option>
                @else
                    <option value="0">متزن</option>
                @endif
            </select>
        </div>



        <div class="form-group">

            @if ($main_salary_employee_data['final_the_net'] > 0)
                <label>صافي المبلغ المستحق له</label>
                <input readonly type="text" name="final_the_net" id="final_the_net"
                    class="form-control" value="{{ $main_salary_employee_data['final_the_net'] * 1 }}">
            @elseif ($main_salary_employee_data['final_the_net'] < 0)
                <label>صافي المبلغ المستحق عليه</label>
                <input readonly type="text" name="final_the_net" id="final_the_net"
                    class="form-control" value="{{ $main_salary_employee_data['final_the_net'] * 1 * -1 }}">
            @else
                <label>قيمة المبلغ متزن</label>
                <input readonly type="text" name="final_the_net" id="final_the_net"
                    class="form-control" value="{{ $main_salary_employee_data['final_the_net'] * 1 }}">
            @endif

        </div>

        <div class="form-group">

            @if ($main_salary_employee_data['final_the_net'] > 0)
                <label>صافي المبلغ المصروف له الان</label>
                <input readonly type="text" name="action_money_value_now" id="action_money_value_now"
                    class="form-control" value="{{ $main_salary_employee_data['final_the_net'] * 1 }}" data-max="{{$main_salary_employee_data['final_the_net']}}">
            @elseif ($main_salary_employee_data['final_the_net'] < 0)
                <label>صافي المبلغ المدين به الموظف وسيرحل للشهر القادم </label>
                <input readonly type="text" name="action_money_value_now" id="action_money_value_now"
                    class="form-control" value="{{ $main_salary_employee_data['final_the_net'] * 1 * -1 }}" data-max="{{$main_salary_employee_data['final_the_net']}}">
            @else
                <label>قيمة المبلغ متزن</label>
                <input readonly type="text" name="action_money_value_now" id="action_money_value_now"
                    class="form-control" value="{{ $main_salary_employee_data['final_the_net'] * 1 }}" data-max="{{$main_salary_employee_data['final_the_net']}}">
            @endif

        </div>





        <div class="form-group text-center">
            <button id="do_archive_now_btn" class="btn btn-sm btn-danger" type="submit" name="submit">ارشفة
                الراتب الان</button>
        </div>





    </form>
@else
    <p class="bg-danger text-center">عفواً لا توجد بيانات لعرضها</p>
@endif
