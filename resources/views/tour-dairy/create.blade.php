@extends('layout')

@section('content')
@php
  $user = Auth::user();
@endphp

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header sty-one">
      <h1>Tour Dairy</h1>
      <ol class="breadcrumb">
        <li><a href="#">Dashboard</a></li>
        <li class="sub-bread"><i class="fa fa-angle-right"></i> Tour Dairies</li>
        <li><i class="fa fa-angle-right"></i> Add Tour Dairy</li>
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
                <h5 class="text-white m-b-0">Add Tour Dairy for {{ date('F') }}<span class="float-right">Tour Dairy ID : <b class="text-white">td{{ $monthly_detail['td_form_id']  }}</b></span></h5>
              </div>
              <div class="card-body">


                      <!-- if there are creation errors, they will show here -->
<form action="{{ route('tour-dairy.store') }}" method="POST" aria-readonly="true">
    @csrf

     <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">

          @php
          $month = date('m');
          $year = date('y');
          $num = cal_days_in_month(CAL_GREGORIAN, $month, $year);
          $dates_month = array();
          @endphp
          <div class="table-responsive">
            <table class="table table-striped table-bordered"  >
              <thead class="thead-light">
                <tr>
                  <th scope="col">Date</th>
                  <th scope="col">Description</th>
                  <th scope="col">Night Stay</th>
                </tr>
              </thead>
              <tbody>

              @php

              ($monthly_detail['submit_status'])?'1':'0';

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
                    <th scope="row" width="150" class="py-1"><small>{{ $dates_month[$i] }}</small></th>
                    <td class="py-1">
                      <textarea class="form-control" name="detail[description][{{ strtotime($dates_month[$i]) }}]" row="3" {{ ($monthly_detail['submit_status'])?'disabled':'0' }}>{{ (isset($td_detail['description'][strtotime($dates_month[$i])]))?$td_detail['description'][strtotime($dates_month[$i])]:'' }}</textarea>
                    </td>
                    <td width="150" class="py-1"><input type="text" class="form-control" name="detail[ns][{{ strtotime($dates_month[$i]) }}]"
                      value="{{ (isset($td_detail['ns'][strtotime($dates_month[$i])]))?$td_detail['ns'][strtotime($dates_month[$i])]:'' }}"
                      {{ ($monthly_detail['submit_status'])?'disabled':'0' }} /></td>

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
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                @if(!$monthly_detail['submit_status'])
                    <button type="submit" class="btn btn-primary">Update Tour Dairy Information</button>
                @endif
                {{-- <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#popup_bill">Submit Tour Dairy</button> --}}




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
                  Mr. {{ $user->name }}, Please carefully fill & check DA entries. After submitting You will be not to add or edit any entry for {{ date('F') }}.
                </div>
                <div class="modal-footer">
                  <form action="{{ route('daily-allowance.store') }}" method="POST" aria-readonly="true">
                    @csrf
                    <input type="hidden" name="td_form_id" value="{{ $monthly_detail['td_form_id'] }}" />
                    <input type="hidden" name="submit_da_bill" value="1" />
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">Submit Tour Dairy</button>
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



@endsection
