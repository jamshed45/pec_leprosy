<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>Leprosy Hospital</title>
<!-- Tell the browser to be responsive to screen width -->
<meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1" />

<!-- v4.0.0-alpha.6 -->
<link rel="stylesheet" href="{{ URL::asset('dist/bootstrap/css/bootstrap.min.css') }}">

<!-- Google Font -->
<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">

<!-- Theme style -->
<link rel="stylesheet" href="{{ URL::asset('dist/css/style.css') }}">
<link rel="stylesheet" href="{{ URL::asset('dist/css/font-awesome/css/font-awesome.min.css') }}">
<link rel="stylesheet" href="{{ URL::asset('dist/css/et-line-font/et-line-font.css') }}">
<link rel="stylesheet" href="{{ URL::asset('dist/css/themify-icons/themify-icons.css') }}">

<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

{{-- <link rel="stylesheet" href="{{ URL::asset('dist/plugins/bootstrap-switch/bootstrap-switch.css') }}"> --}}

<link rel="stylesheet" href="{{ URL::asset('dist/plugins/datatables/css/dataTables.bootstrap.min.css') }}">

<!-- form wizard -->
<link rel="stylesheet" href="{{ URL::asset('dist/plugins/formwizard/jquery-steps.css') }}">

<link rel="stylesheet" href="{{ URL::asset('css/app.css') }}">

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

<!-- jQuery 3 -->
<script src="{{ URL::asset('dist/js/jquery.min.js') }}"></script>
<script src="{{ URL::asset('dist/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- v4.0.0-alpha.6 -->
<script src="https://npmcdn.com/tether@1.2.4/dist/js/tether.min.js"></script>
<script src="{{ URL::asset('dist/bootstrap/js/bootstrap.min.js') }}"></script>

