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
        <li class="sub-bread"><i class="fa fa-angle-right"></i> DA Bills</li>
        <li><i class="fa fa-angle-right"></i> Add DA Bill</li>
      </ol>
    </div>
    
    
    <div class="content">


        <div class="row">
          <div class="col-lg-12">
            <div class="card card-outline">
              <div class="card-header bg-blue">
                <h5 class="text-white m-b-0">Add DA Bill for {{ date('F') }}<span class="float-right">Bill ID : <b class="text-white">da{{ $monthly_detail['da_form_id']  }}</b></span></h5>
              </div>
              <div class="card-body">
      

                      <!-- if there are creation errors, they will show here -->
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
                  <th scope="col">Place</th>
                  <th scope="col">N/S</th>
                  <th scope="col">Amount</th>
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
              
              $da_detail =  json_decode($monthly_detail['da_detail'], true);

              $total_da = 0;
              for ($i = 1; $i <= $num; $i++)
                {
                  
                $mktime = mktime(0, 0, 0, $month, $i, $year);
                $date = date("d-M-Y", $mktime);
                $dates_month[$i] = $date;
                if(isset($da_detail['amount'][strtotime($dates_month[$i])]))
                {
                  $total_da += ($da_detail['amount'][strtotime($dates_month[$i])])?$da_detail['amount'][strtotime($dates_month[$i])]:'';
                }
                // {{ $da_detail->description[strtotime($dates_month[$i])] }}
                @endphp  
                  <tr>
                    <th scope="row" width="150" class="py-1">{{ $dates_month[$i] }} </th>
                    <td class="py-1">
                        {{ (isset($da_detail['description'][strtotime($dates_month[$i])]))?$da_detail['description'][strtotime($dates_month[$i])]:'' }}
                    </td>
                    <td width="150" class="py-1">
                        {{ (isset($da_detail['ns'][strtotime($dates_month[$i])]))?$da_detail['ns'][strtotime($dates_month[$i])]:'' }}
                    </td>
                    <td width="100" class="py-1">
                        {{ (isset($da_detail['amount'][strtotime($dates_month[$i])]))?$da_detail['amount'][strtotime($dates_month[$i])]:'' }}
                    </td>
                  </tr>
                @php
                }
              @endphp
                <tr>
                  <th colspan=2></th>
                  <th class="bg-dark text-white">Total</th>
                  <th class="bg-success text-white">Rs{{ $total_da }}/-</th>
                </tr>
              </tbody>
            </table>
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