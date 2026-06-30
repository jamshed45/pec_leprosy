@extends('layout')

@section('content')
@php
  $user = Auth::user();
@endphp
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper"> 
    <!-- Content Header (Page header) -->
    <div class="content-header sty-one">
      <h1>TA Bills</h1>
      <ol class="breadcrumb">
        <li><a href="#">Dashboard 1</a></li>
        <li class="sub-bread"><i class="fa fa-angle-right"></i> TA Bills</li>
        <li><i class="fa fa-angle-right"></i> Add TA Bill</li>
      </ol>
    </div>
    
  @php    
    $timestamp    = strtotime($monthly_detail['entry_month']);
    $start_date = date('Y-m-01', $timestamp);
    $end_date  = date('Y-m-t', $timestamp); // A leap year!
  @endphp

    <div class="content">

          <!-- will be used to show any messages -->
    @if (Session::has('success'))
    <div class="alert alert-info">{{ Session::get('success') }}</div>
  @endif

        <div class="row">
          <div class="col-lg-12">
            <div class="card card-outline">
              <div class="card-header bg-blue">
                <h5 class="text-white m-b-0">Add TA Bill for {{ date('F', strtotime($monthly_detail['entry_month'])) }}<span class="float-right">Bill ID : <b class="text-white">ta{{ $monthly_detail['ta_form_id']  }}</b></span></h5>
              </div>
              <div class="card-body">
      

                      <!-- if there are creation errors, they will show here -->
       
<form action="{{ route('travelling-allowance.update',$monthly_detail->ta_form_id) }}" method="POST" aria-readonly="true" class="taform" data-mindate="{{ $start_date }}" data-maxdate="{{ $end_date }}">
    @csrf
    @method('PUT')
      <input type="hidden" name="bill_id" value="{{ $monthly_detail['ta_form_id']  }}" />
      <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">

          <div class="table-responsive">
            <table class="table table-striped table-bordered"  >
              <thead class="thead-light">
                <tr>
                  <th scope="col">Date</th>
                  <th scope="col">Place</th>
                  <th scope="col">N/S</th>
                  <th scope="col">Amount</th>
                </tr>
              </thead>
              <tbody id="ta_rows">

              @php
              
              $total_ta = 0;
               
              ($monthly_detail['submit_status'])?'1':'0';
              if($monthly_detail['ta_detail'])
              {
                $ta_detail =  json_decode($monthly_detail['ta_detail'], true);
          
            
                @endphp
                @if($ta_detail)
                @foreach ($ta_detail['date'] as  $key => $value )
                @php
                $total_ta += $ta_detail['amount'][$key];
                @endphp
                  <tr>
                    <td><input class="form-control" type="date" name="detail[date][]" value="{{ $value }}" min="{{ $start_date }}" max="{{ $end_date }}" required /></td>
                    <td>
                      <textarea class="form-control" name="detail[description][]" row="3" required >{{ $ta_detail['description'][$key] }}</textarea>
                    </td>
                    <td>
                      <input type="number" class="form-control" name="detail[amount][]" value="{{ $ta_detail['amount'][$key] }}" required />
                    </td>
                    <td>
                      <button class="btn btn-danger" id="DeleteRow" type="button"><span class="bi bi-plus-square-dotted"></span> Remove</button
                    </td>
                  </tr>
                @endforeach
                @endif
              @php
              } 
              @endphp 
                

                <tr>
                  <th colspan=2></th>
                  <th class="bg-dark text-white">Total</th>
                  <th class="bg-success text-white">Rs = 
                    {{ $total_ta }}
                  </th>
                </tr>
              </tbody>
            </table>

            <button id="rowAdder" type="button" class="btn btn-dark">
              <span class="bi bi-plus-square-dotted"></span> ADD
            </button>

          </div>
            <br>
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
          <button type="submit" class="btn btn-primary">Update TA Bill Information</button>
          @if($user->hasRole('pec-user'))
            <button type="button" class="btn btn-danger float-right" data-toggle="modal" id="submit-frm-verify" >Submit TA Bill</button>
          @endif
          @if($user->hasRole('pec-data-verifier'))
            @if($monthly_detail['send_status'] && $monthly_detail['verify_status'])
            <button type="button" class="btn btn-danger float-right mx-1" data-toggle="modal" data-target="#popup_unsubmit_bill">Unsubmit  TA Bill for Lots</button> 
            @endif
            @if(!$monthly_detail['verify_status'])
            <button type="button" class="btn btn-success float-right" data-toggle="modal" data-target="#popup_verify_bill">Verify TA Bill</button>
            @endif
          @endif
          @if($user->hasRole('pec-admin'))

            <button type="button" class="btn btn-success float-right" data-toggle="modal" data-target="#popup_approve_bill">Approve TA Bill</button>

            <button type="button" class="btn btn-danger float-right mr-2" data-toggle="modal" data-target="#popup_usapprove_bill">Un Approve TA Bill</button>

          @endif
          {{-- @if($user->hasRole('pec-user'))      
                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#popup_bill">Submit TA Bill</button>
          @endif       --}}
          


         

        </div>
    </div>

