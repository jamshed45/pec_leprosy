@extends('layout')

@section('content')
@php
  $user = Auth::user();
@endphp
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header sty-one">
      <h1>Daily PEC Entry Report</h1>
      <ol class="breadcrumb">
        <li><a href="#">Dashboard</a></li>
        <li class="sub-bread"><i class="fa fa-angle-right"></i> Patient Entry</li>
        <li><i class="fa fa-angle-right"></i> Daily Report</li>
      </ol>
    </div>
    @php
      if($user->hasRole('pec-data-verifier'))
        {
          $patients = DB::table('pec_refferal_forms')
                ->where('user_id', '=',  $records_set_unique_id_info->user_id)
                ->where('entry_at', '=',  $records_set_unique_id_info->entry_at)
                ->orderBy('entry_at', 'desc')
                ->get()
                ->toArray();

    @endphp
    <div class="content" style="min-height: 0%">
      <div class="row">
        <div class="col-lg-12">
          <!-- Button trigger modal -->
          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#verify_pec_entry">
            Verfiy This Entry Below
          </button>

          <!-- Modal -->
          <div class="modal fade" id="verify_pec_entry" tabindex="-1" role="dialog" aria-labelledby="verify_pec_entry" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Verify PEC Entry</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  Please confirm refferal forms & pec entries before verify
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <form action="{{ route('patient-entries.update-entries-saved', $records_set_unique_id_info->records_set_unique_id) }}" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="verify_patient_entry" value="1" />
                    <input type="hidden" name="records_set_unique_id" value="{{ $records_set_unique_id_info->records_set_unique_id }}">
                    <input type="hidden" name="pec_user_id" value="{{ $records_set_unique_id_info->user_id }}">
                    <button type="submit" class="btn btn-primary">Verify</button>
                  </form>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
    @if($patients)
    <div class="content">
         <div class="row">
          <div class="col-lg-12">
            <div class="card card-outline">
              <div class="card-header bg-blue">
                <h5 class="text-white m-b-0">Refferal Patient's List</h5>
              </div>
              <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                  <thead>
                    <tr>
                        <td>No</td>
                        @if (!$user->hasRole('pec-user'))
                        <td>Lot's Name</td>
                        @endif
                        <td>Date & Record ID</td>
                        <td>Name</td>
                        <td>Information</td>
                        <td>Cat. Book Number</td>
                        <td>Actions</td>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($patients as $patient )

                    <tr>
                        <td><small>{{ $loop->iteration }}</small></td>
                        @if (!$user->hasRole('pec-user'))
                        @php
                          $user_name = DB::table('users')->where('id', $patient->user_id)->get()->toArray();
                        @endphp
                        <td><small><b>{{ $user_name[0]->name }}</b></small></td>
                        @endif
                        <td><small><?=date('j M, Y', strtotime($patient->entry_at)); ?>
                          <hr class="my-1">
                          <b>Record ID :</b> <?=$patient->refferal_forms_id; ?>
                          </small>
                        </td>
                        <td>
                          <small>
                          <b>Name : </b><br>{{ $patient->full_name  }} <b>{{ $patient->relation  }}</b> {{ $patient->relative_name  }}
                          <hr class="my-1">
                          <b>Age : </b> {{ $patient->age  }}
                          <hr class="my-1">
                          <b>Gender : </b>
                          @if ($patient->gender == 'ma')
                            Male Adult
                          @elseif ($patient->gender == 'fa')
                            Female Adult
                          @elseif ($patient->gender == 'mc')
                            Male Child
                          @else
                            Female Child
                          @endif
                          <hr class="my-1">
                          <b>CNIC : </b> {{ $patient->cnic_number  }}
                          <hr class="my-1">
                          <b>Contact No : </b> {{ $patient->contact_number  }}
                        </small>
                        </td>
                        <td>
                          <small>
                          <b>Refer From : </b><br>
                          @php
                          $refer_by =  DB::table('pec_form_categories')
                          ->select('category_name')
                          ->where('pec_form_cat_id', $patient->refer_by)
                          ->first();
                          echo $refer_by->category_name;
                          @endphp
                          <hr class="my-1">
                          <b>Referral : </b><br>
                          @php
                          $referral_by =  DB::table('pec_form_categories')
                                ->select('category_name')
                                ->where('pec_form_cat_id', $patient->referrals)
                                ->first();
                          echo $referral_by->category_name;

                          $operated_by_check = DB::table('pec_refferal_form_meta')
                                ->where('refferal_forms_id', $patient->refferal_forms_id)
                                ->where('operated_by', 1)
                                ->first();

                          $operated_by = DB::table('pec_refferal_form_meta')
                          ->join('doctors', 'pec_refferal_form_meta.doctor_id', '=', 'doctors.doctor_id')
                          ->join('health_facilities', 'pec_refferal_form_meta.health_facility_id', '=', 'health_facilities.health_facility_id')
                          ->where('refferal_forms_id', $patient->refferal_forms_id)
                          ->where('operated_by', 1)
                          ->select('pec_refferal_form_meta.*', 'doctors.doctor_name', 'health_facilities.health_facility_name')
                          ->first();

                          $refer_to_check = DB::table('pec_refferal_form_meta')
                                ->where('refferal_forms_id', $patient->refferal_forms_id)
                                ->where('refer_to', 1)
                                ->select('pec_refferal_form_meta.private')
                                ->first();

                          $refer_to_array = DB::table('pec_refferal_form_meta')
                                ->join('doctors', 'pec_refferal_form_meta.doctor_id', '=', 'doctors.doctor_id')
                                ->join('health_facilities', 'pec_refferal_form_meta.health_facility_id', '=', 'health_facilities.health_facility_id')
                                ->where('refferal_forms_id', $patient->refferal_forms_id)
                                ->where('refer_to', 1)
                                ->select('pec_refferal_form_meta.*', 'doctors.doctor_name', 'health_facilities.health_facility_name')
                                ->first();

                          @endphp

                          @if ( $refer_to_check->private != 1)
                            <hr class="my-1">
                            <b>Refer To</b>
                            <hr class="my-1">
                            <small>
                            <b>Health Facility : </b>
                          @if (isset($refer_to_array->health_facility_name))
                              {{ $refer_to_array->health_facility_name }}<br>
                          @endif
                            <b>Dotor : </b>
                            @if (isset($refer_to_array->doctor_name))
                              {{ $refer_to_array->doctor_name }}
                              @endif
                            </small>
                          @else
                            <hr class="my-1">
                            <b>Refer To : </b><br>Private
                          @endif

                          @if($operated_by_check)

                            @if ($operated_by_check->private != 1)
                              <hr class="my-1">
                              <b>Operated By</b>
                              <hr class="my-1">
                              <small>
                              <b>Health Facility : </b>
                                {{ $operated_by->health_facility_name  }}<br>
                              <b>Dotor : </b>
                                {{ $operated_by->doctor_name  }}

                            @else
                              <hr class="my-1">
                              <b>Operated By :</b>
                              Private
                            @endif
                            <hr class="my-1">
                            <b>Operation Date :</b>
                            {{ $operated_by_check->operation_date }}
                          @endif
                          </small>
                        </td>
                        <td><small>{{ $patient->cat_book_number }}</small></td>
                        <td>
                          <small>
                          <a target="_blank"  href="{{ route('refferal-forms.show',$patient->refferal_forms_id) }}">View</a>
                          </small>
                        </td>
                    </tr>
                @endforeach
                  </tbody>

                </table>
                    </div>

              </div>
            </div>
          </div>
        </div>
    </div>
    @endif
    @php
        }

    if($user->hasRole('pec-user') && $records_set_unique_id_info->verify)
    {
      $verified_patient_entry = 'disabled';
    }
    else
    {
      $verified_patient_entry = '';
    }
    @endphp



    <div class="content patients-entries-report">
        {{-- <form action="{{ URL::to('patient-entries/update-entries-saved') }}" method="POST"> --}}
        <form action="{{ route('patient-entries.update-entries-saved', $records_set_unique_id) }}" method="POST">
          {{-- {{ route('user.massaction') }} --}}

        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="records_set_unique_id" value="{{ $records_set_unique_id_info->records_set_unique_id }}">
        <input type="hidden" name="user_id" value="{{ $records_set_unique_id_info->user_id }}">
        <input type="hidden" name="activity_location_id" value="{{ $records_set_unique_id_info->activity_location_id }}">
        <input type="hidden" name="province_id" value="{{ $records_set_unique_id_info->province_id }}">
        <input type="hidden" name="division_id" value="{{ $records_set_unique_id_info->division_id }}">
        <input type="hidden" name="district_id" value="{{ $records_set_unique_id_info->district_id }}">
        <input type="hidden" name="tehsil_id" value="{{ $records_set_unique_id_info->tehsil_id }}">
        <input type="hidden" name="center_id" value="{{ $records_set_unique_id_info->center_id }}">
        <input type="hidden" name="entry_at" value="{{ $records_set_unique_id_info->entry_at }}">

        {{-- @method('PUT') --}}
        <div class="row">
            @foreach ($form_categories as $category)
                <div class="col-lg-12 mb-2">
                    <div class="card card-outline">
                    <div class="card-header bg-blue">
                        <h5 class="text-white m-b-0">{{ $category['category_name'] }} </h5>
                    </div>
                    <div class="card-body">
                    @php
                      $form_sub_cat = DB::table('pec_form_categories')
                              ->where('parent_slug', $category['slug'])
                              ->get()
                              ->toArray();

                  $total_array = array();

                  $ma_total = 0;
                  $fa_total = 0;
                  $mc_total = 0;
                  $fc_total = 0;
                  foreach($form_sub_cat as $sub_cat)
                  {
                    if (array_key_exists($sub_cat->slug,$patient_entries))
                    {
                      if($sub_cat->add_in_total == 'enable')
                        {
                          $ma_total = $ma_total + $patient_entries[$sub_cat->slug]['ma'];
                          $fa_total = $fa_total + $patient_entries[$sub_cat->slug]['fa'];
                          $mc_total = $mc_total + $patient_entries[$sub_cat->slug]['mc'];
                          $fc_total = $fc_total + $patient_entries[$sub_cat->slug]['fc'];
                        }

                    }
                  }

                @endphp

                <table class="table tg">
                  <thead>
                    <tr>
                      <th class="tg-p81p" rowspan="2">Activities Done</th>
                    </tr>
                    <tr>
                      <th class="tg-u38u">MA</th>
                      <th class="tg-u38u">FA</th>
                      <th class="tg-u38u">MC</th>
                      <th class="tg-u38u">FC</th>
                      <th class="tg-u38u">Total</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <th class="tg-hu93">Total</th>
                      <td class="tg-hu93">{{ $ma_total != 0 ? $ma_total:'-' }}</td>
                      <td class="tg-hu93">{{ $fa_total != 0 ? $fa_total:'-' }}</td>
                      <td class="tg-hu93">{{ $mc_total != 0 ? $mc_total:'-' }}</td>
                      <td class="tg-hu93">{{ $fc_total != 0 ? $fc_total:'-' }}</td>
                      <td class="tg-hu93">@php $total = $ma_total + $fa_total + $mc_total + $fc_total @endphp {{ $total != 0 ? $total:'-' }}</td>
                    </tr>

                    @foreach ($form_sub_cat as $item)

                    @php

                    if(isset($patient_entries[$item->slug]['ma']))
                      {
                        if($patient_entries[$item->slug]['ma'] != 0)
                        {
                          $ma = $patient_entries[$item->slug]['ma'];

                          $total_array[$item->slug]['ma'] = $patient_entries[$item->slug]['ma'];

                        }
                        else
                        {
                          $ma = '';
                        }
                      }
                      else
                      {
                        $ma = '';
                      }

                      if(isset($patient_entries[$item->slug]['fa']) && $patient_entries[$item->slug]['fa'])
                      {
                        if($patient_entries[$item->slug]['fa'] != 0)
                        {
                          $fa = $patient_entries[$item->slug]['fa'];

                          $total_array[$item->slug]['fa'] = $patient_entries[$item->slug]['fa'];
                        }
                        else
                        {
                          $fa = '';
                        }
                      }
                      else
                      {
                        $fa = '';
                      }

                      if(isset($patient_entries[$item->slug]['mc']) && $patient_entries[$item->slug]['mc'])
                      {
                        if($patient_entries[$item->slug]['mc'] != 0)
                        {
                          $mc = $patient_entries[$item->slug]['mc'];

                          // $total_array[$item->slug] = array(
                          //   'mc' => $patient_entries[$item->slug]->mc,
                          // );
                          $total_array[$item->slug]['mc'] = $patient_entries[$item->slug]['mc'];
                        }
                        else
                        {
                          $mc = '';
                        }
                      }
                      else
                      {
                        $mc = '';
                      }

                      if(isset($patient_entries[$item->slug]['fc']) && $patient_entries[$item->slug]['fc'])
                      {
                        if($patient_entries[$item->slug]['fc'] != 0)
                        {
                          $fc = $patient_entries[$item->slug]['fc'];

                          // $total_array[$item->slug] = array(
                          //   'fc' => $patient_entries[$item->slug]->fc,
                          // );
                          $total_array[$item->slug]['fc'] = $patient_entries[$item->slug]['fc'];

                        }
                        else
                        {
                          $fc = '';
                        }
                      }
                      else
                      {
                        $fc = '';
                      }

                    if(isset($patient_entries[$item->slug]))
                      {
                    @endphp
                    <input type="hidden" name="patient_entry[{{ $item->slug }}][entry_existing_id]" value="{{ $patient_entries[$item->slug]['center_patient_record_id'] }}" {{ $item->entry_status == 'disable'?'disabled':''; }}>
                    <tr>
                      <td class="tg-zvdi">{{ $item->category_name }}</td>
                      <td class="tg-tkru">
                        <input type="number" min="0" name="patient_entry[{{ $item->slug }}][ma]" {{ $item->entry_status == 'disable'?'disabled':''; }} value="{{ ($patient_entries[$item->slug]['ma'])?$patient_entries[$item->slug]['ma']:'' }}" placeholder="-" />
                      </td>
                      <td class="tg-tkru">
                        <input type="number" min="0" name="patient_entry[{{ $item->slug }}][fa]" {{ $item->entry_status == 'disable'?'disabled':''; }} value="{{ ($patient_entries[$item->slug]['fa'])?$patient_entries[$item->slug]['fa']:'' }}" placeholder="-" />
                      </td>
                      <td class="tg-tkru">
                        <input type="number" min="0" name="patient_entry[{{ $item->slug }}][mc]" {{ $item->entry_status == 'disable'?'disabled':''; }} value="{{ ($patient_entries[$item->slug]['mc'])?$patient_entries[$item->slug]['mc']:'' }}" placeholder="-" />
                      </td>
                      <td class="tg-tkru">
                        <input type="number" min="0" name="patient_entry[{{ $item->slug }}][fc]" {{ $item->entry_status == 'disable'?'disabled':''; }} value="{{ ($patient_entries[$item->slug]['fc'])?$patient_entries[$item->slug]['fc']:'' }}" placeholder="-" />
                      </td>
                      <td class="tg-tkru"><b>
                        @php $total = (int)$ma + (int)$fa + (int)$mc + (int)$fc @endphp {{ $total != 0 ? $total:'-' }}</b></td>
                    </tr>
                    @php
                      } else {
                    @endphp
                    <input type="hidden" name="patient_entry[{{ $item->slug }}][entry_existing_id]" value="new" {{ $item->entry_status == 'disable'?'disabled':''; }}>
                    <tr>
                      <td class="tg-zvdi">{{ $item->category_name }}</td>
                      <td class="tg-tkru">
                        <input type="number" min="0" name="patient_entry[{{ $item->slug }}][ma]" {{ $item->entry_status == 'disable'?'disabled':''; }} value="" placeholder="-" {{ $verified_patient_entry }} />
                      </td>
                      <td class="tg-tkru">
                        <input type="number" min="0" name="patient_entry[{{ $item->slug }}][fa]" {{ $item->entry_status == 'disable'?'disabled':''; }} value="" placeholder="-" {{ $verified_patient_entry }} />
                      </td>
                      <td class="tg-tkru">
                        <input type="number" min="0" name="patient_entry[{{ $item->slug }}][mc]" {{ $item->entry_status == 'disable'?'disabled':''; }} value="" placeholder="-" {{ $verified_patient_entry }} />
                      </td>
                      <td class="tg-tkru">
                        <input type="number" min="0" name="patient_entry[{{ $item->slug }}][fc]" {{ $item->entry_status == 'disable'?'disabled':''; }} value="" placeholder="-" {{ $verified_patient_entry }} />
                      </td>
                      <td class="tg-tkru"><b>
                        @php $total = (int)$ma + (int)$fa + (int)$mc + (int)$fc @endphp {{ $total != 0 ? $total:'-' }}</b></td>
                    </tr>
                    @php
                      }
                    @endphp

                    @endforeach

                  </tbody>
                  </table>

              <!-- if there are creation errors, they will show here -->

                    </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="row">
          <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                  <button type="submit" class="btn btn-block btn-primary py-4"><b class="text-white h5">Update Entries</b></button>
          </div>
        </div>

        </form>
      </div>
  </div>

@endsection