@php
$user = Auth::user();
@endphp
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper boxed-wrapper">
  <header class="main-header">
    <!-- Logo -->
    <a href="index.html" class="logo blue-bg">
    Leprosy PEC </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar blue-bg navbar-static-top">
      <!-- Sidebar toggle button-->
      <ul class="nav navbar-nav pull-left">
        <li><a class="sidebar-toggle" data-toggle="push-menu" href=""></a> </li>
      </ul>
      <div class="pull-left search-box">

        <!-- search form --> </div>
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">

          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu p-ph-res"> <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            @if($user->img)
              <img src="{{ url('uploads') }}/{{ $user->img }}" class="user-image" alt="User Image">
            @else
              <img src="{{ URL::asset('dist/img/img1.jpg') }}" class="user-image" alt="User Image">
            @endif


            <span class="hidden-xs">{{ $user->name }}</span> </a>
            <ul class="dropdown-menu">

              <li class="user-header">

                <div class="pull-left user-img">
                  @if($user->img)
                    <img src="{{ url('uploads') }}/{{ $user->img }}" class="img-responsive" alt="User">
                  @else
                    <img src="{{ URL::asset('dist/img/img1.jpg') }}" class="img-responsive" alt="User">
                  @endif
                </div>
                <p class="text-left">{{ $user->name }} <small>{{ $user->email }}</small> </p>
                <div class="view-link text-left"><a href="{{ URL::to('profile/edit-profile') }}/{{ $user->id }}">View Profile</a> </div>
              </li>
              {{-- <li><a href="#"><i class="icon-profile-male"></i> My Profile</a></li>
              <li><a href="#"><i class="icon-wallet"></i> My Balance</a></li>
              <li><a href="#"><i class="icon-envelope"></i> Inbox</a></li>
              <li role="separator" class="divider"></li>
              <li><a href="#"><i class="icon-gears"></i> Account Setting</a></li> --}}
              <li role="separator" class="divider"></li>
              <li><a href="{{ route('signout') }}"><i class="fa fa-power-off"></i> Logout</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <div class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="image text-center"><img src="{{ URL::asset('dist/img/logo.png') }}" class="img-circle" alt="User Image"> </div>
        <div class="info">
          {{-- <p>Alexander Pierce</p> --}}
          {{-- <a href="#"><i class="fa fa-cog"></i></a> <a href="#"><i class="fa fa-envelope-o"></i></a>  --}}
          <a href="{{ route('signout') }}"><i class="fa fa-power-off"></i></a> </div>
      </div>

      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">

        <li class="header">LINKS</li>
        <li class="treeview"> <a href="#"> <i class="fa fa-dashboard"></i> <span>Dashboard</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
          <ul class="treeview-menu">
            <li><a href="{{ URL::to('dashboard?date=') }}@php echo date('Y-m-d') @endphp">Dashboard Day Wise</a></li>
            <li><a href="{{ URL::to('dashboard?month=') }}@php echo date('Y-m') @endphp">Dashboard Month Wise</a></li>
            <li><a href="{{ URL::to('dashboard?year=') }}@php echo date('Y') @endphp">Dashboard Year Wise</a></li>
          </ul>
        </li>
        @php
          $center_session = session()->get('center_info');
          if(isset($center_session))
            {
              $disbled_class = '';
            }
            else
            {
              $disbled_class = 'disabled-btns';
            }
        @endphp

        @if ($user->hasRole('pec-user'))
          <li class="treeview"> <a href="#"> <i class="fa fa-bullseye"></i> <span>My Centers</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
            <ul class="treeview-menu">
              <li><a href="{{ URL::to('centers/my-center') }}">My Centers</a></li>
              <li><a href="{{ URL::to('centers/add-my-center') }}">Add My Center</a></li>
            </ul>
          </li>
          <li class="treeview"> <a href="#"> <i class="fa fa-bullseye"></i> <span>Reports</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
            <ul class="treeview-menu">
              <li><a href="{{ URL::to('patients-entries/all-patients-entries-report') }}">All Patient Entries Report</a></li>
              <li><a href="{{ URL::to('referrals-reports/all-referrals-report') }}">All Refferals Report</a></li>
            </ul>
          </li>
          <li class="treeview"> <a href="#"> <i class="fa fa-bullseye"></i> <span>TA DA Bills</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
            <ul class="treeview-menu">
              <li><a href="{{ URL::to('daily-allowance/') }}">All DA Bills</a></li>
              <li><a href="{{ URL::to('daily-allowance/create') }}">Add DA Bill for {{ date('F') }}</a></li>
              <li><a href="{{ URL::to('travelling-allowance/') }}">All TA Bills</a></li>
              <li><a href="{{ URL::to('travelling-allowance/create') }}">Add TA Bill for {{ date('F') }}</a></li>
            </ul>
          </li>
          <li class="treeview"> <a href="#"> <i class="fa fa-bullseye"></i> <span>Advance Tour Prog</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
            <ul class="treeview-menu">
              <li><a href="{{ URL::to('advance-tour-programme/') }}">All ATP</a></li>
              <li><a href="{{ URL::to('advance-tour-programme/create') }}">Add ATP
                @php
                  $date = date("F", strtotime(" +1 months"));
                echo $date;
                @endphp</a></li>
            </ul>
          </li>
          <li class="treeview"> <a href="#"> <i class="fa fa-bullseye"></i> <span>Tour Dairy</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
            <ul class="treeview-menu">
              <li><a href="{{ URL::to('tour-dairy/') }}">All Tour Dairies</a></li>
              <li><a href="{{ URL::to('tour-dairy/create') }}">Add Tour Dairy
                @php
                  $date = date("F", strtotime(" +1 months"));
                echo $date;
                @endphp</a></li>
            </ul>
          </li>
          <li class="treeview"> <a href="#"> <i class="fa fa-bullseye"></i> <span>Refferal Patients</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
            <ul class="treeview-menu">
              <li><a class="<?=$disbled_class;?>" href="{{ route('refferal-forms.index') }}">View Refferal Patients</a></li>
              <li><a class="<?=$disbled_class;?>" href="{{ route('refferal-forms.create') }}">Add Refferal Patient</a></li>
              <li><a class="<?=$disbled_class;?>" href="{{ URL::to('refferal-forms/add-old-refferal-patient') }}">Add Old Refferal Patient</a></li>

            </ul>
          </li>
          @php
            $form_links = DB::table('pec_form_categories')
                              ->where('parent_id', '=', NULL )
                              ->orderBy('main_cat_order_by', 'asc')
                              ->get()
                              ->toArray();
          @endphp
          <li class=""> <a href="#"> <i class="fa fa-envelope-o "></i> <span>Entries</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
            <ul class="emp-entries">
              <li class="emp-all-entries"><a href="{{ URL::to('patient-entries/') }}">All Entries</a></li>
              @foreach ($form_links as $link)
                <li><a class="<?=$disbled_class;?>" href="{{ URL::to('patient-entries/') }}{{ '/'.$link->slug }}">{{ $link->category_name }}</a></li>
              @endforeach
            </ul>
          </li>
        @endif

        @if ($user->hasRole('pec-admin'))
          <li class="treeview"> <a href="#"> <i class="fa fa-bullseye"></i> <span>Users</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
            <ul class="treeview-menu">
              <li><a href="{{ route('users.index') }}">View Users</a></li>
              <li><a href="{{ route('users.create') }}">Add User</a></li>
            </ul>
          </li>
          <li class="header">TA DA Bills</li>
          <li class="treeview"> <a href="#"> <i class="fa fa-bullseye"></i> <span>TA DA Bills</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
            <ul class="treeview-menu">
              <li><a href="{{ URL::to('daily-allowance/') }}">All DA Bills</a></li>
              <li><a href="{{ URL::to('travelling-allowance/') }}">All TA Bills</a></li>
            </ul>
          </li>
          <li class="treeview"> <a href="#"> <i class="fa fa-bullseye"></i> <span>Advance Tour Prog</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
            <ul class="treeview-menu">
              <li><a href="{{ URL::to('advance-tour-programme/') }}">All Advance Tour Programms</a></li>
            </ul>
          </li>
          <li class="header">REPORTS</li>
          <li class="treeview"> <a href="#"> <i class="fa fa-bullseye"></i> <span>Refferal Patients</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
            <ul class="treeview-menu">
              <li><a href="{{ route('refferal-forms.index') }}">View All Refferal Patients</a></li>
              <li><a href="{{ URL::to('refferal-forms/refferal-patients-by-user') }}">Refferal Patients By User</a></li>
              <li><a href="{{ URL::to('refferal-forms/refferal-patients-by-center') }}">Refferal Patients By Center</a></li>
              <li><a href="{{ URL::to('refferal-forms/refferal-patients-by-location') }}">Refferal Patients By Location</a></li>
              <li><a href="{{ URL::to('refferal-forms/refferal-patients-by-tehsil') }}">Refferal Patients By Tehsil</a></li>
              <li><a href="{{ URL::to('refferal-forms/refferal-patients-by-district') }}">Refferal Patients By District</a></li>
              <li><a href="{{ URL::to('refferal-forms/refferal-patients-by-division') }}">Refferal Patients By Division</a></li>
              <li><a href="{{ URL::to('refferal-forms/refferal-patients-by-province') }}">Refferal Patients By Province</a></li>
            </ul>
          </li>
          <li class="treeview"> <a href="#"> <i class="fa fa-bullseye"></i> <span>Patients Entries</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
            <ul class="treeview-menu">
              <li><a href="{{ URL::to('patients-entries/view-all-patient-entries') }}">View All Patients Entries</a></li>
              <li><a href="{{ URL::to('patients-entries/patients-entries-by-user') }}">Patients Entries By User</a></li>
              <li><a href="{{ URL::to('patients-entries/patients-entries-by-center') }}">Patients Entries By Center</a></li>
              <li><a href="{{ URL::to('patients-entries/patients-entries-by-location') }}">Refferal Patients By Location</a></li>
              <li><a href="{{ URL::to('patients-entries/patients-entries-by-tehsil') }}">Patients Entries By Tehsil</a></li>
              <li><a href="{{ URL::to('patients-entries/patients-entries-by-district') }}">Patients Entries By District</a></li>
              <li><a href="{{ URL::to('patients-entries/patients-entries-by-division') }}">Patients Entries By Division</a></li>
              <li><a href="{{ URL::to('patients-entries/patients-entries-by-province') }}">Patients Entries By Province</a></li>
            </ul>
          </li>
          <li class="treeview"> <a href="#"> <i class="fa fa-bullseye"></i> <span>Refferal</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
            <ul class="treeview-menu">
              <li><a href="{{ URL::to('referrals-reports') }}">View All Refferal</a></li>
              <li><a href="{{ URL::to('referrals-reports/referrals-reports-by-user') }}">Refferal By User</a></li>
              <li><a href="{{ URL::to('referrals-reports/referrals-reports-by-center') }}">Refferal By Center</a></li>
              <li><a href="{{ URL::to('referrals-reports/referrals-reports-by-location') }}">Refferal By Location</a></li>
              <li><a href="{{ URL::to('referrals-reports/referrals-reports-by-tehsil') }}">Refferal By Tehsil</a></li>
              <li><a href="{{ URL::to('referrals-reports/referrals-reports-by-district') }}">Refferal By District</a></li>
              <li><a href="{{ URL::to('referrals-reports/referrals-reports-by-division') }}">Refferal By Division</a></li>
              <li><a href="{{ URL::to('referrals-reports/referrals-reports-by-province') }}">Refferal By Province</a></li>
            </ul>
          </li>
          <li class="treeview"> <a href="#"> <i class="fa fa-bullseye"></i> <span>Confirmed Refferal</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
            <ul class="treeview-menu">
              <li><a href="{{ URL::to('confirmed-referrals-reports') }}">View All Refferal</a></li>
              <li><a href="{{ URL::to('confirmed-referrals-reports/confirmed-referrals-reports-by-user') }}">Confirmed Refferal By User</a></li>
              <li><a href="{{ URL::to('confirmed-referrals-reports/confirmed-referrals-reports-by-center') }}">Confirmed Refferal By Center</a></li>
              <li><a href="{{ URL::to('confirmed-referrals-reports/confirmed-referrals-reports-by-location') }}">Confirmed Refferal By Location</a></li>
              <li><a href="{{ URL::to('confirmed-referrals-reports/confirmed-referrals-reports-by-tehsil') }}">Confirmed Refferal By Tehsil</a></li>
              <li><a href="{{ URL::to('confirmed-referrals-reports/confirmed-referrals-reports-by-district') }}">Confirmed Refferal By District</a></li>
              <li><a href="{{ URL::to('confirmed-referrals-reports/confirmed-referrals-reports-by-division') }}">Confirmed Refferal By Division</a></li>
              <li><a href="{{ URL::to('confirmed-referrals-reports/confirmed-referrals-reports-by-province') }}">Confirmed Refferal By Province</a></li>
            </ul>
          </li>
          <li class="treeview"> <a href="#"> <i class="fa fa-bullseye"></i> <span>Diabetic Screening</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
            <ul class="treeview-menu">
              <li><a href="{{ URL::to('diabetic-screening-reports') }}">View All Diabetic Screening</a></li>
              <li><a href="{{ URL::to('diabetic-screening-reports/diabetic-screening-reports-by-user') }}">Diabetic Screening By User</a></li>
              <li><a href="{{ URL::to('diabetic-screening-reports/diabetic-screening-reports-by-center') }}">Diabetic Screening By Center</a></li>
              <li><a href="{{ URL::to('diabetic-screening-reports/diabetic-screening-reports-by-location') }}">Diabetic Screening By Location</a></li>
              <li><a href="{{ URL::to('diabetic-screening-reports/diabetic-screening-reports-by-tehsil') }}">Diabetic Screening By Tehsil</a></li>
              <li><a href="{{ URL::to('diabetic-screening-reports/diabetic-screening-reports-by-district') }}">Diabetic Screening By District</a></li>
              <li><a href="{{ URL::to('diabetic-screening-reports/diabetic-screening-reports-by-division') }}">Diabetic Screening By Division</a></li>
              <li><a href="{{ URL::to('diabetic-screening-reports/diabetic-screening-reports-by-province') }}">Diabetic Screening By Province</a></li>
            </ul>
          </li>
          <li class="header">HEALTH FACILITIES</li>
          <li class="treeview"> <a href="#"> <i class="fa fa-bullseye"></i> <span>Doctors</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
            <ul class="treeview-menu">
              <li><a href="{{ route('doctors.index') }}">View Doctors</a></li>
              <li><a href="{{ route('doctors.create') }}">Add Doctor</a></li>
            </ul>
          </li>
          <li class="treeview"> <a href="#"> <i class="fa fa-bullseye"></i> <span>Health Facilities</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
            <ul class="treeview-menu">
              <li><a href="{{ route('health-facilities.index') }}">View Health Facilities</a></li>
              <li><a href="{{ route('health-facilities.create') }}">Add Health Facility</a></li>
            </ul>
          </li>
          <li class="header">CENTERS PANEL</li>
          <li class="treeview"> <a href="#"> <i class="fa fa-bullseye"></i> <span>Provinces</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
            <ul class="treeview-menu">
              <li><a href="{{ route('provinces.index') }}">View Provinces</a></li>
              <li><a href="{{ route('provinces.create') }}">Add Province</a></li>
            </ul>
          </li>
          <li class="treeview"> <a href="#"> <i class="fa fa-bullseye"></i> <span>Divisions</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
            <ul class="treeview-menu">
              <li><a href="{{ route('divisions.index') }}">View Divisions</a></li>
              <li><a href="{{ route('divisions.create') }}">Add Division</a></li>
            </ul>
          </li>
          <li class="treeview"> <a href="#"> <i class="fa fa-bullseye"></i> <span>Districts</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
            <ul class="treeview-menu">
              <li><a href="{{ route('districts.index') }}">View Districts</a></li>
              <li><a href="{{ route('districts.create') }}">Add District</a></li>
            </ul>
          </li>
          <li class="treeview"> <a href="#"> <i class="fa fa-bullseye"></i> <span>Tehsils</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
            <ul class="treeview-menu">
              <li><a href="{{ route('tehsils.index') }}">View Tehsils</a></li>
              <li><a href="{{ route('tehsils.create') }}">Add Tehsil</a></li>
            </ul>
          </li>
          <li class="treeview"> <a href="#"> <i class="fa fa-bullseye"></i> <span>Centers</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
            <ul class="treeview-menu">
              <li><a href="{{ route('centers.index') }}">View Centers</a></li>
              <li><a href="{{ route('centers.create') }}">Add Center</a></li>
            </ul>
          </li>
        @endif
        @if ($user->hasRole('pec-data-verifier'))
        <li class="treeview"> <a href="#"> <i class="fa fa-bullseye"></i> <span>Refferal Patients</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
          <ul class="treeview-menu">
            <li><a href="{{ URL::to('refferal-forms/view-unverified-refferal') }}">View Unverified Refferal</a></li>
            <li><a href="{{ URL::to('refferal-forms/view-verified-refferal') }}">View Verified Refferal</a></li>
          </ul>
        </li>
        <li class="treeview"> <a href="#"> <i class="fa fa-bullseye"></i> <span>TA DA Bills</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
          <ul class="treeview-menu">
            <li><a href="{{ URL::to('daily-allowance/') }}">All DA Bills</a></li>
            <li><a href="{{ URL::to('travelling-allowance/') }}">All TA Bills</a></li>
          </ul>
        </li>
        <li class="treeview"> <a href="#"> <i class="fa fa-bullseye"></i> <span>Advance Tour Prog</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
          <ul class="treeview-menu">
            <li><a href="{{ URL::to('advance-tour-programme/') }}">All Advance Tour Programms</a></li>
          </ul>
        </li>
        <li class="treeview"> <a href="#"> <i class="fa fa-bullseye"></i> <span>Patient Entries</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
          <ul class="treeview-menu">
            @php
             $pec_users =  DB::table('users')
             ->join('users_roles', 'users_roles.user_id', '=', 'users.id')
             ->where('users_roles.role_id', 5)
             ->get()->toArray();
            @endphp
            @foreach($pec_users as $pec_user)
              @php
              $record_count = DB::table('pec_center_patients_record')
                ->where('records_set_unique_id', '!=', '')
                ->where('user_id', '=', $pec_user->id)
                ->where('verify', '=', 0)
                ->count();
                // print_r($record_count);
                // echo "------------";
                // echo "<br>";
              @endphp

            @if($record_count != 0)
            <li style="position: relative;">
            <a href="{{ URL::to('patient-entries/verify-entries') }}/{{ $pec_user->id }}">{{ $pec_user->name }}</a>
            <span type="button" class="btn btn-sm btn-warning" style=" position: absolute; right:0; top:0; margin-right: 8px;
            margin-top: 6px;
            font-size: 10px;
            float: right;
            padding: 4px;">{{ $record_count }}</span>
            </li>
            @endif
            @endforeach
          </ul>
        </li>
        @endif




      </ul>
    </div>
    <!-- /.sidebar -->
  </aside>



  @yield('content')


  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="pull-right hidden-xs">Version 1.0</div>
    Copyright © @php
      echo date('Y');
    @endphp Leprosy PEC. All rights reserved.</footer>
