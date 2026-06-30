@extends('layout')

@section('content')
@php
  $user = Auth::user();
@endphp
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header sty-one">
      <h1>DA Bills</h1>
      <ol class="breadcrumb">
        <li><a href="#">Dashboard</a></li>
        <li class="sub-bread"><i class="fa fa-angle-right"></i> Tour Dairy  Bills</li>
        <li><i class="fa fa-angle-right"></i> Add Tour Dairy  Bill</li>
      </ol>
    </div>


    <div class="content">

        <div class="row">
          <div class="col-lg-12">
            <div class="card card-outline">
              <div class="card-header bg-blue">
                <h5 class="text-white m-b-0">Add Tour Dairy  Bill for {{ date('F', strtotime($monthly_detail['entry_month'])) }}<span class="float-right">Bill ID : <b class="text-white">da{{ $monthly_detail['td_form_id']  }}</b></span></h5>
              </div>
              <div class="card-body">


                      <!-- if there are creation errors, they will show here -->

<form action="{{ route('tour-dairy.update',$monthly_detail->td_form_id) }}" method="POST" aria-readonly="true">
    @csrf
    @method('PUT')
      <input type="hidden" name="bill_id" value="{{ $monthly_detail['td_form_id']  }}" />
      <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">

          @php
          $month = date('m' , strtotime($monthly_detail['entry_month']));
          $year = date('y', strtotime($monthly_detail['entry_month']));
          $num = cal_days_in_month(CAL_GREGORIAN, $month, $year);
          $dates_month = array();
          @endphp
          <div class="table-responsive">
            <table class="table table-striped table-bordered"  >
              <thead class="thead-light">
                <tr>
                  <th scope="col">Date</th>
                  <th scope="col">Place</th>
                  <th scope="col">Night Stay</th>
                </tr>
              </thead>
              <tbody>

              @php

              ($monthly_detail['submit_status'])?'1':'0';
              if($monthly_detail['submit_status'] && $user->hasRole('pec-user'))
              {
                $bill_disable = 'disabled';
              }
              else
              {
                $bill_disable = '';
              }

              $td_detail =  json_decode($monthly_detail['td_detail'], true);

              $total_da = 0;
              for ($i = 1; $i <= $num; $i++)
                {

                $mktime = mktime(0, 0, 0, $month, $i, $year);
                $date = date("d-M-Y", $mktime);
                $dates_month[$i] = $date;
                if(isset($td_detail['amount'][strtotime($dates_month[$i])]))
                {
                  $total_da += ($td_detail['amount'][strtotime($dates_month[$i])])?$td_detail['amount'][strtotime($dates_month[$i])]:'';
                }
                // {{ $td_detail->description[strtotime($dates_month[$i])] }}
                @endphp
                  <tr>
                    <th scope="row" width="150" class="py-1">{{ $dates_month[$i] }} </th>
                    <td class="py-1">
                      <textarea class="form-control" name="detail[description][{{ strtotime($dates_month[$i]) }}]" row="3" {{ $bill_disable }}>{{ (isset($td_detail['description'][strtotime($dates_month[$i])]))?$td_detail['description'][strtotime($dates_month[$i])]:'' }}</textarea>
                    </td>
                    <td width="150" class="py-1"><input type="text" class="form-control" name="detail[ns][{{ strtotime($dates_month[$i]) }}]"
                      value="{{ (isset($td_detail['ns'][strtotime($dates_month[$i])]))?$td_detail['ns'][strtotime($dates_month[$i])]:'' }}"
                      {{ $bill_disable }} /></td>

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
        <div class="col-xs-6 col-sm-6 col-md-6 my-3">
            <div id="signature-pad">
              <canvas></canvas><br><br>
              <button type="click" class="btn btn-success" id="clear-button">Clear</button>
            </div>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6 my-3" id="sign-container-outer" data-signature="{{ ($monthly_detail->signature)?'true':'false'; }}">
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
          <button type="submit" class="btn btn-primary">Update Tour Dairy  Bill Information</button>
          @if($user->hasRole('pec-user'))
            <button type="button" class="btn btn-danger float-right"  id="submit-frm-verify" >Submit Tour Dairy  Bill</button>
          @endif
          @if($user->hasRole('pec-data-verifier'))
            @if($monthly_detail['submit_status'] && $monthly_detail['verify_status'])
            <button type="click" class="btn btn-danger float-right mx-1"  data-toggle="modal" data-target="#popup_bill">Unsubmit  Tour Dairy  Bill for Lots</button>
            @endif
            @if(!$monthly_detail['verify_status'])
            <button type="button" class="btn btn-success float-right" data-toggle="modal" data-target="#popup_verify_bill">Verify Tour Dairy  Bill</button>
            @endif
          @endif
          @if($user->hasRole('pec-admin'))

            <button type="button" class="btn btn-success float-right" data-toggle="modal" data-target="#popup_approve_bill">Approve Tour Dairy  Bill</button>

            <button type="button" class="btn btn-danger float-right mr-2" data-toggle="modal" data-target="#popup_usapprove_bill">Un Approve Tour Dairy  Bill</button>

          @endif
          {{-- @if($user->hasRole('pec-user'))
                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#popup_bill">Submit Tour Dairy  Bill</button>
          @endif       --}}





        </div>
    </div>

</form>

 <!-- Modal -->
 <div class="modal fade" id="popup_bill" tabindex="-1" role="dialog" aria-labelledby="popup_bill" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="popup_bill">Tour Dairy Warning</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Mr. {{ $user->name }}, Please carefully fill & check Tour Dairy  entries. After submitting You will be not to add or edit any entry for {{ date('F') }}.
      </div>
      <div class="modal-footer">
        <form action="{{ route('tour-dairy.store') }}" method="POST" aria-readonly="true">
          @csrf
          <input type="hidden" name="td_form_id" value="{{ $monthly_detail['td_form_id'] }}" />
          <input type="hidden" name="submit_da_bill" value="1" />
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Submit Tour Dairy  Bill</button>
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
        <h5 class="modal-title" id="popup_verify_bill">Tour Dairy Warning</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Mr. {{ $user->name }}, Please check Tour Dairy  bill entries for {{ date('F') }}.
      </div>
      <div class="modal-footer">
        <form action="{{ route('tour-dairy.store') }}" method="POST" aria-readonly="true">
          @csrf
          <input type="hidden" name="td_form_id" value="{{ $monthly_detail['td_form_id'] }}" />
          <input type="hidden" name="verify_da_bill" value="1" />
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Submit Tour Dairy  Bill</button>
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
        Mr. {{ $user->name }}, Are you sure you want to send Tour Dairy  bill to lots for {{ date('F') }}.
      </div>
      <div class="modal-footer">
        <form action="{{ route('tour-dairy.store') }}" method="POST" aria-readonly="true">
          @csrf
          <input type="hidden" name="td_form_id" value="{{ $monthly_detail['td_form_id'] }}" />
          <input type="hidden" name="unsubmit_da_bill" value="1" />
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Submit Tour Dairy  Bill</button>
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
        Mr. {{ $user->name }}, Are you sure you want to Un Approve Tour Dairy  bill for {{ date('F') }}.
      </div>
      <div class="modal-footer">
        <form action="{{ route('tour-dairy.store') }}" method="POST" aria-readonly="true">
          @csrf
          <input type="hidden" name="td_form_id" value="{{ $monthly_detail['td_form_id'] }}" />
          <input type="hidden" name="approve_da_bill" value="1" />
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Approve Tour Dairy  Bill</button>
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
        Mr. {{ $user->name }}, Are you sure you want to Approve Tour Dairy  bill for {{ date('F') }}.
      </div>
      <div class="modal-footer">
        <form action="{{ route('tour-dairy.store') }}" method="POST" aria-readonly="true">
          @csrf
          <input type="hidden" name="td_form_id" value="{{ $monthly_detail['td_form_id'] }}" />
          <input type="hidden" name="unapprove_da_bill" value="1" />
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Approve Tour Dairy  Bill</button>
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

@endsection
