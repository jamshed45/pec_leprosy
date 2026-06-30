@extends('layout')

@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper"> 
    <!-- Content Header (Page header) -->
    <div class="content-header sty-one">
      <h1> Refferal Patient Operation Information</h1>
      <ol class="breadcrumb">
        <li><a href="#">Dashboard</a></li>
        <li class="sub-bread"><i class="fa fa-angle-right"></i> Operation</li>
        <li><i class="fa fa-angle-right"></i> Information</li>
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

            // $uer_tehsil_ids = DB::table('usermeta')
            //         ->where('user_id', $user->id)
            //         ->where('meta_key', 'tehsil_id')
            //         ->pluck('meta_value')
            //         ->toArray();
        


            // $health_facilities = DB::table('health_facilities')
            //         ->whereIn('tehsil_id',  $uer_tehsil_ids)
            //         ->get();
            //       }

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
                  <!-- will be used to show any messages -->
    @if (Session::has('success'))
    <div class="alert alert-info">{{ Session::get('success') }}</div>
  @endif
        <div class="row">
          <div class="col-lg-12">
            <div class="card card-outline">
              <div class="card-header bg-blue">
                <h5 class="text-white m-b-0">Add Operation Information</h5>
              </div>
              <div class="card-body">
         
                <div class="row">
                  <div class="col-md-12 my-1">
                    <b>Name : </b> {{ $patient_info->full_name }} {{ $patient_info->relation }} {{ $patient_info->full_name }} 
                    <hr>
                  </div>
                  <div class="col-md-4 my-1">
                    <?php 
                      $refer_by  = DB::table('pec_form_categories')
                                  ->where('pec_form_cat_id', $patient_info->refer_by)
                                  ->get()
                                  ->toArray();        
                      ?>
                    <b>Refer By : </b> {{ $refer_by[0]->category_name }} 
                    <hr>
                  </div>
                  <div class="col-md-4 my-1">
                    <b>Book No : </b> {{ $patient_info->cat_book_number }}
                    <hr>
                  </div>
                  <div class="col-md-4 my-1">
                    <?php 
                      $referrals  = DB::table('pec_form_categories')
                                  ->where('pec_form_cat_id', $patient_info->referrals)
                                  ->get()
                                  ->toArray();        
                      ?>
                    <b>Referrals : </b> {{ $referrals[0]->category_name }}
                    <hr>
                  </div>
                  <div class="col-md-6 my-1">
                    <b>Diabetic Status : </b>  {{ ($patient_type->diabetic == 1)?'Diabetic':'Non Diabetic' }}
                    <hr>
                  </div>
                  <div class="col-md-6 my-1">
                    <b>Diabetic Values : </b> {{ $patient_type->diabetic_value }}
                    <hr>
                  </div>
                  <div class="col-md-12 my-1">
                    <b>Cataract Operation : </b> {{ ucwords(str_replace("-"," ",$patient_type->patient_type)) }}
                    <hr>
                  </div>
                  
       
                </div>  


                      <!-- if there are creation errors, they will show here -->
<form action="{{ route('edit-update-operated-by') }}" >
    @method('POST')
    @csrf
    
      <input type="hidden" name="refferal_forms_id" value="{{ $refferal_forms_id }}">
      <input type="hidden" name="refferal_form_meta_id" value="{{ $patient_operated_type->pec_refferal_form_meta_id }}">

      <div class="row" id="health-facility-container">
        <div class="col-xs-12 col-sm-12 col-md-12">
          <div class="form-group">
              <label for="first_name">Select operation Date:</label>
              <input type="date" name="operation_date" class="form-control" value="{{ $patient_operated_type->operation_date }}" required>
          </div>
        </div>
        
        <div class="col-xs-12 col-sm-12 col-md-12" >
          <div class="alert alert-secondary" role="alert"> Operated By Health Facility </div>
          <hr>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
          <div class="form-group">
            <label>If Health facility information not available :</label>
            <input type="checkbox" id="operation_private" name="operation_private" value="private" {{ $patient_operated_type->private != 0?'checked':'' }} /> <b class="text-danger">Private</b>
          </div>
          <hr>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6">
          <div class="form-group">
            <label>Healt Facilities :</label>
            <select class="form-control" name="health_facility_id" id="select_health_facility_referal" data-url='{{ url("/doctors/get_doctors") }}' {{ $patient_operated_type->private != 0?'disabled':'' }}  required>
              <option value="">Select Health Facility</option>
              @if (isset($health_facilities))
              @foreach ($health_facilities as $item)
                <option @selected(  $patient_operated_type->health_facility_id == $item->health_facility_id) value="{{ $item->health_facility_id }}">{{ $item->health_facility_name }}</option>
              @endforeach 
              @endif
            </select>
          </div>
        </div>

        <div class="col-xs-6 col-sm-6 col-md-6">
          <div class="form-group">
            <label for="first_name">Select Doctor :</label>
            <select class="form-control" name="doctor_id" id="doctor_id" {{ $patient_operated_type->private != 0?'disabled':'' }}> 
              <option value="">Select Doctor</option>
              @if (isset($hosptal_doctors))
              @foreach ($hosptal_doctors as $item)
                <option @selected(  $patient_operated_type->doctor_id != 0) value="{{ $item->health_facility_id }}">{{ $item->doctor_name }}</option>
              @endforeach 
              @endif
          </select>
          </div>
        </div>
      
    </div>

    @if ($patient_type->patient_type == 'cataract-surgery')    
  
    <div class="row" id="cataract-container">
      <div class="col-xs-12 col-sm-12 col-md-12" >
        <div class="alert alert-info" role="alert"> Select Cataract for patient </div>
        <hr>
      </div> 
      <div class="col-xs-6 col-sm-6 col-md-6" >
          <div class="form-group">
              <label>Confirmed Cataract Surgery :</label>
              <select class="form-control" name="confirmed_cataract_surgery" id="confirmed_cataract_surgery" required>
                <option value="">Select Surgery Type</option>
                <option value="with-iol" @selected(  $patient_operated_type->confirmed_cataract_surgery == 'with-iol') >With IOL</option>
                <option value="witout-iol" @selected(  $patient_operated_type->confirmed_cataract_surgery == 'witout-iol') >Without OIL</option>
              </select>
          </div>
      </div>
      <div class="col-xs-6 col-sm-6 col-md-6" >
        <div class="form-group">
            <label>Post - OP :</label>
            <select class="form-control" name="post_op" id="post_op" required>
              <option value="">Select Post - OP</option>
              <option value="number-of-patients-66618" @selected($patient_operated_type->post_op == '6/6-6/18')>Number of patients 6/6-6/18</option>
              <option value="number-of-patients-618660" @selected($patient_operated_type->post_op == '6/18-6/60')>Number of patients < 6/18-6/60</option>
              <option value="number-of-patients-660below" @selected($patient_operated_type->post_op == '6/60 or below')>Number of patients 6/60 or below</option>
            </select>
        </div>
    </div>       
    </div>
    @endif

    <div class="row">
      <div class="col-xs-12 col-sm-12 col-md-12" id="remark">
          <div class="form-group">
              <label>Remarks :</label>
              <textarea name="remark" class="form-control">{{ $patient_operated_type->remark }}</textarea>
          </div>
      </div>
    </div>

      <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <div id="form_error"></div>
                <button type="submit" class="btn  btn-block big-s-btn btn-primary">Update Operated By</button>
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