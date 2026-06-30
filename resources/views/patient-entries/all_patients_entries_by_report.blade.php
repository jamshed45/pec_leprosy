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
    
    @include('user_center_section')

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
                  <label for="first_name">Select Category : </label>
                  <select class="form-control" name="filter_cat_slug">             
                        <option value="">Select Category</option>
                        @foreach ($form_categories as $item)                        
                          <option value="{{ $item['slug'] }}" {{ ($search_field_info['filter_cat_slug'] == $item['slug'] )?'selected':'' }}>{{ $item['category_name'] }}</option>
                        @endforeach
    
                  </select>
              </div>
            </div>
            <div class="col-md-4">  
              <div class="form-group">
                  <label for="first_name"> Date :</label>
                  <input type="date" id="filter_date" name="filter_date" class="form-control" value="{{ $search_field_info['filter_date'] }}" />
              </div>
            </div>
            <div class="col-md-4">  
              <div class="form-group">
                  <label for="first_name">Month :</label>
                  <input type="month" name="searh_month" class="form-control" id="searh_month_filter" value="{{ $search_field_info['searh_month'] }}" />
              </div>
            </div>
            <div class="col-md-4">
              <label for="first_name">Quater :</label>
              <div class="form-inline">
              <div class="form-group mr-2">
                <select name="year_qauterly" id="year_qauterly_filter" class="form-control">
                  <option value="">Year</option>
                  <option value="2023" {{($search_field_info['searh_year_qauterly'] == '2023')?'selected':''}}>2023</option>
                  <option value="2024" {{($search_field_info['searh_year_qauterly'] == '2024')?'selected':''}}>2024</option>
                  <option value="2025" {{($search_field_info['searh_year_qauterly'] == '2025')?'selected':''}}>2025</option>
                  <option value="2026" {{($search_field_info['searh_year_qauterly'] == '2026')?'selected':''}}>2026</option>
                </select>
              </div>  
              <div class="form-group">
                  <select name="searh_qauterly" id="searh_qauterly_filter" class="form-control">
                    <option value="">Select Qauter</option>
                    <option value="1" {{($search_field_info['searh_qauterly'] == '1')?'selected':''}}>1st Qauter</option>
                    <option value="2" {{($search_field_info['searh_qauterly'] == '2')?'selected':''}}>2nd Qauter</option>
                    <option value="3" {{($search_field_info['searh_qauterly'] == '3')?'selected':''}}>3rd Qauter</option>
                    <option value="4" {{($search_field_info['searh_qauterly'] == '4')?'selected':''}}>4rth Qauter</option>
                  </select>
              </div>
              </div>
            </div>
           
          </div>
          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                    <a href="{{ URL::current() }}" class="btn btn-primary">Clear Fields</a> <button type="submit" class="btn btn-primary">Generate Report</button>
            </div>
          </div>
          </form>
        </div>


        @include('patient-entries/patients_entries_grid_filter_part') 



      </div>     
    </div>
    
  </div>

  <script type="">
    $(document).ready(function(){

      $("#filter_date").change(function(){
        $("#searh_month_filter").val('');
        $("#year_qauterly_filter").val('');
        $("#searh_qauterly_filter").val('');
      });

      $("#searh_month_filter").change(function(){
        $("#filter_date").val('');
        $("#year_qauterly_filter").val('');
        $("#searh_qauterly_filter").val('');
      });

      $("#year_qauterly_filter").change(function(){
        $("#filter_date").val('');
        $("#searh_month_filter").val('');
      });

      $("#searh_qauterly_filter").change(function(){
        $("#filter_date").val('');
        $("#searh_month_filter").val('');
      });


    });
    </script>

@endsection