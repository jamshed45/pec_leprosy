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
        <div class="row">
          <div class="col-lg-12">
            <div class="card card-outline">
              <div class="card-header bg-blue">
                <h5 class="text-white m-b-0">Advance Tour Programme</b></span></h5>
              </div>
              <div class="card-body">
      
        <!-- if there are creation errors, they will show here -->

          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12  stick-head-table-container">
    
              @php
              $month = date('m', strtotime($monthly_detail['entry_month']));
              $year = date('y', strtotime($monthly_detail['entry_month']));
              $num = cal_days_in_month(CAL_GREGORIAN, $month, $year);
              $dates_month = array();
              @endphp
              <div class="table-responsive  stick-head-table-container" >
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
               ;
                  $total_da = 0;
                  // for ($i = 1; $i <= 1; $i++)
                  for ($i = 1; $i <= $num; $i++)
                    {
                      
                    $mktime = mktime(0, 0, 0, $month, $i, $year);
                    $date = date("d-M-Y", $mktime);
                    $dates_month[$i] = $date;

  
                    // {{ $da_detail->description[strtotime($dates_month[$i])] }}
                    @endphp  
                      <tr>
                        <th scope="row"><small>{{ $dates_month[$i] }}</small></th>
                        <td>
                          @php
                          if($atp_detail['center_id'][strtotime($dates_month[$i])])
                          {  
                            $get_user_center = DB::table('pec_center')
                            ->where('center_id', $atp_detail['center_id'][strtotime($dates_month[$i])])->first(); 
                            echo $get_user_center->center_name;
                          } 
                          @endphp
                        </td>
                        <td>
                          {{ $atp_detail['mode_traveling'][strtotime($dates_month[$i])] }}
                        </td>
                        <td>
                          {{ isset($atp_detail['leprosy_medicine_reg_no'][strtotime($dates_month[$i])])?$atp_detail['leprosy_medicine_reg_no'][strtotime($dates_month[$i])]:'' }}
                        </td>
                        <td>
                          {{ isset($atp_detail['user_surv_reg_no'][strtotime($dates_month[$i])])?$atp_detail['user_surv_reg_no'][strtotime($dates_month[$i])]:'' }}
                        </td>
                        <td>
                          {{ isset($atp_detail['contacts_reg_no'][strtotime($dates_month[$i])])?$atp_detail['contacts_reg_no'][strtotime($dates_month[$i])]:'' }}
                        </td>
                        <td>
                          {{ isset($atp_detail['smear_reg_no'][strtotime($dates_month[$i])])?$atp_detail['smear_reg_no'][strtotime($dates_month[$i])]:'' }}  
                        </td>
                        <td>
                          {{ isset($atp_detail['pod_reg_no'][strtotime($dates_month[$i])])?$atp_detail['pod_reg_no'][strtotime($dates_month[$i])]:'' }}
                        </td>
                        <td>
                          {{ isset($atp_detail['defualter_reg_no'][strtotime($dates_month[$i])])?$atp_detail['defualter_reg_no'][strtotime($dates_month[$i])]:'' }}
                        </td>
                        <td>
                          {{ isset($atp_detail['notification_name'][strtotime($dates_month[$i])])?$atp_detail['notification_name'][strtotime($dates_month[$i])]:'' }}
                        </td>
                        <td>
                          @if(isset($atp_detail['social_work'][strtotime($dates_month[$i])]))
                              <i class="fa fa-check-circle text-success"></i><br>
                          @endif                        
                        </td>
                        <td>
                          @if(isset($atp_detail['pec_work'][strtotime($dates_month[$i])]))
                              <i class="fa fa-check-circle text-success"></i><br>
                          @endif
                        </td>
                        <td>
                          {{ isset($atp_detail['ss_name_school'][strtotime($dates_month[$i])])?$atp_detail['ss_name_school'][strtotime($dates_month[$i])]:'' }}
                        </td>
                        <td>
                          {{ isset($atp_detail['survey_name_school'][strtotime($dates_month[$i])])?$atp_detail['survey_name_school'][strtotime($dates_month[$i])]:'' }}  
                        </td>
                        <td>
                          {{ isset($atp_detail['any_other_work'][strtotime($dates_month[$i])])?$atp_detail['any_other_work'][strtotime($dates_month[$i])]:'' }}
                        </td>
                        <td>
                          {{ isset($atp_detail['ss_clinic_cat'][strtotime($dates_month[$i])])?$atp_detail['ss_clinic_cat'][strtotime($dates_month[$i])]:'' }}
                        </td>
                        <td>
                          {{ isset($atp_detail['night_stay'][strtotime($dates_month[$i])])?$atp_detail['night_stay'][strtotime($dates_month[$i])]:'' }}
                        </td>
                        <td>
                          {{ isset($atp_detail['village'][strtotime($dates_month[$i])])?$atp_detail['village'][strtotime($dates_month[$i])]:'' }}
                        </td>
                        <td>
                          {{ isset($atp_detail['union_council'][strtotime($dates_month[$i])])?$atp_detail['union_council'][strtotime($dates_month[$i])]:'' }}
                        </td>
                        <td>
                          {{ isset($atp_detail['remarks'][strtotime($dates_month[$i])])?$atp_detail['remarks'][strtotime($dates_month[$i])]:'' }}
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


          <div class="row">
            <input type="hidden" id="signature-input" name="signature">
            <div class="col-md-12">
              <hr>
            </div>
  
            <div class="col-12 col-sm-12 col-md-12 my-3" id="sign-container-outer" data-signature="{{ ($monthly_detail->signature)?'true':'false'; }}">
              @if ($monthly_detail->signature)
              <div class="sign-container">
              
                <img src="{{ $monthly_detail->signature }}" class="border" />
              </div>
              @endif
              <span id="signature_error" style="display:none;">
                <div class="alert alert-warning" role="alert">
                  Signed the Form Before Submitting!
                </div>
              </span>
            </div>
            <div class="col-md-12">
              <hr>
            </div>
          </div>




</div>



          
              </div>
            </div>
          </div>
        </div>     
      </div>
      
  </div>

  

@endsection