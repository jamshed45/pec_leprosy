@extends('layout')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header sty-one">
            <h1>Edit User</h1>
            <ol class="breadcrumb">
                <li><a href="#">Dashboard</a></li>
                <li class="sub-bread"><i class="fa fa-angle-right"></i> PEC User</li>
                <li><i class="fa fa-angle-right"></i> User</li>
            </ol>
        </div>

        <div class="content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-outline">
                        <div class="card-header bg-blue">
                            <h5 class="text-white m-b-0">Edit PEC User</h5>
                        </div>
                        <div class="card-body">

                            <!-- if there are creation errors, they will show here -->
                            <form action="{{ route('users.update', $record->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                         
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <label for="first_name">Name :</label>
                                            <input type="text" name="name" class="form-control" value="{{ $record->name }}">
                                        </div>
                                  
                                    </div>
                                    
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <label>Username :</label>
                                            <input type="text" name="username" class="form-control" value="{{ $record->username }}">
                                        </div>
                                    </div>

                                </div>
                                <div class="row">

                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <label>Password :</label>
                                            <input type="text" name="password"  class="form-control" autocomplete="new-password" value="{{ $record->password_visible }}">
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <label>Select Province</label><br>
                                    </div>
                                </div>

                                <div class="row" >
                                    <div class="col-xs-12 col-sm-12 col-md-12" id="area_selection">
                                   
            
                                        @foreach ($provinces as $item)
                                        @php
                                            $province_unique_id = uniqid();
                                            $check_province_tehsil = DB::table('tehsil')
                                                ->where('province_id', '=', $item->province_id)
                                                ->get()
                                                ->toArray();
                                        @endphp
                                       
                                        @if(empty($check_province_tehsil))
                                            {{-- // $data is not empty --}}
                                            @continue
                                        @endif
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12">    
                                                <input type="checkbox" @checked(in_array($item->province_id, $user_province_id)) name="user_provinces[]" class="select_province" data-pid="{{ $province_unique_id }}" data-url="{{ url("/tehsils/get_province_tehsils") }}" value="{{ $item->province_id }}" /> {{ $item->province_name }}
                                            </div>
                                        </div> 
                                        
                                                {{-- <span class="slider round"></span> --}}
                                     
                                            <div id="pid-{{ $province_unique_id }}">
                                                @if (in_array($item->province_id, $user_province_id))
                                
                                                @php
                                                    $user_tehsil_id = DB::table('usermeta')
                                                        ->where('user_id', '=', $record->id)
                                                        ->where('meta_key', '=', 'tehsil_id')
                                                        ->where('parent_id', '=', $item->province_id)
                                                        ->get()
                                                        ->pluck('meta_value')
                                                        ->ToArray();
                                                        // print_r($user_tehsil_id);
                                                        
                                                $get_province_tehsil = DB::table('tehsil')
                                                        ->where('province_id', '=', $item->province_id)
                                                        ->orderBy('tehsil_name')
                                                        ->get()
                                                        ->ToArray();
                                                // print_r($get_province_tehsil);        
                                                @endphp

                                                

                                                @foreach ($get_province_tehsil as $tehsil)

                                                @php
                                                    $check_tehsil_center = DB::table('pec_center')
                                                        ->where('tehsil_id', '=', $tehsil->tehsil_id)
                                                        ->get()
                                                        ->toArray();
                                                @endphp

                                                @if(empty($check_tehsil_center))
                                                {{-- // $data is not empty --}}
                                                @continue
                                                @endif
                                                
                                                @php
                                                    // echo "<pre>";
                                                    // print_r($city);
                                                    // echo "</pre>";
                                                    
                                                    // print_r($get_province_cities);   

                                                    $tehsil_unique_id = uniqid();
                                                @endphp
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="user-tehsil-container">
                                                            <input class='select_tehsil' @checked(in_array($tehsil->tehsil_id, $user_tehsil_id)) name='user_tehsils[{{ $tehsil->province_id }}][]' data-cid='{{ $tehsil_unique_id }}' type='checkbox' class=select_tehsil' value='{{ $tehsil->tehsil_id }}'  data-url='{{ url("/centers/get_tehsil_centers") }}' /> {{ $tehsil->tehsil_name }}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-8" id='cid-{{ $tehsil_unique_id }}'>
                                                        @if (in_array($tehsil->tehsil_id, $user_tehsil_id))
                                                    @php
                                    
                                                    $user_center_id = DB::table('usermeta')
                                                        ->where('user_id', '=', $record->id)
                                                        ->where('meta_key', '=', 'center_id')
                                                        ->where('parent_id', '=', $tehsil->tehsil_id)
                                                        ->get()
                                                        ->pluck('meta_value')
                                                        ->ToArray();
                                                 
                                                    $get_tehsils_centers = DB::table('pec_center')
                                                            ->where('tehsil_id', '=', $tehsil->tehsil_id)
                                                            ->get()
                                                            ->ToArray();

                                                    @endphp

                                                    @foreach($get_tehsils_centers as $center)

                                                    @php
                                                        $activity_location = DB::table('activity_locations')
                                                                        ->where('activity_location_id', $center->activity_location_id)
                                                                        ->first();
                                                    @endphp
                                                                                                            
                                                    <div class="center-select-contianer" >
                                                    <input type="checkbox" @checked(in_array($center->center_id, $user_center_id)) name="user_centers[{{ $tehsil->tehsil_id }}][]" id="{{ $center->center_id }}" value="{{ $center->center_id }}">
                                                    <label for="{{ $center->center_id }}">{{ $center->center_name }} <small><b>({{ $activity_location->activity_name }})</b></small></label>
                                                    </div>
                                                    
                                                    @endforeach
                                                    @endif
                                                    </div>
                                                    <div class="col-md-12"><hr></div>
                                                </div>
    
                                                     
                                                @endforeach    
                                                @endif
        
                                            </div>
                                            <div class="col-md-12">
                                                <hr>
                                            </div>
                                        @endforeach
                               
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
