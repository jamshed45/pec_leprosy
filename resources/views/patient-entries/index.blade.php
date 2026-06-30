@extends('layout')


@section('content')

<!-- will be used to show any messages -->
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper"> 
    <!-- Content Header (Page header) -->
    <div class="content-header sty-one">
      <h1>View All Entries</h1>
      <ol class="breadcrumb">
        <li><a href="#">Dashboard</a></li>
        <li class="sub-bread"><i class="fa fa-angle-right"></i> All Entries</li>
        <li><i class="fa fa-angle-right"></i> Entries</li>
      </ol>
    </div>
    
    
    <div class="content">
        <div class="row">
          <div class="col-lg-12">
            <div class="card card-outline">
              <div class="card-header bg-blue">
                <h5 class="text-white m-b-0">View All Entries</h5>
              </div>
              <div class="card-body">
                @php
                  // echo "<pre>";
                  // print_r($patient_entries);
                  // echo "</pre>";
                  // die();
                  $user = Auth::user();
                @endphp
           

                <div class="table-responsive">
                    <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                        <td>No</td>
                        <td>Date</td>
                        @if (!$user->hasRole('pec-user'))
                            <td>Lots Name</td>
                        @endif
                        <td>province</td>
                        <td>Tehsil</td>
                        <td>Center</td>
                        <td>Actions</td>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($patient_entries as $item )
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ date('jS M, Y',strtotime($item->entry_at)) }}</td>
                        @if (!$user->hasRole('pec-user'))
                          <td>{{ $item->name  }}</td>
                        @endif
                        
                        <td>
                          {{ $item->province_name }}
                        </td>
                        <td>{{ $item->tehsil_name }}</td>
                        <td>{{ $item->center_name }}</td>
       
       
                        <!-- we will also add show, edit, and delete buttons -->
                        <td>
                          <a class="btn btn-info" href="{{ URL::to('patient-entries/update-entries') }}{{ '/'.$item->records_set_unique_id }}">Show & Update</a>
                        </td>
                    </tr>
             
    
                @endforeach
                  </tbody>
    
                </table>
                    </div>

              </div>
            </div>
          </div>
        </div>     
      </div>
  </div>


@endsection
