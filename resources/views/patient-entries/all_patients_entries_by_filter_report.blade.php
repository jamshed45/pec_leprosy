@extends('layout')

@section('content')
@php

  $user = Auth::user();

@endphp
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header sty-one">
      <h1>Patient Entries Report</h1>
      <ol class="breadcrumb">
        <li><a href="#">Dashboard</a></li>
        <li class="sub-bread"><i class="fa fa-angle-right"></i> Patient Entries</li>
        <li><i class="fa fa-angle-right"></i> Patient Entries Report</li>
      </ol>
    </div>

    <div class="content">
      <div class="row">

        <div class="col-xs-12 col-sm-12 col-md-12 mb-4">
          <form action="{{ route('all-patients-entries-report') }}">
            @method('POST')
            @csrf
          <div class="row">
            @if (!$user->hasRole('pec-user'))
            <div class="col-md-4">
              <div class="form-group">
                  <label for="first_name">Select User :</label>
                  <select class="form-control user_filter_id" name="user_id" id="user_id">
                      <option value="">Select User</option>
                      @foreach ($users as $item)
                          <option value="{{ $item->id }}" {{ ($search_field_info['user_id'] == $item->id )?'selected':'' }}>{{ $item->name }}
                          </option>
                      @endforeach
                  </select>
              </div>
            </div>
            @else
              <input type="hidden" name="user_id" value="{{ $user->id }}" />
            @endif

            <div class="col-md-4">
              <div class="form-group">
                  <label for="first_name">Select Location Activity :</label>
                  <select class="form-control" name="activity_location_id">
                      <option value="">Select Activity Location</option>
                      @foreach ($activity_location as $item)
                          <option value="{{ $item->activity_location_id }}" {{($search_field_info['activity_location_id'] == $item->activity_location_id)?'selected':''}}>{{ $item->activity_name }}
                          </option>
                      @endforeach
                  </select>
              </div>
            </div>
            <div class="col-md-4">

              <div class="form-group">
                  <label for="first_name">Select Center :</label>
                  <select class="form-control" name="center_id">
                        <option value="">Select Center</option>
                        @foreach ($centers as $item)
                          <option value="{{ $item->center_id }}" {{($search_field_info['center_id'] == $item->center_id)?'selected':''}}>{{ $item->center_name }}</option>
                        @endforeach

                  </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                  <label for="first_name"> Date :</label>
                  <input type="date" name="from_date" class="form-control" value="{{ $search_field_info['filter_date'] }}" />
              </div>
            </div>

          </div>
          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                    <a href="{{ URL::current() }}" class="btn btn-primary">Clear Fields</a> <button type="submit" class="btn btn-primary">Search Patient Entries</button>
            </div>
          </div>
          </form>
        </div>


        @include('patient-entries/patients_entries_grid_part')



      </div>
    </div>

  </div>

@endsection
