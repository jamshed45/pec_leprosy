@extends('layout')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header sty-one">
            <h1>Add User</h1>
            <ol class="breadcrumb">
                <li><a href="#">Dashboard</a></li>
                <li class="sub-bread"><i class="fa fa-angle-right"></i> PEC User</li>
                <li><i class="fa fa-angle-right"></i> User</li>
            </ol>
        </div>

        <!-- will be used to show any messages -->
        <div class="col-md-12">
            @if (Session::has('error'))
            <div class="alert alert-info">{{ Session::get('error') }}</div>
            @endif
        </div>

        <div class="content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-outline">
                        <div class="card-header bg-blue">
                            <h5 class="text-white m-b-0">Add PEC User</h5>
                        </div>
                        <div class="card-body">

                            <!-- if there are creation errors, they will show here -->
                            <form action="{{ route('users.store') }}" method="POST" autocomplete="off">
                                @csrf
                                @method('POST')

                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <label for="name">Name :</label>
                                            <input type="text" name="name" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <label for="email">Username :</label>
                                            <input type="text" name="username" class="form-control" required>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">

                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <label for="password">Password :</label>
                                            <input type="text" name="password" class="form-control"
                                                autocomplete="new-password" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <label>Select Province</label><br>
                                    </div>
                                </div>

                                <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12" id="area_selection">
                                    

                                        @foreach ($provinces as $item)
                                            @php
                                                $province_unique_id = uniqid();
                                                $check_province_city = DB::table('district')
                                                    ->where('province_id', '=', $item->province_id)
                                                    ->get()
                                                    ->toArray();
                                            @endphp

                                            @if (empty($check_province_city))
                                                {{-- // $data is not empty --}}
                                                @continue
                                            @endif
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12">    
                                            <input type="checkbox" name="user_provinces[]" class="select_province"
                                                data-pid="{{ $province_unique_id }}"
                                                data-url="{{ url('/tehsils/get_province_tehsils') }}"
                                                value="{{ $item->province_id }}" /> {{ $item->province_name }}
                                                </div>
                                            </div>    
                                                {{-- <span class="slider round"></span> --}}
                                            
                                            <div  id="pid-{{ $province_unique_id }}"></div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <hr>
                                                </div>
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
