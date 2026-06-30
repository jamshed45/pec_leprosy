@extends('layout')

@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper"> 
    <!-- Content Header (Page header) -->
    <div class="content-header sty-one">
      <h1></h1>
      <ol class="breadcrumb">
        <li><a href="#">Dashboard</a></li>
        <li class="sub-bread"><i class="fa fa-angle-right"></i> Province</li>
        <li><i class="fa fa-angle-right"></i> Patients</li>
      </ol>
    </div>
    
    
    <div class="content">
        <div class="row">
          <div class="col-lg-12">
            <div class="card card-outline">
              <div class="card-header bg-blue">
                <h5 class="text-white m-b-0">Add New Province</h5>
              </div>
              <div class="card-body">
      

                      <!-- if there are creation errors, they will show here -->
                      <form action="{{ route('doctors.update',$record->doctor_id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="doctor_id" value="{{$record->doctor_id}}" />
                         <div class="row">
                            <div class="col-xs-6 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label>Name:</label>
                                    <input type="text" name="doctor_name" class="form-control" value="{{$record->doctor_name}}">
                                </div>
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6">
                              <div class="form-group">
                                <label for="first_name">Select Health Faciity :</label>
                         
                               <select class="form-control" name="health_facility_id" id="health_facility_id"  required>
                                <option value="">Select category</option>
                                @foreach ($heathFacility as $item)
                                <option value="{{ $item->health_facility_id }}" {{ ($item->health_facility_id == $record->health_facility_id) ? 'selected' : ''}}>{{ $item->health_facility_name }}</option>
                                @endforeach
                             </select>
                              </div>
                          </div>
                    
                          </div>
                    
                          <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                    <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    
                    </form>
              </div>
            </div>
          </div>
        </div>     
      </div>
  </div>


@endsection