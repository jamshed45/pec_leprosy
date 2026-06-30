@extends('layout')

@section('content')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header sty-one">
            <h1 class="text-black">Dashboard</h1>
            <ol class="breadcrumb">
                <li><a href="#">Dashboard</a></li>
                <li class="sub-bread"><i class="fa fa-angle-right"></i> Dashboard</li>

            </ol>
        </div>

        @include('user_center_section')

        @php
            $user = Auth::user();
        @endphp

        <!-- Main content -->
        <div class="content">

            @if ($get_date)
                <div class="row">
                    <div class="col-lg-3 col-xs-3 mb-3">
                        <input class="form-control" type="date" id="dashboard_daily_date" value="{{ $get_date }}"
                            data-dailydateurl="{{ URL::current() }}?date=" />
                    </div>
                    <div class="col-lg-3 col-xs-3 mb-3">
                        <a id="get_daily_stats" class="btn btn-primary" style="display:none">Get Stats</a>
                    </div>
                    <div class="col-lg-2 col-xs-2 mb-3">

                    </div>
                    <div class="col-lg-4 col-xs-4 mb-3">
                        <a href="{{ URL::current() }}?date={{ date('Y-m-d', strtotime($get_date . ' -1 day')) }}"
                            id="get_daily_stats" class="btn btn-primary">Previous Date</a>
                        <a href="{{ URL::current() }}?date={{ date('Y-m-d', strtotime($get_date . ' 1 day')) }}"
                            id="get_daily_stats" class="btn btn-primary">Next Date</a>
                    </div>
                </div>
            @elseif($get_month)
                <div class="row">
                    <div class="col-lg-3 col-xs-3 mb-3">
                        <input class="form-control" type="month" id="dashboard_monthly_date" value="{{ $get_month }}"
                            data-monthlydateurl="{{ URL::current() }}?month=" />
                    </div>
                    <div class="col-lg-3 col-xs-3 mb-3">
                        <a id="get_monthly_stats" class="btn btn-primary" style="display:none">Get Stats</a>
                    </div>
                    <div class="col-lg-2 col-xs-2 mb-3">
                    </div>
                    <div class="col-lg-4 col-xs-4 mb-3">
                        <a href="{{ URL::current() }}?month={{ date('Y-m', strtotime($get_month . ' -1 month')) }}"
                            id="get_daily_stats" class="btn btn-primary">Previous Month</a>
                        <a href="{{ URL::current() }}?month={{ date('Y-m', strtotime($get_month . ' 1 month')) }}"
                            id="get_daily_stats" class="btn btn-primary">Next Month</a>
                    </div>
                </div>
            @elseif($get_year)
                <div class="row">
                    <div class="col-lg-3 col-xs-3 mb-3">
                        <input class="form-control" type="number" min="1900" max="2099" id="dashboard_yearly_date"
                            step="1" value="{{ $get_year }}" data-yearlydateurl="{{ URL::current() }}?year=" />
                    </div>
                    <div class="col-lg-3 col-xs-3 mb-3">
                        <a id="get_yearly_stats" class="btn btn-primary" style="display:none">Get Stats</a>
                    </div>
                    <div class="col-lg-2 col-xs-2 mb-3">
                    </div>
                    <div class="col-lg-4 col-xs-4 mb-3">
                        <a href="{{ URL::current() }}?year={{ date('Y', strtotime($get_year . ' -1 year')) }}"
                            id="get_daily_stats" class="btn btn-primary">Previous Year</a>
                        <a href="{{ URL::current() }}?year={{ date('Y', strtotime($get_year . ' 1 year')) }}"
                            id="get_daily_stats" class="btn btn-primary">Next Year</a>
                    </div>
                </div>
            @endif

            @php
                $coun_count = 0;
                if ($total_patient_examined) {
                    foreach ($total_patient_examined as $count) {
                        $coun_count += $count['ma'] + $count['mc'] + $count['fa'] + $count['fc'];
                    }
                } else {
                    $coun_count = 0;
                }

                $refractive_count = 0;
                if ($refractive_errors) {
                    foreach ($refractive_errors as $count) {
                        $refractive_count += $count['ma'] + $count['mc'] + $count['fa'] + $count['fc'];
                    }
                } else {
                    $refractive_count = 0;
                }

                $referrals_count = 0;
                if ($referrals) {
                    foreach ($referrals as $count) {
                        $referrals_count += $count['ma'] + $count['mc'] + $count['fa'] + $count['fc'];
                    }
                } else {
                    $referrals_count = 0;
                }

                $cataract_referrals_count = 0;
                if ($cataract_referrals) {
                    foreach ($cataract_referrals as $count) {
                        $cataract_referrals_count += $count['ma'] + $count['mc'] + $count['fa'] + $count['fc'];
                    }
                } else {
                    $cataract_referrals_count = 0;
                }

                $confirmed_cataract_count = 0;
                if ($confirmed_cataracts) {
                    foreach ($confirmed_cataracts as $count) {
                        $confirmed_cataract_count += $count['ma'] + $count['mc'] + $count['fa'] + $count['fc'];
                    }
                } else {
                    $confirmed_cataract_count = 0;
                }

                $persons_with_disability_count = 0;
                if ($persons_with_disability) {
                    foreach ($persons_with_disability as $count) {
                        $persons_with_disability_count += $count['ma'] + $count['mc'] + $count['fa'] + $count['fc'];
                    }
                } else {
                    $persons_with_disability_count = 0;
                }

                $punjab_consultation = 0;
                $kpk_consultation = 0;
                $punjab_refferal = 0;
                $kpk_refferal = 0;
                $punjab_confirmed = 0;
                $kpk_confirmed = 0;
                if ($province_wise_pie) {
                    foreach ($province_wise_pie as $count) {
                        if ($count['province_id'] == 1) {
                            if ($count['patient_type'] == 'new-patients') {
                                $punjab_consultation += $count['ma'] + $count['mc'] + $count['fa'] + $count['fc'];
                            }
                            if ($count['patient_type'] == 'old-patients') {
                                $punjab_consultation += $count['ma'] + $count['mc'] + $count['fa'] + $count['fc'];
                            }
                            if ($count['patient_type'] == 'leprosy-patients') {
                                $punjab_consultation += $count['ma'] + $count['mc'] + $count['fa'] + $count['fc'];
                            }
                            if ($count['patient_type'] == 'number-of-children-under-six') {
                                $punjab_consultation += $count['ma'] + $count['mc'] + $count['fa'] + $count['fc'];
                            }

                            if ($count['patient_type'] == 'cataract-surgery') {
                                $punjab_refferal += $count['ma'] + $count['mc'] + $count['fa'] + $count['fc'];
                            }
                            if ($count['patient_type'] == 'glaucoma-surgery') {
                                $punjab_refferal += $count['ma'] + $count['mc'] + $count['fa'] + $count['fc'];
                            }
                            if ($count['patient_type'] == 'trichiasis-surgery') {
                                $punjab_refferal += $count['ma'] + $count['mc'] + $count['fa'] + $count['fc'];
                            }
                            if ($count['patient_type'] == 'pterygium-surgery') {
                                $punjab_refferal += $count['ma'] + $count['mc'] + $count['fa'] + $count['fc'];
                            }
                            if ($count['patient_type'] == 'minor-surgery') {
                                $punjab_refferal += $count['ma'] + $count['mc'] + $count['fa'] + $count['fc'];
                            }
                            if ($count['patient_type'] == 'investigations') {
                                $punjab_refferal += $count['ma'] + $count['mc'] + $count['fa'] + $count['fc'];
                            }

                            if ($count['patient_type'] == 'with-iol') {
                                $punjab_confirmed += $count['ma'] + $count['mc'] + $count['fa'] + $count['fc'];
                            }
                            if ($count['patient_type'] == 'witout-iol') {
                                $punjab_confirmed += $count['ma'] + $count['mc'] + $count['fa'] + $count['fc'];
                            }
                        } else {
                            if ($count['patient_type'] == 'new-patients') {
                                $kpk_consultation += $count['ma'] + $count['mc'] + $count['fa'] + $count['fc'];
                            }
                            if ($count['patient_type'] == 'old-patients') {
                                $kpk_consultation += $count['ma'] + $count['mc'] + $count['fa'] + $count['fc'];
                            }
                            if ($count['patient_type'] == 'leprosy-patients') {
                                $kpk_consultation += $count['ma'] + $count['mc'] + $count['fa'] + $count['fc'];
                            }
                            if ($count['patient_type'] == 'number-of-children-under-six') {
                                $kpk_consultation += $count['ma'] + $count['mc'] + $count['fa'] + $count['fc'];
                            }

                            if ($count['patient_type'] == 'cataract-surgery') {
                                $kpk_refferal += $count['ma'] + $count['mc'] + $count['fa'] + $count['fc'];
                            }
                            if ($count['patient_type'] == 'glaucoma-surgery') {
                                $kpk_refferal += $count['ma'] + $count['mc'] + $count['fa'] + $count['fc'];
                            }
                            if ($count['patient_type'] == 'trichiasis-surgery') {
                                $kpk_refferal += $count['ma'] + $count['mc'] + $count['fa'] + $count['fc'];
                            }
                            if ($count['patient_type'] == 'pterygium-surgery') {
                                $kpk_refferal += $count['ma'] + $count['mc'] + $count['fa'] + $count['fc'];
                            }
                            if ($count['patient_type'] == 'minor-surgery') {
                                $kpk_refferal += $count['ma'] + $count['mc'] + $count['fa'] + $count['fc'];
                            }
                            if ($count['patient_type'] == 'investigations') {
                                $kpk_refferal += $count['ma'] + $count['mc'] + $count['fa'] + $count['fc'];
                            }

                            if ($count['patient_type'] == 'with-iol') {
                                $kpk_confirmed += $count['ma'] + $count['mc'] + $count['fa'] + $count['fc'];
                            }
                            if ($count['patient_type'] == 'witout-iol') {
                                $kpk_confirmed += $count['ma'] + $count['mc'] + $count['fa'] + $count['fc'];
                            }
                        }
                    }
                }

            @endphp
            <div class="row">

                <div class="col-lg-4 col-xs-4">
                    <div class="info-box"> <span class="info-box-icon bg-aqua"><i class="icon-briefcase"></i> <i
                                class="icon-user"></i></span>
                        <div class="info-box-content"> <span class="info-box-number">{{ $coun_count }}</span> <span
                                class="info-box-text">Total No of Consultations</span> </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->

                <div class="col-lg-4 col-xs-4">
                    <div class="info-box"> <span class="info-box-icon bg-aqua"><i class="icon-briefcase"></i> <i
                                class="icon-user"></i></span>
                        <div class="info-box-content"> <span class="info-box-number">{{ $refractive_count }}</span> <span
                                class="info-box-text">Refractive Errors</span> </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->

                <div class="col-lg-4 col-xs-4">
                    <div class="info-box"> <span class="info-box-icon bg-aqua"><i class="icon-briefcase"></i> <i
                                class="icon-user"></i></span>
                        <div class="info-box-content"> <span class="info-box-number">{{ $referrals_count }}</span> <span
                                class="info-box-text">Referrals</span> </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->

                <div class="col-lg-6 col-xs-6">
                    <div class="info-box"> <span class="info-box-icon bg-aqua"><i class="icon-briefcase"></i> <i
                                class="icon-user"></i></span>
                        <div class="info-box-content"> <span class="info-box-number">{{ $referrals_count }}</span> <span
                                class="info-box-text">Cataract Referrals</span> </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->

                <div class="col-lg-6 col-xs-6">
                    <div class="info-box"> <span class="info-box-icon bg-aqua"><i class="icon-briefcase"></i> <i
                                class="icon-user"></i></span>
                        <div class="info-box-content"> <span
                                class="info-box-number">{{ $confirmed_cataract_count }}</span> <span
                                class="info-box-text">Confirmed Cataracts</span> </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->

                {{-- <div class="col-lg-4 col-xs-4">
                    <div class="info-box"> <span class="info-box-icon bg-aqua"><i class="icon-briefcase"></i> <i
                                class="icon-user"></i></span>
                        <div class="info-box-content"> <span
                                class="info-box-number">{{ $persons_with_disability_count }}</span> <span
                                class="info-box-text">No of Persons with Disability</span> </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div> --}}
                <!-- /.col -->
                @if (!$user->hasRole('pec-user'))
                    <div class="col-lg-6" style="border-right:1px solid #c1c1c1">
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex flex-wrap">
                                    <div>
                                        <h5>Pubjab</h5>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12 col-xs-12">
                                <div class="info-box"> <span class="info-box-icon bg-aqua"><i class="icon-briefcase"></i>
                                        <i class="icon-user"></i></span>
                                    <div class="info-box-content"> <span
                                            class="info-box-number">{{ $punjab_consultation }}</span> <span
                                            class="info-box-text">Total No of Consultations</span> </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-xs-12">
                                <div class="info-box"> <span class="info-box-icon bg-aqua"><i class="icon-briefcase"></i>
                                        <i class="icon-user"></i></span>
                                    <div class="info-box-content"> <span
                                            class="info-box-number">{{ $punjab_refferal }}</span> <span
                                            class="info-box-text">Refferals</span> </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-xs-12">
                                <div class="info-box"> <span class="info-box-icon bg-aqua"><i class="icon-briefcase"></i>
                                        <i class="icon-user"></i></span>
                                    <div class="info-box-content"> <span
                                            class="info-box-number">{{ $punjab_confirmed }}</span> <span
                                            class="info-box-text">Confirmed Cataract</span> </div>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="info-box">
                                    <div class="col-12">

                                    </div>
                                    <div>
                                        <canvas id="punjab-chart"></canvas>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="info-box">
                                    <div>
                                        <canvas id="punjab-pie-chart" height="150"></canvas>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex flex-wrap">
                                    <div>
                                        <h5>KPK</h5>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12 col-xs-12">
                                <div class="info-box"> <span class="info-box-icon bg-aqua"><i class="icon-briefcase"></i>
                                        <i class="icon-user"></i></span>
                                    <div class="info-box-content"> <span
                                            class="info-box-number">{{ $kpk_consultation }}</span> <span
                                            class="info-box-text">Total No of Consultations</span> </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-xs-12">
                                <div class="info-box"> <span class="info-box-icon bg-aqua"><i class="icon-briefcase"></i>
                                        <i class="icon-user"></i></span>
                                    <div class="info-box-content"> <span
                                            class="info-box-number">{{ $kpk_refferal }}</span> <span
                                            class="info-box-text">Refferals</span> </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-xs-12">
                                <div class="info-box"> <span class="info-box-icon bg-aqua"><i class="icon-briefcase"></i>
                                        <i class="icon-user"></i></span>
                                    <div class="info-box-content"> <span
                                            class="info-box-number">{{ $kpk_confirmed }}</span> <span
                                            class="info-box-text">Confirmed Cataract</span> </div>
                                </div>
                            </div>


                            <div class="col-lg-12">
                                <div class="info-box">
                                    <div>
                                        <canvas id="kpk-chart"></canvas>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="info-box">
                                    <div>
                                        <canvas id="kpk-pie-chart" height="150"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>

            @if(!$user->hasRole('pec-user'))
            @if(!$user->hasRole('pec-super-admin'))
            <div class="row">
              <div class="col-lg-6" style="border-right:1px solid #c1c1c1">
                <div class="info-box">
                  <h5 class="dash-heading-ent">Refferal Patients Added By Lots</h5>
                  <table class="table dashboard_entries_table">
                      <thead>
                          <tr>
                              <th>Name</th>
                              <th>Center</th>
                          </tr>
                      </thead>
                      <tbody>
                        @if($today_punjab_refferals_user)
                          @php
                            $user_exist_check = 0;
                            $i = 1
                          @endphp
                            @foreach ($today_punjab_refferals_user as $key => $lots)
                              <tr>
                                <td>
                                  @if (isset($today_punjab_refferals_user[$user_exist_check - 1]) &&
                                                        $today_punjab_refferals_user[$user_exist_check - 1]['id'] == $lots['id'])
                                  @else
                                    {{ $i }} - {{ $lots['name'] }}
                                  @endif
                                </td>
                                <td>{{ $lots['center_name'] }}</td>

                                </td>
                              </tr>
                            @php
                              $user_exist_check++;
                              $i++
                            @endphp
                            @endforeach
                        @else

                        @endif

                      </tbody>
                  </table>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="info-box">
                  <h5 class="dash-heading-ent">Refferal Patients Added By Lots</h5>
                  <table class="table dashboard_entries_table">
                      <thead>
                          <tr>
                              <th>Name</th>
                              <th>Center</th>
                          </tr>
                      </thead>
                      <tbody>
                        @if($today_kpk_refferals_user)
                          @php
                            $user_exist_check = 0;
                            $i = 1
                          @endphp
                            @foreach ($today_kpk_refferals_user as $key => $lots)
                              <tr>
                                <td>
                                  @if (isset($today_kpk_refferals_user[$user_exist_check - 1]) &&
                                                        $today_kpk_refferals_user[$user_exist_check - 1]['id'] == $lots['id'])
                                  @else
                                    {{ $i }} - {{ $lots['name'] }}
                                  @endif
                                </td>
                                <td>{{ $lots['center_name'] }}</td>

                                </td>
                              </tr>
                            @php
                              $user_exist_check++;
                              $i++
                            @endphp
                            @endforeach
                        @else

                        @endif

                      </tbody>
                  </table>
                </div>
              </div>
            </div>
            @endif
            @php

                if(isset($_GET['date']))
                {
                    $query_string = '?date='.$_GET['date'];
                }
                elseif (isset($_GET['month']))
                {
                    $query_string = '?month='.$_GET['month'];
                }
                else
                {
                    $query_string = '?year='.$_GET['year'];
                }

            @endphp
            <div class="row">

              <div class="col-lg-6"  style="border-right:1px solid #c1c1c1">
                <div class="info-box">
                    <h5 class="dash-heading-ent">PEC entries by lots</h5>
                    <table class="table dashboard_entries_table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Center</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($today_punjab_patient_entries_user))

                                @php
                                  $user_exist_check = 0;
                                  $i = 1
                                @endphp
                                @foreach ($today_punjab_patient_entries_user as $lots)
                                    <tr>
                                        <td>
                                            @if (isset($today_punjab_patient_entries_user[$user_exist_check - 1]) &&
                                                    $today_punjab_patient_entries_user[$user_exist_check - 1]['id'] == $lots['id']
                                            )
                                            @else
                                                {{ $i }} - <a href="{{ URL::to('patients-entries/patients-entries-by-stat') }}{{ $query_string  }}&user_id={{ $lots['id'] }}" target="_blank">{{ $lots['name'] }}</a>
                                            @endif
                                        </td>
                                        <td><a href="{{ URL::to('patients-entries/patients-entries-by-stat') }}{{ $query_string  }}&center_id={{ $lots['center_id'] }}" target="_blank">{{ $lots['center_name'] }}</a></td>
                                    </tr>
                                    @php
                                      $user_exist_check++;
                                      $i++
                                    @endphp
                                @endforeach
                            @else
                                <tr>
                                    <td>No Refferal Added By User Yet!</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="info-box">
                    <h5 class="dash-heading-ent">PEC entries by lots</h5>
                    <table class="table dashboard_entries_table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Center</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($today_kpk_patient_entries_user))
                                @php
                                  $user_exist_check = 0;
                                  $i = 1
                                @endphp
                                @foreach ($today_kpk_patient_entries_user as $lots)
                                    <tr>
                                        <td>
                                            @if (isset($today_punjab_patient_entries_user[$user_exist_check - 1]) &&
                                                    $today_punjab_patient_entries_user[$user_exist_check - 1]['id'] == $lots['id']
                                            )
                                            @else
                                                {{ $i }} - <a href="{{ URL::to('patients-entries/patients-entries-by-stat') }}{{ $query_string  }}&user_id={{ $lots['id'] }}" target="_blank">{{ $lots['name'] }}</a>
                                            @endif
                                        </td>
                                        <td><a href="{{ URL::to('patients-entries/patients-entries-by-stat') }}{{ $query_string  }}&center_id={{ $lots['center_id'] }}" target="_blank">{{ $lots['center_name'] }}</a></td>
                                    </tr>
                                    @php
                                      $user_exist_check++;
                                      $i++
                                    @endphp
                                @endforeach
                            @else
                                <tr>
                                    <td>No Refferal Added By User Yet!</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
              </div>

            </div>
            @endif


            <div class="row mt-4">
                <div class="col-lg-12">
                    <div class="info-box">

                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

                            @foreach ($form_categories as $category)
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="headingOne">
                                        <h4 class="panel-title patient-entries-panel-title"> {{ $loop->iteration }} -
                                            <a role="button" data-toggle="collapse" data-parent="#accordion"
                                                href="#{{ $category['slug'] }}" aria-expanded="true"
                                                aria-controls="{{ $category['slug'] }}">
                                                {{ $category['category_name'] }}
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="{{ $category['slug'] }}" class="panel-collapse collapse in" role="tabpanel"
                                        aria-labelledby="headingOne">
                                        <div class="panel-body pb-0">

                                            @php
                                                $form_sub_cat = DB::table('pec_form_categories')
                                                    ->where('parent_slug', $category['slug'])
                                                    ->get()
                                                    ->toArray();
                                            @endphp

                                            <table class="table dashboard_entries_table mb-0">
                                                <thead>
                                                    <tr>
                                                        <th rowspan="2">Activities Done</th>
                                                    </tr>
                                                    <tr>
                                                        <th width="50">MA</th>
                                                        <th width="50">FA</th>
                                                        <th width="50">MC</th>
                                                        <th width="50">FC</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($form_sub_cat as $item)
                                                        @php
                                                            if (isset($patient_entries[$item->slug]['ma'])) {
                                                                if ($patient_entries[$item->slug]['ma'] != 0) {
                                                                    $ma = $patient_entries[$item->slug]['ma'];

                                                                    $total_array[$item->slug]['ma'] = $patient_entries[$item->slug]['ma'];
                                                                } else {
                                                                    $ma = '';
                                                                }
                                                            } else {
                                                                $ma = '';
                                                            }

                                                            if (isset($patient_entries[$item->slug]->fa) && $patient_entries[$item->slug]->fa) {
                                                                if ($patient_entries[$item->slug]->fa != 0) {
                                                                    $fa = $patient_entries[$item->slug]->fa;

                                                                    $total_array[$item->slug]['fa'] = $patient_entries[$item->slug]->fa;
                                                                } else {
                                                                    $fa = '';
                                                                }
                                                            } else {
                                                                $fa = '';
                                                            }

                                                            if (isset($patient_entries[$item->slug]->mc) && $patient_entries[$item->slug]->mc) {
                                                                if ($patient_entries[$item->slug]->mc != 0) {
                                                                    $mc = $patient_entries[$item->slug]->mc;

                                                                    // $total_array[$item->slug] = array(
                                                                    //   'mc' => $patient_entries[$item->slug]->mc,
                                                                    // );
                                                                    $total_array[$item->slug]['mc'] = $patient_entries[$item->slug]->mc;
                                                                } else {
                                                                    $mc = '';
                                                                }
                                                            } else {
                                                                $mc = '';
                                                            }

                                                            if (isset($patient_entries[$item->slug]->fc) && $patient_entries[$item->slug]->fc) {
                                                                if ($patient_entries[$item->slug]->fc != 0) {
                                                                    $fc = $patient_entries[$item->slug]->fc;

                                                                    // $total_array[$item->slug] = array(
                                                                    //   'fc' => $patient_entries[$item->slug]->fc,
                                                                    // );
                                                                    $total_array[$item->slug]['fc'] = $patient_entries[$item->slug]->fc;
                                                                } else {
                                                                    $fc = '';
                                                                }
                                                            } else {
                                                                $fc = '';
                                                            }
                                                        @endphp

                                                        @if (isset($patient_entries[$item->slug]))
                                                            <tr>
                                                                <th>{{ $item->category_name }}</th>
                                                                <td>{{ $patient_entries[$item->slug]['ma'] ? $patient_entries[$item->slug]['ma'] : '-' }}
                                                                </td>
                                                                <td>{{ $patient_entries[$item->slug]['fa'] ? $patient_entries[$item->slug]['fa'] : '-' }}
                                                                </td>
                                                                <td>{{ $patient_entries[$item->slug]['mc'] ? $patient_entries[$item->slug]['mc'] : '-' }}
                                                                </td>
                                                                <td>{{ $patient_entries[$item->slug]['fc'] ? $patient_entries[$item->slug]['fc'] : '-' }}
                                                                </td>
                                                            </tr>
                                                        @else
                                                            <tr>
                                                                <th>{{ $item->category_name }}</th>
                                                                <td>-</td>
                                                                <td>-</td>
                                                                <td>-</td>
                                                                <td>-</td>
                                                            </tr>
                                                        @endif

                                                    @endforeach
                                                </tbody>
                                            </table>


                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>

                    </div>
                </div>

            </div>



        </div>



    </div>


    <!-- Chartjs JavaScript -->
    <script src="{{ URL::asset('dist/plugins/chartjs/chart.min.js') }}"></script>

    <script>
        $(document).ready(function() {

            // ======
            // Pie chart
            // ======
            new Chart(document.getElementById("punjab-pie-chart"), {
                type: 'pie',
                data: {
                    labels: ['Consultation', 'Referrals', 'Confirmed Cat'],
                    datasets: [{
                        'label': 'My First Dataset',
                        data: [<?= $punjab_consultation ?>, <?= $punjab_refferal ?>,
                            <?= $punjab_confirmed ?>
                        ],
                        backgroundColor: ['rgb(255, 99, 132)',
                            'rgb(54, 162, 235)',
                            'rgb(255, 205, 86)'
                        ],
                    }]
                },
                options: {
                    responsive: true
                }
            });


            new Chart(document.getElementById("kpk-pie-chart"), {
                type: 'pie',
                data: {
                    labels: ['Consultation', 'Referrals', 'Confirmed Cat'],
                    datasets: [{
                        'label': 'My First Dataset',
                        data: [<?= $kpk_consultation ?>, <?= $kpk_refferal ?>,
                            <?= $kpk_confirmed ?>
                        ],
                        backgroundColor: ['rgb(255, 99, 132)',
                            'rgb(54, 162, 235)',
                            'rgb(255, 205, 86)'
                        ],
                    }]
                },
                options: {
                    responsive: true
                }
            });


            // ======
            // Bar chart
            // ======
            var ctx = document.getElementById('punjab-chart').getContext('2d');
            var chart = new Chart(ctx, {
                // The type of chart we want to create
                type: 'bar',

                // The data for our dataset
                data: {
                    labels: ["January"],
                    datasets: [{
                        label: "Consultation",
                        backgroundColor: 'rgb(255, 99, 132)',
                        borderColor: '',
                        data: [<?= $punjab_consultation ?>],
                        fill: false,
                    }, {
                        label: "Referrals",
                        backgroundColor: 'rgb(54, 162, 235)',
                        borderColor: '',
                        data: [<?= $punjab_refferal ?>],
                    }, {
                        label: "Confirmed Cat",
                        backgroundColor: 'rgb(255, 205, 86)',
                        borderColor: '',
                        data: [<?= $punjab_confirmed ?>],
                    }]
                },
                options: {
                    responsive: true
                }
            });



            // ======
            // Bar chart
            // ======
            var ctx = document.getElementById('kpk-chart').getContext('2d');
            var chart = new Chart(ctx, {
                // The type of chart we want to create
                type: 'bar',

                // The data for our dataset
                data: {
                    labels: ["January"],
                    datasets: [{
                        label: "Consultation",
                        backgroundColor: 'rgb(255, 99, 132)',
                        borderColor: '',
                        data: [<?= $kpk_consultation ?>],
                        fill: false,
                    }, {
                        label: "Referrals",
                        backgroundColor: 'rgb(54, 162, 235)',
                        borderColor: '',
                        data: [<?= $kpk_refferal ?>],
                    }, {
                        label: "Confirmed Cat",
                        backgroundColor: 'rgb(255, 205, 86)',
                        borderColor: '',
                        data: [<?= $kpk_confirmed ?>],
                    }]
                },
                options: {
                    responsive: true
                }
            });

        });
    </script>

@endsection
