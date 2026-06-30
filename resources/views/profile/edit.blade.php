@extends('layout')

@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper"> 
    <!-- Content Header (Page header) -->
    <div class="content-header sty-one">
      <h1>Profile</h1>
      <ol class="breadcrumb">
        <li><a href="#">Dashboard</a></li>
        <li class="sub-bread"><i class="fa fa-angle-right"></i> Profile</li>
        <li><i class="fa fa-angle-right"></i> Profile</li>
      </ol>
    </div>
    {{-- @php
      print_r($user_info);
      die();
    @endphp --}}

    <div class="col-md-12">
      <!-- will be used to show any messages -->
      @if (Session::has('message'))
          <div class="alert alert-info">{{ Session::get('message') }}</div>
      @endif
    </div>
    
    <div class="content">
    
      <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                
                              {{-- <form class="form-horizontal form-material"> --}}
                                <form action="{{ route('profile.update-profile') }}" id="profile_form" method="POST" enctype="multipart/form-data" >
                                  @csrf  
                                  <input type="hidden" name="user_id" value="{{ $user_info->id }}" />
                                <div class="form-group">
                                  <label class="col-md-12">Name</label>
                                  <div class="col-md-12">
                                    <input class="form-control form-control-line" type="text" name="name" value="{{ $user_info->name }}" required>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label for="example-email" class="col-md-12">Username</label>
                                  <div class="col-md-12">
                                    <b>{{ $user_info->username }}</b>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label for="example-email" class="col-md-12">Enter Username to Update</label>
                                  <div class="col-md-12">
                                    <input class="form-control form-control-line" id="check_username" name="new_user_name" type="text" autocomplete="off" data-url="{{ url('/users/check_username') }}" value="" >
                                    <span id="check_useranme_result"></span>
                                  </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                  <hr>
                                </div>
                                <div class="form-group">
                                  <label class="col-md-12">Password</label>
                                  <div class="col-md-12">
                                    <input class="form-control form-control-line" autocomplete="off" id="password" name="new_password" type="password" value="">
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label class="col-md-12">Retype Password</label>
                                  <div class="col-md-12">
                                    <input class="form-control form-control-line" autocomplete="off" id="retype_password" name="new_retype_password" type="password" value="">
                                  </div>
                                </div>

                                <div class="col-md-12 mb-3">
                                  <hr>
                                </div>

                                <div class="form-group">
                                  <label class="col-md-12">Image</label>
                                  <div class="row">
                                    <div class="col-md-6">
                                      <label class="custom-file center-block block">
                                        <input type="file" id="file" name="image" class="custom-file-input">
                                        <span class="custom-file-control"></span> </label>
                                    </div>
                                    <div class="col-md-6">
                                      @if ($user_info->img)
                                        @php
                                          $user_img  = $user_info->img;
                                        @endphp
                                        <div class="admin-manage-img-container">
                                        <img src="{{ url('uploads') }}/{{ $user_img }}" width="150" height="150" />
                                        </div>
                                      @endif
                                    </div>
                                  </div>
                                </div>

                                <div class="form-group">
                                  <div class="row">
                                    <div class="col-sm-12">
                                      <button class="btn btn-success" id="profile_sunmit_btn" type="submit" disabled>Update Profile</button>
                                    </div>
                                    <div class="col-sm-12">
                                      <span id="pass_error"></span>
                                    </div>
                                  </div>
                                </div>
                              </form>

                            </div>
                        </div>
                    </div>
                </div>
    </div>


  </div>


  <script type="">
    $(document).ready(function() {

      $(".admin-edit-img-container").click(function(){
        $('.admin-edit-img-container').hide();
        $('#exist_admin_img').val('');
      });


      $("#check_username").focusout(function () {

            var user_url  = $(this).attr("data-url");

            var inputString = $(this).val();

            var regex = /[!@#$%^&*()_+{}\[\]:;<>,.?~\\/\s]/; // Regular expression to check for special characters or spaces

            if (inputString == '') {
              $('#check_useranme_result').html('');
              $('#profile_sunmit_btn').prop('disabled', false);
              return;
            }

            if (regex.test(inputString)) {
              $('#check_useranme_result').html('<div class="alert alert-warning mt-3" role="alert"> Remove special characters or spaces in username</div>');
              $('#profile_sunmit_btn').prop('disabled', true);
              return;
            }
            $('#check_useranme_result').html('');

            $('#check_useranme_result').html('<i class="fa fa-spin fa-spinner"></i>');

            $.ajax({
                url: user_url,
                method: 'get',
                data: {
                    user_name: inputString,
                },
                success: function(result){                            

                  if(result.status == 'success')
                    {
                      $('#check_useranme_result').html('<div class="alert alert-success mt-3" role="alert"> ' + result.message + ' </div>');
                      $('#profile_sunmit_btn').prop('disabled', false);
                    }
                    else
                    {
                      $('#check_useranme_result').html('<div class="alert alert-danger mt-3" role="alert">' + result.message + '</div>');
                      $('#profile_sunmit_btn').prop('disabled', true);
                    }


                }});

        });

    $("#profile_form").submit(function(e){

      var password = $('#password').val();
      var retype_password = $('#retype_password').val();
      console.log('pass =' + password);
      console.log('new pass =' + retype_password);
      // if(password != '' || password == retype_password)
      if(password != '' && password != retype_password)
      {
        $('#pass_error').html('<div class="alert alert-warning mt-3" role="alert">Password Mismatched!</div>');
        e.preventDefault();
      }
      else
      {
        $('#pass_error').html('');
      }


    });

    });
  </script>

@endsection