</div>
<!-- ./wrapper -->



<!-- template -->
<script src="{{ URL::asset('dist/js/niche.js') }}"></script>

{{-- <script src="{{ URL::asset('dist/plugins/bootstrap-switch/bootstrap-switch.js') }}"></script>
<script src="{{ URL::asset('dist/plugins/bootstrap-switch/highlight.js') }}"></script>
<script src="{{ URL::asset('dist/plugins/bootstrap-switch/main.js') }}"></script> --}}


<script src="{{ URL::asset('js/app.js') }}"></script>



<script src="{{ URL::asset('dist/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('dist/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>

@php
$route = Route::current()->getName();
@endphp
{{-- @if($route === 'refferal-forms.create' ||  $route === 'refferal-forms.edit' ||  $route === 'refferal-forms.edit') --}}
<!-- form wizard -->
<script src="{{ URL::asset('dist/plugins/formwizard/jquery-steps.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>

<script>
    var frmRes = $('#frmRes');
    var frmResValidator = frmRes.validate();

    var frmInfo = $('#frmInfo');
    var frmInfoValidator = frmInfo.validate();

    var frmLogin = $('#frmLogin');
    var frmLoginValidator = frmLogin.validate();

    var frmMobile = $('#frmMobile');
    var frmMobileValidator = frmMobile.validate();

    $('#demo1').steps({
      onChange: function (currentIndex, newIndex, stepDirection) {
        console.log('onChange', currentIndex, newIndex, stepDirection);
        // tab1
        if (currentIndex === 0) {
          if (stepDirection === 'forward') {
            var valid = frmRes.valid();
            return valid;
          }
          if (stepDirection === 'backward') {
            frmResValidator.resetForm();
          }
        }

		// tab2
        if (currentIndex === 1) {
          if (stepDirection === 'forward') {
            var valid = frmInfo.valid();
            return valid;
          }
          if (stepDirection === 'backward') {
            frmInfoValidator.resetForm();
          }
        }

        // tab3
        if (currentIndex === 2) {
          if (stepDirection === 'forward') {
            var valid = frmLogin.valid();
            return valid;
          }
          if (stepDirection === 'backward') {
            frmLoginValidator.resetForm();
          }
        }

        // tab4
        if (currentIndex === 3) {
          if (stepDirection === 'forward') {
            var valid = frmMobile.valid();
            return valid;
          }
          if (stepDirection === 'backward') {
            frmMobileValidator.resetForm();
          }
        }

        return true;

      },
      onFinish: function () {
        alert('Wizard Completed');
      }
    });
  </script>
