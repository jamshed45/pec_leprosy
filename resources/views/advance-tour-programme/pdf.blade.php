<style type="">

  @page { margin: 0px; }
  body { margin: 0px 50px 0px 50px; }

  table.dashboard_entries_table { transform: rotate(90deg);
    transform-origin: left bottom;
    position: absolute;
    left: 50px; /* Adjust positioning as needed */
    bottom: 1500px; /* Adjust positioning as needed */ background: #f7fbff; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif; border-spacing: 0; font-size: 8px; }
  table.dashboard_entries_table th { border: #c0d3e3 1px solid !important; }
  table.dashboard_entries_table td { border: #c0d3e3 1px solid !important; }
  table.dashboard_entries_table td, table.dashboard_entries_table th {
      padding: 0.15rem;  }
  
  .dash-heading {  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;  margin: 10px;  }    
  
  .info-box { display: block; background: #f2d7d7; width: 98%; 
      padding: 1%; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif; font-size: 10px; line-height: 20px;  }
  .info-box h5  {  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif; font-size: 15px; margin: 0;  }
  .info-box h5 span{ font-size: 15px; font-weight: 400;  }
  .info-box .col-lg-4 { margin-right: 10px; display: inline-block; font-size: 12px; }
  .info-box .info-box-half { width: 49%; display: inline-block}
  .info-box .info-right-half { text-align: right; }

  .lep-info { width: 98%; margin-bottom: 10px;
      padding: 1%; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif; font-size: 20px; padding-bottom:10px;  }
  .lep-info img { display: inline; }
  .lep-info span { display: inline; margin-left: 20px; padding-top: 30px; vertical-align: top } 


  table.locations {  } 

table.locations { background: #a7ccff; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif; border-spacing: 0; font-size: 12px; width: 100%; margin-bottom:30px; }
table.locations th { border: #ededed 1px solid !important; background: #00bcd614; }
table.locations td { border: #ededed 1px solid !important; }
table.locations td, table.locations th {
    padding: 0.25rem;  }



  table.pdf-tabel-styling { transform: rotate(90deg);
    transform-origin: left bottom;
    position: absolute;
    left: 70px; /* Adjust positioning as needed */
    bottom: 1620px; /* Adjust positioning as needed */ 
    /* bottom: 1550px; /* Adjust positioning as needed */  */
    
    background: #ffffff; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif; border-spacing: 0; border-spacing: 0; font-size: 8px; }
  table.pdf-tabel-styling th { border: #ededed 1px solid !important; background: #00bcd614; }
  table.pdf-tabel-styling td { border: #ededed 1px solid !important; }
  table.pdf-tabel-styling td, table.pdf-tabel-styling th {
      padding: 0.25rem;  }
  
  .dash-heading {  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;  margin: 10px;  }    
  
  .info-box { display: block; background: #f2d7d7; width: 98%; margin-bottom: 5px;
      padding: 1%; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif; font-size: 10px; padding-bottom:10px; }
  .info-box h5  {  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif; font-size: 15px; margin: 0;  }
  .info-box h5 span{ font-size: 15px; font-weight: 400;  }
  .info-box .col-lg-4 { margin-right: 10px; display: inline-block; font-size: 12px; }
  
  
  .sign-container {
  float: left;
  background-color: azure;
  border-radius: 15px;
  border: 3px solid #def2f2;
  margin-top: 830px;}


  </style>

<table class="lep-info">
  <tr>
    <td>
      <img src="{{ url('img') }}/logo.jpg" width="50" height="50" />
    </td>
    <td>
      <span>Rawalpindi Leprosy Hospital</span>
    </td>
  </tr>
</table>

<div class="info-box">
  <div class="info-box-half">
    <h5 >Lots' Name : <span>{{ $user->name }}</span></h5>
  </div>
  <div class="info-box-half info-right-half">
    <h5 >Month : <span>{{ date('F-Y', strtotime($monthly_detail['entry_month'])) }}</span></h5>
  </div>
</div>

<table class="locations">
  <tr>
    <td><b>Provice : </b>{{ $area_info->province_name }}</td>
    <td><b>Division : </b>{{ $area_info->division_name }}</td>
    <td><b>District : </b>{{ $area_info->district_name }}</td>
  </tr>
</table> 
    @php
      $month = date('m', strtotime($monthly_detail['entry_month']));
      $year = date('y', strtotime($monthly_detail['entry_month']));
      $num = cal_days_in_month(CAL_GREGORIAN, $month, $year);
      $dates_month = array();
    @endphp

      <table class="pdf-tabel-styling" >
        <thead class="thead-light">
          <tr>
            <th scope="col"><div style="width: 100px">Date</div></th>
            <th scope="col"><div>Place</div></th>
            <th scope="col"><div>Mode of Travelling</div></th>
            <th scope="col"><div>Leprosy Medicines to Reg. NO.</div></th>
            <th scope="col"><div>Under Surv. Reg No.</div></th>
            <th scope="col"><div>Contacts of Reg No.</div></th>
            <th scope="col"><div>Smear of Reg No.</div></th>
            <th scope="col"><div>P.O.D of Reg No.</div></th>
            <th scope="col"><div>Defaulter Reg No.</div></th>
            <th scope="col"><div>Notification Name</div></th>
            <th scope="col"><div>Social Work Reg No.</div></th>
            <th scope="col"><div>PEC Work</div></th>
            <th scope="col"><div>School Screening<br>
              (Name of School)</div>
            </th>
            <th scope="col"><div>Survey<br>
              (Name of School)</div>
            </th>
            <th scope="col"><div>Any other  Work</div></th>
            <th scope="col"><div>S.S - Field Clinic CAT F/U - ETC</div></th>
            <th scope="col"><div>Night Stay at</div></th>
            <th scope="col"><div>Village</div></th>
            <th scope="col"><div>Union Council</div></th>
            <th scope="col"><div>Remarks</div></th>
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
                    {{ 'Yes' }}
                @endif                        
              </td>
              <td>
                @if(isset($atp_detail['pec_work'][strtotime($dates_month[$i])]))
                  {{ 'Yes' }}
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

      <div class="sign-container">
        <img src="{{ $monthly_detail->signature }}" class="border" width="200" />
      </div>
