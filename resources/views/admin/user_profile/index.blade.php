@extends('layouts.admin')

@section('title')
    البيانات الشخصية
@endsection

@section('contentheader')
    البروفايل
@endsection

@section('contentheaderactivelink')
    <a href="{{ route('userProfile.index') }}">الصفحة الشخصية</a>
@endsection

@section('contentheaderactive')
    عرض
@endsection

@section('content')
 
    <div class="col-md-12">
        <div class="card">

            <div class="card-header">
                <h3 class="card-title card_title_center">تفاصيل البروفايل الشخصي </h3>
            </div>


            <div class="card-body">

                @if (@isset($data) and !@empty($data))
                    <table id="example2" class="table table-bordered table-hover">

                        <tr>
                            <td class="width30">اسم المستخدم كاملا</td>
                            <td>{{ $data['name'] }}</td>
                        </tr>

                        <tr>
                            <td class="width30">اسم المستخدم للدخول الى النظام</td>
                            <td>{{ $data['username'] }}</td>
                        </tr>

                        <tr>
                            <td class="width30">البريد الالكتروني </td>
                            <td>{{ $data['email'] }}</td>
                        </tr>

                        <tr>
                            <td class="width30">الصورة الشخصية</td>
                            <td> 
                                @if (!@empty($data['image']))
                                <img src="{{ asset('assets/admin/uploads') . '/' . $data['image'] }}"
                                    style="border-radius: 50%; width: 80px; height: 80px;" class="rounded-circle"
                                    alt="صورة الموظف">
                                
                            @endif
                        </td>
                        </tr>

                        <tr>
                            <td class="width30">حالة تفعيل المستخدم</td>
                            <td>
                                @if ($data['active'] == 1)
                                    مفعل
                                @else
                                    معطل
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <td class="width30">تاريخ الاضافة</td>
                            <td>
                                @php
                                    $dt = new DateTime($data['created_at']);
                                    $date = $dt->format('Y-m-d');
                                    $time = $dt->format('h:i');
                                    $newDateTime = date('a', strtotime($time));
                                    $newDateTimeType = $newDateTime == 'AM' ? 'صباحاً' : 'مساءً';
                                @endphp
                                {{ $date }}
                                {{ $time }}
                                {{ $newDateTimeType }}
                                بواسطة
                                {{ $data['name'] }}
                            </td>
                        </tr>

                        <tr>
                            <td class="width30">تاريخ اخر تحديث</td>
                            <td>
                                @if ($data['updated_at'] > '0' and $data['updated_by'] != null)
                                    @php
                                        $dt = new DateTime($data['updated_at']);
                                        $date = $dt->format('Y-m-d');
                                        $time = $dt->format('h:i');
                                        $newDateTime = date('a', strtotime($time));
                                        $newDateTimeType = $newDateTime == 'AM' ? 'صباحاً' : 'مساءً';
                                    @endphp
                                    {{ $date }}
                                    {{ $time }}
                                    {{ $newDateTimeType }}
                                    بواسطة
                                    {{ $data['name'] }}
                                    @else
                                    لايوجد تحديث
                                @endif

                                <a class="btn btn-sm btn-success" href="{{ route('userProfile.edit') }}">تعديل</a>

                            </td>
                        </tr>

                        
    

                      

                       
                    </table>
                    <br>
            
                @else
                    <p class="bg-danger text-center">عفواً لا توجد بيانات لعرضها</p>
                @endif


            </div>
        </div>
    </div>



@endsection

@section('script')
    <script></script>
@endsection
