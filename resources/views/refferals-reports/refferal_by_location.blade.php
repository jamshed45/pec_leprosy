@extends('layout')

@section('content')
<?php 
$user = Auth::user();
// die();
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper"> 
    <!-- Content Header (Page header) -->
    <div class="content-header sty-one">
      <h1>Refferal Patients by User</h1>
      <ol class="breadcrumb">
        <li><a href="#">Dashboard</a></li>
        <li class="sub-bread"><i class="fa fa-angle-right"></i> Refferal Patients</li>
        <li><i class="fa fa-angle-right"></i> Patient's List by user</li>
      </ol>
    </div>
   
    <div class="content">
        <div class="row">
    
          <div class="col-xs-12 col-sm-12 col-md-12 mb-4">
            <form action="{{ route('referrals-reports-by-location') }}">
              @method('POST')
              @csrf
            <div class="row">
              <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <label>Referrals : <span class="text-danger"><small>required</small></span></label>
                    <select class="form-control" name="referrals" id="select_referrals" required>
                      <option value="">Select Referral</option>
                      @foreach ($referral as $item)
                        <option value="{{ $item->pec_form_cat_id }}" data-slug="{{ $item->slug }}" {{ ($search_field_info['referral_id'] == $item->pec_form_cat_id )?'selected':'' }}>{{ $item->category_name }}</option>
                      @endforeach 
                    </select>
                </div>
              </div>   
            </div>
            <div class="row">  
              <div class="col-md-3">  
                <div class="form-group">
                    <label for="first_name">Select Location Activity :</label>
                    <select class="form-control activity_location_filter_id" name="activity_location_id" id="activity_location_filter_id" >
                        <option value="">Select Activity Location</option>
                        @foreach ($activity_location as $item)
                            <option value="{{ $item->activity_location_id }}" {{($search_field_info['activity_location_id'] == $item->activity_location_id)?'selected':''}}>{{ $item->activity_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
              </div>
             
              <div class="col-md-4">  
                <div class="form-group">
                    <label for="first_name">Month/Year Date : <span class="text-danger"><small>required</small></span></label>
                    <input type="month" name="monthly_yearly" class="form-control" value="{{ $search_field_info['select_date']}}"  required/>
                </div>
              </div>
              <div class="col-md-4">  
                <div class="form-group">
                    <label for="first_name">Monthly :</label><br>
                    <input type="checkbox" name="monthly_or_yearly" value="1" {{ ($search_field_info['monthly'])?'checked':'' }}  />
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <hr>
              </div>
              <div class="col-md-12">  
                <div class="form-group">
                  @php
                    $refferal_column_check = 'checked';
                    if($search_field_info['search_status'] && !isset($sub_filter_info['refferal_column']))
                    {
                      $refferal_column_check = '';
                    }
                    elseif($search_field_info['search_status'] && isset($sub_filter_info['refferal_column'])) 
                    {
                      $refferal_column_check = 'checked';
                    }
             
                  @endphp
                    <label for="Name">Refferal Column :</label>
                    <input type="checkbox" name="sub_filter_info[refferal_column]" value="1" {{ $refferal_column_check }} />
                </div>
              </div>
              <div class="col-md-2">  
                <div class="form-group">
                    <label for="relation_name">Surgery :</label>
                    <input type="checkbox" name="sub_filter_info[refferal_surgery]" value="1" {{ (isset($sub_filter_info['refferal_surgery']))?'checked':'' }}  />
                </div>
              </div>
              <div class="col-md-2">  
                <div class="form-group">
                    <label for="age">Diabetic :</label>
                    <input type="checkbox" name="sub_filter_info[refferal_diabetic]" value="1" {{ (isset($sub_filter_info['refferal_diabetic']))?'checked':'' }}  />
                </div>
              </div>
              <div class="col-md-2">  
                <div class="form-group">
                    <label for="age">Val :</label>
                    <input type="checkbox" name="sub_filter_info[refferal_diabetic_val]" value="1" {{ (isset($sub_filter_info['refferal_diabetic_val']))?'checked':'' }}  />
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <hr>
              </div>
              <div class="col-md-12">  
                <div class="form-group">
                  @php
                    $lots_column_check = 'checked';
                    if($search_field_info['search_status'] && !isset($sub_filter_info['lots_column']))
                    {
                      $lots_column_check = '';
                    }
                    elseif($search_field_info['search_status'] && isset($sub_filter_info['lots_column'])) 
                    {
                      $lots_column_check = 'checked';
                    }
             
                  @endphp
                    <label for="Name">Lots Column :</label>
                    <input type="checkbox" name="sub_filter_info[lots_column]" value="1" {{ $lots_column_check }} />
                </div>
              </div>
              <div class="col-md-2">  
                <div class="form-group">
                    <label for="relation_name">Location :</label>
                    <input type="checkbox" name="sub_filter_info[activity_name]" value="1" {{ (isset($sub_filter_info['activity_name']))?'checked':'' }}  />
                </div>
              </div>
              <div class="col-md-2">  
                <div class="form-group">
                    <label for="age">Center :</label>
                    <input type="checkbox" name="sub_filter_info[center_name]" value="1" {{ (isset($sub_filter_info['center_name']))?'checked':'' }}  />
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <hr>
              </div>

              <div class="col-md-12">  
                <div class="form-group">
                  @php
                    $patient_column_check = 'checked';
                    if($search_field_info['search_status'] && !isset($sub_filter_info['patient_column']))
                    {
                      $patient_column_check = '';
                    }
                    elseif($search_field_info['search_status'] && isset($sub_filter_info['patient_column'])) 
                    {
                      $patient_column_check = 'checked';
                    }
                
                  @endphp
                    <label for="Name">Patient Column :</label>
                    <input type="checkbox" name="sub_filter_info[patient_column]" value="1" {{ $patient_column_check }} />
                </div>
              </div>
              <div class="col-md-2">  
                <div class="form-group">
                    <label for="relation_name">Relation Name :</label>
                    <input type="checkbox" name="sub_filter_info[relation_name]" value="1" {{ (isset($sub_filter_info['relation_name']))?'checked':'' }}  />
                </div>
              </div>
              <div class="col-md-2">  
                <div class="form-group">
                    <label for="age">Age :</label>
                    <input type="checkbox" name="sub_filter_info[age]" value="1" {{ (isset($sub_filter_info['age']))?'checked':'' }}  />
                </div>
              </div>
              <div class="col-md-2">  
                <div class="form-group">
                    <label for="age">Gender :</label>
                    <input type="checkbox" name="sub_filter_info[gender]" value="1" {{ (isset($sub_filter_info['gender']))?'checked':'' }} />
                </div>
              </div>
              <div class="col-md-2">  
                <div class="form-group">
                    <label for="contact_no">Contact No :</label>
                    <input type="checkbox" name="sub_filter_info[contact_no]" value="1" {{ (isset($sub_filter_info['contact_no']))?'checked':'' }} />
                </div>
              </div>
              <div class="col-md-2">  
                <div class="form-group">
                    <label for="contact_no">CNIC :</label>
                    <input type="checkbox" name="sub_filter_info[cnic]" value="1" {{ (isset($sub_filter_info['cnic']))?'checked':'' }} />
                </div>
              </div>
            </div>

            


            <div class="row">
              <div class="col-md-12">
                <hr>
              </div>
              <div class="col-md-12">  
                <div class="form-group">
                  @php
                    $remarks_column_check = 'checked';
                    if($search_field_info['search_status'] && !isset($sub_filter_info['remarks_column']))
                    {
                      $remarks_column_check = '';
                    }
                    elseif($search_field_info['search_status'] && isset($sub_filter_info['remarks_column'])) 
                    {
                      $remarks_column_check = 'checked';
                    }
             
                  @endphp
                    <label for="Name">Remarks Column :</label>
                    <input type="checkbox" name="sub_filter_info[remarks_column]" value="1" {{ $remarks_column_check }} />
                </div>
              </div>
              </div>

              
              <div class="row">
              <div class="col-md-12">
                <hr>
              </div>
              <div class="col-md-12">  
                <div class="form-group">
                  @php
                    $referto_column_check = 'checked';
                    if($search_field_info['search_status'] && !isset($sub_filter_info['referto_column']))
                    {
                      $referto_column_check = '';
                    }
                    elseif($search_field_info['search_status'] && isset($sub_filter_info['referto_column'])) 
                    {
                      $referto_column_check = 'checked';
                    }
             
                  @endphp
                    <label for="Name">Refer To Column :</label>
                    <input type="checkbox" name="sub_filter_info[referto_column]" value="1" {{ $referto_column_check }} />
                </div>
              </div>
              </div>

              <div class="row">
              <div class="col-md-2">  
                <div class="form-group">
                    <label for="relation_name">Healt Facility :</label>
                    <input type="checkbox" name="sub_filter_info[health_facility]" value="1" {{ (isset($sub_filter_info['health_facility']))?'checked':'' }}  />
                </div>
              </div>
              <div class="col-md-2">  
                <div class="form-group">
                    <label for="age">Doctor :</label>
                    <input type="checkbox" name="sub_filter_info[doctor]" value="1" {{ (isset($sub_filter_info['doctor']))?'checked':'' }}  />
                </div>
              </div>
             
            </div>

            <div class="row">
              <div class="col-md-12">
                <hr>
              </div>
              <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                      <a href="{{ URL::current() }}" class="btn btn-primary">Clear Fields</a> <button type="submit" class="btn btn-primary">Generate Refferal Report</button>
              </div>
            </div>
            </form>
          </div>

          @include('refferals-reports/refferal_by_grid_part') 
          
        </div>     
      </div>

  </div>
@endsection
