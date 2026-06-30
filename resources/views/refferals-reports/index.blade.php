@extends('layout')

@section('content')
<?php 
$user = Auth::user();
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper"> 
    <!-- Content Header (Page header) -->
    <div class="content-header sty-one">
      <h1>Refferal Patients</h1>
      <ol class="breadcrumb">
        <li><a href="#">Dashboard</a></li>
        <li class="sub-bread"><i class="fa fa-angle-right"></i> Refferal Patients</li>
        <li><i class="fa fa-angle-right"></i> Patient's List</li>
      </ol>
    </div>


      @include('counts_stats')     
   

    <div class="content">

        <div class="row">
          <div class="col-lg-12">
            <div class="card card-outline">
            
              <div class="card-body">
                @if($all_refferals)
                <div class="table-responsive">
                  <table class="table dashboard_entries_table">
                    <thead class="thead-dark">
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Refferal</th>
                        <th scope="col">Date</th>
                        <th scope="col">LOTs Name</th>
                        <th scope="col">Patient Info</th>
                        <th scope="col">Contact/CNIC</th>
                        <th scope="col">Refer To</th>
                        <th scope="col">Remarks</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($all_refferals as $refferal)
                      @php
                        //  print_r($refferal);
                      @endphp
                      <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td> {{ ucwords(str_replace("-"," ",$refferal->patient_type)) }}
                            @if($refferal->patient_type == 'cataract-surgery')
                              <br><b>Surgery :</b>
                              @if($refferal->cataract_op_slug == 'cataract-right-eye')
                              RT
                              @elseif($refferal->cataract_op_slug == 'cataract-left-eye')
                              LT
                              @else
                              BT
                              @endif
                              <br><b>Diabetic :</b> 
                              @if($refferal->diabetic == 1 )
                              Yes
                              @else
                              No 
                              @endif
                              <br><b>Value :</b> {{ $refferal->diabetic_value }}
                            @endif
                        </td>
                        <td>@php echo date('j M, Y', strtotime($refferal->created_at)) @endphp</td>
                        <td>{{ (isset($refferal->user_name))?$refferal->user_name:'' }}</td>
                        <td>
                          <b>Name :</b> {{ $refferal->full_name }}<br>
                          <b>Age :</b> {{ $refferal->age }} Y<br>
                          <b>Gender :</b> {{ $refferal->gender }}<br>
                        </td>
          
                        <td>
                          <b>Contact No :</b> {{ $refferal->contact_number }}<br>
                          <b>CNIC :</b> {{ $refferal->cnic_number }}
                        </td>
                        <td>
                          @if($refferal->private != '')
                          <b>Health Facility :</b> {{ $refferal->health_facility_name }}<br>
                          <b>Doctor :</b> {{ $refferal->doc_name }}
                          @else
                          <b>Health Facility :</b>  Private
                          @endif

                        </td>
                        <td>{{ $refferal->remark }}</td>
                      </tr>
                      
                      @endforeach

                    </tbody>
                  </table>
                </div>
                @endif

              </div>
            </div>
          </div>
        </div>     
      </div>


  </div>

@endsection
