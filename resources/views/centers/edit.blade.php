@extends('layout')

@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper"> 
    <!-- Content Header (Page header) -->
    <div class="content-header sty-one">
      <h1>Centers</h1>
      <ol class="breadcrumb">
        <li><a href="#">Dashboard</a></li>
        <li class="sub-bread"><i class="fa fa-angle-right"></i> Centers</li>
        <li><i class="fa fa-angle-right"></i> Center Center</li>
      </ol>
    </div>
    
    
    <div class="content">
        <div class="row">
          <div class="col-lg-12">
            <div class="card card-outline">
              <div class="card-header bg-blue">
                <h5 class="text-white m-b-0">Edit Center</h5>
              </div>
              <div class="card-body">
      

                      <!-- if there are creation errors, they will show here -->
{{-- <form action="{{ route('centers.update') }}" method="POST"> --}}
<form action="{{ route('centers.update',$record->center_id) }}" method="POST">
    
    @csrf
    @method('PUT')
     <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6">
            <div class="form-group">
                <label for="center_name">Edit Center Name:</label>
                <input type="text" name="center_name" class="form-control" value="{{ $record->center_name  }}" required>
            </div>
        </div>
      
      <div class="col-xs-6 col-sm-6 col-md-6">
        <div class="form-group">
          <label for="first_name">Select Tehsil :</label>
          <select class="form-control" name="tehsil_id" id="tehsil_id">
            <option value="">Select Teshil</option>
            @foreach ($tehsils as $item)
            <option value="{{ $item->tehsil_id }}" {{ ($item->tehsil_id == $record->tehsil_id) ? 'selected' : ''}}>{{ $item->tehsil_name }}</option>
            @endforeach
         </select>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
      <div class="form-group">
        <label for="first_name">Select Activity Location :</label>
        <select class="form-control" name="activity_location_id" id="activity_location_id" required>
          <option value="">Select Activity Location</option>
          @foreach ($activity_location as $item)
          <option value="{{ $item->activity_location_id }}" {{ ($item->activity_location_id == $record->activity_location_id) ? 'selected' : ''}}>{{ $item->activity_name }}</option>
          @endforeach
       </select>
      </div>
   </div>
       
      </div>
   
      <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
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