</form>

 <!-- Modal -->
 <div class="modal fade" id="popup_bill" tabindex="-1" role="dialog" aria-labelledby="popup_bill" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="popup_bill">TA Bill Warning</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Mr. {{ $user->name }}, Please carefully fill & check TA entries. After submitting You will be not to add or edit any entry for {{ date('F') }}.
      </div>
      <div class="modal-footer">
        <form action="{{ route('travelling-allowance.store') }}" method="POST" aria-readonly="true">
          @csrf
          <input type="hidden" name="ta_form_id" value="{{ $monthly_detail['ta_form_id'] }}" />
          <input type="hidden" name="submit_ta_bill" value="1" />
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Submit TA Bill</button>
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
        <h5 class="modal-title" id="popup_verify_bill">TA Bill Warning</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Mr. {{ $user->name }}, Please check TA bill entries for {{ date('F') }}.
      </div>
      <div class="modal-footer">
        <form action="{{ route('travelling-allowance.store') }}" method="POST" aria-readonly="true">
          @csrf
          <input type="hidden" name="ta_form_id" value="{{ $monthly_detail['ta_form_id'] }}" />
          <input type="hidden" name="verify_ta_bill" value="1" />
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Submit TA Bill</button>
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
        Mr. {{ $user->name }}, Are you sure you want to send TA bill to lots for {{ date('F') }}.
      </div>
      <div class="modal-footer">
        <form action="{{ route('travelling-allowance.store') }}" method="POST" aria-readonly="true">
          @csrf
          <input type="hidden" name="ta_form_id" value="{{ $monthly_detail['ta_form_id'] }}" />
          <input type="hidden" name="unsubmit_ta_bill" value="1" />
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Submit TA Bill</button>
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
        Mr. {{ $user->name }}, Are you sure you want to Un Approve TA bill for {{ date('F') }}.
      </div>
      <div class="modal-footer">
        <form action="{{ route('travelling-allowance.store') }}" method="POST" aria-readonly="true">
          @csrf
          <input type="hidden" name="ta_form_id" value="{{ $monthly_detail['ta_form_id'] }}" />
          <input type="hidden" name="approve_ta_bill" value="1" />
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Approve TA Bill</button>
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
        Mr. {{ $user->name }}, Are you sure you want to Approve TA bill for {{ date('F') }}.
      </div>
      <div class="modal-footer">
        <form action="{{ route('travelling-allowance.store') }}" method="POST" aria-readonly="true">
          @csrf
          <input type="hidden" name="ta_form_id" value="{{ $monthly_detail['ta_form_id'] }}" />
          <input type="hidden" name="unapprove_ta_bill" value="1" />
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Approve TA Bill</button>
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


  <script type="text/javascript">
 
    $("#rowAdder").click(function () {
      var unique_id = Math.random().toString(16).slice(2);
      
        min_date = $('.taform').attr("data-mindate");
        max_date = $('.taform').attr("data-maxdate");

        newRowAdd =
        '<tr><td><input class="form-control" type="date" name="detail[date]['+unique_id+']" min="'+ min_date +'" max="'+ max_date +'" required /></td><td><textarea class="form-control" name="detail[description]['+unique_id+']" row="3" required></textarea></td><td><input type="number" class="form-control" name="detail[amount]['+unique_id+']" value="" required /></td><td><button class="btn btn-danger" id="DeleteRow" type="button"><span class="bi bi-plus-square-dotted"></span> Remove</button></td></tr>';

        $('#ta_rows').append(newRowAdd);
    });

    $("body").on("click", "#DeleteRow", function () {
        $(this).parents("tr").remove();
    })
</script>

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