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
                      <form action="{{ route('districts.update',$record->district_id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="cityID" value="{{$record->district_id}}" />
                         <div class="row">
                            <div class="col-xs-6 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label>Name:</label>
                                    <input type="text" name="district_name" class="form-control" value="{{$record->district_name}}">
                                </div>
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6">
                              <div class="form-group">
                                <label for="first_name">Select Division :</label>
                                <select class="form-control" name="division_id" id="division_id">
                                  <option selected>Select category</option>
                                  @foreach ($division as $item)
                                  <option value="{{ $item->division_id }}" {{ ($item->division_id == $record->division_id) ? 'selected' : ''}}>{{ $item->division_name }}</option>
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