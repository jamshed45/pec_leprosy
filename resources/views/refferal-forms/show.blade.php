@extends('layout')

@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header sty-one">
      <h1>Patient</h1>
      <ol class="breadcrumb">
        <li><a href="#">Dashboard</a></li>
        <li class="sub-bread"><i class="fa fa-angle-right"></i> Patient</li>
        <li><i class="fa fa-angle-right"></i> information</li>
      </ol>
    </div>

    <div class="col-lg-12">
      @include('user_center_section')
    </div>
    @php
      $user = Auth::user();
    @endphp

    <div class="content">
        @if($user->hasRole('pec-data-verifier'))
          {{-- <form action="{{ route('refferal-forms.verify-refferal-patient') }}" method="POST">
            @csrf
            <input type="hidden" name="refferal_forms_id" value="{{ $RefferalForm->refferal_forms_id }}" />
            <div class="row">
              <div class="col-3 mb-3">
                <select class="form-control" name="verify">
                  <option value="1" {{ ($RefferalForm->verify == 1)?'selected':'' }}>Verify</option>
                  <option value="0" {{ ($RefferalForm->verify == 0)?'selected':'' }}>Not Verify</option>
                </select>
              </div>
              <div class="col-3 mb-3">
                <button type="submit" class="btn btn-success waves-effect waves-light">Save</button>
              </div>
            </div>
          </form> --}}
        @endif
          <div class="row">
            <div class="col-12">

                <div class="card mb-2">
                    <div class="card-body">
                          <h5 class="bg-info p-2 text-white">View <b>{{ $RefferalForm->full_name }}</b> information</h5>


                        <div class="row">
                          <div class="col-md-4 my-1">
                            <b>Name : </b> {{ $RefferalForm->full_name }} {{ $RefferalForm->relation }} {{ $RefferalForm->full_name }}
                            <hr class="my-1">
                          </div>
                          <div class="col-md-4 my-1">
                            <b>Gender : </b>
                            @if ($RefferalForm->gender == 'ma')
                              Male Adult
                            @elseif ($RefferalForm->gender == 'fa')
                              Female Adult
                            @elseif ($RefferalForm->gender == 'mc')
                              Male Child
                            @else
                              Female Child
                            @endif
                            <hr class="my-1">
                          </div>
                          <div class="col-md-4 my-1">
                            <b>Age : </b> {{ $RefferalForm->age }} Years
                            <hr class="my-1">
                          </div>
                          <div class="col-md-6 my-1">
                            <b>CNIC No : </b> {{ $RefferalForm->cnic_number }}
                            <hr class="my-1">
                          </div>
                          <div class="col-md-6 my-1">
                            <b>Contact No : </b> {{ $RefferalForm->contact_number }}
                            <hr class="my-1">
                          </div>
                          <div class="col-md-12 my-1">
                            <b>
                              Address : </b> {{ $RefferalForm->address }}
                            <hr class="my-1">
                          </div>
                          <div class="col-md-6">
                            <b>Refer By : </b>
                            @php
                              $refer_by = DB::table('pec_form_categories')
                                  ->where('pec_form_cat_id', $RefferalForm->refer_by)
                                  ->first();
                            @endphp
                            {{ $refer_by->category_name }}
                            <hr class="my-1">
                          </div>
                          <div class="col-md-6 my-1">
                            <b>Book No : </b> {{ $RefferalForm->cat_book_number }}
                            <hr class="my-1">
                          </div>


                        </div>

                    </div>
                </div>


                <div class="card">
                  <div class="card-body">
                      <h5 class="bg-info p-2 text-white">Refferal <b>
                        @php
                        $referrals = DB::table('pec_form_categories')
                            ->where('pec_form_cat_id', $RefferalForm->referrals)
                            ->first();
                      @endphp
                    {{ $referrals->category_name }}</b>
                      </h5>
                      <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6 my-2">
                            <b>Book Number : </b>{{ $RefferalForm->cat_book_number }}
                            <hr class="my-1">
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-6 my-2">
                          <b>Referrals : </b>
                          @php
                              $referrals = DB::table('pec_form_categories')
                                  ->where('pec_form_cat_id', $RefferalForm->referrals)
                                  ->first();
                          @endphp
                          {{ $referrals->category_name }}
                          <hr class="my-1">
                        </div>
                        @php
                          $refer_to_check = DB::table('pec_refferal_form_meta')
                            ->where('refferal_forms_id', $RefferalForm->refferal_forms_id)
                            ->where('refer_to', 1)
                            ->first();
                        @endphp

                        @if ($referrals->slug == 'cataract-surgery')
                        <div class="col-xs-6 col-sm-6 col-md-6">
                          <b>Diabetic :</b>
                          @if ($refer_to_check->diabetic)
                            Yes
                          @else
                            No
                          @endif
                          <hr class="my-1">
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                          <b>Value : </b>
                          {{ $refer_to_check->diabetic_value  }}
                          <hr class="my-1">
                        </div>

                        @endif

                        @if($refer_to_check)
                        <div class="col-xs-6 col-sm-6 col-md-6 my-2">
                          <h6 class="bg-secondary p-2 text-white">Refer To</h6>
                          @if($refer_to_check->private)
                            <div class="col-xs-6 col-sm-6 col-md-6 my-2">
                              <b>Health Facility : </b>
                              Private
                            </div>
                          @else
                            <div class="col-xs-12 col-sm-12 col-md-12 my-2">
                              <b>Health Facility : </b>
                              @php
                              $health_facility = DB::table('health_facilities')->where('health_facility_id', $refer_to_check->health_facility_id)->first();
                              @endphp
                              {{ $health_facility->health_facility_name  }}
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 my-2">
                              <b>Dotor : </b>
                              @php
                              $doctor = DB::table('doctors')->where('doctor_id', $refer_to_check->doctor_id)->first();
                              @endphp
                              @if (isset($doctor->doctor_name))
                                {{ $doctor->doctor_name  }}
                                @endif

                            </div>
                          @endif
                        </div>
                        @endif

                        @php
                          $operated_by_check = DB::table('pec_refferal_form_meta')
                                ->where('refferal_forms_id', $RefferalForm->refferal_forms_id)
                                ->where('operated_by', 1)
                                ->first();
                        @endphp

                        @if($operated_by_check)
                        <div class="col-xs-6 col-sm-6 col-md-6 my-2">
                          <h6 class="bg-secondary p-2 text-white">Operated By</h6>
                          @if($operated_by_check->private)
                            <div class="col-xs-6 col-sm-6 col-md-6 my-2">
                              <b>Health Facility : </b>
                              Private
                            </div>
                          @else
                            <div class="col-xs-12 col-sm-12 col-md-12 my-2">
                              <b>Health Facility : </b>
                              @php
                              $health_facility = DB::table('health_facilities')->where('health_facility_id', $operated_by_check->health_facility_id)->first();
                              @endphp
                              {{ $health_facility->health_facility_name  }}
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 my-2">
                              <b>Dotor : </b>
                              @php
                              $doctor = DB::table('doctors')->where('doctor_id', $operated_by_check->doctor_id)->first();
                              @endphp
                              {{ $doctor->doctor_name  }}
                            </div>
                          @endif
                        </div>
                        @endif




                      </div>
                  </div>
              </div>

            </div>
        </div>
    </div>


  </div>


@endsection