<script>
    $('#demo').steps({
      onFinish: function () {
        alert('Wizard Completed');
      }
    });
  </script>
{{-- @endif --}}

@stack('js')
<script>

  $(function () {
    $('#example1').DataTable()
    $('#example').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : false,
      'info'        : true,
      'autoWidth'   : false
    })
  })


  $(document).ready(function(){

       $('#select_province').change(function(e){
          e.preventDefault();
          var cities_url  = $(this).attr("data-url");

          console.log($(this).val());
        console.log(cities_url);

          // return;
        //   $.ajaxSetup({
        //      headers: {
        //          'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        //      }
        //  });
         $.ajax({
             url: cities_url,
             method: 'get',
             data: {
                province_id: $(this).val(),
             },
             success: function(result){
                console.log(result);
                $('#select_city').html(result);
                $('#select_city').prop("disabled", false);
                $('#select_center').html('');
             }});
          });

          $('#select_city').change(function(e){
          e.preventDefault();
          var centers_url  = $(this).attr("data-url");
          console.log(centers_url);
        //   $.ajaxSetup({
        //      headers: {
        //          'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        //      }
        //  });
         $.ajax({
             url: centers_url,
             method: 'get',
             data: {
                tehsil_id: $(this).val(),
             },
             success: function(result){
                console.log(result);
                // $('#select_city').html(result);
                // $('#select_city').prop("disabled", false);
                $('#select_center').html(result);
             }});

          });




      $('#set_center_date').click(function(e){

          if($('#center_id').val() == '' || $('#entry_date').val() == '' )
          {
            $('#center-info').html('<div class="alert alert-warning mb-0 mx-3 text-center" role="alert">Select Center & Date to Make Entries</div>');
            return;
          }

          var center_ajax_url  = $(this).attr("data-url");
          console.log(center_ajax_url+$('#center_id').val());

         $.ajax({
             url: center_ajax_url,
             method: 'get',
             data: {
              activity_location_id: $('#user_activity_location').val(),
              activity_location_name: $('#user_activity_location option:selected').text(),
                center_id: $('#center_id').val(),
                center_name: $('#center_id option:selected').text(),
                entry_date: $('#entry_date').val(),
             },
             success: function(result){
                console.log(result);
                $('#center-info').html('');
                $('#center-info').html(result);
                setTimeout(function () {
                  location.reload(true);
                }, 1000);

             }});
          });




       });
</script>

<script>
  $(document).ready(function(){
    $('#phone').mask('0000-0000000');
    $('#cnic_number').mask('00000-0000000-0');

});
</script>
@yield('scripts')


</body>
</html>
