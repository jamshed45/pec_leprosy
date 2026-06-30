
@extends('layout')

@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper"> 
    <!-- Content Header (Page header) -->
    <div class="content-header sty-one">
      <h1>Divisions</h1>
      <ol class="breadcrumb">
        <li><a href="#">Dashboard</a></li>
        <li class="sub-bread"><i class="fa fa-angle-right"></i> Divisions</li>
        <li><i class="fa fa-angle-right"></i> Edit Division</li>
      </ol>
    </div>
    
    
    <div class="content">
        <div class="row">
          <div class="col-lg-12">
            <div class="card card-outline">
              <div class="card-header bg-blue">
                <h5 class="text-white m-b-0">Edit Division</h5>
              </div>
              <div class="card-body">
          
                      <!-- if there are creation errors, they will show here -->
                      <form action="{{ route('divisions.update',$record->division_id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="division_id" value="{{$record->division_id}}" />
                         <div class="row">
                            <div class="col-xs-6 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label>Name:</label>
                                    <input type="text" name="division_name" class="form-control" value="{{$record->division_name}}">
                                </div>
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6">
                              <div class="form-group">
                                <label for="first_name">Select Province :</label>
                                <select class="form-control" name="province_id" id="province_id">
                                  <option selected>Select category</option>
                                  @foreach ($province as $item)
                                  <option value="{{ $item->province_id }}" {{ ($item->province_id == $record->province_id) ? 'selected' : ''}}>{{ $item->province_name }}</option>
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