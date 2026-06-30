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
        <li><a href="#">Dashboard</a></li>
        <li class="sub-bread"><i class="fa fa-angle-right"></i> TA Bills</li>
        <li><i class="fa fa-angle-right"></i> Add TA Bill</li>
      </ol>
    </div>
    
    @php
      
      $timestamp    = strtotime('February 2012');
      echo $first_second = date('m-01-Y 00:00:00', $timestamp);
      echo $last_second  = date('m-t-Y 12:59:59', $timestamp); // A leap year!

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
                <h5 class="text-white m-b-0">Add TA Bill for {{ date('F') }}<span class="float-right">Bill ID : <b class="text-white">ta{{ $monthly_detail['ta_form_id']  }}</b></span></h5>
              </div>
              <div class="card-body">
      

                      <!-- if there are creation errors, they will show here -->
<form action="{{ route('travelling-allowance.store') }}" method="POST" aria-readonly="true">
    @csrf

     <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">

          <div class="table-responsive">
            <table class="table table-striped table-bordered"  >
              <thead class="thead-light">
                <tr>
                  <th scope="col">Date</th>
                  <th scope="col">Description</th>
                  <th scope="col">Amount</th>
                  <th scope="col"></th>
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
                  @if(!$ta_detail)
                  
                  @else
                  
                  @foreach ($ta_detail['date'] as  $key => $value )
                    @php
                    $total_ta += $ta_detail['amount'][$key];
                    @endphp
                    <tr>
                      <td><input class="form-control" type="date" name="detail[date][]" value="{{ $value }}" required /></td>
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
                } else
                { @endphp
                  <tr>
                      <td><input class="form-control" type="date" name="detail[date][]"  required /></td>
                      <td>
                        <textarea class="form-control" name="detail[description][]" row="3" required ></textarea>
                      </td>
                      <td>
                        <input type="number" class="form-control" name="detail[amount][]"  required />
                      </td>
                      <td>
                        <button class="btn btn-danger" id="DeleteRow" type="button"><span class="bi bi-plus-square-dotted"></span> Remove</button
                      </td>
                    </tr>
                @php }
                @endphp  
                <tr>
                  <th colspan=2></th>
                  <th class="bg-dark text-white">Total</th>
                  <th class="bg-success text-white">Rs = {{ $total_ta }}</th>
                </tr>
              </tbody>
          </table>
          <button id="rowAdder" type="button" class="btn btn-dark">
            <span class="bi bi-plus-square-dotted"></span> ADD
          </button>

           
          </div>
            


        </div>
       
      </div>

      <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Update TA Bill Information</button> 
                {{-- <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#popup_bill">Submit TA Bill</button> --}}




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
                  <form action="{{ route('daily-allowance.store') }}" method="POST" aria-readonly="true">
                    @csrf
                    <input type="hidden" name="da_form_id" value="{{ $monthly_detail['ta_form_id'] }}" />
                    <input type="hidden" name="submit_da_bill" value="1" />
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">Submit TA Bill</button>
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
      console.log(unique_id);
        newRowAdd =
        '<tr><td><input class="form-control" type="date" name="detail[date]['+unique_id+']" required /></td><td><textarea class="form-control" name="detail[description]['+unique_id+']" row="3" required></textarea></td><td><input type="number" class="form-control" name="detail[amount]['+unique_id+']" value="" required /></td><td><button class="btn btn-danger" id="DeleteRow" type="button"><span class="bi bi-plus-square-dotted"></span> Remove</button></td></tr>';

        $('#ta_rows').append(newRowAdd);
    });

    $("body").on("click", "#DeleteRow", function () {
        $(this).parents("tr").remove();
    })
</script>

@endsection