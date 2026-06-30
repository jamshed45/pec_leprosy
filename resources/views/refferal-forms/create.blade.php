@extends('layout')

@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header sty-one">
      <h1>Add Refferal Patient</h1>
      <ol class="breadcrumb">
        <li><a href="#">Dashboard</a></li>
        <li class="sub-bread"><i class="fa fa-angle-right"></i> Refferal</li>
        <li><i class="fa fa-angle-right"></i> Add Patient</li>
      </ol>
    </div>

    <div class="col-lg-12">
      @include('user_center_section')
    </div>

    @if (!session()->get('center_info'))
      @php
        $from_disable = 'pointer-events: none; opacity: 0.4;';
      @endphp
    @else
      @php
         $from_disable = '';
      @endphp
    @endif

    @php

        if(session()->has('center_info'))
        {
          $user = Auth::user();

          $center_session = session()->get('center_info');

            // $uer_tehsils_ids = DB::table('usermeta')
            //         ->where('user_id', $user->id)
            //         ->where('meta_key', 'tehsil_id')
            //         ->pluck('meta_value')
            //         ->toArray();



            // $health_facilities = DB::table('health_facilities')
            //         ->whereIn('tehsil_id',  $uer_tehsils_ids)
            //         ->get();

          $tehsil_single_id = DB::table('usermeta')
            ->where('user_id', $user->id)
            ->where('meta_key','tehsil_id')
            ->pluck('meta_value')
            ->first();

          $tehsil = DB::table('tehsil')
              ->where('tehsil_id', $tehsil_single_id)
              ->get()
              ->toArray();

          $health_facilities = DB::table('health_facilities')
                ->where('province_id', $tehsil[0]->province_id)
                ->get();

        }
    @endphp


    <div class="content" style="{{ $from_disable }}">
        <div class="row">
          <div class="col-lg-12">
            <div class="card card-outline">
              <div class="card-header bg-blue">
                <h5 class="text-white m-b-0">Add Refferal Patient - {{ $center_session['activity_location_name'] }}</h5>
              </div>
              <div class="card-body">

                      <!-- if there are creation errors, they will show here -->
