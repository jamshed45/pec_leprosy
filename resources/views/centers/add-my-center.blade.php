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
        <li><i class="fa fa-angle-right"></i> Add My Center</li>
      </ol>
    </div>
    
    @include('user_center_section')
    
    <div class="content">
        <div class="row">
          <div class="col-lg-12">
            <div class="card card-outline">
              <div class="card-header bg-blue">
                <h5 class="text-white m-b-0">Add My Center</h5>
              </div>
              <div class="card-body">
      

                      <!-- if there are creation errors, they will show here -->
<form action="{{ route('centers.store') }}" method="POST">
    @csrf

     <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6">
            <div class="form-group">
                <label for="center_name text-danger">Enter Center Name : <span class="text-danger"><small>required</small></span></label>
                <input type="text" name="center_name" class="form-control" required>
            </div>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6">
          <div class="form-group">
            <label for="tehsil_id">Select Tehsil : <span class="text-danger"><small>required</small></span></label>
            <select class="form-control" name="tehsil_id" id="tehsil_id" required>
              <option value="">Select Tehsil</option>
              @foreach ($tehsils as $item)
              <option value="{{ $item->tehsil_id }}">{{ $item->tehsil_name }}</option>
              @endforeach
           </select>
          </div>
       </div>
       <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
          <label for="first_name">Select Activity Location : <span class="text-danger"><small>required</small></span></label>
          <select class="form-control" name="activity_location_id" id="activity_location_id" required>
            <option value="">Select Activity Location</option>
            @foreach ($activity_location as $item)
            <option value="{{ $item->activity_location_id }}">{{ $item->activity_name }}</option>
            @endforeach
         </select>
        </div>
     </div>
       
      </div>
      {{-- <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <label>Diabetic :</label>
                <select class="form-control" name="diabetic" id="diabetic">
                  <option value="">Select Diabetic Condition</option>
                  <option value="Diabetic">Diabetic</option>
                  <option value="Non Diabetic">Non Diabetic</option>

                </select>
            </div>
        </div>
      </div> --}}
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