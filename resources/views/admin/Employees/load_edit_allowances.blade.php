@if (!@empty($data))
    


        <form action="{{ route('Employees.do_edit_allowances', $data['id']) }}" method="POST">
            @csrf
            <div class="row">
            <div class="col-md-4 ">
                <div class="form-group">
                    <label>قيمة البدل</label>
                    <input required type="text" name="allowances_value_edit" id="allowances_value_edit" class="form-control"
                        oninput="this.value=this.value.replace(/[^0-9.]/g,'');" value="{{ $data['value'] * 1 }}">
                </div>
            </div>



            <div class="col-md-4">
                <div class="form-group text-left">
                    <button style="margin-top: 33px" id="do_allowances_value_edit" class="btn btn-sm btn-danger" type="submit"
                        data-id="{{ $data['id'] }}" 
                        name="submit">تعديل قيمة البدل</button>
                </div>
            </div>
        </div>
        </form>
   
    
@else
    <p class="bg-danger text-center">عفواً لا توجد بيانات لعرضها</p>
@endif
