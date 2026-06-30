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

    <!-- will be used to show any messages -->
    <div class="col-md-12">
      @if (Session::has('success'))
        <div class="alert alert-info">{{ Session::get('success') }}</div>
      @endif
    </div>

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
                        <td>{{ $refferal->doc_name }}</td>
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

      <!-- Modal -->
    <div class="modal fade" id="deleteModel" tabindex="-1" role="dialog" aria-labelledby="deleteModel"
      aria-hidden="true">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">

              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <form id="delete-form" method="POST">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-danger">Delete</button>
                  </form>
              </div>
          </div>
      </div>
  </div>


  </div>

@endsection
