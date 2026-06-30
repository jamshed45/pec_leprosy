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
    
    
    <div class="content">
        <div class="row">
          <div class="col-lg-12">
            <div class="card card-outline">
              <div class="card-header bg-blue">
                <h5 class="text-white m-b-0">Add TA Bill for {{ date('F', strtotime($monthly_detail['entry_month'])) }}<span class="float-right">Bill ID : <b class="text-white">da{{ $monthly_detail['ta_form_id']  }}</b></span></h5>
              </div>
              <div class="card-body">
      

                      <!-- if there are creation errors, they will show here -->
      <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">

          <div class="table-responsive">
            <table class="table table-striped table-bordered"  >
              <thead class="thead-light">
                <tr>
                  <th scope="col">Date</th>
                  <th scope="col">Description</th>
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
              
              $ta_detail =  json_decode($monthly_detail['ta_detail'], true);


                $total_TA = 0;
                @endphp  
                @foreach ($ta_detail['date'] as $key => $val)
                  <tr>
                    <td>{{ $val }}</td>
                    <td>{{ $ta_detail['description'][$key] }}</td>
                    <td>{{ $ta_detail['amount'][$key] }}
                    @php
                        $total_TA += $ta_detail['amount'][$key];
                    @endphp
                    </td>
                  </tr>
                @endforeach
                
            
                <tr>
                  <th colspan=1></th>
                  <th class="bg-dark text-white">Total</th>
                  <th class="bg-success text-white">Rs {{ $total_TA }}/-</th>
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