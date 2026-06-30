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
                <h5 class="text-white m-b-0">Advance Tour Programme 1</b></span></h5>
              </div>

              <div class="card-body">
      
        <!-- if there are creation errors, they will show here -->
                      
        <form action="{{ route('advance-tour-programme.update', $monthly_detail->atp_form_id) }}" method="POST" autocomplete="off">
          @csrf
          @method('PUT')
          <input type="hidden" name="atp_form_id" value="{{ $monthly_detail['atp_form_id']  }}" />
          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 stick-head-table-container">
    
              @php
              $month = date('m', strtotime($monthly_detail['entry_month']));
              $year = date('y', strtotime($monthly_detail['entry_month']));
              $num = cal_days_in_month(CAL_GREGORIAN, $month, $year);
              $dates_month = array();
              @endphp
              <div class="table-responsive stick-head-table-container">
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
                  
                  $total_atp = 0;
                  // for ($i = 1; $i <= 1; $i++)
                  for ($i = 1; $i <= $num; $i++)
                    {
                      
                    $mktime = mktime(0, 0, 0, $month, $i, $year);
                    $date = date("d-M-Y", $mktime);
                    $dates_month[$i] = $date;
                
                    // {{ $atp_detail->description[strtotime($dates_month[$i])] }}
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


          <div class="row">
            <input type="hidden" id="signature-input" name="signature">
            <div class="col-md-12">
              <hr>
            </div>
            <div class="col-12 col-sm-12 col-md-12 my-3">
                <div id="signature-pad">
                  <canvas></canvas><br><br>
                  <button type="click" class="btn btn-success" id="clear-button">Clear</button>
                </div>
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


          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
              <br>
              <input type="hidden" name="update_atp_from" value="{{ $monthly_detail['atp_form_id'] }}"/>
              <button type="submit" class="btn btn-primary">Update ATP Form</button>
              @if($user->hasRole('pec-user'))
                <button type="button" class="btn btn-danger float-right" data-toggle="modal" id="submit-frm-verify">Submit ATP Form</button>
              @endif
              @if($user->hasRole('pec-data-verifier'))
                @if($monthly_detail['submit_status'] && $monthly_detail['verify_status'] && $monthly_detail['approve_status'])
                <button type="button" class="btn btn-danger float-right mx-1" data-toggle="modal" data-target="#popup_unsubmit_bill">Unsubmit  ATP Form for Lots</button> 
                @endif
                @if(!$monthly_detail['verify_status'])
                <button type="button" class="btn btn-success float-right" data-toggle="modal" data-target="#popup_verify_bill">Verify ATP Form</button>
                @endif
              @endif
              @if($user->hasRole('pec-admin'))
           
                <button type="button" class="btn btn-danger float-right" data-toggle="modal" data-target="#popup_approve_bill">Approve ATP Form</button>
        
                <button type="button" class="btn btn-danger float-right" data-toggle="modal" data-target="#popup_usapprove_bill">Un Approve ATP Form</button>
             
              @endif
              {{-- @if($user->hasRole('pec-user'))      
                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#popup_bill">Submit ATP Form</button>
              @endif       --}}
              
    
    
             
    
            </div>
        </div>

        </form>

      </div>


         <!-- Modal -->
 <div class="modal fade" id="popup_bill" tabindex="-1" role="dialog" aria-labelledby="popup_bill" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="popup_bill">ATP Form Warning</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Mr. {{ $user->name }}, Please carefully fill & check ATA Form. After submitting You will be not to add or edit any for {{ date('F') }}.
      </div>
      <div class="modal-footer">
        <form action="{{ route('advance-tour-programme.store') }}" method="POST" aria-readonly="true">
          @csrf
          <input type="hidden" name="atp_form_id" value="{{ $monthly_detail['atp_form_id'] }}" />
          <input type="hidden" name="submit_atp_bill" value="1" />
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Submit ATP Form</button>
      </form>
      </div>
    </div>
  </div>
</div>

 <!-- Modal -->
 <div class="modal fade" id="popup_verify_bill" tabindex="-1" role="dialog" aria-labelledby="popup_verify_bill" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="popup_verify_bill">ATP Form Warning</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Mr. {{ $user->name }}, Please check ATP Form entries for {{ date('F') }}.
      </div>
      <div class="modal-footer">
        <form action="{{ route('advance-tour-programme.store') }}" method="POST" aria-readonly="true">
          @csrf
          <input type="hidden" name="atp_form_id" value="{{ $monthly_detail['atp_form_id'] }}" />
          <input type="hidden" name="verify_atp_bill" value="1" />
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Submit ATP Form</button>
      </form>
      </div>
    </div>
  </div>
</div>

 <!-- Modal -->
 <div class="modal fade" id="popup_unsubmit_bill" tabindex="-1" role="dialog" aria-labelledby="popup_unsubmit_bill" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="popup_unsubmit_bill">Resend Bill to Lots</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Mr. {{ $user->name }}, Are you sure you want to send ATP Form to lots for {{ date('F') }}.
      </div>
      <div class="modal-footer">
        <form action="{{ route('advance-tour-programme.store') }}" method="POST" aria-readonly="true">
          @csrf
          <input type="hidden" name="atp_form_id" value="{{ $monthly_detail['atp_form_id'] }}" />
          <input type="hidden" name="unsubmit_atp_bill" value="1" />
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Submit ATP Form</button>
      </form>
      </div>
    </div>
  </div>
</div>

 <!-- Modal -->
 <div class="modal fade" id="popup_approve_bill" tabindex="-1" role="dialog" aria-labelledby="popup_approve_bill" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="popup_approve_bill">Un Approve Bill</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Mr. {{ $user->name }}, Are you sure you want to Un Approve ATP Form for {{ date('F') }}.
      </div>
      <div class="modal-footer">
        <form action="{{ route('advance-tour-programme.store') }}" method="POST" aria-readonly="true">
          @csrf
          <input type="hidden" name="atp_form_id" value="{{ $monthly_detail['atp_form_id'] }}" />
          <input type="hidden" name="approve_atp_bill" value="1" />
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Approve ATP Form</button>
      </form>
      </div>
    </div>
  </div>
</div>


 <!-- Modal -->
 <div class="modal fade" id="popup_usapprove_bill" tabindex="-1" role="dialog" aria-labelledby="popup_usapprove_bill" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="popup_usapprove_bill1">Approve Bill</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Mr. {{ $user->name }}, Are you sure you want to Approve ATP Form for {{ date('F') }}.
      </div>
      <div class="modal-footer">
        <form action="{{ route('advance-tour-programme.store') }}" method="POST" aria-readonly="true">
          @csrf
          <input type="hidden" name="atp_form_id" value="{{ $monthly_detail['atp_form_id'] }}" />
          <input type="hidden" name="unapprove_atp_bill" value="1" />
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Approve ATP Form</button>
      </form>
      </div>
    </div>
  </div>
</div>

</div>



          
              </div>
            </div>
          </div>
        </div>     
      </div>
      
  </div>


  <script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/1.3.4/signature_pad.min.js" integrity="sha512-Mtr2f9aMp/TVEdDWcRlcREy9NfgsvXvApdxrm3/gK8lAMWnXrFsYaoW01B5eJhrUpBT7hmIjLeaQe0hnL7Oh1w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <script>

    // signature-pad.js
    const canvas = document.querySelector("canvas");
    const signaturePad = new SignaturePad(canvas);
    const clearButton = document.getElementById("clear-button");
    const signatureInput = document.getElementById("signature-input");

    clearButton.addEventListener("click", function (e) {
      e.preventDefault();
        signaturePad.clear();
    });

    document.querySelector("form").addEventListener("submit", function (e) {
        e.preventDefault();
        const signatureData = signaturePad.toDataURL(); // Convert signature to a data URL
        signatureInput.value = signatureData;
        this.submit(); // Submit the form
    });


    $('#submit-frm-verify').click(function () {
        $("#sign-container-outer").attr("data-signature");
        console.log($("#sign-container-outer").attr("data-signature"));
        if($("#sign-container-outer").attr("data-signature") == 'false')
        {
          $("#signature_error").show();
          return;
        }
        else
        {
          $("#signature_error").hide();
        }
        
        // Show the modal when the button is clicked
        $('#popup_bill').modal('show');
    });

  </script>



  <script>

// JavaScript to make the table header responsive on scroll
const tableContainer = document.querySelector('.stick-head-table-container');
const thead = document.querySelector('thead');

tableContainer.addEventListener('scroll', () => {
    if (tableContainer.scrollTop > 0) {
        thead.style.position = 'fixed';
    } else {
        thead.style.position = 'sticky';
    }
});

  </script>
  

@endsection