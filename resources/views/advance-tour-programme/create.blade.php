@extends('layout')

@section('content')
@php
    $user = Auth::user();

    $user_centers_ids = DB::table('usermeta')
                      ->where('user_id', '=', $user->id)
                      ->where('meta_key', '=', 'center_id')
                      ->get()
                      ->pluck('meta_value')
                      ->ToArray();
    $user_centers = DB::table('pec_center')
              ->whereIn('center_id', $user_centers_ids)->get(); 
              $center_session = session()->get('center_info');


@endphp

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper"> 
    <!-- Content Header (Page header) -->
    <div class="content-header sty-one">
      <h1>Advance Tour Programme</h1>
      <ol class="breadcrumb">
        <li><a href="#">Dashboard</a></li>
        <li class="sub-bread"><i class="fa fa-angle-right"></i> Advance Tour Programme</li>
        <li><i class="fa fa-angle-right"></i> Advance Tour Programme</li>
      </ol>
    </div>
    
    <div class="content">

    <!-- will be used to show any messages -->
    @if (Session::has('success'))
      <div class="alert alert-info">{{ Session::get('success') }}</div>
    @endif

        <div class="row">
          <div class="col-lg-12">
            <div class="card card-outline">
              <div class="card-header bg-blue">
                <h5 class="text-white m-b-0">Advance Tour Programme</b></span></h5>
              </div>
              <div class="card-body">
      
        <!-- if there are creation errors, they will show here -->
        <form action="{{ route('advance-tour-programme.store') }}" method="POST" autocomplete="off">
          @csrf
          @method('POST')

          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 stick-head-table-container">
    
              @php
              $month = date('m');
              $year = date('y');
              $num = cal_days_in_month(CAL_GREGORIAN, $month, $year);
              $dates_month = array();
              @endphp
              <div class="table-responsive  stick-head-table-container">
                <table class="table table-striped table-bordered" >
                  <thead class="thead-light">
                    <tr>
                      <th scope="col"><div style="width:80px !important">Date</div></th>
                      <th scope="col"><div style="width:150px">Place</div></th>
    
                      <th scope="col"><div style="width:130px">Mode of Travelling</div></th>
                      <th scope="col"><div style="width:110px">Leprosy Medicines to Reg. NO.</div></th>
                      <th scope="col"><div style="width:110px">Under Surv. Reg No.</div></th>
                      <th scope="col"><div style="width:110px">Contacts of Reg No.</div></th>
                      <th scope="col"><div style="width:110px">Smear of Reg No.</div></th>
                      <th scope="col"><div style="width:110px">P.O.D of Reg No.</div></th>
                      <th scope="col"><div style="width:110px">Defaulter Reg No.</div></th>
                      <th scope="col"><div style="width:110px">Notification Name</div></th>
                      <th scope="col"><div style="width:50px">Social Work Reg No.</div></th>
                      <th scope="col"><div style="width:50px">PEC Work</div></th>
                      <th scope="col"><div style="width:110px">School Screening<br>
                        (Name of School)</div>
                      </th>
                      <th scope="col"><div style="width:110px">Survey<br>
                        (Name of School)</div>
                      </th>
                      <th scope="col"><div style="width:110px">Any other  Work</div></th>
                      <th scope="col"><div style="width:110px">S.S - Field Clinic CAT F/U - ETC</div></th>
                      <th scope="col"><div style="width:110px">Night Stay at</div></th>
                      <th scope="col"><div style="width:110px">Village</div></th>
                      <th scope="col"><div style="width:110px">Union Council</div></th>
                      <th scope="col"><div style="width:200px">Remarks</div></th>
                    </tr>
                  </thead>
                  <tbody>
           
                  @php
      
                  ($monthly_detail['submit_status'])?'1':'0';
                  
                  $atp_detail =  json_decode($monthly_detail['atp_detail'], true);
                  
                  $total_da = 0;
                  // for ($i = 1; $i <= 1; $i++)
                  for ($i = 1; $i <= $num; $i++)
                    {
                      
                    $mktime = mktime(0, 0, 0, $month, $i, $year);
                    $date = date('d-M-Y', strtotime("+1 months", $mktime));
                    $dates_month[$i] = $date;
                
                    // {{ $da_detail->description[strtotime($dates_month[$i])] }}
                    @endphp  
                      <tr>
                        <th scope="row"><small>{{ $dates_month[$i] }}</small></th>
                        <td>
                          <select class="form-control" name="atp_detail[center_id][{{ strtotime($dates_month[$i]) }}]" >
                    <option value="">Select Center</option>
                  @foreach ($user_centers as $item)
                    <option @selected(isset($atp_detail['center_id'][strtotime($dates_month[$i])]) && $atp_detail['center_id'][strtotime($dates_month[$i])] == $item->center_id) value="{{ $item->center_id }}">{{ $item->center_name }}</option>
                  @endforeach
                          </select>
                        </td>

                        <td>
                          <select class="form-control" name="atp_detail[mode_traveling][{{ strtotime($dates_month[$i]) }}]" >
                            <option value="">Select</option>
                            <option "MotorBike" @selected(isset($atp_detail['mode_traveling'][strtotime($dates_month[$i])]) && $atp_detail['mode_traveling'][strtotime($dates_month[$i])] == "MotorBike")>MotorBike</option>
                            <option "Bike" @selected(isset($atp_detail['mode_traveling'][strtotime($dates_month[$i])]) && $atp_detail['mode_traveling'][strtotime($dates_month[$i])] == "Bike")>Bike</option>
                            <option "Walk" @selected(isset($atp_detail['mode_traveling'][strtotime($dates_month[$i])]) && $atp_detail['mode_traveling'][strtotime($dates_month[$i])] == "Walk")>Walk</option>
                          </select>
                        </td>
                        <td>
                          <input type="text" name="atp_detail[leprosy_medicine_reg_no][{{ strtotime($dates_month[$i]) }}]" class="form-control" value="{{ isset($atp_detail['leprosy_medicine_reg_no'][strtotime($dates_month[$i])])?$atp_detail['leprosy_medicine_reg_no'][strtotime($dates_month[$i])]:'' }}">
                        </td>
                        <td>
                          <input type="text" name="atp_detail[user_surv_reg_no][{{ strtotime($dates_month[$i]) }}]" class="form-control" value="{{ isset($atp_detail['user_surv_reg_no'][strtotime($dates_month[$i])])?$atp_detail['user_surv_reg_no'][strtotime($dates_month[$i])]:'' }}">
                        </td>
                        <td>
                          <input type="text" name="atp_detail[contacts_reg_no][{{ strtotime($dates_month[$i]) }}]" class="form-control" value="{{ isset($atp_detail['contacts_reg_no'][strtotime($dates_month[$i])])?$atp_detail['contacts_reg_no'][strtotime($dates_month[$i])]:'' }}">
                        </td>
                        <td>
                          <input type="text" name="atp_detail[smear_reg_no][{{ strtotime($dates_month[$i]) }}]" class="form-control"  value="{{ isset($atp_detail['smear_reg_no'][strtotime($dates_month[$i])])?$atp_detail['smear_reg_no'][strtotime($dates_month[$i])]:'' }}">  
                        </td>
                        <td>
                          <input type="text" name="atp_detail[pod_reg_no][{{ strtotime($dates_month[$i]) }}]" class="form-control" value="{{ isset($atp_detail['pod_reg_no'][strtotime($dates_month[$i])])?$atp_detail['pod_reg_no'][strtotime($dates_month[$i])]:'' }}">
                        </td>
                        <td>
                          <input type="text" name="atp_detail[defualter_reg_no][{{ strtotime($dates_month[$i]) }}]" class="form-control" value="{{ isset($atp_detail['defualter_reg_no'][strtotime($dates_month[$i])])?$atp_detail['defualter_reg_no'][strtotime($dates_month[$i])]:'' }}">
                        </td>
                        <td>
                          <input type="text" name="atp_detail[notification_name][{{ strtotime($dates_month[$i]) }}]" class="form-control" value="{{ isset($atp_detail['notification_name'][strtotime($dates_month[$i])])?$atp_detail['notification_name'][strtotime($dates_month[$i])]:'' }}">
                        </td>
                        <td>
                          <input type="checkbox" name="atp_detail[social_work][{{ strtotime($dates_month[$i]) }}]"
                          {{ (isset($atp_detail['social_work'][strtotime($dates_month[$i])]))?'checked':''; }}>
                        </td>
                        <td>
                          <input type="checkbox" name="atp_detail[pec_work][{{ strtotime($dates_month[$i]) }}]" value="1" 
                          {{ (isset($atp_detail['pec_work'][strtotime($dates_month[$i])]))?'checked':''; }}>
                        </td>
                        <td>
                          <input type="text" name="atp_detail[ss_name_school][{{ strtotime($dates_month[$i]) }}]" class="form-control" value="{{ isset($atp_detail['ss_name_school'][strtotime($dates_month[$i])])?$atp_detail['ss_name_school'][strtotime($dates_month[$i])]:'' }}">
                        </td>
                        <td>
                          <input type="text" name="atp_detail[survey_name_school][{{ strtotime($dates_month[$i]) }}]" class="form-control" value="{{ isset($atp_detail['survey_name_school'][strtotime($dates_month[$i])])?$atp_detail['survey_name_school'][strtotime($dates_month[$i])]:'' }}">
                  
                        </td>
                        <td>
                          <input type="text" name="atp_detail[any_other_work][{{ strtotime($dates_month[$i]) }}]" class="form-control" value="{{ isset($atp_detail['any_other_work'][strtotime($dates_month[$i])])?$atp_detail['any_other_work'][strtotime($dates_month[$i])]:'' }}">
                        </td>
                        <td>
                          <input type="text" name="atp_detail[ss_clinic_cat][{{ strtotime($dates_month[$i]) }}]" class="form-control" value="{{ isset($atp_detail['ss_clinic_cat'][strtotime($dates_month[$i])])?$atp_detail['ss_clinic_cat'][strtotime($dates_month[$i])]:'' }}">
                        </td>
                        <td>
                          <input type="text" name="atp_detail[night_stay][{{ strtotime($dates_month[$i]) }}]" class="form-control"
                          value="{{ isset($atp_detail['night_stay'][strtotime($dates_month[$i])])?$atp_detail['night_stay'][strtotime($dates_month[$i])]:'' }}">
                        </td>
                        <td>
                          <input type="text" name="atp_detail[village][{{ strtotime($dates_month[$i]) }}]" class="form-control"
                          value="{{ isset($atp_detail['village'][strtotime($dates_month[$i])])?$atp_detail['village'][strtotime($dates_month[$i])]:'' }}">
                        </td>
                        <td>
                          <input type="text" name="atp_detail[union_council][{{ strtotime($dates_month[$i]) }}]" class="form-control" value="{{ isset($atp_detail['union_council'][strtotime($dates_month[$i])])?$atp_detail['union_council'][strtotime($dates_month[$i])]:'' }}">
                        </td>
                        <td>
                          <textarea class="form-control" name="atp_detail[remarks][{{ strtotime($dates_month[$i]) }}]">{{ isset($atp_detail['remarks'][strtotime($dates_month[$i])])?$atp_detail['remarks'][strtotime($dates_month[$i])]:'' }}</textarea>
                        </td>

                      </tr>
                    @php
                 
                    }
                  @endphp
           
                  </tbody>
                </table>
              </div>
                
            </div>
          </div>


          <div class="row pt-4">
              <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                  <button type="submit" class="btn btn-primary">Submit</button>
              </div>
          </div>

        </form>

</div>



          
              </div>
            </div>
          </div>
        </div>     
      </div>
      
  </div>

  

@endsection