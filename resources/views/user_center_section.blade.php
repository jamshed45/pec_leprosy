@php  
  
  $user = Auth::user();

  if (!Auth::check()) {
  @endphp
    <script>window.location = "{{ URL::to('/login') }}"</script>
  @php
    die();
    }



@endphp

@if ($user->hasRole('pec-user'))
@php  

$user_centers_ids = DB::table('usermeta')
                      ->where('user_id', '=', $user->id)
                      ->where('meta_key', '=', 'center_id')
                      ->get()
                      ->pluck('meta_value')
                      ->ToArray();
$user_centers = DB::table('pec_center')
              ->whereIn('center_id', $user_centers_ids)->get(); 
              $center_session = session()->get('center_info');


$activity_location = DB::table('activity_locations')
              ->get(); 

@endphp

<div class="col-lg-12 user-center-section">
<div class="row">
    {{-- {{ Hash::make('pec') }} --}}
  <div class="col-lg-9" id="center-info">
    @if (!session()->get('center_info'))
      <div class="alert alert-warning mb-0" role="alert"> Select Center & Date to Add Any Information</div>
    @else
      <div class="col-md-12">
        <div class="row">
          <div class="alert col-md-4 alert-primary mb-0 text-center" style="border-radius: 0 !important;" role="alert"><small class="text-white">Location : </small><b class="text-white">{{ $center_session['activity_location_name'] }}</b></div>
          <div class="alert col-md-4 alert-success mb-0 text-center" style="border-radius: 0 !important;" role="alert"><small class="text-white">Center : </small><b class="text-white">{{ $center_session['center_name'] }}</b></div>
          <div class="alert col-md-4 alert-warning mb-0 text-center" style="border-radius: 0 !important;" role="alert"><small class="text-white">Date : </small><b class="text-white">{{ date('jS M, Y',strtotime($center_session['entry_date']))  }}</b></div>
        </div>
      </div>
    @endif
  </div>
  <div class="col-lg-3">
    @if (!session()->get('center_info'))

      <button class="btn btn-sm btn-primary btn-block big-s-btn py-3 select-center">Select Center</button>
    @else
      <button class="btn btn-sm btn-primary btn-block big-s-btn py-3 select-center">Change Center</button>  
    @endif
  </div>

  
</div>

<div class="row d-none" id="center-info-container">
  <div class="col-lg-12">
    <hr>
  </div>
  <div class="col-md-3">
    <div id="select_center_container">
      <label for="activity_location_id"><b>Select Activity Location</b></label>
          <select class="form-control" name="activity_location_id" id="user_activity_location" style="height: 55px;" data-url="{{ url('/centers/get_activity_location_centers') }}" required>
            <option selected>Select Activity Location</option>
            @foreach ($activity_location as $item)
            <option value="{{ $item->activity_location_id }}">{{ $item->activity_name }}</option>
            @endforeach
         </select>
    </div>
  </div>
  <div class="col-md-3 d-none" id="load_user_center">
    <div id="select_center_container">
      <label for="center_id"><b>Select Center</b></label>
      <select class="form-control py-3 load-activity-location-center" id="center_id" style="height: 55px;">
          <option>Select Center</option>
        @foreach ($user_centers as $item)
          <option @selected(isset($center_session['center_id']) && $center_session['center_id'] == $item->center_id) value="{{ $item->center_id }}">{{ $item->center_name }}</option>
        @endforeach
      </select>
    </div>
  </div>
  <div class="col-md-3 d-none" id="user_entry_date">
    <div id="select_center_container">
      <label><b>Select Date</b></label>
      <input class="form-control  py-3" placeholder=""  type="date" id="entry_date" max="{{ date('Y-m-d') }}" value=""  />
    </div>
  </div>
  <div class="col-md-3 pt-4  d-none" id="set_center">
    <div id="select_center_container">
      <button class="btn btn-sm btn-success btn-block big-s-btn py-3" data-url="{{ url("/users/set_user_center") }}" id="set_center_date">Set Center</button>
    </div>
  </div>

  <div id="loader" class="col-md-12 py-2">

  </div>

</div>
</div>

@endif