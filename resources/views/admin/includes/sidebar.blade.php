<div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
            <img src="{{ asset('assets/admin/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2"
                alt="User Image">
        </div>
        <div class="info">
            <a href="#" class="d-block">{{ auth()->user()->name }}</a>
        </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->
            {{-- قائمة الضبط العام   --}}
            <li
                class="nav-item has-treeview  {{ request()->is('admin/generalSettings*') || request()->is('admin/finance_calender*') || request()->is('admin/branches*') || request()->is('admin/Shiftstypes*') || request()->is('admin/departements*') || request()->is('admin/JobsCategories*') || request()->is('admin/Qualifications*') || request()->is('admin/Occasions*') || request()->is('admin/Resignations*') || request()->is('admin/Nationalities*') || request()->is('admin/Religions*') ? 'menu-open' : '' }}">
                <a href="#"
                    class="nav-link {{ request()->is('admin/generalSettings*') || request()->is('admin/finance_calender*') || request()->is('admin/branches*') || request()->is('admin/Shiftstypes*') || request()->is('admin/departements*') || request()->is('admin/JobsCategories*') || request()->is('admin/Qualifications*') || request()->is('admin/Occasions*') || request()->is('admin/Resignations*') || request()->is('admin/Nationalities*') || request()->is('admin/Religions*') ? 'active' : '' }} ">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>
                        قائمة الضبط
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('admin_panel_settings.index') }}"
                            class="nav-link {{ request()->is('admin/generalSettings*') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>الضبط العام</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('finance_calender.index') }}"
                            class="nav-link {{ request()->is('admin/finance_calender*') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>السنوات المالية</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('branches.index') }}"
                            class="nav-link {{ request()->is('admin/branches*') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>الفروع</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('shiftsTypes.index') }}"
                            class="nav-link {{ request()->is('admin/Shiftstypes*') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>انواع الشفتات</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('departements.index') }}"
                            class="nav-link {{ request()->is('admin/departements*') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>ادارات الموظفين</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('JobsCategories.index') }}"
                            class="nav-link {{ request()->is('admin/JobsCategories*') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>فئات الوظائف</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('Qualifications.index') }}"
                            class="nav-link {{ request()->is('admin/Qualifications*') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>مؤهلات الموظف</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('Occasions.index') }}"
                            class="nav-link {{ request()->is('admin/Occasions*') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>المناسبات الرسمية</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('Resignations.index') }}"
                            class="nav-link {{ request()->is('admin/Resignations*') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>انواع ترك العمل</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('Nationalities.index') }}"
                            class="nav-link {{ request()->is('admin/Nationalities*') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>انواع الجنسيات</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('Religions.index') }}"
                            class="nav-link {{ request()->is('admin/Religions*') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>الديانات</p>
                        </a>
                    </li>

                </ul>
            </li>
            {{-- نهاية قائمة الضبط العام --}}

            {{-- بداية شؤون الموظفين --}}

            <li class="nav-item has-treeview  {{ request()->is('admin/Employees*')||request()->is('admin/additionalTypes*')||request()->is('admin/DiscountType*')||request()->is('admin/Allowances*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ request()->is('admin/Employees*')||request()->is('admin/additionalTypes*')||request()->is('admin/DiscountType*')||request()->is('admin/Allowances*') ? 'active' : '' }} ">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>
                        قائمة شؤون الموظفين
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('Employees.index') }}"
                            class="nav-link {{ request()->is('admin/Employees*') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>بيانات الموظفين</p>
                        </a>
                    </li>

              

                    <li class="nav-item">
                        <a href="{{ route('additionalTypes.index') }}"
                            class="nav-link {{ request()->is('admin/additionalTypes*') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>انواع الاضافي للراتب</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('DiscountType.index') }}"
                            class="nav-link {{ request()->is('admin/DiscountType*') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>انواع خصم الراتب</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('Allowances.index') }}"
                            class="nav-link {{ request()->is('admin/Allowances*') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>انواع بدل الراتب</p>
                        </a>
                    </li>

                  

                </ul>
            </li>
            {{-- نهاية شؤون الموظفين --}}

              {{-- بداية الرواتب --}}

              <li class="nav-item has-treeview  {{ request()->is('admin/MainSalaryRecord*')||request()->is('admin/MainSalarySanctions*')||request()->is('admin/MainSalaryAbsence*')||request()->is('admin/MainSalaryAddition*')  ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ request()->is('admin/MainSalaryRecord*')||request()->is('admin/MainSalarySanctions*')||request()->is('admin/MainSalaryAbsence*')||request()->is('admin/MainSalaryAddition*')  ? 'active' : '' }} ">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>
                        قائمة رواتب الموظفين
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('MainSalaryRecord.index') }}"
                            class="nav-link {{ request()->is('admin/MainSalaryRecord*') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>السجلات الرئيسية للرواتب</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('MainSalarySanctions.index') }}"
                            class="nav-link {{ request()->is('admin/MainSalarySanctions*') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>الجزاءات</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('MainSalaryAbsence.index') }}"
                            class="nav-link {{ request()->is('admin/MainSalaryAbsence*') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>الغيابات</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('MainSalaryAddition.index') }}"
                            class="nav-link {{ request()->is('admin/MainSalaryAddition*') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>الاضافي</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('MainSalaryRecord.index') }}"
                            class="nav-link {{ request()->is('admin/MainSalaryRecord*') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>الخصومات المالية</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('MainSalaryRecord.index') }}"
                            class="nav-link {{ request()->is('admin/MainSalaryRecord*') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>المكافئات المالية</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('MainSalaryRecord.index') }}"
                            class="nav-link {{ request()->is('admin/MainSalaryRecord*') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>البدلات المتغيرة</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('MainSalaryRecord.index') }}"
                            class="nav-link {{ request()->is('admin/MainSalaryRecord*') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>فواتير الهواتف</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('MainSalaryRecord.index') }}"
                            class="nav-link {{ request()->is('admin/MainSalaryRecord*') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>السلف الشهرية</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('MainSalaryRecord.index') }}"
                            class="nav-link {{ request()->is('admin/MainSalaryRecord*') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>السلف المستديمة</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('MainSalaryRecord.index') }}"
                            class="nav-link {{ request()->is('admin/MainSalaryRecord*') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>رواتب الموظفين مفصلة</p>
                        </a>
                    </li>

              

                    

                  

                </ul>
            </li>
            {{-- نهاية الرواتب  --}}
        </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>