<form action="{{ route('refferal-forms.store') }}" method="POST" id="add_referral">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">

     <div class="row">

        <div class="col-xs-5 col-sm-5 col-md-5">
            <div class="form-group">
                <label for="first_name">Full Name: <span class="text-danger"><small>required</small></span></label>
                <input type="text" name="full_name" class="form-control" required>
            </div>
        </div>
        <div class="col-xs-2 col-sm-2 col-md-2">
          <div class="form-group">
              <label for="first_name">Relation:</label>
              <select class="form-control" name="relation" >
                <option value="">Select realtion</option>
                <option value="s/o">s/o</option>
                <option value="d/o">d/o</option>
                <option value="w/o">w/o</option>
              </select>
          </div>
      </div>
        <div class="col-xs-5 col-sm-5 col-md-5">
            <div class="form-group">
                <label>Relative Full Name: </label>
                <input type="text" name="relative_name" class="form-control">
            </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6">
            <div class="form-group">
                <label>Gender : <span class="text-danger"><small>required</small></span></label>
                <select class="form-control" name="gender" id="select_gender" required>
                  <option value="">Select Gender</option>
                  <option value="ma">Male Adult</option>
                  <option value="fa">Female Adult</option>
                  <option value="mc">Male Child</option>
                  <option value="fc">Female Child</option>
                </select>
            </div>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6">
            <div class="form-group">
                <label>Age : <b><small id="age_limit_alert" class="d-none"></small></b> <span class="text-danger"><small>required</small></span></label>
                <input type="number" name="age" id="age_limit" class="form-control" disabled placeholder="" required>
            </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6">
            <div class="form-group">
                <label>CNIC No : </label> <small><i><b>Format :</b> 37405-0738379-1</i></small><span class="text-danger"><small> CNIC or Mobile No Required</small></span>
                <input type="text" name="cnic_number" id="cnic_number" class="form-control pec-special-feild1" placeholder="37405-0738379-1" >
            </div>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6">
          <div class="form-group">
              <label>Contact No :</label> <small><i><b>Format :</b> 0333-5383745</i></small>
              <input type="text" name="contact_number" id="phone" class="form-control phone  pec-special-feild1" placeholder="0333-5383745">
          </div>
      </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <label>Address :</label>
                <textarea class="form-control" name="address"></textarea>
            </div>
        </div>
      </div>
      <div class="row">

        <div class="col-xs-6 col-sm-6 col-md-6">
            <div class="form-group">
                <label>Refer By : <span class="text-danger"><small>required</small></span></label>
                <select class="form-control" name="refer_by" required>
                  <option value="">Select Refer By</option>
                  @foreach ($referby as $item)
                    <option value="{{ $item->pec_form_cat_id }}">{{ $item->category_name }}</option>
                  @endforeach
                </select>
            </div>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6">
            <div class="form-group">
                <label>Book Number : <span class="text-danger"><small>required</small></span></label>
                <input type="text" name="cat_book_number" class="form-control" required>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
          <div class="form-group">
              <label>Referrals : <span class="text-danger"><small>required</small></span></label>
              <select class="form-control" name="referrals" id="select_referrals" required>
                <option value="">Select Referral</option>
                @foreach ($referral as $item)
                  <option value="{{ $item->pec_form_cat_id }}" data-slug="{{ $item->slug }}">{{ $item->category_name }}</option>
                @endforeach
              </select>
          </div>
        </div>
      </div>

      <div class="row  d-none" id="diabetic-container">
        <div class="col-xs-12 col-sm-12 col-md-12" >
          <div class="alert alert-info" role="alert"> Select & Enter Diabetic Values </div>
          <hr>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6" >
            <div class="form-group">
                <label>Diabetic :</label>
                <select class="form-control" name="diabetic" id="diabetic">
                  <option value="">Select Diabetic Condition</option>
                  <option value="1">Diabetic</option>
                  <option value="0">Non Diabetic</option>

                </select>
            </div>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6">
          <div class="form-group">
              <label>Value :</label>
              <input type="text" name="diabetic_value" id="diabetic_value" class="form-control">
          </div>
        </div>
      </div>

      <div class="row  d-none" id="cataract-container">
        <div class="col-xs-12 col-sm-12 col-md-12" >
          <div class="alert alert-info" role="alert"> Select Cataract for patient </div>
          <hr>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12" >
            <div class="form-group">
                <label>Cataract Operation :</label>
                <select class="form-control" name="cataract_op_slug" id="cataract_op_slug">
                  <option value="">Select Cataract</option>
                  <option value="cataract-right-eye">Cataract (Right Eye)</option>
                  <option value="cataract-left-eye">Cataract (Left Eye)</option>
                  <option value="cataract-both-eyes">Cataract (Both Eyes)</option>
                </select>
            </div>
        </div>
      </div>

      <div class="row  d-none" id="health-facility-container">
        <div class="col-xs-12 col-sm-12 col-md-12" >
          <div class="alert alert-secondary" role="alert"> Refer to Health Facility </div>
          <hr>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
          <div class="form-group">
            <label>If Healt facility information not available :</label>
            <input type="checkbox" id="operation_private" name="operation_private" value="private" /> <b class="text-danger">Private</b>
          </div>
          <hr>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6">
          <div class="form-group">
            <label>Healt Facilities :</label>
            <select class="form-control" name="health_facility_id" id="select_health_facility_referal" data-url='{{ url("/doctors/get_doctors") }}' required>
              <option value="">Select Health Facility</option>
              @if (isset($health_facilities))
              @foreach ($health_facilities as $item)
                <option value="{{ $item->health_facility_id }}">{{ $item->health_facility_name }}</option>
              @endforeach
              @endif
            </select>
          </div>
        </div>

        <div class="col-xs-6 col-sm-6 col-md-6">
          <div class="form-group">
            <label for="first_name">Select Doctor :</label>
            <select class="form-control" name="doctor_id" id="doctor_id" disabled>
          </select>
          </div>
        </div>

    </div>

      <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12" id="remark">
            <div class="form-group">
                <label>Remarks :</label>
                <textarea name="remark" class="form-control"></textarea>
            </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <div id="form_error"></div>
                <input type="submit" class="btn  btn-block big-s-btn btn-primary" value="Add Information">
        </div>

    </div>

</form>
              </div>
            </div>
          </div>
        </div>
      </div>
  </div>


@endsection
