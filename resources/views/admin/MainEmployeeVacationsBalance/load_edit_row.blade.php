@if (!@empty( $other['data_row']) && !@empty( $other['CurrentOpenMonth']))
    <div class="row">
        


        <div class="col-md-4 related_employees_edit" >
            <div class="form-group">
                <label>الرصيد المستهلك لهذا الشهر</label>
                <input type="text" name="spent_balance_edit" id="spent_balance_edit" class="form-control"
                    value="{{$other['data_row']['spent_balance']}}">
            </div>
        </div>


       <div class="col-md-3">
            <div class="form-group text-left">
                <button style="margin-top: 33px" id="do_edit_now" class="btn btn-sm btn-danger" type="submit" data-id="{{ $other['data_row']['id'] }}"
                                             
                    name="submit">تعديل الرصيد الان</button>
            </div>
        </div> 


    </div>
@else
    <p class="bg-danger text-center">عفواً لا توجد بيانات لعرضها</p>
@endif
