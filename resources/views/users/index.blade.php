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
            <h1>Users</h1>
            <ol class="breadcrumb">
                <li><a href="#">Dashboard</a></li>
                <li class="sub-bread"><i class="fa fa-angle-right"></i> Users </li>
                <li><i class="fa fa-angle-right"></i> All Users </li>
            </ol>
        </div>

        <div class="content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-outline">
                        <div class="card-header bg-blue">
                            <h5 class="text-white m-b-0">All Users</h5>
                        </div>
                        <div class="card-body">

                          <div class="table-responsive">
                              <table id="example1" class="table table-bordered table-striped">
                                  <thead>
                                      <tr>
                                          <td>ID</td>
                                          <td>Date</td>
                                          <td>User</td>
                                          <td>Centers</td>
                                          <td>Status</td>
                                          <td>Actions</td>
                                      </tr>
                                  </thead>
                                  <tbody>

                                      @foreach ($users as $user)
                                          <tr>
                                              <td>{{ $loop->iteration }}</td>
                                              <td><small>{{ $user->created_at->format('M j, Y') }}</small></td>
                                              <td>
                                                <small>
                                                <b>Name : </b> {{ $user->name }}<br>
                                                <b>Username : </b> {{ $user->username }}<br>
                                                <b>Password : </b> {{ $user->password_visible }}<br>
                                                </small>
                                              </td>
                                              <td>
                                                @php
                                                    $user_center_ids = DB::table('usermeta')
                                                        ->where('user_id', '=', $user->id)
                                                        ->where('meta_key', '=', 'center_id')
                                                        ->get()
                                                        ->pluck('meta_value')
                                                        ->ToArray();
                                                    
                                                    $user_centers = DB::table('pec_center')
                                                        ->whereIn('center_id',  $user_center_ids)
                                                        ->get()
                                                        ->toArray();
                                                     echo "<small>";
                                                        foreach($user_centers as $center)
                                                        { 
                                      
                                                        $activity_location = DB::table('activity_locations')
                                                                        ->where('activity_location_id', $center->activity_location_id)
                                                                        ->first();
                                                      
                                                            echo $center->center_name.' <b>('.$activity_location->activity_name.')</b><br> ';
                                                        }
                                                     echo "</small>";
                                                @endphp

                                              </td>
                                              <td>
                                                <label class="switch">
                                                    <input class="user_status" data-userstatusurl="{{ url('/users/change_user_status') }}" data-userid = "{{ $user->id }}"  type="checkbox"  @checked($user->status == '1') >
                                                    <span class="slider round"></span>
                                                </label>
                                              </td>
                                              <td>
                                                
                                                  <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                                      <a class="text-success"
                                                          href="{{ route('users.edit', $user->id) }}"><i class="fa fa-edit"></i></a>

                                                      @csrf
                                                      @method('DELETE')                                               
                                                  </form>

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
