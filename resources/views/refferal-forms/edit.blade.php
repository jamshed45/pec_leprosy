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

            $uer_tehsils_ids = DB::table('usermeta')
                    ->where('user_id', $user->id)
                    ->where('meta_key', 'tehsil_id')
                    ->pluck('meta_value')
                    ->toArray();

            $center_session = session()->get('center_info');

            $health_facilities = DB::table('health_facilities')
                    ->whereIn('tehsil_id',  $uer_tehsils_ids)
                    ->get();       
        }
        // echo "<pre>";
        //     print_r($RefferalForm);
        // echo "</pre>";   
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
<form action="{{ route('refferal-forms.update',$RefferalForm->refferal_forms_id) }}" method="POST" id="add_referral">
    @csrf
    @method('PUT')
    
    <input type="hidden" name="province_id" value="{{ $RefferalForm->province_id }}">
    <input type="hidden" name="division_id" value="{{ $RefferalForm->division_id }}">
    <input type="hidden" name="district_id" value="{{ $RefferalForm->district_id }}">
    <input type="hidden" name="tehsil_id" value="{{ $RefferalForm->tehsil_id }}">
    {{-- <input type="hidden" name="center_id" value="{{ $RefferalForm->center_id }}"> --}}

    <div class="row" id="center-info-container">
      <?php 
      $activity_location = DB::table('activity_locations')
      ->get()
      ->toArray();

      $my_centers = DB::table('usermeta')
                      ->where('user_id', $user->id)
                      ->where('meta_key','center_id')
                      ->pluck('meta_value')
                      ->toArray();

      $user_centers = DB::table('pec_center')
                      ->wherein('center_id', $my_centers)
                      ->where('activity_location_id', $RefferalForm->activity_location_id)
                      ->orderBy('center_name')
                      ->get()
                      ->toArray();
      
      ?>
      <div class="col-md-3">
        <div id="select_center_container">
          <label for="activity_location_id"><b>Select Activity Location</b></label>
              <select class="form-control" name="activity_location_id" id="user_activity_location_old_rec" style="height: 55px;" data-url="{{ url('/centers/get_activity_location_centers') }}" required>
                <option value="">Select Activity Location</option>
                @foreach ($activity_location as $item)
                <option value="{{ $item->activity_location_id }}" {{ ($item->activity_location_id == $RefferalForm->activity_location_id) ? 'selected' : ''}}>{{ $item->activity_name }}</option>
                @endforeach
             </select>
        </div>
      </div>
      
      <div class="col-md-3" id="load_user_center_old_rec">
        <div id="select_center_container">
          <label for="center_id"><b>Select Center</b></label>
          <select class="form-control py-3 load-activity-location-center-old-rec" name="center_id" id="center_id" style="height: 55px;">
              <option>Select Center</option>
            @foreach ($user_centers as $item)
              <option @selected(isset($RefferalForm->center_id) && $RefferalForm->center_id == $item->center_id) value="{{ $item->center_id }}">{{ $item->center_name }}</option>
            @endforeach
          </select>
        </div>
      </div>
      
      <div class="col-md-3" id="user_entry_date_old_rec">
        <div id="select_center_container">
          <label><b>Select Date</b></label>
          <input class="form-control  py-3" placeholder=""  type="date" id="entry_date" max="{{ date('Y-m-d') }}" value="<?=date('Y-m-d', strtotime($RefferalForm->created_at));?>"  />
        </div>
      </div>
    
      <div id="loader" class="col-md-12 py-2">
    
      </div>
      <div class="col-md-12">
        <hr>
      </div>
    </div>

    
     <div class="row">
        
        <div class="col-xs-5 col-sm-5 col-md-5">
            <div class="form-group">
                <label for="first_name">Full Name:</label>
                <input type="text" name="full_name" class="form-control" value="{{ $RefferalForm->full_name }}" required>
            </div>
        </div>
        <div class="col-xs-2 col-sm-2 col-md-2">
          <div class="form-group">
              <label for="first_name">Relation:</label>
              <select class="form-control" name="relation" required>
                <option value="">Select realtion</option>
                <option value="s/o" {{ ($RefferalForm->relation == 's/o') ? 'selected' : ''}}>s/o</option>
                <option value="d/o" {{ ($RefferalForm->relation == 'd/o') ? 'selected' : ''}}>d/o</option>
                <option value="w/o" {{ ($RefferalForm->relation == 'w/o') ? 'selected' : ''}}>w/o</option>
              </select>
          </div>
      </div>
        <div class="col-xs-5 col-sm-5 col-md-5">
            <div class="form-group">
                <label>Relative Full Name:</label>
                <input type="text" name="relative_name" class="form-control" value="{{ $RefferalForm->relative_name }}" required>
            </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6">
            <div class="form-group">
                <label>Gender :</label>
                <select class="form-control" name="gender" id="select_gender">
                  <option value="">Select Gender</option>
                  <option value="ma" {{ ($RefferalForm->gender == 'ma') ? 'selected' : ''}}>Male Adult</option>
                  <option value="fa" {{ ($RefferalForm->gender == 'fa') ? 'selected' : ''}}>Female Adult</option>
                  <option value="mc" {{ ($RefferalForm->gender == 'mc') ? 'selected' : ''}}>Male Child</option>
                  <option value="fc" {{ ($RefferalForm->gender == 'fc') ? 'selected' : ''}}>Female Child</option>
                </select>
            </div>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6">
            <div class="form-group">
                <label>Age : <b><small id="age_limit_alert" class="d-none"></small></b></label>
                <input type="number" name="age" id="age_limit" class="form-control" value="{{ $RefferalForm->age }}" placeholder="">
            </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6">
            <div class="form-group">
                <label>CNIC No : </label> <small><i><b>Format :</b> 37405-0738379-1</i></small>
                <input type="text" name="cnic_number" id="cnic_number" class="form-control pec-special-feild1" value="{{ $RefferalForm->cnic_number }}" placeholder="37405-0738379-1" >
            </div>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6">
          <div class="form-group">
              <label>Contact No :</label> <small><i><b>Format :</b> 0333-5383745</i></small>
              <input type="text" name="contact_number" id="phone" class="form-control phone  pec-special-feild1" value="{{ $RefferalForm->contact_number }}" placeholder="0333-5383745">
          </div>
      </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <label>Address :</label>
                <textarea class="form-control" name="address">{{ $RefferalForm->address }}</textarea>
            </div>
        </div>
      </div>
      <div class="row">

        <div class="col-xs-6 col-sm-6 col-md-6">
            <div class="form-group">
                <label>Refer By :</label>
                <select class="form-control" name="refer_by" required>
                  <option value="">Select Refer By</option>
                  @foreach ($referby as $item)
                    <option value="{{ $item->pec_form_cat_id }}" {{ ($item->pec_form_cat_id == $RefferalForm->refer_by) ? 'selected' : ''}}>{{ $item->category_name }}</option>
                  @endforeach 
                </select>
            </div>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6">
            <div class="form-group">
                <label>Book Number :</label>
                <input type="text" name="cat_book_number" class="form-control" value="{{ $RefferalForm->cat_book_number }}" required>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
          <div class="form-group">
              <label>Referrals :</label>
              <select class="form-control" name="referrals" id="select_referrals" required>
                <option value="">Select Referral</option>
                @foreach ($referral as $item)
      
                <option value="{{ $item->pec_form_cat_id }}" data-slug="{{ $item->slug }}" {{ ($item->pec_form_cat_id == $RefferalForm->referrals) ? 'selected' : ''}}>{{ $item->category_name }}</option>

                @endforeach
              </select>
          </div>
        </div>
      </div>
      @php
        $refferal_cat = DB::table('pec_form_categories')
                      ->where('pec_form_cat_id', $RefferalForm->referrals)
                      ->first();
                    
      @endphp
      <div class="row {{ ($refferal_cat->slug == 'cataract-surgery')?'':'d-none' }}" id="diabetic-container">
        <div class="col-xs-12 col-sm-12 col-md-12" >
          <div class="alert alert-info" role="alert"> Select & Enter Diabetic Values </div>
          <hr>
        </div> 
        <div class="col-xs-6 col-sm-6 col-md-6" >
            <div class="form-group">
                <label>Diabetic :</label>
                <select class="form-control" name="diabetic" id="diabetic">
                  <option value="">Select Diabetic Condition</option>
                  <option value="1" {{ ($RefferalFormReferTo->diabetic == 1) ? 'selected' : ''}}>Diabetic</option>
                  <option value="0" {{ ($RefferalFormReferTo->diabetic == 0) ? 'selected' : ''}}>Non Diabetic</option>

                </select>
            </div>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6">
          <div class="form-group">
              <label>Value :</label>
              <input type="text" name="diabetic_value" id="diabetic_value" value="{{ $RefferalFormReferTo->diabetic_value }}" class="form-control">
          </div>
        </div>
      </div>

      <div class="row  {{ ($refferal_cat->slug == 'cataract-surgery')?'':'d-none' }}" id="cataract-container">
        <div class="col-xs-12 col-sm-12 col-md-12" >
          <div class="alert alert-info" role="alert"> Select Cataract for patient </div>
          <hr>
        </div> 
        <div class="col-xs-12 col-sm-12 col-md-12" >
            <div class="form-group">
                <label>Cataract Operation :</label>
                <select class="form-control" name="cataract_op_slug" id="cataract_op_slug">
                  <option value="">Select Cataract</option>
                  <option value="cataract-right-eye" {{ ($RefferalFormReferTo->cataract_op_slug == 'cataract-right-eye') ? 'selected' : ''}}>Cataract (Right Eye)</option>
                  <option value="cataract-left-eye" {{ ($RefferalFormReferTo->cataract_op_slug == 'cataract-left-eye') ? 'selected' : ''}}>Cataract (Left Eye)</option>
                  <option value="cataract-both-eyes" {{ ($RefferalFormReferTo->cataract_op_slug == 'cataract-both-eyes') ? 'selected' : ''}}>Cataract (Both Eyes)</option>
                </select>
            </div>
        </div>     
      </div>
   
      <div class="row" id="health-facility-container">
        <div class="col-xs-12 col-sm-12 col-md-12" >
          <div class="alert alert-secondary" role="alert"> Refer to Health Facility </div>
          <hr>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
          <div class="form-group">
            <label>If Healt facility information not available :</label>
            <input type="checkbox" id="operation_private" name="operation_private" {{ ($RefferalFormReferTo->private == 1) ? 'checked' : ''}} value="private" /> <b class="text-danger">Private</b>
          </div>
          <hr>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6">
          <div class="form-group">
            <label>Healt Facilities :</label>
            <select class="form-control" name="health_facility_id" id="select_health_facility_referal" data-url='{{ url("/doctors/get_doctors") }}' {{ ($RefferalFormReferTo->private == 1) ? 'disabled' : ''}} required>
              <option value="">Select Health Facility</option>
              @if (isset($health_facilities))
              @foreach ($health_facilities as $item)
                <option value="{{ $item->health_facility_id }}" {{ ($item->health_facility_id == $RefferalFormReferTo->health_facility_id) ? 'selected' : ''}}>{{ $item->health_facility_name }}</option>
              @endforeach 
              @endif
            </select>
          </div>
        </div>
        @php
          $doc_info = DB::table('doctors')
                ->where('doctor_id', $RefferalFormReferTo->doctor_id)
                ->first();
        @endphp
        <div class="col-xs-6 col-sm-6 col-md-6">
          <div class="form-group">
            <label for="first_name">Select Doctor :</label>
            <select class="form-control" name="doctor_id" id="doctor_id" {{ ($RefferalFormReferTo->private == 1) ? 'disabled' : ''}}>
              @if($doc_info)
              <option value="{{ $doc_info->doctor_id }}">{{ $doc_info->doctor_name }}</option>
              @endif
          </select>
          </div>
        </div>
      
    </div>

      <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12" id="remark">
            <div class="form-group">
                <label>Remarks :</label>
                <textarea name="remark" class="form-control">{{ $RefferalForm->address }}</textarea>
            </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <div id="form_error"></div>
                <button type="submit" class="btn  btn-block big-s-btn btn-primary">Add Information</button>
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